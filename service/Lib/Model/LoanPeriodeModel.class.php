<?php
/*
 * 借款期限管理Model
 * 
 */
class LoanPeriodeModel extends Model{
    //数据表名称
    protected $trueTableName = "loan_periode";
    /*
     * 借款期限单位 
     *      中文+英文一一对应
     */
    public $loanPeriodeUnit = ["天","月","年"];
    public $loanPeriodeUnitE = ["days","month","year"];
    public $changeUnitList = ["天"=>1,"月"=>30,"年"=>365];//单位换算
   /*
    * 获取借款期限列表
    *   @param $where 查询条件
    *   @param $order 排序
    *   @return array
    */ 
   public function getLoanPeriodeList($where = [],$order = null){
       if(null===$order)$order = "type asc,periode asc";
       return (array)$this->where($where)->order($order)->select();
   }
   
}
