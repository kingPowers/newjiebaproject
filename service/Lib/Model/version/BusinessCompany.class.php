<?php
/*
 * 商户公司处理类
 * 
 */
namespace version;
use version\Object;

class BusinessCompany extends Object{
    //
    public $allowAppMethods = [];
    //图片显示路径
    public $savePictuePath = false;
    
    /*
     * 新增商户公司
     * @param array $data:商户公司数据
     */
    public function addBusinessCompany($data){
        $model = new \BusinessCompanyModel();
        $model->commonModel = $this->commonModel;
        $model->postData = $data;
        if($model->create($data)){
            if(false==($addId = $model->add())){
                    $this->clearPictureTemp($model);
                    $this->error("哎呀,没保存成功(".$model->getError().")");
            }else{
                    M("business_channel")->add(["business_company_id"=>$addId,"channel_company_id"=>$data["channel_company_id"]]);
                    $this->movePictureTo($model,$addId);
                    $this->success("新增商户公司成功",["addId"=>$addId]);
            }
        }else{
            $this->clearPictureTemp($model);
            $this->error($model->getError());
        }
    }
    
    /*
     * 修改商户公司
     * @param array $data:商户公司数据
     *              $data = [
     *                  "id"=>"",//主键，参数必传
     *              ]
     */
    public function saveBusinessCompany($data){
        $model = new \BusinessCompanyModel();
        $model->commonModel = $this->commonModel;
        $model->postData = $data;
        if($model->create($data)){
            if(false==($saveId = $model->save())){
                    $this->clearPictureTemp($model);
                    $this->error("哎呀没保存成功(".$model->getError().")");
            }else{
                M("business_channel")->where(["business_company_id"=>$data["id"]])->save(["channel_company_id"=>$data["channel_company_id"]]);
                $this->movePictureTo($model,$data["id"]);
                $this->success("修改成功",["saveId"=>$saveId]);
            }
        }else{
            $this->clearPictureTemp($model);
            $this->error($model->getError());
        }
    }
    
    /*
    * 获取合作公司列表
    *      根据公司名称查询合作公司列表
    *   @param string $companyName 合作公司名称
    *   @return array
    */
    public function getBusinessCompanyList($companyName){
        if(empty($companyName))return [];
        $params = [
            "where"=>["companyname"=>$companyName],
            "order"=>"",
        ];
        return $this->allCompanyList($params, 1, 5);
    }

  /*
   * 合作公司类型
   */
  public function getCompanyType(){
      $model = new \BusinessCompanyModel();
      return $model->companyType;
  }
  
  /*
   * 所有公司列表
   *        根据指定的条件查询所有公司列表
   *  @param array $params  查询条件,包括where,order
   *  @param  int   $page    页数， 规定：-1表示不分页
   *  @param  int   $number  每页的条数  默认为12
   *  
   *  @return array
   * 
   *        eg.
   *        $params = [
   *            "where"=>["companyname"=>$companyName,],//where查询条件
   *            "order"=>"",//排序方式
   *        ];
   */
  public function allCompanyList($params = [],$page = 1,$number = 12){
      $model = new \BusinessCompanyModel();
      $b = $model->getTableName();
      
      $channelModel = new \ChannelCompanyModel();
      $c = $channelModel->getTableName();
      
      $picturePath = $this->showPictuePath();
      
      $paramWhere = isset($params["where"])?$params["where"]:[];
      //合作公司名称查询
      if(isset($paramWhere["companyname"]) && !empty($paramWhere["companyname"])){
          $where["b.companyname"] = ["like","%{$paramWhere["companyname"]}%"];
      }
      //合作公司ID查询
      if(isset($paramWhere["company_id"]) && !empty($paramWhere["company_id"])){
          $where["b.id"] = $paramWhere["company_id"];
      }
      //合作公司所属渠道ID查询
      if(isset($paramWhere["channel_company_id"]) && !empty($paramWhere["channel_company_id"])){
          $where["c.id"] = $paramWhere["channel_company_id"];
      }
      if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
          $where["b.status"] = $paramWhere["status"];
      }
      
      $where["_string"] = "b.id = bc.business_company_id and bc.channel_company_id = c.id ";
      //$where["bc.channel_company_id"] = ["exp","= c.id"];
      
      $order = isset($params["order"])?$params["order"]:"";
      $fields[] = "b.*";
      $fields[] = "c.companyname as channel_companyname,c.id as channel_company_id,c.channel_level";
      
      $fields[] = "concat('{$picturePath}',b.id,'/',b.legal_certinumber_pic) as legal_certinumber_path";
      $fields[] = "concat('{$picturePath}',b.id,'/',b.license_number_pic) as license_number_path";
      $fields[] = "concat('{$picturePath}',b.id,'/',b.agreement1_pic) as agreement1_path";
      $fields[] = "concat('{$picturePath}',b.id,'/',b.agreement2_pic) as agreement2_path";
     
       
      $field = implode(",",$fields);
      if($page===-1){
            $list = (array)$model  ->table("{$b} as b,{$c} as c,business_channel as bc")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->select();
            return $list;
        }
        $count = $model->table("{$b} as b,{$c} as c,business_channel as bc")
                      ->where($where)
                      ->count();
         $list = (array)$model  ->table("{$b} as b,{$c} as c,business_channel as bc")
                              ->where($where)
                              ->order($order)
                              ->field($field)
                              ->limit(($page-1)*$number.",".$number)
                              ->select();
         //dump(M()->getLastSql());
         return ["count"=>intval($count),"list"=>$list];
      
      
  }
  
  /*
   * 关联资金方
   *        --合作公司关联的资金方
   * @param int $companyId 公司ID
   * @return array
   */
  public function connectCapitalCompany($companyId){
      $model = new \BusinessCompanyModel();
      $list = $model->table("business_loan bus_l,capital_loan cap_l,capital_company cap")
            ->field("cap.capital_number,cap.companyname,cap_l.name")
            ->where("bus_l.business_company_id='{$companyId}' and bus_l.capital_loan_id=cap_l.id and cap_l.capital_company_id=cap.id")
            ->select();
      return $list;
  }
  
  
  
  /*
   * 合作公司的状态列表
   * 
   */
  public function getCompanyStatus(){
      $model = new \BusinessCompanyModel();
      return $model->getCompanyStatus();
  }
  
  /*
   * 清空新增商户公司上传的临时图片
   * 
   */
  private function clearPictureTemp($model){
      $sessionPic = $model->saveUploadPicture;
      foreach($sessionPic as $savePathName){
          @unlink($savePathName);
      }
  }
  /*
   * 图片移动到新目录下（公司主键目录下）
   * @param string $keyCompanyName 缓存key
   * @param string $companyId 公司ID
   */
  private function movePictureTo($model,$companyId){
      $sessionPic = $model->saveUploadPicture;
      foreach($sessionPic as $savePathName){
          if(is_file($savePathName)){
              $dir = dirname($savePathName).DIRECTORY_SEPARATOR.$companyId;
              if(!is_dir($dir))@mkdir($dir,0755);
              @copy($savePathName, $dir.DIRECTORY_SEPARATOR. basename($savePathName));
              @unlink($savePathName);
          }
      }
      
  }
  
  //图片显示路径
  public function showPictuePath(){
      if(false===$this->savePictuePath){
          $this->savePictuePath = C("TMPL_PARSE_STRING._STATIC_")."/Upload/businessCompany/company/";
      }
      return $this->savePictuePath;
  }
 
}
