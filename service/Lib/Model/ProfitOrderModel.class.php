<?php

/**
 * 分润订单Model
 * 
 */
class ProfitOrderModel extends CommonModel{
    //数据表名称
    protected $trueTableName = "profit_order";
    /*
     * 自动验证
     */
    protected $_validate = [
        ["channel_company_id","require","渠道ID不能为空",self::MUST_VALIDATE],
        ["profit_money","require","分润金额不能为空",self::MUST_VALIDATE],
        ["tender_id","require","tender_id不能为空",self::MUST_VALIDATE],
       
    ];
    //自动完成
    protected $_auto = [
        ["profit_order_number","createProfitOrderNumber",self::MODEL_INSERT,"callback"],
    ];
    
    public function createProfitOrderNumber($number = null){
        if($number!==null && !$this->where(["profit_order_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "pro".time().rand(111,999).rand(111,999);
            if(false==$this->where(["profit_order_number"=>$number])->find())break;
        }
        return $number;
    }
    /*
     * 获取渠道分润
     *      @param int tenderId 订单ID
     *      return array
     */
    public function getChannelProfit($tenderId,$channelLevel = null){
        $profits = [];
        $list = $this->where(["tender_id"=>$tenderId])->select();
        foreach($list as $profit){
            $profits[$profit["channel_level"]] = $profit;
        }
        return $channelLevel===null?$profits:$profits[$channelLevel];
    }
    
    //分润订单列表
    public function getProfitOrderList($paramWhere = []){
        
        if(!empty($paramWhere["startTime"]) && empty($paramWhere["endTime"])){
            $where["timeadd"] = ["egt",$paramWhere["startTime"]];
        }elseif(empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["timeadd"] = ["elt",$paramWhere["endTime"]];
        }elseif(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["timeadd"] = ["between",[$paramWhere["startTime"],$paramWhere["endTime"]]];
        }
        //dump(M()->getLastSql());
        return $this->where($where)->select();
         
    }
    
}
