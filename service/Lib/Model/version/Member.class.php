<?php
/*
 * 会员处理类
 * 
 */
namespace version;

use version\Object;
use version\Sms;

class Member extends Object{
    public $allowAppMethods = ["register","login","sendRegisterSms","isLogin","bankCardStatus",
        "reSetPassword","reSetPasswordSms","getMemberInfo","preBindBankCard","bindBankCardSubmit",
        "getBankNames","changePassword","getBusinessContent","loginOut"];
    //登录前的事件函数
    const EVENT_BEFORE_LOGIN = "beforeLogin";
    //登录后的事件函数
    const EVENT_AFTER_LOGIN="afterLogin";
    
    const CACHE_TIMES = 259200;//默认缓存3天的时间
   
    public $wxOpenId;//微信openID
    
    public $mobile;//手机号
    
    public $password;//密码
    
    public $repassword;//确认密码
    
    public $business_company_id;//公司ID
    
    public $sms_code;//验证码
    
    private $token;//token

    public $isAutoLogin = 1;//是否自动登录  0：否  1：是
    
    private $_isUniqueLogin = 1;//是否只允许一个设备登录，默认为1是
    
    public $baofu;//宝付(Baofu.class.php类)对象
    
    public function init() {
        parent::init();
        $this->token = $this->commonModel->getToken();
    }
    
    
    /*
     * 会员注册
     */
    public function register(){
        $model = new \MemberModel();
        $model->commonModel = $this->commonModel;
        if($model->create($model->commonModel)){
            if($addId = $model->add()){
                //return $this->success("注册成功",["add_id"=>$addId]);
                //注册成功后是否直接登陆
                return $this->login();
            }else{
                $this->error("哎呀，没注册成功(501)");
            }
        }else{
            $this->error($model->getError());
        }
    }
    //会员注册发送验证码
    public function sendRegisterSms(){
        $memberModel = new \MemberModel();
        $memberModel->commonModel = $this->commonModel;
        $mobile = $this->commonModel->mobile;
        if(empty($mobile))$this->error("手机号不能为空");
        $businessCompanyId = $this->commonModel->business_company_id;
        if(empty($businessCompanyId))$this->error("商户公司ID不能为空");
        if(false==$memberModel->mobileUnique($mobile)){
            $this->error("手机号已被注册");
        }
        Sms::$staticCommonModel = $this->commonModel;
        $smsResult = Sms::sendVerify($mobile, "sendRegisterSms");
        if(true===$smsResult){
            return $this->success("验证码发送成功");
        }
    }
    
    /*
     * 会员登录
     */
    public function login(){
        if(empty($this->mobile))$this->error("请输入手机号");
        if(empty($this->password))$this->error("请输入密码");
        if(empty($this->business_company_id))$this->error("商户公司ID不能为空");
        $preKey = $this->business_company_id.$this->mobile;
        $arrLoginError = $this->getCache($preKey."loginError");
        if(intval($arrLoginError["errorCount"])>5){
            $minutes = ceil((time()-$arrLoginError["errorTime"])/60);
            $this->error("您密码错误次数过多，请{$minutes}分钟后再登录");
        }
        
        $model = new \MemberModel();
        $result = $model->where(["mobile"=>$this->mobile,"business_company_id"=>$this->business_company_id,"password"=>$model->encPassword($this->password)])->find();
        if(false==$result){
            $this->setloginErrorSession($preKey."loginError");
            $this->error("用户名或密码错误！");
        }
        if($result["status"]==$model::STATUS_DISABLE){
            $this->error("对不起，您的账号已被禁用!");
        }
        $this->token = md5("{$preKey}".time());
        $this->saveToken($result);
        $this->refreshCache($result["id"]);
        return $this->success("登录成功",["token"=>$this->token]);
    }
    /*
     * 修改会员的状态
     *         1：启用   9：禁用
     * @param 
     */
    public function  memberStatus($memberId){
        $memberModel = new \MemberModel();
        if(false==$memberId){$this->error("会员ID为空");}
        if(false!=($memberInfo = $memberModel->where(["id"=>$memberId])->find())){
            $status = $memberInfo["status"]==1?\MemberModel::STATUS_DISABLE:\MemberModel::STATUS_ENABLE;
            if(false==$memberModel->where(["id"=>$memberId])->save(["status"=>$status])){
                $this->error("会员状态未修改成功");
            }else{
                $this->success("操作成功");
            }
        }else{
            $this->error("会员未注册");
        }
    }
    
