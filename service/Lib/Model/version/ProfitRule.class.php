<?php

namespace version;

/**
 *  分润规则管理
 * 
 */
use version\Object;
use version\BusinessCompany;
class ProfitRule  extends Object{
    
    /*
     * 分润规则列表
     *          eg.
     *          $params = ["where"=>[
     *              "companyname"=>合作公司名称
     *              ],
     *          ];
     */
   public function ruleList($params,$page = -1,$number = 12){
       $businessObject = new BusinessCompany($this->commonModel);
       $businessList = $businessObject->allCompanyList($params,-1,$number);
       $list = [];
       $model = new \BusinessRuleModel();
       foreach($businessList as $k=>$v){
           $list[$k]["id"] = $v["id"];//合作公司ID
           $list[$k]["channel_company_id"] = $v["channel_company_id"];//渠道ID
           $list[$k]["business_number"] = $v["business_number"];//合作公司编号
           $list[$k]["channel_companyname"] = $v["channel_companyname"];//所属渠道
           $list[$k]["companyname"] = $v["companyname"];//合作公司名称
           $busRuleOne = (array)$model->where(["business_company_id"=>$v["id"]])->order("grade desc")->find();
           $list[$k]["author"] = $busRuleOne["author"];//创建人
           $list[$k]["timeadd"] = $busRuleOne["timeadd"];//规则创建时间
       }
       
       return $list;
   }
   /*
    * 删除分润规则
    *   $id:规则ID
    *   return 
    */
   public function deleteRule($id){
       if(!$this->model instanceof \CommonModel)$this->error("model 对象错误");
       if(false!=$this->model->where(["id"=>$id])->delete()){
           $this->success("删除成功");
       }else{
           $this->error("哎呀，删除失败了");
       }
   }
    
}
