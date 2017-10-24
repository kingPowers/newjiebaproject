<?php
/*
 * 资金管理Model
 */
class CapitalModel extends CommonModel{
    
    public $postData;

     //数据表名称
    protected $trueTableName = "capital";
    
    protected $_validate = [
        ["business_company_id","require","合作公司ID不能为空",self::MUST_VALIDATE],
        ["capital_company_id","require","资金方ID不能为空",self::MUST_VALIDATE],
        ["first_credit_money","require","授信额度不能为空",self::MUST_VALIDATE],
        ["promise_money","require","保证金金额不能为空",self::MUST_VALIDATE],
        ["author","require","添加人不能为空",self::MUST_VALIDATE],
        
        ["first_credit_money","is_numeric","授信额度不正确",self::MUST_VALIDATE,"function"],
        ["promise_money","is_numeric","保证金金额不正确",self::MUST_VALIDATE,"function"],
        ["first_credit_money","checkFirstCreditMoney","授信额度不得小于保证金金额",self::MUST_VALIDATE,"callback"],
        
    ];
    
    //自动完成
    protected $_auto = [
        ["capital_number","createCapitalNumber",self::MODEL_INSERT,"callback"],
        ["promise_money_pos","promiseMoneyPos",self::MODEL_INSERT,"callback"],
    ];
    
    public function createCapitalNumber($number = null){
        if($number!==null && !$this->where(["capital_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "cap".time().rand(111,999).rand(111,999);
            if(false==$this->where(["capital_number"=>$number])->find())break;
        }
        return $number;
    }
    //保证金头寸
    public function promiseMoneyPos(){
        $capital = new \version\Capital($this);
        $where = ["where"=>["businessId"=>$this->postData["business_company_id"],"capitalId"=>$this->postData["capital_company_id"],]];
        $list =  $capital->getAllCapitalList($where);
        return round($list[0]["promiseMoneyPos"],2);
    }
    //授信额度不得小于保证金金额
    public function checkFirstCreditMoney($firstMoney){
        if(false!=$this->where(["business_company_id"=>$this->postData["business_company_id"],"capital_company_id"=>$this->postData["capital_company_id"]])->find())return true;
        return $firstMoney<$this->postData["promise_money"]?false:true;
    }
    
}
