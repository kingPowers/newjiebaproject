<?php
/*
 * 借款期限
 *      产品借款期限管理
 */
namespace version;

use version\LoanConfig;

class LoanPeriode extends LoanConfig{
   /*
    * 借款期限列表
    */
   public function getLoanPeriodeList(){
       $model = new \LoanPeriodeModel();
       return $model->getLoanPeriodeList();
   } 
    /*
     * 获取借款期限列表
     * @param array $params  查询条件,包括where,order
    *  @param  int   $page    页数， 规定：-1表示不分页
    *  @param  int   $number  每页的条数  默认为12
    *  
    *  @return array
    * 
    *        eg.
    *        $params = [
    *            "where"=>["companyname"=>$companyName,],//where查询条件
    *            "order"=>"",//排序方式
    *        ];
    */
   public function getAllPeriodeList($params = [],$page = -1,$number = 12){
        $model = new \LoanPeriodeModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        //合作公司ID
        if(!empty($paramWhere["businessCompanyId"])){
            $where["loan.business_company_id"] = $paramWhere["businessCompanyId"];
        }
        //产品状态
        if(!empty($paramWhere["loanStatus"])){
            $where["loan.status"] = $paramWhere["loanStatus"];
        }
        $where["_string"] = "periode.id=loan.loan_periode_id";
        
        $fields = "periode.*,loan.min_loanmoney,loan.max_loanmoney";
        
        $table = "loan_periode periode,"
                . "business_loan loan";
                
        if($page===-1){
            $list = (array)$model
                    ->table($table)
                    ->where($where)
                    ->field($fields)
                    ->order($order)
                    ->select();
            //var_dump(M()->getLastSql());
            return $list;
        }
        $count = $model->table($table)
                        ->where($where)
                        ->field($fields)
                        ->order($order)
                        ->count();
        $list = (array)$model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit(($page-1)*$number.",".$number)
                            ->select();
        //dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list];     
   }
   
}
