<?php
class Eqian{
    //public $project_id = '1111563517';
    //public $project_secret = '95439b0863c241c63a861b87d1e647b7';
    
    public $memberId;//用户个人ID
    
    public $businessCompanyId;//合作公司ID
    
    private $_error;//错误提示
    private $sign;//eqian对象
    private $accountId = [];//用户或企业accountId
    
    public function __CONSTRUCT() {
        vendor('Eqian.eSignOpenAPI');
        vendor('Eqian.core.eSign');
        vendor('Eqian.core.ErrorConstant');
        $this->sign = new tech\core\eSign();
        $this->sign->init();
    }
    
     /**
     * 普通用户签章
      *         ---个人账户签章
      *         --支持多个文档同时签章（上限10个）
      * @param int $memberId 用户ID
      * 
     * @parm  	$signFiles:待签署的文件数组
      *             eg.
      *                 [
      *                 0=>["file"=>"c:/aa.pdf",
      *                     "key"=>"甲方盖章"
      *                     ]
      *                 ],
      * 
      * @param string $key:关键字 
      *             eg.甲方【盖章】，pdf中所有出现此关键字的地方都会被盖上章
      * 
     *  @param $code:获取的验证码
      * 
      * @param $imageSavePath:印章图片保存路径
      * 
     * @return boolean  成功签章返回true,否则返回false
     * 			
     */
    public function userMultiSignPDF($memberId,$signFiles = [],$code,$imageSavePath = null){
         //创建个人账户
        if(false==($accountId = $this->addPersonAccount($memberId))){
            return false;
        }
        //生成个人印章
        if(false==($image64=$this->addTemplateSeal($accountId))){
            return false;
        }
        //生成图片印章
        $this->savePicSign($image64,$imageSavePath?$imageSavePath: dirname($signFiles[0]["file"])."/{$memberId}.png");
        /*
         * 开始签章
         *      @param string $accountId  账户唯一ID，必须
         *      @param array $signParams 待签章的文件数组，必须
         *      @param string $image64  印章图片字符串，必须传
         *      $mobile 接收验证码的手机号，可为空
         *      $code  验证码
         */
        $signType = "Key";//关键字签章
        $signParams = [];
        foreach($signFiles as $k=>$val){
          if(!is_file($val["file"])){$this->error = "文件不存在{$val["file"]}";return false;}
          $fileBean = ["srcPdfFile"=>$val["file"],"dstPdfFile"=>$val["file"],"fileName"=> basename($val["file"]),"ownerPassword"=>""];
          $signPos = ["posPage"=>'',"posX"=>"120","posY"=>"","key"=>$val["key"],"width"=>""];//签章位置
          $signParams[$k]  = ["signType"=>$signType,"fileBean"=>$fileBean,"signPos"=>$signPos];
        }
        $result = $this->sign->userMutilSignPDF($accountId, $signParams,$image64, $mobile = '', $code);
        if($result["errCode"]==0){
            return true;
        }else{//dump($result);
            $this->error = $result["msg"];
            return false;
        }
    }
    /*
     * 用户发送验证码
     *      $param $memberId：会员用户ID
     *      @return boolean
     *      验证码发送成功，返回true,否则返回false 
     */
    public function sendSignCode($memberId){
        //创建个人账户
        if(false==($accountId = $this->addPersonAccount($memberId))){
            return false;
        }
        $result = $this->sign->sendSignCode($accountId);
        if($result["errCode"]==0){
            return true;
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
    
    public function  getError(){
        return $this->error;
    }
	//注销个人账户
    public function delUserAccount($accountId){
        $result = $this->sign->delUserAccount($accountId);
        if($result['errCode']==0){
            return true;
        }else{
            $this->error = $result['msg'];
            return false;
        }
    }

    /*
     * 公司、个人、法人 单个文件盖章
     *      
     *    @param $id:个人或公司ID
     *    @param $srcFile:待签署的文件
     *    @param $key:关键字
     *    @param $type:member|company|legal  个人用户   合作公司  法人章
     *   
     */
    public function selfSignPDF($id,$srcFile,$key,$type = "member"){
        if($type=="member"  && false==($accountId = $this->addPersonAccount($id))){//个人账户
            return false;
        }elseif($type=="company"  && false==($accountId = $this->addOrganizeAccount($id))){//公司账户
            return false;
        }elseif($type=="legal"  && false==($accountId = $this->addLegalAccount($id))){//法人账户
            return false;
        }
        if(empty($accountId)){$this->error = "AccountID为空";return false;}
        //生成印章
        if(false==($image64=$this->addTemplateSeal($accountId,$type=="company"?"star":"rectangle"))){
            return false;
        }
        $signFile = ['srcPdfFile' =>$srcFile,'dstPdfFile' => $srcFile ,'fileName' => "",'ownerPassword' => ' ',];
        $signPos = ['posPage' =>"",'posX' =>"120" ,'posY' =>"",'key' =>$key,'width' =>"",];
        $result = $this->sign->userSignPDF($accountId,$signFile,$signPos,"Key",$image64);
        if($result["errCode"]==0){
            return true;
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
    


    /*
     * init项目初始化
     * $return boolean
     * */
    private function init() {
        $iRet = $this->sign->init();
        if(0 == $iRet["errCode"] && ($this->sign->projectid_login())){
            return true;
        }else{
            $this->_error= "初始化失败({$iRet["errCode"]},{$iRet['msg']})";
            return false;
        }
        
    }
    
    /**
     * @param $memberId
     * @return bool|string 
     *          成功时返回个人账户ID标识
     *          失败时返回false
     * 创建个人账户
     */
    private function addPersonAccount($memberId){
        if(!empty($this->accountId[$memberId]))return $this->accountId[$memberId];
        $member_info = M('member_info mi,member m')->where("m.id=mi.memberid and m.id='{$memberId}'")->find();
        if(empty($member_info["names"]) || empty($member_info["certiNumber"])){
            $this->error = "用户姓名或身份证号不能为空";
            return false;
        }
        $return = $this->sign->addPersonAccount($member_info['mobile'],$member_info['names'],$member_info['certiNumber']);
        if($return['errCode']==0){
            return $this->accountId[$memberId] = $return['accountId'];
        }else{
           $this->error = $return['msg'];
           return false;
        }
    }

    /**
     * @param $accountId e签登录id
     * @param $templateType 印章的类型   矩形|正方形|艺术字
     *         公司章则传输：star，标准公章
     * @return bool
     * 创建个人模板印章
     */
    private function addTemplateSeal($accountId,$templateType = "rectangle"){
        //参数说明 账户标识   印章类型   印章颜色
        $return = $this->sign->addTemplateSeal($accountId,$templateType,"red");
        if($return['errCode']==0){
            return $return["imageBase64"];
        }else{
            $this->error = $return['msg'];
            return false;
        }
        
    }
    /*
     * 创建企业账户
     *   $param $businessCompanyId  合作公司ID
     */
    private function addOrganizeAccount($businessCompanyId){
        if(!empty($this->accountId[$businessCompanyId]))return $this->accountId[$businessCompanyId];
        $companyInfo = M("business_company")->where(["id"=>$businessCompanyId])->find();
        if(empty($companyInfo["company_code"])){
            $this->error = "组织机构代码不能为空";
            return false;
        }
        //参数说明：机构名称    组织机构代码号    企业注册类型
        //$merge 表示是否三证合一
        $regType = $merge?1:0;//组织机构类型  0：组织机构代码号   1：多证合一
        $result = $this->sign->addOrganizeAccount($companyInfo["legal_mobile"],$companyInfo["companyname"],$companyInfo["company_code"],$regType);
        if($result["errCode"]==0){
            return $this->accountId[$businessCompanyId] = $result["accountId"];
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
    
    /*
     * 创建企业法人账户
     *          （企业法人个人账户）
     *   $param $businessCompanyId  合作公司ID
     *   @return accountID
     */
    private function addLegalAccount($businessCompanyId){
        $companyInfo = M("business_company")->where(["id"=>$businessCompanyId])->find();
        if(!empty($this->accountId[$companyInfo["legal_name"]]))return $this->accountId[$companyInfo["legal_name"]];
        if(empty($companyInfo["legal_name"]) || empty($companyInfo["legal_certinumber"]) || empty($companyInfo["legal_mobile"])){
            $this->error = "企业法人姓名、身份证号、手机号不能为空、不能为空";
            return false;
        }
        $return = $this->sign->addPersonAccount($companyInfo['legal_mobile'],$companyInfo['legal_name'],$companyInfo['legal_certinumber']);
        if($return['errCode']==0){
            return $this->accountId[$companyInfo["legal_name"]] = $return['accountId'];
        }else{
           $this->error = $return['msg'];
           return false;
        }
    }
    
    
    /*
     * 写入图片
     *      个人或公司章转换为图片形式
     *    @param $image64  字符串形式的base64图片
     *    @param $savePath  保存路径
     */
    public function savePicSign($image64,$savePath){
        if(is_file($savePath) && false!=($fileInfo = fstat(fopen($savePath, "r")))){
            if(time()<$fileInfo["mtime"]+7*24*3600){
                return true;
            }
        }
        $result = file_put_contents($savePath,base64_decode($image64));
        if($result){
            //此处可添加图片裁切等
            return true;
        }
        return false;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
 
}
