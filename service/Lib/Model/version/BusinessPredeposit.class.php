<?php
namespace version;
/**
 *合作公司预存款管理
 * 
 */
use version\Object;
class BusinessPredeposit extends Object{
    public $model;
    public function init() {
        parent::init();
        $this->model = new \BusinessPredepositModel();
    }
    
    /*
     * 新增预存款
     * 
     */
    public function addPredeposit($data){
        $this->model->postData = $data;
        if($this->model->create($data)){
            if(!$this->model->add()){
                $this->error("哎呀，没保存成功".$this->model->getDbError());
            }else{
                $this->success("添加成功");
            }
        }else{
            $this->error($this->model->getError());
        }
    }
    /*
     * 预存款明细列表
     *  @param int $businessCompanyId 合作公司ID
     *  @return array
     */
    public function predepositDetailList($businessCompanyId){
        $list = $this->model->where(["business_company_id"=>$businessCompanyId])->order("timeadd desc")->select();
        return $list;
    }
    
    /*
     * 支出明细列表
     @param $params 检索条件
     *              eg.$params = ["where"=>where条件];
     * @param $page 页数 -1表示不限制
     * @param $number = 12 每页显示的条数
     *  @return array
     */
    public function costoutDetailList($params = [],$page = -1,$number = 12){
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $paramOrder = isset($params["order"])?$params["order"]:"";
        //合作公司ID
       if(isset($paramWhere["business_company_id"]) && !empty($paramWhere["business_company_id"])){
           $where["bus.id"] = $paramWhere["business_company_id"];
       }
        $where["_string"] = "cost.tender_id=tender.id "
                . "and tender.business_loan_id=loan.id "
                . "and loan.business_company_id=bus.id";
       
        $fields = "cost.*,tender.memberid";
        
        $table = "business_costout cost,"
                . "loan_tender tender,"
                . "business_loan loan,"
                . "business_company bus";
        if($page===-1){
            $limit = "";
        }else{
            $limit = ($page-1)*$number.",".$number;
        }
        
        $count = $this->model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->count();
        $list = (array)$this->model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit(($page-1)*$number.",".$number)
                            ->select();
        //dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list];                    
        
        
    }
    
    /*
     * 合作公司预存款列表
     *  @param $params 检索条件
     *              eg.$params = ["where"=>where条件];
     * @param $page 页数 -1表示不限制
     * @param $number = 12 每页显示的条数
     */
    public function predeList($params = [],$page = -1,$number = 12){
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $paramOrder = isset($params["order"])?$params["order"]:"";
        
      //合作公司名称查询
      if(isset($paramWhere["companyname"]) && !empty($paramWhere["companyname"])){
          $where["bus.companyname"] = ["like","%{$paramWhere["companyname"]}%"];
      }
      $where["_string"] = "bus.id=bc.business_company_id  "
                       . "and bc.channel_company_id=cha.id ";
       
        $fields = "bus.companyname as business_companyname,bus.business_number,bus.id as business_company_id,"
                . "cha.companyname as channel_companyname";
              
        
        $table = "channel_company cha,"
                . "business_channel bc,"
                . "business_company bus";
        if(-1===intval($page)){//不分页
             $list = (array)$this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->select();
             foreach($list as &$val){
                 $val["total_balance"] = $this->model->totalBalance($val["business_company_id"]);//预存账户余额
                 $val["totalMoney"] = $this->model->totalMoney($val["business_company_id"]);//总预存金额
             }
           //dump(M()->getLastSql()); 
             return $list;
        }else{
            $count = $this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->count();
            $list = (array)$this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->limit(($page-1)*$number.",".$number)
                                ->select();
            foreach($list as &$val){
                 $val["total_balance"] = $this->model->totalBalance($val["business_company_id"]);//预存账户余额
                 $val["totalMoney"] = $this->model->totalMoney($val["business_company_id"]);//总预存金额
             }
            //dump(M()->getLastSql());
            return ["count"=>intval($count),"list"=>$list];                    
        }
    }
    
}
