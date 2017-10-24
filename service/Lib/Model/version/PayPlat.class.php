<?php
/*
 * 第三方支付交易管理
 * 
 * 
 * 
 *      1.所有支付平台均在此对接
 *      2.所有支付均需要调用createOrder创建交易订单
 *      3.付款、收款常常写到其他功能的事物中，所以需要在此之前先创建订单
 *      
 */
namespace version;

class PayPlat extends Object{
    public $businessCompanyId;//合作公司ID
    
    public $memberId;//会员ID
    
    public $baofu;//宝付对象
    
    private $error;//错误信息
    
    public function init() {
        parent::init();
        if(empty($this->businessCompanyId)){$this->error("businessCompanyId不能为空");}
        if(empty($this->memberId)){$this->error("memberId不能为空");}
       
    }
    
   /*
    * 贷款订单--打款
    *       资金从商户到客户
    *    $orderParams:交易订单列表
    * 
    *    @return 打款成功返回数组  
    *            否则返回false
     */
    public function pay($orderParams){
        if(false==($orderInfo = $this->createOrder($orderParams)))return false;
        $result = $this->getBaofu()->bfPay($orderInfo['trans_no'],$orderInfo['trans_money'],$orderInfo['trans_type']);
        if(false===$result){$this->error = $this->getBaofu()->getError();return false;}
        return [
            "payPlat"=>"宝付",//支付平台
            "payReturn"=>$result,//支付平台返回信息
        ];
    }
    /*
    * 还款
    *       资金从客户到商户
    *    $orderParams:交易订单列表
    * 
    *    @return 还款成功返回数组  
    *            否则返回false
     */
    public function repayment($orderParams){
        if(false==($orderInfo = $this->createOrder($orderParams)))return false;
        $result = $this->getBaofu()->bfPay($orderInfo['trans_no'],$orderInfo['trans_money'],$orderInfo['trans_type']);
        if(false===$result){$this->error = $this->getBaofu()->getError();return false;}
        return [
            "payPlat"=>"宝付",//支付平台
            "payReturn"=>$result,//支付平台返回信息
        ];
    }
    
    //返回宝付对象
    public function getBaofu(){
        if($this->baofu===null){
            import("Think.ORG.Util.Baofu");
            $this->baofu = new \Baofu($this->memberId,$this->businessCompanyId);
        }
        return $this->baofu;
    }
    //创建并返回订单信息
    public function createOrder($orderParams = []){
        if(false==($orderInfo = $this->getBaofu()->createOrder($orderParams))){
            $this->error = $this->getBaofu()->getError();
            return false;
        }
        return $orderInfo;
    }
    
    public function getError(){
        return $this->error;
    }
    
    
}
