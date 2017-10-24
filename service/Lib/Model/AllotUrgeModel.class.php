<?php
/*
 * 催收记录Model
 * 
 */
class AllotUrgeModel  extends CommonModel{
    public $urgeType = ["1"=>"未处理","2"=>"催款成功","3"=>"催款失败"];
    public $urgeError;
    //数据表名称
    protected $trueTableName = "allot_urge";
    
    public $postData;
    protected $_validate = [
        ["tender_id","require","tenderID不能为空",self::MUST_VALIDATE],
        ["allot_id","require","allotID不能为空",self::MUST_VALIDATE],
        ["content","require","催收备注不能为空",self::MUST_VALIDATE],
        ["urge_type","require","催收结果不能为空",self::MUST_VALIDATE],
        ["author","require","催收人不能为空",self::MUST_VALIDATE],
        ["urge_type","checkUrgeType","催收结果参数值不正确",self::MUST_VALIDATE,"callback",self::MODEL_INSERT],
        ["tender_id","checkTenderId","tenderID不正确",self::MUST_VALIDATE,"callback",self::MODEL_INSERT],
        
    ];
    //催收结果核查
    public function checkUrgeType($type){
        if(!in_array($type,[2,3])){
            $this->urgeError = "请选择催收结果";
            return false;
        }
        return true;
    }
    
    public function checkTenderId($tenderId){
        $result = (new \version\AllotUrge($this))->urgeResult($tenderId);
        if($result==2){
            $this->urgeError = "此订单已催收成功，不必添加催收记录了";
            return false;
        }
        return true;
    }
            
}