    //退出登录
    public function loginOut(){
        if($this->isLogin()){
            $this->setCache($this->token,null);
            M("member_token")->where(["token"=>$this->token])->delete();
            return $this->success("您已成功退出系统");
        }
        $this->error("您已退出系统");
    }
    /*
     * 找回密码
     */
    public function reSetPassword(){
        $memberModel = new \MemberModel();
        $mobile = $this->commonModel->mobile;
        if(empty($mobile))$this->error("手机号不能为空");
        $businessCompanyId = $this->commonModel->business_company_id;
        if(empty($businessCompanyId))$this->error("商户公司ID不能为空");
        $password = $this->commonModel->password;
        if(empty($password))$this->error("新密码不能为空");
        $sms_code = $this->commonModel->sms_code;
        if(empty($sms_code))$this->error("验证码不能为空");
        Sms::$staticCommonModel = $this->commonModel;
        if(false==SMS::checkVerify($this->commonModel->mobile,$sms_code,"reSetPasswordSms")){
            $this->error("验证码不正确");
        }
        if($memberModel->where(["mobile"=>$mobile,"business_company_id"=>$businessCompanyId])->save(["password"=>$memberModel->encPassword($password)])){
            return $this->success("密码找回成功");
        }else{
            $this->error("哎呀，网络连接超时，请稍后再找回密码");
        }
        
    }
    //修改密码
    public function changePassword(){
        if(false==($memberInfo = $this->getMemberInfo())){
            $this->error("用户未登录");
        }
        $oldPassword = $this->commonModel->old_password;//原密码
        $newPassword = $this->password;//新密码
        $rePassword = $this->repassword;//确认密码
        if(empty($oldPassword))$this->error("原密码不能为空");
        if(empty($newPassword))$this->error("新密码不能为空");
        if(empty($rePassword))$this->error("确认密码不能为空");
        if($newPassword!=$rePassword)$this->error("新密码和确认密码不一致");
        $model = new \MemberModel();
        if(false==$model->where(["id"=>$memberInfo["id"],"password"=>$model->encPassword($oldPassword)])->find())$this->error("原密码错误");
        if(false==$model->where(["id"=>$memberInfo["id"]])->save(["password"=>$model->encPassword($newPassword)]))$this->error("密码未修改成功，请稍后再试");
        $this->success("密码修改成功");
    }
    
    //找回密码-发送验证码
    public function reSetPasswordSms(){
        $memberModel = new \MemberModel();
        $memberModel->commonModel = $this->commonModel;
        $mobile = $this->commonModel->mobile;
        if(empty($mobile))$this->error("手机号不能为空");
        $businessCompanyId = $this->commonModel->business_company_id;
        if(empty($businessCompanyId))$this->error("商户公司ID不能为空");
        if(true==$memberModel->mobileUnique($mobile)){
            $this->error("手机号未被注册");
        }
        Sms::$staticCommonModel = $this->commonModel;
        $smsResult = Sms::sendVerify($mobile, "reSetPasswordSms");
        if(true===$smsResult){
            return $this->success("验证码发送成功");
        }
    }
    //关于公司介绍
    public function getBusinessContent(){
        if(false==($memberInfo = $this->getMemberInfo())){
            $this->error("用户未登录");
        }
        return (new \BusinessCompanyModel)->where(["id"=>$memberInfo["business_company_id"]])->find();
    }
    
    
    //是否登录
    public function isLogin(){
        $isLogin = $this->token!==null && $this->getMemberInfo();
        if($isLogin){
            return $this->success("用户已登录",["loginStatus"=>intval($isLogin)]);
        }else{
            return $this->error("用户未登录",["loginStatus"=>intval($isLogin)]);
        }
        
    }
   /*
    *  绑定银行卡-发送验证码
    *   
     */
    public function preBindBankCard(){
        if(false==($memberInfo = $this->getMemberInfo())){
            $this->error("您未登录");
        }
        $paramsCard = [
                        "acc_no"=>"银行卡号不能为空",
                        "id_card"=>"身份证号不能为空",
                        "mobile"=>"银行预留电话不能为空",
                        "bank_name"=>"开户银行名称不能我空",
                        "names"=>"持卡人姓名不能为空",
                        ];
        $cardData = [];
        foreach($paramsCard as $key=>$val){
            if(empty($this->commonModel->$key)){
                $this->error($val);
            }else{
                $cardData[$key] = $this->commonModel->$key;
            }
        }
        
        $result = $this->getBaofu($memberInfo["id"])->preBindCard($cardData);
        if(false==$result)$this->error($this->getBaofu($memberInfo["id"])->getError());
        return $this->success("验证码发送成功");
    }
    //提交绑定的银行卡
    public function bindBankCardSubmit(){
        if(false==($memberInfo = $this->getMemberInfo())){
            $this->error("您未登录");
        }
        if(empty($this->commonModel->sms_code))$this->error("请输入验证码");
        $sms_code = $this->commonModel->sms_code;
        $result = $this->getBaofu($memberInfo["id"])->preBindCardSubmit($sms_code);
        if(false==$result)$this->error($this->getBaofu($memberInfo["id"])->getError());
        $this->refreshCache($memberInfo["id"]);
        return $this->success("银行卡绑定成功");
    }
    //开户银行列表
    public function getBankNames(){
        if(false==($memberInfo = $this->getMemberInfo())){
            $this->error("您未登录");
        }
        return $this->getBaofu($memberInfo["id"])->getBankNames();
    }
    
