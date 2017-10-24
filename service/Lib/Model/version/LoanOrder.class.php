<?php

/*
 * 贷款订单管理
 *      1.申请订单
 *      2.审核订单
 *      3.订单处理结果
 * 
 */

namespace version;

use version\LoanConfig;
class LoanOrder extends LoanConfig{
//    const EVENT_ORDER_PASS = "orderPass";//审核通过事件
//    const EVENT_AFTER_ORDER_DENY = "orderDeny";//审核拒单事件
    
    public $allowAppMethods = ["creditIndex","apply","calculateFee","orderSignSms","orderSign","orderRepayment","getBusinessLoanList","getMyTenderList"];
    
    public $business_company_id;//商户公司ID
    public $member; //member对象
    public $loanId;//产品ID
    public $money;//申请金额
    public $tenderId;//订单ID
    public $signCode;//e签宝验证码
    
    
    /*
     * 当前商户公司的产品列表
     */
    private $_loanList;
    
    public function init() {
        parent::init();
    }
    /*
     * 会员申请现金额贷首页
     * 
     */
    public function creditIndex(){
        $memberInfo = $this->getMember()->getMemberInfo();
        if(false===$memberInfo){
            $this->error("会员未登录");
        }
        //额度
        $quota = new Quota($this->commonModel,["memberId"=>$memberInfo["id"]]);
        //产品-期限
        $where = ["businessCompanyId"=>$memberInfo["business_company_id"],"loanStatus"=>1];
        $loanList = (new LoanPeriode($this->commonModel))->getAllPeriodeList(["where"=>$where,"order"=>"periode.type asc,periode.periode desc,loan.min_loanmoney asc"]);
        return [
            "quota"=>$quota->getCreditQuota(),//额度范围,["minMoney"=>1000,"maxMoney"=>10000]
            "tenderSuccess"=>(new \LoanTenderModel())->tenderSuccessMember(),//成功借款人数
            "maxPeriode"=>$loanList[0]["periode"].$loanList[0]["unit"],//最高借款天数
            "minLoanMoney"=>$loanList[0]["min_loanmoney"],//最低起借金额
        ];
    }
    
     /*
     * 会员申请现金贷
     *      1.会员需要登录的前提下
     *      2.
     *  @param $loanId string 产品ID
     *  @param $money float 借款金额
     *  @return array
      * 
      * 
      *         eg.applyList = [
      *                     "7天"=>[
            *                     "0"=>["repayment"=>"付息还本","loanId"=>3],
            *                     "1"=>["repayment"=>"等额本息","loanId"=>5],
      *                         ],
      *                      "15天"=>[
      *                             "0"=>["repayment"=>"付息还本","loanId"=>10],
      *                         ],
      *                 ]
     */
    public function apply(){
        $loanId = isset($this->loanId)?$this->loanId:"";//产品ID
        $money  = isset($this->money)?round($this->money,2):0;//申请金额
        
        $memberInfo = $this->getMember()->getMemberInfo();
        if(false===$memberInfo){
            $this->error("会员未登录");
        }
        $loanList = $this->getBusinessLoanList($memberInfo["business_company_id"]);
        $loanList = [];
        foreach($loanList as $key=>$val){
            $loanList[$val["periodeName"]][] = ["repayment"=>$val["repaymentName"],"loanId"=>$val["id"]];
        }
        //额度
        $quota = new Quota($this->commonModel,["memberId"=>$memberInfo["id"]]);
        if(!empty($loanId)){
             $feeList = $this->calculateFee($loanId, $money);
        }
        return [
            "quota"=>$quota->getCreditQuota(),//额度范围,["minMoney"=>1000,"maxMoney"=>10000]
            "feeList"=>(array)$feeList,//费用列表
            "loanList"=>$loanList,//产品列表
        ];
        
    }
    
    
    
