<?php

namespace version;

/**
    导入用户
 *          Excel导入预授信额度用户列表
 */
class MemberImport  extends Member{
    
    public $businessCompanyId;//合作公司ID
    
    public $deleteAlreadyExists = 1;//删除某合作公司下已存在的所有用户
    /*
     * Excel导入
     *      导入预授信额度的用户
     *      return array  Excel总行数、导入成功行数 、导入失败行数
     */
    public function importMember($businessCompanyId){
        if(false==$businessCompanyId)$this->error ("合作公司ID不能为空");
        $this->businessCompanyId = $businessCompanyId;
        $excelObject = new Excel();
        $arrExcelContent = $excelObject->getExcelContent();
        if(false==$arrExcelContent){
            $this->error($excelObject->getError());
        }
        if($this->deleteAlreadyExists){
            $this->deleteImportMember();
        }
        $successCount = $errorCount = 0;
        foreach($arrExcelContent as $line=>$cols){
            $model = new \memberImportModel();
            $import = [];
            $import["business_company_id"] = $this->businessCompanyId;//合作公司ID
            $import["names"] = $cols["A"];//姓名
            $import["certiNumber"] = $cols["B"];//身份证号
            $import["acc_no"] = $cols["C"];//银行卡号
            $import["mobile"] = $cols["D"];//手机号
            $import["promise_money"] = $cols["E"];//授信额度
            if($model->create($import) && $model->add()){//新增成功
                $successCount++;
            }else{
                $errorCount++;
            }
        }
        return ["totalCount"=>count($arrExcelContent),"errorCount"=>$errorCount,"successCount"=>$successCount];
    }
    //删除某合作公司下所有导入用户
    private function deleteImportMember(){
        if($this->businessCompanyId===null)return false;
        $memberImportModel = new \memberImportModel();
        $deleteResult = $memberImportModel->where(["business_company_id"=>$this->businessCompanyId])->delete();
        return $deleteResult?true:false;
    }
    /*
     * 设置预授信额度
     * @param $id:member_import 主键
     * @param $promiseMoney:授信额度
     * 
     */
    public function setPromiseMoney($id,$promiseMoney){
        if(empty($id))$this->error("ID为空");
        if(round($promiseMoney,2)<0)$this->error("授信额度不正确");
        if(false==(new \memberImportModel())->where(["id"=>$id])->save(["promise_money"=>$promiseMoney])){
            $this->error("修改失败".$id);
        }else{
            $this->success("调额成功");
        }
    }
    
    /*
     * 导入的客户列表
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
    public function importMemberList($params = [],$page = -1,$number = 12){
        $model = new \memberImportModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        
        //渠道公司名称
        if(isset($paramWhere["chaCompanyname"]) && !empty($paramWhere["chaCompanyname"])){
            $where["cha.companyname"] = ["like","%{$paramWhere["chaCompanyname"]}%"];
        }
        //合作公司名称
        if(isset($paramWhere["busCompanyname"]) && !empty($paramWhere["busCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["busCompanyname"]}%"];
        }
        //手机号
        if(isset($paramWhere["mobile"]) && !empty($paramWhere["mobile"])){
            $where["m.mobile"] =["like","%{$paramWhere["mobile"]}%"];
        }
        //姓名
        if(isset($paramWhere["names"]) && !empty($paramWhere["names"])){
            $where["m.names"] = ["like","%{$paramWhere["names"]}%"];
        }
        
        $where["_string"] = "m.business_company_id=bus.id  "
                . "and  bus.id=bc.business_company_id  "
                . "and bc.channel_company_id=cha.id";
        
        $fields = "m.*,m.id as member_import_id,"
                . "mi.nameStatus,"
                . "cha.companyname as channel_companyname,"
                . "bus.companyname as business_companyname";
        
        $table = "business_company bus,business_channel bc,"
                . "channel_company cha,member_import m";
        
        $limit = $page===-1?"":($page-1)*$number.",".$number;
        
        $count = $model->table($table)
                        ->join("member_info mi on m.certiNumber=mi.certiNumber ")
                        ->where($where)
                        ->field($fields)
                        ->order($order)
                        ->count();
        $list = (array)$model->table($table)
                            ->join("member_info mi on m.certiNumber=mi.certiNumber ")
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit($limit)
                            ->select();
        //dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list]; 
    }
    
}
