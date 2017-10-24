<?php
/**
    合作公司预存款管理
 */
class BusinessPredepositModel extends CommonModel{
    //数据表名称
    protected $trueTableName = "business_predeposit";
    
    public $postData;
    
    protected $_validate = [
        ["business_company_id","require","合作公司ID不能为空",self::MODEL_INSERT],
        ["money","require","预存金额不能为空",self::MODEL_INSERT],
        ["author","require","创建人不能为空",self::MODEL_INSERT],
        
        ["money","is_numeric","预存金额不正确",self::MUST_VALIDATE,"function"],
        
    ];
    
    protected $_auto = [
        ["deposit_number","createPredepositNumber",self::MODEL_INSERT,"callback"],
        ["total_balance","autoTotalBalanceField",self::MODEL_INSERT,"callback"],
        
    ];
    
    /*
     * 创建编号
     */
    public function createPredepositNumber($number = null){
        if($number!==null && !$this->where(["deposit_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "dep".time().rand(111,999).rand(111,999);
            if(false==$this->where(["deposit_number"=>$number])->find())break;
        }
        return $number;
    }
    //预存余额
    public function autoTotalBalanceField($businessCompanyId = null){
        $businessCompanyId = $businessCompanyId?$businessCompanyId:$this->postData["business_company_id"];
        $costoutModel = new BusinessCostOutModel();
        $totalMoney = $this->totalBalance($businessCompanyId)+$this->postData["money"];    
        return $totalMoney;
    }
    
    /*
     * 统计预存款总和
     *      --统计某合作公司的总预存款金额
     * @param $businessCompanyId 合作公司ID
     * 
     */
    public function totalMoney($businessCompanyId){
        return  round($this->where("business_company_id='{$businessCompanyId}'")->sum("money"),2);
    }
    /*
     * 合作公司的预存款账户余额
     *  @param $businessCompanyId   合作公司ID
     *  
     */
    public function totalBalance($businessCompanyId){
        $costoutModel = new BusinessCostOutModel();
       return  $this->totalMoney($businessCompanyId)-$costoutModel->totalBalance($businessCompanyId);
    }
    
    
    
}