    /*
     * 费用计算
     * @param int   $loanId:产品ID
     * @param float $money:借款金额
     * @return array
     *      
     *         目前计算公式仅仅支持 【本息到付】
     *          
     *          eg.[
     *              "periode_fee"=>0,//借款费用
                    "procedure_free"=>0,//手续费
                    "plat_free"=>0,//平台管理费
     *              "total"=>0,//统计费用
     *              ]
     *          
     */
    public function calculateFee($loanId = null,$money = null){
        if($loanId!==null)$this->loanId = $loanId;
        if($money!==null)$this->money = $money;
       return (new LoanIssue($this->commonModel,["loanId"=>$this->loanId,"money"=>$this->money]))->calculateFee();
    }
    /*
     *  获取个人贷款列表
     * APP接口
     *    
     *   需要参数：token
     *             allotStatus: 0：还款中  1：已还款 
     *             page   页数
     *             number  每页的条数
     */
    public function getMyTenderList(){
        $memberInfo = $this->getMember()->getMemberInfo();
        if(false===$memberInfo){
            $this->error("会员未登录");
        }
        $whereParams = [
            "where"=>["memberId"=>$memberInfo["id"],"status"=>isset($this->commonModel->allotStatus)?2:"","allotStatus"=>$this->commonModel->allotStatus],
            "order"=>"tender.status asc,tender.timeadd asc,tender.is_approve asc,tender.is_sign asc,allot.status asc",
        ];
        if(!empty($this->commonModel->tenderId))$whereParams["where"]["id"] = $this->commonModel->tenderId;
        $page = intval($this->commonModel->page)?intval($this->commonModel->page):-1;
        $number = intval($this->commonModel->number)>0?intval($this->commonModel->number):12;
        return $this->getAllTenderOrderList($whereParams,$page,$number);
        
    }

