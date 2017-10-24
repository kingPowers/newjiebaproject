<?php
/*
 * 资金方公司管理类
 * 
 */
namespace version;
use version\Object;

class CapitalCompany extends Object{
    /*
     * 资金方类型
     * 
     */
    private $_typename = ["资金方类型1","资金方类型2","资金方类型3"];
    /*
     * 资金方主体
     */
    private $_subjectname = ["资金方主体1","资金方主体2","资金方主体3"];
    /*
     * 账号类型
     */
    private $_legal_opentype = ["账号类型1","账号类型2","账号类型3"];
    
    /*
     * 新增资金方
     * @param array $data:资金方数据
     */
    public function addCapitalCompany($data){
        $model = new \CapitalCompanyModel();
        $model->commonModel = $this->commonModel;
        if($model->create($data)){
            if($addId = $model->add()){
                $this->success("资金方新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，没保存成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    
    /*
     * 保存资金方
     */
    public function saveCapitalCompany($data){
        $model = new \CapitalCompanyModel();
        $model->commonModel = $this->commonModel;
        if($model->create($data)){
            if($saveId = $model->save()){
                $this->success("资金方修改成功",["save_id"=>$saveId]);
            }else{
                $this->error("哎呀，没修改成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    
    /*
     * 资金方类型
     */
    public function getTypeNmame(){
        return $this->_typename;
    }
    /*
     * 资金方主体
     */
   public function getSubjectname(){
       return $this->_subjectname;
   }
   /*
     * 账号类型
     */
   public function getLegalOpentype(){
       return $this->_legal_opentype;
   }
      /*
        * 获取资金方列表
        *      根据公司名称查询资金方公司列表
        *   @param string $companyName 资金方公司名称
        *   @return array
        */
    public function getCapitalCompanyList($companyName){
         $model = new \CapitalCompanyModel();
        if(empty($companyName))return [];
        return (array)$model->where("companyname like '%{$companyName}%'")->limit(5)->select();
    }
    /*
     * 资金方所有公司列表
     *      
     *  @param $param
     *  @param $page:分页
     *  @param $number:每一页显示的条数
     */
    public function getAllCapitalCompanyList($param = [],$page = 1,$number = 12){
        $model = new \CapitalCompanyModel();
        $paramWhere = isset($param["where"])?$param["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        $fields = "cap.*";
        //主键
        if(isset($paramWhere["id"]) && !empty($paramWhere["id"])){
            $where["cap.id"] = $paramWhere["id"];
        }
        //资金方公司名称
        if(isset($paramWhere["companyname"]) && !empty($paramWhere["companyname"])){
            $where["cap.companyname"] = ["like","%{$paramWhere["companyname"]}%"];
        }
        //资金方公司状态
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            $where["cap.status"] = $paramWhere["status"];
        }
        if(-1===intval($page)){//不分页
            $list = $model->table("capital_company cap")
                          ->where($where)
                          ->field($fields)
                          ->order($order)
                          ->select();
            return (array)$list;
        }else{
            $count = $model->table("capital_company cap")
                          ->where($where)
                          ->count();
            $list = $model->table("capital_company cap")
                          ->where($where)
                          ->field($fields)
                          ->order($order)
                          ->limit(($page-1)*$number.",".$number)
                          ->select();
            return ["count"=>intval($count),"list"=>(array)$list];      
        }
        
    }
    /*
     * 查询一条资金方公司信息
     * @param string $id
     * @return  array
     */
    public function getOneCapitalCompany($id){
        $list = $this->getAllCapitalCompanyList(["where"=>["id"=>$id]],-1);
        return (array)$list[0];
    }
    
}
