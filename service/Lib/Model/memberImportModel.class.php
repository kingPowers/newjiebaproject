<?php

/*
    导入用户管理

 */

class memberImportModel extends CommonModel{
    //数据表名称
    protected $trueTableName = "member_import";
    
    /*
     * 获取导入用户
     */
    public function getImportMember($businessCompanyId,$certiNumber){
        return (array)$this->where(["business_company_id"=>$businessCompanyId,"certiNumber"=>$certiNumber])->find();
    }
}