     /*
        * 所有的贷款订单列表
        *        根据指定的条件查询所有贷款订单列表
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
    public function getAllTenderOrderList($params = [],$page = -1,$number = 12){
        $model = new \LoanTenderModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        //订单的开始-截止日期
         if(!empty($paramWhere["startTime"]) && empty($paramWhere["endTime"])){
            $where["tender.timeadd"] = ["egt",$paramWhere["startTime"]];
        }elseif(empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["tender.timeadd"] = ["elt",$paramWhere["endTime"]];
        }elseif(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["tender.timeadd"] = ["between",[$paramWhere["startTime"],$paramWhere["endTime"]]];
        } 
        
        //订单ID
        if(isset($paramWhere["id"]) && !empty($paramWhere["id"])){
            $where["tender.id"] =$paramWhere["id"];
        }
        //产品ID
        if(isset($paramWhere["busLoanId"]) && !empty($paramWhere["busLoanId"])){
            $where["bus_l.id"] =$paramWhere["busLoanId"];
        }
        //渠道公司名称
        if(isset($paramWhere["chaCompanyname"]) && !empty($paramWhere["chaCompanyname"])){
            $where["cha.companyname"] = ["like","%{$paramWhere["chaCompanyname"]}%"];
        }
        //渠道公司ID
        if(isset($paramWhere["channelCompanyId"]) && !empty($paramWhere["channelCompanyId"])){
            $where["cha.id"] = $paramWhere["channelCompanyId"];
        }
        //合作公司名称
        if(isset($paramWhere["busCompanyname"]) && !empty($paramWhere["busCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["busCompanyname"]}%"];
        }
        //合作公司ID
        if(isset($paramWhere["busCompanyId"]) && !empty($paramWhere["busCompanyId"])){
            $where["bus.id"] = $paramWhere["busCompanyId"];
        }
        //手机号
        if(isset($paramWhere["mobile"]) && !empty($paramWhere["mobile"])){
            $where["m.mobile"] =$paramWhere["mobile"];
        }
        //memberID
        if(isset($paramWhere["memberId"]) && !empty($paramWhere["memberId"])){
            $where["m.id"] =$paramWhere["memberId"];
        }
        //催收状态0：全部 1：未处理  2：催款成功   3：催款失败
        if(!empty($paramWhere["urgeStatus"]) && $paramWhere["urgeStatus"]==1){
            $where["allot.id"] = ["exp","not in(select allot_id from allot_urge)"];
        }elseif(!empty($paramWhere["urgeStatus"]) && $paramWhere["urgeStatus"]==2){
            $where["allot.id"] = ["exp","in(select allot_id from allot_urge where urge_type=2)"];
        }elseif(!empty($paramWhere["urgeStatus"]) && $paramWhere["urgeStatus"]==3){
            $where["allot.id"] = ["exp","in(select allot_id from allot_urge where urge_type=3 and tender_id not in(select tender_id from allot_urge where urge_type=2))"];
        }
        
        //姓名
        if(isset($paramWhere["names"]) && !empty($paramWhere["names"])){
            $where["mi.names"] = ["like","%{$paramWhere["names"]}%"];
        }
        //是否已结清(还款) 0:未还款   1：已还款
        if(isset($paramWhere["allotStatus"])){
            $where["allot.status"] =$paramWhere["allotStatus"];
        }
        //订单是否逾期 0:全部  1:订单已逾期  2：订单未逾期
        if(isset($paramWhere["isLate"]) && !empty($paramWhere["isLate"])){
            switch($paramWhere["isLate"]){
                case 1:$where["allot.late_fee"] = ["exp",">'0' or (allot.status=0 and allot.end_time<NOW())"];break;
                case 2:$where["allot.late_fee"] = ["exp","='0' and (allot.status=1 or (allot.status=0 and allot.end_time>=NOW()))"];break;
                default:break;
            }
        }
        //订单状态  1:申请中  2：成单  3：拒单
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            $where["tender.status"] =$paramWhere["status"];
        }
        //订单分类 orderStatus  0：全部贷款   1：审核中   2：未结清   3：已结清  4:未完成（订单未有结果）
        if(!empty($paramWhere["orderStatus"])){
            switch ($paramWhere["orderStatus"]) {
                case 1:$where["_string"] = " tender.status=1 and ";break;
                case 2:$where["_string"] = " tender.status=2 and allot.status=0 and ";break;    
                case 3:$where["_string"] = " tender.status=2 and allot.status=1 and ";break;
                case 4:$where["_string"] = " (tender.status=1  or (tender.status=2 and allot.status=1)) and ";break;
                default:break;
            }
        }
        //订单状态
        $orderStatusName = "case when tender.status=1 and tender.is_sign=0 and tender.is_approve=0 and allot.status=0 then '待审核' "
                . " when tender.status=1 and tender.is_sign=0 and tender.is_approve=1 and allot.status=0 then '待签约' "
                . " when tender.status=1 and tender.is_sign=1 and tender.is_approve=1 and allot.status=0 then '待打款' "
                . " when tender.status=2 and tender.is_sign=1 and tender.is_approve=1 and allot.status=0 then '待还款' "
                . " when tender.status=2 and tender.is_sign=1 and tender.is_approve=1 and allot.status=1 then '已结清' "
                . " when tender.status=3 then  '已拒单' " 
                . "end as order_status_name";
        
        $fields = "tender.*,"
                . "allot.id as loan_allot_id,allot.late_fee,allot.start_time,allot.end_time,allot.back_real_time,allot.late_days,allot.back_real_money,allot.repayment_money,allot.status as allot_status,"
                . "bus.companyname as bus_companyname,bus.id as business_company_id,bus.business_number,"    
                . "cha.companyname as cha_companyname,cha.id as channel_company_id,"
                . "bus_l.name as bus_l_name,bus_l.capital_loan_id,bus_l.periode_rate,bus_l.late_periode_rate,if(bus_l.loan_repayment_id=2,'本息到付','') as repayment_name,"
                . "m.mobile,"
                . "mi.names,concat(lp.periode,lp.unit) as lp_name,mi.certiNumber,{$orderStatusName}";
        
        $where["_string"].= " tender.business_loan_id=bus_l.id  "
                . "and tender.id=allot.tender_id "
                . "and tender.memberid=m.id "
                . "and m.id=mi.memberid "
                . "and bus_l.business_company_id=bus.id  "
                . "and bus_l.loan_periode_id=lp.id "
                . "and bus.id=bc.business_company_id and bc.channel_company_id=cha.id  ";
        
        $table = "loan_tender tender,"
                . "loan_allot allot,"
                . "member m,"
                . "member_info mi,"
                . "business_loan bus_l,"
                . "loan_periode lp,"
                . "business_channel bc,"
                . "business_company bus,"
                . "channel_company cha";
        $limit = $page===-1?"":($page-1)*$number.",".$number;
        
        $count = $model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->count();
        $list = (array)$model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit($limit)
                            ->select();
        
        foreach($list as &$val){
            if($val["order_status_name"]=="待还款"){
                $lateInfo = (new LoanIssue($this->commonModel, ["loanId"=>$val["business_loan_id"]]))->calculateLateFee($val["id"]);
                $val["late_days"] = $lateInfo["late_days"];
                $val["late_fee"] = $lateInfo["late_fee"];
            }
        }
        //var_dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list];                    
        
    }
    
    /*
     * 订单审核通过-之前
     *      
     */
    protected function beforeOrderPass($tenderId,$params = []){
        //订单信息
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        //商户公司可用额度
        $businessLoan = (new BusinessLoan($this->commonModel))->getAllBusinessLoanList(["where"=>["id"=>$tenderInfo["business_loan_id"]]],-1);
        $quotaInfo = (new Capital($this->commonModel))->getAllCapitalList(["where"=>["businessId"=>$businessLoan[0]["business_company_id"],"capitalId"=>$businessLoan[0]["cap_company_id"]]], -1);
        
        if($quotaInfo[0]["realAvailQuotaMoney"]<$tenderInfo["money"]){
            $this->error("放款额度不足，请添加保证金,当前额度：{$quotaInfo[0]["realAvailQuotaMoney"]}元");
        }
        
    }
    /*
     * 订单审核通过
     *          计息日从订单审核通过第二天开始计息
     *  @param int $tenderId tenderID
     *  @param array $params  其他参数
     *  @return boolean
     * 
     */
    public function orderPass($tenderId,$params = []){
        $this->beforeOrderPass($tenderId,$params);
        if(empty($params["author"]))$this->error("审核人姓名不能为空");
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        $calFee = $this->calculateFee($tenderInfo["business_loan_id"], $tenderInfo["money"]);
        //资金方
        $capitalList = (new CapitalLoan($this->commonModel))->getAllCapitalLoanList(["where"=>["id"=>$tenderInfo["capital_loan_id"]]],-1);
        $capitalInfo = [
            "capital_legal_name"=>$capitalList[0]["capital_legal_name"],
            "capital_legal_certinumber"=>$capitalList[0]["capital_legal_certinumber"],
            "capital_company_address"=>$capitalList[0]["capital_company_address"],
            ];
        $tenderInfo = array_merge($tenderInfo,$calFee,$capitalInfo);
        //dump(array_merge($tenderInfo,$calFee,$capitalInfo));exit;
        //生成PDF文档
        import("Think.ORG.Util.PDF");
        \PDF::makenew(array_merge($tenderInfo,$calFee,$capitalInfo));
        \PDF::makenew1(array_merge($tenderInfo,$calFee,$capitalInfo));
        //审核通过
        $model = new \LoanTenderModel();
        if(!($tenderInfo["status"]==1 && $tenderInfo["is_approve"]==0 && $tenderInfo["is_sign"]==0)){
            $this->error("此订单未处于待审核中");
        }
        if($tenderInfo["is_approve"]==1){
            $this->error("此订单已审核通过");
        }
        try {
            $trans = false;
            if (!$model->inTrans()) {
                $model->startTrans();
                $trans = true;
            }
            
            if(false==$model->where(["id"=>$tenderId])->save(["is_approve"=>1,"remark"=>$params["remark"],"author"=>$params["author"],"approve_time"=>date("Y-m-d H:i:s")])){
                throw new Exception("没审核通过(101)");
            }
            $allotSave = [
                "start_time"=>$calFee["start_time"],//开始时间
                "end_time"=>$calFee["end_time"],//结束时间
            ];
            if(false==M("loan_allot")->where(["tender_id"=>$tenderId])->save($allotSave)){
                throw new Exception("没审核通过(102)");
            }
            if ($trans) {
    		$model->commit();
            }
            $this->success("操作成功");
        } catch (Exception $exc) {
            $error = $ex->getMessage();
            if ($trans) {
                $model->rollback();
            }
            $this->error($error);
        }

        
    }
    