    /*
     * 是否绑定银行卡
     *  @param  int $memberId 会员ID
     *  @return boolean
     */
    public function  bankCardStatus($memberId){
        $baofu = $this->getBaofu($memberId);
        return $baofu->bindBankCardStatus($bankInfo["acc_no"]);
    }
    
    //获取宝付对象
    public function getBaofu($memberId){
        if($this->baofu===null){
            import("Think.ORG.Util.Baofu");
            $this->baofu = new \Baofu($memberId);
        }
        return $this->baofu;
    }
    /*
     * 获取会员信息
     *      --Token方式获取会员信息
     *      --可以标记自动登录
     */
    public function getMemberInfo(){
        if(false==$this->token){
            return false;
        }
        if(false!=($memberInfo = $this->getCache($this->token))){
            return $memberInfo;
        }
        $tokenInfo = M("member_token")->where(["token"=>$this->token])->find();
        if(false==$tokenInfo)return false;
        if($tokenInfo["autologin"]==1){
            return $this->refreshCache($tokenInfo["memberid"]);
        }
       return false;
    }
    /*
     * 获取所有用户列表
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
     * 
     */
    public function getAllMemberList($params = [],$page = -1,$number = 12){
        $model = new \MemberModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        
        if(!empty($paramWhere["startTime"]) && empty($paramWhere["endTime"])){
            $where["m.timeadd"] = ["egt",$paramWhere["startTime"]];
        }elseif(empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["m.timeadd"] = ["elt",$paramWhere["endTime"]];
        }elseif(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["m.timeadd"] = ["between",[$paramWhere["startTime"],$paramWhere["endTime"]]];
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
        //手机号
        if(isset($paramWhere["mobile"]) && !empty($paramWhere["mobile"])){
            $where["m.mobile"] =["like","%{$paramWhere["mobile"]}%"];
        }
        //memberID
        if(isset($paramWhere["memberId"]) && !empty($paramWhere["memberId"])){
            $where["m.id"] =$paramWhere["memberId"];
        }
        //状态 1:正常  9：禁用  3:存量客户
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            if($paramWhere["status"]==3){
                return (new MemberImport($this->commonModel))->importMemberList($params,$page,$number);
            }
            $where["m.status"] =$paramWhere["status"];
        }
        //姓名
        if(isset($paramWhere["names"]) && !empty($paramWhere["names"])){
            $where["mi.names"] = ["like","%{$paramWhere["names"]}%"];
        }
        $where["_string"] = "m.id=mi.memberid "
                . "and m.business_company_id=bus.id  "
                . "and  bus.id=bc.business_company_id  "
                . "and bc.channel_company_id=cha.id";
        
        $fields = "m.*,"
                . "mi.names,mi.certiNumber,mi.nameStatus,"
                . "cha.companyname as channel_companyname,"
                . "bus.companyname as business_companyname";
        $table = "member m,member_info mi,"
                . "business_company bus,business_channel bc,"
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
        $memberImportModel = new \memberImportModel();
        foreach($list as &$member){
            $importInfo = $memberImportModel->getImportMember($member["business_company_id"], $member["certiNumber"]);
            $member["acc_no"] = M("baofu_bindcard")->where(["memberid"=>$member["id"]])->getField("acc_no");//银行卡号
            $member["promise_money"] = $importInfo["promise_money"];//预授信额度
            $member["member_import_id"] = $importInfo["id"];
        }
        //dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list];     
    }
    
    
    
