<?php
/*
    资金方管理model
 */

class CapitalCompanyModel  extends Model{
    //数据表名称
    protected $trueTableName = "capital_company";
    /*
     * 自动验证
     */
    protected $_validate = [
        ["companyname","require","资金方名称不能为空",self::MUST_VALIDATE],
        ["subjectname","require","资金方主体名称不能为空",self::MUST_VALIDATE],
        ["typename","require","资金方类型不能为空",self::MUST_VALIDATE],
        ["legal_name","require","法人姓名不能为空",self::MUST_VALIDATE],
        ["companyname","require","资金方名称不能为空",self::MUST_VALIDATE],
        ["legal_certinumber","require","法人身份证号不能为空",self::MUST_VALIDATE],
        ["organize_code","require","组织机构代码证号不能为空",self::MUST_VALIDATE],
        ["company_address","require","地址不能为空",self::MUST_VALIDATE],
        ["license_number","require","营业执照不能为空",self::MUST_VALIDATE],
        ["contact_name","require","联系人姓名不能为空",self::MUST_VALIDATE],
        ["contact_mobile","require","联系人电话不能为空",self::MUST_VALIDATE],
        ["contact_email","require","联系人邮箱不能为空",self::MUST_VALIDATE],
        ["legal_bank","require","开户银行名称不能为空",self::MUST_VALIDATE],
        ["legal_accno","require","开户银行卡号不能为空",self::MUST_VALIDATE],
        ["legal_openname","require","开户名称不能为空",self::MUST_VALIDATE],
        ["legal_opentype","require","开户账号类型不能为空",self::MUST_VALIDATE],
        
        ["deposit_percent","require","保证金比例不能为空",self::MUST_VALIDATE],
    ];
    //自动完成
    protected $_auto = [
       ["capital_number","createCapitalNumber",self::MODEL_INSERT,"callback"],
    ];
    
    public function getCapitalCompany($id){
        if(false!=($capitalInfo = M($this->trueTableName)->where(["id"=>$id])->find())){
            return $capitalInfo;
        }else{
            return false;
        }
    }
    
    /*
     * 创建编号
     */
    public function createCapitalNumber($number = null){
        if($number!==null && !$this->where(["capital_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "Cap".time().rand(111,999).rand(111,999);
            if(false==$this->where(["capital_number"=>$number])->find())break;
        }
        return $number;
    }
}