    /*
     * 拒单
     *    @param int $tenderId tenderID
     *    @param array $params 其他参数
     *    @return 
     *      
     */
    public function orderDeny($tenderId,$params = []){
        if(empty($params["author"]))$this->error("审核人姓名不能为空");
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        $this->getLog("tender_{$tenderId}")->info("参数：tenderId:{$this->tenderId},其他参数". json_encode($params));
        if($tenderInfo["status"]!=1){
            $this->getLog("tender_{$tenderId}")->error("此订单未处于待审核中，无法拒单;data:".json_encode($tenderInfo));
            $this->error("此订单未处于待审核中");
        }
        $model = new \LoanTenderModel();
        if(false==$model->where(["id"=>$tenderId])->save(["status"=>3,"remark"=>$params["remark"],"author"=>$params["author"],"approve_time"=>date("Y-m-d H:i:s")])){
            $this->getLog("tender_{$tenderId}")->error("拒单失败sql:".$model->getLastSql());
            $this->error("处理拒单失败！网络超时，请稍后再试");
        }else{
            $this->getLog("tender_{$tenderId}")->info("拒单成功");
            $costResult = (new BusinessCostOut($this->commonModel))->createCostOrder($tenderId);
            $this->getLog("tender_{$tenderId}")->info("拒单分润:". json_encode($costResult));
            $this->success("拒单成功");
        }
    }
   
