<?php
/*
 * 渠道分润分润规则管理Model
 * 
 */
class ChannelRuleModel extends CommonModel{
    //put your code here
    //数据表名称
    protected $trueTableName = "channel_rule";
    
    public $postData;
    
    public $ValidateError;
    
    protected $_validate = [
        ["business_company_id","require","合作公司ID不能为空",self::MODEL_INSERT],
        ["channel_company_id","require","渠道公司ID不能为空",self::MODEL_INSERT],
        ["min_money","require","阶梯最小金额不能为空",self::MODEL_INSERT],
        ["max_money","require","阶梯最大金额不能为空",self::MODEL_INSERT],
        ["grade","require","阶梯等级不能为空",self::MODEL_INSERT],
        ["refuse_money","require","拒单费不能为空",self::MODEL_INSERT],
        ["rule_type","require","规则分类不能为空",self::MODEL_INSERT],
        ["author","require","创建人不能为空",self::MODEL_INSERT],
        
        ["min_money","is_numeric","阶梯最小金额不正确",self::MUST_VALIDATE,"function"],
        ["max_money","is_numeric","阶梯最大金额不正确",self::MUST_VALIDATE,"function"],
        //["rule_percent","is_numeric","百分比的数值不正确",self::MUST_VALIDATE,"function"],
        //["rule_value","is_numeric","固定值的数值不正确",self::MUST_VALIDATE,"function"],
        //["refuse_money","is_numeric","固定值的数值不正确",self::MUST_VALIDATE,"function"],
        //["min_money","checkMinMoney","最小金额不正确",self::MUST_VALIDATE,"callback"],
        //["max_money","checkMaxMoney","最大金额不正确",self::MUST_VALIDATE,"callback"],
        ["rule_type","checkRuleTypeValue","比例值或固定值不正确",self::MUST_VALIDATE,"callback"],
    ];
    
    protected $_auto = [
        //["grade","autoGrade",self::MODEL_INSERT,"callback"],
        
    ];
    
    public function checkRuleTypeValue($type){
        if($type==1){//百分比
            if(round($this->postData["rule_percent"],2)<=0){
                $this->ValidateError = "百分比值不能为空";
                return false;
            }elseif(round($this->postData["rule_percent"],2)>=100){
                $this->ValidateError = "百分比值必须小于100";
                return false;
            }
        }else{
            if(round($this->postData["rule_value"],2)<=0){
                $this->ValidateError = "固定值不能为空";
                return false;
            }elseif(round($this->postData["rule_value"],2)>=200){
                $this->ValidateError = "固定值必须小于200";
                return false;
            }
        }
        return true;
    }
    
    //核查最小金额
    public function  checkMinMoney($minMoney){
        if($minMoney<0)return false;
        $findOne = $this->where(["business_company_id"=>$this->postData["business_company_id"],
                                 "channel_company_id"=>$this->postData["channel_company_id"]])
                        ->order("grade desc")->find();
        if(false!=$findOne && $findOne["max_money"]!=$minMoney){
            $this->ValidateError = "本阶梯最小金额必须设置{$findOne["max_money"]}元";
            return false;
        }
        return true;
    }
    //核查最大金额
    public function checkMaxMoney($maxMoney){
        $space = 300;//间隔
        if($this->postData["min_money"]+$space>$maxMoney){
            $this->ValidateError = "最小金额和最大金额间隔应至少{$space}元";
            return false;
        }
        return true;
    }
    
    public function autoGrade(){
        $findOne = $this->where(["business_company_id"=>$this->postData["business_company_id"],
                                 "channel_company_id"=>$this->postData["channel_company_id"]])
                        ->order("grade desc")->find();
        return $findOne?(intval($findOne["grade"])+1):1;
    }
}
