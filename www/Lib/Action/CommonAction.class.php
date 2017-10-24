<?php
/**
 * 继承基类
 */
class CommonAction extends Action {

    public $M;
	public $currentprotocol = 'http://';
	public $httphost;
    public $online = true;
    public $member_info;

    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
        $this->set_seo();//设置网页标题、简介
        $allow_action = ['login','register','registerverify','recoverpwd','recoververify'];
        if (!in_array(strtolower(ACTION_NAME),$allow_action)) {
          if (!$this->isLogin()) {
            $this->assign("jumpurl","/Member/Login");
            $this->assign("redirect_url","/".MODULE_NAME."/".ACTION_NAME);
            $this->assign("waitSecond",1);
            $this->error("您尚未登录");
          }
        }
        $this->member_info = $this->getMemberInfo();
    }

    /*
     * 定义调用的接口
     *  $param  必传参数：cmd:接口名
 	 *                   client:设备名
 	 *                   version:接口版本号
 	 *                   sign:签名
 	 *  登录后必传参数：token：TOKEN
     * */
   public function service($data){
   		$service = C('TMPL_PARSE_STRING');
   		$url = !empty($service["_SERVICE_"])?$service["_SERVICE_"]:"jieservice.".DOMAIN_ROOT;
   		return  $this->curlpost($url,array_merge((array)$data,$this->setParams($data['cmd'])));
   }

   
   public function setParams($cmd){
    	$_clientKeys = ["android"=>"123456","ios"=>"654321","weixin"=>"111111","h5"=>"222222"];
    	$client = $this->getClient();
   		return array(
   			'client'=>$client,//设备类型
   			'sign'=>md5("{$client}|{$_clientKeys[$client]}|{$cmd}"),//签名
   			'version'=>'1.0',
   			'token'=>$this->getToken(),
                        "business_company_id"=>$this->getBusinessCompanyId(), 
   		);
   }
   public function getClient()
   {
   		return 'h5';
   }
   //获取商户公司ID
   public function getBusinessCompanyId(){
       $currentDomain = "http://{$_SERVER["HTTP_HOST"]}";
       $arrDomains = C("COMPANY_DOMAIN");
       return $arrDomains[$currentDomain]?$arrDomains[$currentDomain]:"25";
   }
   public function getToken(){
   	return $_SESSION['token'];
   }
   
   /*
    * 网页获取微信code
    * 	
    * 
    * */
   public function getCode(){return '';
   		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) return '';
   	 	$appid = C('AppID');
   	 	$return_url = urlencode("http://".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI']);
   	 	header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$return_url}&response_type=code&scope=snsapi_base&state=wx&connect_redirect=1#wechat_redirect");
   }   
   /*
    * 网页获取微信openid
    *   example:
    *      if(empty($_GET['code']))
    		$this->getCode();
    	$openid = $this->getOpenid($_GET['code']);
    *
    * */
 	public function getOpenid($code){
 		 if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false || empty($code))return '';
         $appid = C('AppID');$secret = C('AppSecret');
         $res = $this->curlpost("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code",array());
         return $res['openid'];
         
   }
   //用户是否登录
   public function isLogin(){
        if(false!=$this->getToken()){
           $memberInfo = $this->service(["cmd"=>"Member/isLogin"]);
           return (boolean)$memberInfo["dataresult"]["loginStatus"];
       }
     return false;
   }
   //获取用户的详细信息
   public function getMemberInfo(){
	if($this->getToken()){
           $memberInfo = $this->service(["cmd"=>"Member/getMemberInfo"]);
           return $memberInfo["dataresult"];
        }  
   	return false;
   }
    public function page_limit() {
        $limitstr = ($this->M->page['no'] - 1) * $this->M->page['num'] . ',' . $this->M->page['num'];
        return $limitstr;
    }

    public function set_pages($pageurl, $new = "") {
        $this->assign('page_vars', $this->M->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page');
        $this->assign('page', $pagehtml);
    }   
    //设置分页
    public function set_SimplePages($pageurl, $new = "") {
        $this->assign('page_vars', $this->M->simple_page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:simple_page');
        $this->assign('page', $pagehtml);
    }
    //设置页面SEO
    public function set_seo($config = array()) {
        //$system = M('system')->cache(true)->find();
        $pageseo = array('title' => '借吧', 'keywords' => '借吧', 'description' => $system['description']);
        if (!empty($config)) {
            $pageseo = array_merge($pageseo ,$config);
        }
        $this->assign('pageseo', $pageseo);
    }
    public function curlpost($url,$array){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//dump($array);exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt($ch, CURLOPT_TIMEOUT,20);   //只需要设置一个秒的数量就可以
        $data = curl_exec($ch);
        curl_close($ch);//dump($data);//exit;
        $data = json_decode($data,true);//exit;
        return $data;
    }

    
    //空方法
    protected function _empty() {
        R('Empty/_empty');
    }
    
    protected function ajaxSuccess($message='',$data=''){
    	$this->ajaxReturn($data,$message,1);
    }
    
    protected function ajaxError($message='',$data=''){
    	$this->ajaxReturn($data,$message,0);
    }
    public function set_session($name) { 
        return $_SESSION[$name] = md5(microtime(true)); 
    }
    public function valid_session($name) { 
        $return = $_REQUEST[$name] === $_SESSION[$name] ? true : false; 
        return $return; 
    } 
    
}