    /*
     * 签约发送验证码
     *       @param $tenderId:loan_tenderID
     *      
     */
    public function  orderSignSms(){
        if(false==($tenderId = $this->tenderId)){$this->error("tenderId不能为空");}
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        if(!($tenderInfo["status"]==1 && $tenderInfo["is_approve"]==1 && $tenderInfo["is_sign"]==0))$this->error("此订单未处于签约中");
        import("Think.ORG.Util.Eqian");
        $eqian = new \Eqian();
        $result = $eqian->sendSignCode($tenderInfo["memberid"]);
        if(true===$result){
            $this->getLog("tender_{$tenderId}")->info("验证码发送成功");
            return $this->success("验证码发送成功");
        }else{
            $this->getLog("tender_{$tenderId}")->error("签约验证码失败;return:".$eqian->getError());
            $this->error("验证码没发出去(".$eqian->getError().")");
        }
    }
    /*
     * 签约 
     *      @param $tenderId:loan_tenderID
     *      @param $code:验证码
     *      @return 
     */
    public function orderSign(){
        if(false==($tenderId = $this->tenderId)){
            $this->getLog("tender_{$tenderId}")->error("tenderId为空");
            $this->error("tenderId不能为空");
        }
        if(false==($code = $this->signCode)){
            $this->getLog("tender_{$tenderId}")->error("验证码为空");
            $this->error("验证码不能为空");
        }
        $this->getLog("tender_{$tenderId}")->info("参数：tenderId:{$this->tenderId},验证码：{$this->signCode}");
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        if(!($tenderInfo["status"]==1 && $tenderInfo["is_approve"]==1 && $tenderInfo["is_sign"]==0)){
             $this->getLog("tender_{$tenderId}")->error("订单未处于签约中".json_encode($tenderInfo));
             $this->error("此订单未处于签约中");
        }
        //签约
        import("Think.ORG.Util.Eqian");
        import("Think.ORG.Util.PDF");
        $pdfPath = \PDF::getPdfPath();
        $eqian = new \Eqian();
        $signFiles = [
            "0"=>["file"=> "{$pdfPath}{$tenderInfo["tender_number"]}_1.pdf","key"=>"乙方(盖章)"],
            "1"=>["file"=> "{$pdfPath}{$tenderInfo["tender_number"]}_2.pdf","key"=>"甲方(盖章)"],
        ];
        //借款人签章
        $result = $eqian->userMultiSignPDF($tenderInfo["memberid"],$signFiles,$code);
        if(true===$result){
            //出借人签章
            $legal1 = $eqian->selfSignPDF($tenderInfo["business_company_id"],$signFiles[0]["file"],"甲方(盖章)","legal");//借款协议-法人签章
            $otherLog = "借款协议-法人签章:return:{$legal1},error:".$eqian->getError();
            $company1 = $eqian->selfSignPDF($tenderInfo["business_company_id"],$signFiles[0]["file"],"丙方(盖章)","company");//借款协议-合作公司签章
             $otherLog.= "借款协议-合作公司:return:{$legal1},error:".$eqian->getError();
            $company2 = $eqian->selfSignPDF($tenderInfo["business_company_id"],$signFiles[1]["file"],"乙方(盖章)","company");//居间协议-合作公司签章
             $otherLog.= "居间协议-合作公司:return:{$legal1},error:".$eqian->getError();
            $this->getLog("tender_{$tenderId}")->info("其他签章；{$otherLog}");
        }else{
            $this->getLog("tender_{$tenderId}")->error("没签约成功;return:".$eqian->getError());
            $this->error("没签成功(".$eqian->getError().")");
        }
        $model = new \LoanTenderModel();
        if($model->where(["id"=>$tenderId])->save(["is_sign"=>1])){
            $this->getLog("tender_{$tenderId}")->info("签约成功");
            return $this->success("签约成功");
        }else{
            $this->getLog("tender_{$tenderId}")->error("没签成功,".$model->getDbError()."sql:".$model->getLastSql());
            $this->error("哎呀，没签成功");
        }
    }
    /*
     * 放款
     * 
     */
    public function orderPay($tenderId,$params = []){
        set_time_limit(0);
        $this->getLog("tender_{$tenderId}")->info("参数：tenderID：{$tenderId},params:".json_encode($params));
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        if(!($tenderInfo["status"]==1 && $tenderInfo["is_approve"]==1 && $tenderInfo["is_sign"]==1)){
            $this->getLog("tender_{$tenderId}")->error("订单未处于放款中:". json_encode($tenderInfo));
            $this->error("此订单未处于放款中");
        }
        $model = new \LoanTenderModel();
        $payObject = new PayPlat($this->commonModel,["businessCompanyId"=>$tenderInfo["business_company_id"],"memberId"=>$tenderInfo["memberid"]]);
        //创建支付订单
        $createOrder = [
                    "tender_number"=>$tenderInfo["tender_number"],//贷款订单号
                    "trans_money"=>$tenderInfo["pay_money"],//交易金额
                    "trans_type"=>1,//交易类型 1：打款  2：还款
                    "trans_content"=>"【{$tenderInfo["bus_companyname"]}-贷款-放款】",//交易内容
            ];
        if(false==($payOrderInfo = $payObject->createOrder($createOrder))){
            $this->getLog("tender_{$tenderId}")->error("支付订单生成失败:{$payOrderInfo}".$payObject->getError());
            $this->error("支付订单生成失败:".$payObject->getError());
        }
        $this->getLog("tender_{$tenderId}")->info("支付订单生成成功:".json_encode($payOrderInfo));
        $trans = false;
        if (!$model->inTrans()) {
            $model->startTrans();
            $trans = true;
        }
        if(false==($result=$payObject->pay($payOrderInfo))){
           $this->getLog("tender_{$tenderId}")->error("支付失败：".$payObject->getError());
           if($trans)$model->rollback ();
           $this->error($payObject->getError());
        }
        $remark = $tenderInfo["remark"]?"{$tenderInfo["remark"]}-打款成功：{$result["payPlat"]}":"打款成功：{$result["payPlat"]}";        
        $this->getLog("tender_{$tenderId}")->info("支付成功：{$remark}，支付返回：".json_encode($result));
        if(false==$model->where(["id"=>$tenderId])->save(["status"=> \LoanTenderModel::STATUS_SUCCESS,"success_time"=>date("Y-m-d H:i:s"),"remark"=>$remark])){
            $this->getLog("tender_{$tenderId}")->error("打款超时-未成功修改订单状态".$model->getDbError()."sql:".M()->getLastSql());
            if($trans)$model->rollback ();
            $this->error("打款超时，请稍后再次打款");
        }
        $this->getLog("tender_{$tenderId}")->info("订单支付-打款成功-订单状态修改成功");
        if ($trans) {
            $model->commit();
        }
        $costResult = (new BusinessCostOut($this->commonModel))->createCostOrder($tenderId);
        $this->getLog("tender_{$tenderId}")->info("成单分润:". json_encode($costResult));
        $this->success("打款成功");
    }
    
