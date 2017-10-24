<?php
/*
 * 分润订单管理Model
 * 
 */
class BusinessCostOutModel extends CommonModel{
    const STATUS_DISABLE = 0;//未核算状态
    const STATUS_ENABLE = 1;//已核算状态
    //数据表名称
    protected $trueTableName = "business_costout";
    
    /*
     * 统计支出总和
     *      --统计某合作公司的总支出金额
     * @param $businessCompanyId 合作公司ID
     */
    public function totalBalance($businessCompanyId){
        $totalMoney = $this->table("business_loan loan,loan_tender tender,business_costout cost")
                    ->where("loan.business_company_id='{$businessCompanyId}' and loan.id=tender.business_loan_id and cost.tender_id=tender.id")
                    ->sum("cost.cost_money");
            return round($totalMoney,2);
    }
    
}
