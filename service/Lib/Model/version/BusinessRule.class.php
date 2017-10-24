<?php

namespace version;

/**
 * 合作公司规则
 * 
 */
use version\BusinessCompany;
use version\ChannelCompany;
use version\ProfitRule;
class BusinessRule extends ProfitRule{
    public $model;
    
    public function init() {
        parent::init();
        $this->model = new \BusinessRuleModel();
    }
    /*
     * 新增规则
     *      eg.新增数据格式
     *      $data = [
     *          0=>["business_company_id"=>"3",……],
     *          1=>["business_company_id"=>"3",……],
     *      ];
     */
    public function addBusinessRule($data){
        foreach($data as $one){
            $this->model->postData = $one;
            if($this->model->create($one)){
                if(!$this->model->add()){
                    $this->error("哎呀，没保存成功".$this->model->getDbError());
                }
            }else{
                $this->error($this->model->getError());
            }
        }
        $this->success("添加成功");
        
    }
    /*
     * 查看规则详情
     * @param $businessCompanyId  合作公司ID
     * 
     *          eg.列表格式
     *          $list = [
     *                  business=>[
     *                      rule=>[0=>,1=>,],//规则二维数组
     *                      ……            公司信息
     *                  ],
     *                  channel=>[
     *                      0=>[
     *                          rule=>[0=>,1=>,]//规则二维数组
     *                          ……            渠道公司信息
     *                       ],
     *                      1=>[
     *                          rule=>[0=>,1=>,]//规则二维数组
     *                          ……            渠道公司信息
     *                       ],
     *                  ],
     *              ];
     */
    public function viewBusinessRule($businessCompanyId){
        $businessObject = new BusinessCompany($this->commonModel);
        $businessList = $businessObject->allCompanyList(["where"=>["company_id"=>$businessCompanyId]],-1);
        if(false==$businessList)$this->error("合作公司ID不正确");
        $list = [];
        $businessInfo = ["companyname"=>$businessList[0]["companyname"],"id"=>$businessList[0]["id"]];
        $list = ["business"=>$businessInfo];
        //祖父级别的渠道
        $parentChannelList = (new ChannelCompany($this->commonModel))->getChannelRuleCompany($businessList[0]["channel_company_id"]);
        
        $channelRuleModel = new \ChannelRuleModel();
        foreach($parentChannelList as $key=>$val){
            $parentChannelList[$key]["rule"] = $channelRuleModel->where(["business_company_id"=>$businessCompanyId,"channel_company_id"=>$val["id"]])->order("grade asc")->select();
        }
        $list["channel"] = $parentChannelList;
        return $list;
    }
    
}
