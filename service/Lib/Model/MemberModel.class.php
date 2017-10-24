<?php
/*
 * 会员处理类
 * 
 */

class MemberModel extends Model{
    //加密Key
    const ENCRYKEY = "2302ec25b6c1a0d647e8c8405cba654d";
	
	const STATUS_ENABLE = 1;//会员正常状态
	
    const STATUS_DISABLE = 9;//会员禁用状态
	
    //数据表名称
    protected $trueTableName = "member";
    
    public $commonModel;
    /*
     * 自动验证
     */
    protected $_validate = [
        ["mobile","require","手机号不能为空",self::MUST_VALIDATE],
        ["business_company_id","require","business_company_id不能为空",self::MUST_VALIDATE],
        ["business_company_id","checkBusinessCompany","business_company_id不正确",self::MUST_VALIDATE,"callback"],
        ["sms_code","require","验证码不能为空",self::MUST_VALIDATE],
        ["password","require","密码不能为空",self::MUST_VALIDATE],
        ["repassword","require","确认密码不能为空",self::MUST_VALIDATE],
        ['password','repassword','两次密码不一致',1,'confirm'],
        ["mobile","mobileUnique","手机号已被注册",1,"callback"],
        ["sms_code","checkSmsCode","验证码不正确",1,"callback"],
    ];
    //自动完成
    protected $_auto = [
        ["password","encPassword",self::MODEL_BOTH,"callback"],
        ["username","mobile",self::MODEL_INSERT,"field"],
        ["mobile_location","getMobileLocation",self::MODEL_INSERT,"callback"],
        ["last_useragent","getUseragent",self::MODEL_BOTH,"callback"],
        ["source_client","getSourceClient",self::MODEL_INSERT,"callback"],
        ["source_useragent","getUseragent",self::MODEL_INSERT,"callback"],
        ["invitecode","createInviteCode",self::MODEL_INSERT,"callback"],
        ["lastip","getLastip",self::MODEL_BOTH,"callback"],
    ];


    /*
     * 判断手机号是否唯一
     *          手机号是否已被注册
     * 
     * 被注册返回false，否则返回true
     */
    public function mobileUnique($mobile,$company_id = null){
        $company_id = $company_id?$company_id:$this->commonModel->business_company_id;
        if(empty($company_id))return false;
        if(false!=M("member")->where("mobile='{$mobile}' and mobile!='' and business_company_id='{$company_id}' and business_company_id!=''")->find()){
            return false;
        }else{
            return true;
        } 
    }
    //商户ID是否正确
    public function checkBusinessCompany($company_id){
        $companyModel = new \BusinessCompanyModel();
        if(false!=($businessCompany=$companyModel->getBusinessCompany($company_id))){
            return true;
        }
        return false;
    }
    //验证手机验证码是否正确
    public function checkSmsCode($smsCode){
        version\Sms::$staticCommonModel = $this->commonModel;
        return version\SMS::checkVerify($this->commonModel->mobile,$smsCode,"sendRegisterSms");
    }
    //客户使用设备信息
    public function getUseragent(){
       return $this->commonModel->httpUserAgent;
    }
    //注册使用的设备标识
    public function getSourceClient(){
         return $this->commonModel->client;
    }
    //获取用户的邀请码
    public function createInviteCode(){
        $invitecode = "";
        for($i=0;$i<6;$i++){
            $invitecode = str_shuffle(rand(111,999).rand(111,999));
            if(false==M("member")->where("invitecode='{$invitecode}'")->find()){
                break;
            }
        }
        return $invitecode;
    }
    public function getMobileLocation(){
        return get_mobile_location($this->commonModel->mobile);
    }
    public function getLastip(){
        return $this->commomModel->clientIp;
    }
    public function encPassword($password){
        return md5(static::ENCRYKEY.$password);
    }
//    //用户统计
//    public function memberCount(){
//        
//    }
    
}
