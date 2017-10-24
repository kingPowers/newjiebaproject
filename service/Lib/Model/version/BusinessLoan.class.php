<?php
/*
 * 产品管理
 *      合作公司产品管理
 */
namespace version;

use version\LoanConfig;

class BusinessLoan extends LoanConfig{
    /*
     * 产品启用状态
     */
    const STATUS_ENABLE = 1;
    
    /*
     * 产品停用状态
     */
    const STATUS_DISENABLE = 2;
    
    
    
    
    /*
     * 新增产品
     * @param array $data:产品数据
     */
    public function addBusinessLoan($data){
        $model = new \BusinessLoanModel();
        $model->postData = $data;
        if($model->create($data)){
            if($addId = $model->add()){
                $this->success("新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，没保存成功(".$model->getError().")");
            }
        }else{
           $this->error($model->capLoanError?$model->capLoanError:$model->getError());
        }
    }
    
    /*
     * 保存产品
     */
    public function saveBusinessLoan($data){
        $model = new \BusinessLoanModel();
        $model->postData = $data;
        if($model->create($data)){
            if($saveId = $model->save()){
                $this->success("修改成功",["save_id"=>$saveId]);
            }else{
                $this->error("哎呀，没修改成功(".$model->getError().")");
            }
        }else{
            $this->error($model->capLoanError?$model->capLoanError:$model->getError());
        }
    }
    
    /*
     * (资方范围内)借款期限列表
     *      资金方“产品范围内”获取借款期限列表
     *      
     */
    public function getPeriodeRate($capitalLoanId){
        $periodeList = $this->getLoanPeriodes();
    }
    
     /*
        * 所有产品列表
        *        根据指定的条件查询所有产品列表
        *  @param array $params  查询条件,包括where,order
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
    public function getAllBusinessLoanList($params = [],$page = -1,$number = 12){
        $model = new \BusinessLoanModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
       
        //产品ID
        if(isset($paramWhere["id"]) && !empty($paramWhere["id"])){
            $where["bus_l.id"] =$paramWhere["id"];
        }
        //渠道公司名称
        if(isset($paramWhere["chaCompanyname"]) && !empty($paramWhere["chaCompanyname"])){
            $where["cha.companyname"] = ["like","%{$paramWhere["chaCompanyname"]}%"];
        }
        //合作公司名称
        if(isset($paramWhere["busCompanyname"]) && !empty($paramWhere["busCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["busCompanyname"]}%"];
        }
        //合作公司ID
        if(isset($paramWhere["busCompanyId"]) && !empty($paramWhere["busCompanyId"])){
            $where["bus.id"] = $paramWhere["busCompanyId"];
        }
        //资金方公司ID
        if(isset($paramWhere["capCompanyId"]) && !empty($paramWhere["capCompanyId"])){
            $where["cap.id"] = $paramWhere["capCompanyId"];
        }
        //产品名称
        if(isset($paramWhere["name"]) && !empty($paramWhere["name"])){
            $where["bus_l.name"] = ["like","%{$paramWhere["name"]}%"];
        }
        //产品状态
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            $where["bus_l.status"] = $paramWhere["status"];
        }
        //产品状态  1:已启用  2：未启用
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            $where["bus_l.status"] =$paramWhere["status"];
        }
        
         $fields = "bus_l.*,"
                . "bus.companyname as bus_companyname,"    
                . "cha.companyname as cha_companyname,"
                . "cap_l.name as cap_l_name,cap_l.id as cap_l_id,"
                . "cap.companyname as cap_companyname,cap.capital_number,cap.id as cap_company_id";
        
        $where["_string"] = "bus_l.business_company_id=bus.id  "
                . "and bus.id=bc.business_company_id and bc.channel_company_id=cha.id  "
                . "and bus_l.capital_loan_id=cap_l.id  "
                . "and cap_l.capital_company_id=cap.id ";
        
        $table = "business_loan bus_l,"
                . "business_channel bc,"
                . "business_company bus,"
                . "channel_company cha,"
                . "capital_loan cap_l,"
                . "capital_company cap";
        if($page===-1){
            $list = (array)$model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->select();
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
        return ["count"=>intval($count),"list"=>$list];                    
        
    }
    
   /*
     * 查询一条商户产品信息
     * @param string $id
     * @return  array
     */
    public function getOneBusinessLoan($id){
        $list = $this->getAllBusinessLoanList(["where"=>["id"=>$id]],-1);
        return (array)$list[0];
    }
    
    
    
}