    //微信openid登陆
    public function loginByWx(){
        if(false==$this->preLogin())return false;
        
    }
    //自动登录
    public function autoLogin(){
        
    }
    
    /*
     * 设置登录session
     * 
     */
    private function setloginErrorSession($key,$time = 600){
         $arrLoginErr = (array)$this->getCache($preKey."loginError");
         $arrLoginErr["errorCount"] = intval($arrLoginErr["errorCount"])+1;
         $arrLoginErr["errorTime"] = $arrLoginErr["errorTime"]?$arrLoginErr["errorTime"]:time();
         $this->setCache($key, $arrLoginErr,$time);
    }
    //保存token
    private function saveToken($memberInfo){
        if($tokenInfo = M("member_token")->where(["memberid"=>$memberInfo["id"]])->find()){
            if($this->_isUniqueLogin==1){//限制设备唯一
                $this->setCache($tokenInfo["token"], null);
            }
            $save = [
                "token"=>$this->token,
                "login_time"=>date("Y-m-d H:i:s"),
                "autologin"=>$this->isAutoLogin,
            ];
            return M("member_token")->where(["id"=>$tokenInfo["id"]])->save($save);
        }else{
            $add = [
                "token"=>$this->token,
                "memberid"=>$memberInfo["id"],
                "username"=>$memberInfo["username"],
                "login_time"=>date("Y-m-d H:i:s"),
                "autologin"=>$this->isAutoLogin,
            ];
            return M("member_token")->add($add);
        }
    }
    //存到cache中的数据
    private function getCacheData($memberId){
        $memberData = M("member_info i,member m")->field("m.id,m.business_company_id,m.username,m.mobile,m.wx_openid,m.timeadd,i.names,i.nameStatus,i.certiNumber")
                ->where("memberid='{$memberId}' and m.id=i.memberid")->find();
        $bindBaofuBankInfo = $this->bankCardStatus($memberData["id"]);
        $memberData["bankStatus"] = false==$bindBaofuBankInfo?0:1;//是否绑定银行卡
        $memberData["acc_no"] = $memberData["bankStatus"]==1?substr_replace($bindBaofuBankInfo["acc_no"],"**********",4,10):"";//银行卡号
        $memberData["bank_name"] = $bindBaofuBankInfo["bank_name"];
        $memberData["bank_mobile"] = $bindBaofuBankInfo["mobile"];
        $memberData["company_mobile"] = (new \BusinessCompanyModel())->getBusinessCompany($memberData["business_company_id"])["company_mobile"];//公司400电话
        $memberData["loan_money"] = round(M("loan_tender tender,loan_allot allot")->where(["tender.status"=>2,"allot.status"=>0,"tender.memberid"=>$memberId,"_string"=>"tender.id=allot.tender_id"])->sum("tender.money"),2);//借款金额
        $repaymentMoney = (new LoanOrder($this->commonModel))->getAllTenderOrderList(["where"=>["memberId"=>$memberId,"status"=>2,"allotStatus"=>0]], -1);
        $memberData["repayment_money"] = round(array_sum(array_column($repaymentMoney["list"],"repayment_money"))+array_sum(array_column($repaymentMoney["list"],"late_fee")),2);//待还款金额
        return $memberData;
    }
    //刷新cache中的数据
    public function refreshCache($memberId){
        if($this->token){
            $memberInfo = $this->getCacheData($memberId);
            $this->setCache($this->token,null);
            $this->setCache($this->token, $memberInfo,self::CACHE_TIMES);
            return $memberInfo;
        }
        return false;
    }
    
}