    /*
     * 还款
     *    @param int $tenderId tenderID
     *    @param array $params 其他参数值
     *    
     *    @return 
     */
    public function orderRepayment($tenderId = null,$params = []){
        set_time_limit(0);
        $tenderId = $tenderId?$tenderId:$this->commonModel->tenderId;
        $this->getLog("tender_{$tenderId}")->info("参数：tenderID：{$tenderId},params:".json_encode($params));
        if(empty($tenderId))$this->error("订单ID为空");
        $tenderInfo = $this->getAllTenderOrderList(["where"=>["id"=>$tenderId]],-1)["list"][0];
        if($tenderInfo["status"]==2 && $tenderInfo["allot_status"]==1)$this->error("您已还过款了！");
        if(!($tenderInfo["status"]==2 && $tenderInfo["allot_status"]==0))$this->error("此订单未处于还款中");
        $model = new \LoanAllotModel();
        $repaymentMoney = $tenderInfo["repayment_money"]+$tenderInfo["late_fee"];//还款金额
        $payObject = new PayPlat($this->commonModel,["businessCompanyId"=>$tenderInfo["business_company_id"],"memberId"=>$tenderInfo["memberid"]]);
        //创建支付订单
        $createOrder = [
                    "tender_number"=>$tenderInfo["tender_number"],//贷款订单号
                    "trans_money"=>$repaymentMoney,//交易金额
                    "trans_type"=>2,//交易类型 1：打款  2：还款
                    "trans_content"=>"【{$tenderInfo["bus_companyname"]}-贷款-还款】",//交易内容
        ];
        if(false==($payOrderInfo = $payObject->createOrder($createOrder))){
            $this->getLog("tender_{$tenderId}")->error("还款订单生成失败:{$payOrderInfo}".$payObject->getError()."-data:".json_encode($createOrder));
            $this->error($payObject->getError());
        }
        $this->getLog("tender_{$tenderId}")->info("还款订单生成成功");
        //开始还款
        if(false==($result=$payObject->repayment($payOrderInfo))){
            $this->getLog("tender_{$tenderId}")->error("还款失败：".$payObject->getError());
            $this->error("还款失败".$payObject->getError());
        }
        $this->getLog("tender_{$tenderId}")->info("还款成功：扣款金额：{$repaymentMoney}");
        $allotSave = [
            "late_fee"=>round($tenderInfo["late_fee"],2),
            "late_days"=>intval($tenderInfo["late_days"]),
            "status"=>"1",
            "back_real_money"=>$repaymentMoney,
            "back_real_time"=>date("Y-m-d H:i:s"),
            ];
        if(false==$model->where(["tender_id"=>$tenderId])->save($allotSave)){
            $this->error("交易超时，请稍后再次还款");
        }
        $this->success("还款成功");
    }
    



