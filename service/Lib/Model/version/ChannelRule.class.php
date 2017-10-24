<?php

namespace version;

/**
 * 渠道规则管理
 * 
 */
use version\ProfitRule;
class ChannelRule extends ProfitRule{
    
    public $businessRule;//BusinessRule.class.php类对象
    
    public $model;
    
    public function init() {
        parent::init();
        $this->model = new \ChannelRuleModel();
    }
    /*
     * 新增规则
     *      eg.新增数据格式
     *      $data = [
     *          0=>[
        *          0=>["business_company_id"=>"3",……],
        *          1=>["business_company_id"=>"3",……],
     *          ],
     *      ];
     */
    public function addChannelRule($data){
        foreach($data as $value){
            foreach($value as $key=>$val){
                if(is_array($value[$key+1]) && $value[$key+1]["min_money"]!=$val["max_money"]){
                    $this->error("{$val["grade"]}级阶梯的最大值金额必须等于{$value[$key+1]["grade"]}级阶梯的最小值金额");
                }
                $this->model->postData = $val;
                if(!$this->model->create($val)){
                    $this->error(!empty($this->model->ValidateError)?$this->model->ValidateError:$this->model->getError());
                }
                $list[] = $val;
            }
        }
        
        foreach($list as $one){
            $where = ["business_company_id"=>$one["business_company_id"],
                       "channel_company_id"=>$one["channel_company_id"],
                        "grade"=>$one["grade"],
                    ];
            $exists = $this->model->where($where)->find();
            //$this->model->create($one);
                if($exists){
                    $this->model->where(["id"=>$exists["id"]])->save($one);
                }else{
                    $addId = $this->model->add($one);
                }
                sleep(0.05);
        }
        $this->success("操作成功");
        
    }
    /*
     * 根据渠道规则计算分润
     *  @param array $order  贷款订单
     *          $order = [];
     */
    public function calculateRule($order){
        $orderStatus = ["2"=>"成单","3"=>"拒单"];
        $calculateList = [];
        $channelList = $this->getChannelRuleList($order["business_company_id"]);
        foreach($channelList["channel"] as $channel){
            $calculate = [];
            foreach($channel["rule"] as $rules){
                if($rules["min_money"]<$order["money"] && $order["money"]<=$rules["max_money"]){
                    $calculate["business_company_id"] = $order["business_company_id"];//商户公司ID
                    $calculate["channel_company_id"] = $rules["channel_company_id"];//channel_company_id
                    $calculate["channel_level"] = $channel["grade"];//渠道等级
                    $calculate["_profit_money"] = $rules["rule_type"]==1?round($rules["rule_percent"]*$order["money"]/100,2):$rules["rule_value"];
                    $calculate["_refuse_money"] = $rules["refuse_money"];//拒单金额
                    $calculate["profit_money"] = $order["status"]== \LoanTenderModel::STATUS_SUCCESS?$calculate["_profit_money"]:$calculate["_refuse_money"];//分润金额
                    $calculate["tender_id"] = $order["id"];//tenderID 
                    $calculate["remark"] = "{$channel["companyname"]}-{$channel["grade"]}级分润(订单状态:{$orderStatus[$order["status"]]})";
                    $calculateList[] = $calculate;break;
                }
            }//foreach
            
            //无规则适用于此渠道
            if(false==$calculate){
                $calculate["business_company_id"] = $order["business_company_id"];//商户公司ID
                $calculate["channel_company_id"] = $channel["id"];//channel_company_id
                $calculate["channel_level"] = $channel["channel_level"];//渠道等级
                $calculate["profit_money"] = 0;
                $calculate["refuse_money"] = 0;//拒单金额
                $calculate["profit_money"] = $order["status"]== \LoanTenderModel::STATUS_SUCCESS?$calculate["profit_money"]:$calculate["refuse_money"];//分润金额
                $calculate["tender_id"] = $order["id"];//tenderID 
                $calculate["remark"] = "商户{$channelList["business"]["companyname"]}无{$channel["companyname"]}{$channel["grade"]}级分润规则(订单金额：{$order["money"]})";
                $calculateList[] = $calculate;
                
                
                
                //通知某某某完善此规则
                
            }
            
        }//foreach
        
        return $calculateList;
    }
    
   /*
     * 获取合作公司对应的渠道规则
     *  @param $businessCompanyId
     *  return array
     * 
     *          eg.
     * 
     */
    public function getChannelRuleList($businessCompanyId){
       if($this->businessRule===null){
           $this->businessRule = new BusinessRule($this->commonModel);
       }
       return $this->businessRule->viewBusinessRule($businessCompanyId);
    } 
    
    
}
