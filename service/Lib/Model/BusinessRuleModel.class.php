<?php
/*
 * 合作公司分润规则管理Model
 * 
 */
class BusinessRuleModel extends CommonModel{
    //数据表名称
    protected $trueTableName = "business_rule";
    
    public $postData;
    
    protected $_validate = [
        ["business_company_id","require","合作公司ID不能为空",self::MODEL_INSERT],
        ["min_money","require","本阶段最小金额不能为空",self::MODEL_INSERT],
        ["max_money","require","本阶段最大金额不能为空",self::MODEL_INSERT],
        ["rule_type","require","规则分类不能为空",self::MODEL_INSERT],
        ["author","require","创建人不能为空",self::MODEL_INSERT],
        
        ["min_money","is_numeric","本阶段最小金额不正确",self::MUST_VALIDATE,"function"],
        ["max_money","is_numeric","本阶段最大金额不正确",self::MUST_VALIDATE,"function"],
        ["rule_percent","is_numeric","百分比的数值不正确",self::MUST_VALIDATE,"function"],
        ["rule_value","is_numeric","固定值的数值不正确",self::MUST_VALIDATE,"function"],
        
        
    ];
    
    protected $_auto = [
        ["grade","autoGrade",self::MODEL_INSERT,"callback"],
        
    ];
    
    public function autoGrade(){
        $findOne = $this->where(["business_company_id"=>$this->postData["business_company_id"]])->order("grade desc")->find();
        return $findOne?(intval($findOne["grade"])+1):1;
    }
    
}