    /*
     *产品列表
     *       当前商户公司正常状态的产品列表
     *      
     *      @param int $companyId:商户公司ID
     *      @return array
     */
    public function getBusinessLoanList($companyId = null){
        $companyId = $companyId?$companyId:$this->business_company_id;
        if($this->_loanList===null){
            $businessModel = new \BusinessLoanModel();
            $where = [
                "bus_l.business_company_id"=>$companyId,
                "bus_l.status"=>$businessModel::STATUS_ENABLE,
                "_string"=>"bus_l.loan_periode_id=lp.id and bus_l.loan_repayment_id=lr.id",
        ];
        $this->_loanList = (array)$businessModel->table("business_loan bus_l,loan_periode lp,loan_repayment lr")
                                                ->field("bus_l.*,lp.id as lp_id,concat(lp.periode,lp.unit) as periodeName,lr.name as repaymentName")
                                                ->where($where)
                                                ->order("lp.type asc,lp.periode asc")
                                                ->select();
        //var_dump(M()->getLastSql());
        }
        
        return $this->_loanList;
    }
    
    //获取member对象
    public function getMember(){
        if($this->member===null){
            $this->member = new Member($this->commonModel);
        }
        return $this->member;
    }
    
    
    
    //订单状态
    public function getTenderStatusList(){
        $tenderModel = new \LoanTenderModel();
        return [
            "0"=>"全部",
            $tenderModel::STATUS_APPLY=>"申请中",
            $tenderModel::STATUS_SUCCESS=>"成单",
            $tenderModel::STATUS_FAIL=>"拒单",
        ];
    }
    
}
