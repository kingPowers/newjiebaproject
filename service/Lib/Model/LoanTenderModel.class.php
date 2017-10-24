<?php

class LoanTenderModel extends Model{
   
    const STATUS_APPLY = 1;//订单申请中
    const STATUS_SUCCESS = 2;//成单状态
    const STATUS_FAIL = 3;//拒单状态
    //数据表名称
    protected $trueTableName = "loan_tender";
    
    /*
     * 自动验证
     */
    protected $_validate = [
        ["business_loan_id","require","产品ID不能为空",self::MODEL_INSERT],
        ["memberid","require","用户ID不能我空",self::MODEL_INSERT],
        ["money","require","申请金额不能为空",self::MODEL_INSERT],
        ["total_fee","require","总费用不能我空",self::MODEL_INSERT],
    ];
    
    //自动完成
    protected $_auto = [
        ["tender_number","createTenderNumber",self::MODEL_INSERT,"callback"],
    ];
    
    public function createTenderNumber($number = null){
        if($number!==null && !$this->where(["tender_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "ten".time().rand(111,999).rand(111,999);
            if(false==$this->where(["tender_number"=>$number])->find())break;
        }
        return $number;
    }
    
    //借款成功人数
    public function tenderSuccessMember(){
        return (int)$this->where(["status"=>2])->group("memberid")->count();
    }
}
