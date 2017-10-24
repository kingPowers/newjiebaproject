<?php
/*
 * 公共处理类
 * 
 */
class CommonModel extends Model{
    
    const CLASS_METHOD_SPACE = "/";
    
    /*
     * 版本号列表
     *  APP迭代的版本号，该版本号用于 版本接口
     */
    private $_versionList = ["1.0","1.1"];
    /*
     * 设备简单加密key,对接口的数据作简单校验
     * eg:  sign=md5(client|clientKeys|_cmd)  $this->_sign===md5("android|123456|member_add");
     */
    private $_clientKeys = ["android"=>"123456","ios"=>"654321","weixin"=>"111111","h5"=>"222222"];
    /*
     * 设备标识，接口必须
     *      安卓设备：android
     *      苹果设备：ios
     *      微信设备：weixin
     *      浏览器  :h5
     */
    private $_client;
    /*
     * 接口名称
     *      接口名称是由 '类名'和 ‘方法名’组成的，并且用斜线'/'连接,默认为 ‘/’
     *      eg. member_add  member类的add方法
     */
    private $_cmd;
    /*
     * 版本号，接口必须
     *      APP迭代的版本号，此版本号对应的接口不区分设备
     *      高版本的接口会覆盖低版本的接口，否则会继承低版本的接口
     */
    private $_version ;//版本号
    /*
     * TOKEN
     *      用户登录以后作的标识
     */
    private $_token;//token
    private $_sign;//签名,接口必须
    /*
     * 客户端设备标识
     */
    private $_clientIp;//客户端IP
    private $_httpUserAgent;//用户设备信息
    
    public $modelPath = "";
    public function __construct() {
        parent::__construct();
        //设置model路径
        $this->setModePath();
    }
    /*
     * 开始调用接口
     *      调用'接口类'和'方法名'，直接返回接口的返回值
     */
    public function run(){
        $this->init();
        list($className,$method) = explode(self::CLASS_METHOD_SPACE,$this->_cmd);
        $className = "version\\".$className;
        if(false==$className || false==$method){
            throw new Exception("请求接口不存在(101)",404);
        }
        return call_user_func([new $className($this),$method]);
    }
    //初始化
    public function init(){
        //校验检查参数
        $checkParams = ["_cmd","_version","_client","_sign"];
        //$checkParams = ["_version","_client"];
        foreach($checkParams as $param){
            if(empty($this->$param)){
                throw new Exception("{$param}参数错误(201)", 403);
            }
        }
    }

    public function __set($name, $value) {
        $setter = "set{$name}";
        if(method_exists($this,$setter)){
            return $this->$setter($value);
        }elseif(method_exists($this,"get{$name}")){
            throw new Exception("设置一个只读的参数:{$name}", 403);
        }elseif(!property_exists($this,$name)){
            $this->$name = $value;
        }
        
    }
    
    public function __get($name) {
        $getter = "get{$name}";
        if(method_exists($this, $getter)){
            return $this->$getter();
        }elseif(method_exists($this,"set{$name}")){
            throw new Exception("读取一个只写的属性：". get_called_class()."::{$name}", 403);
        }else{
            //throw new Exception("读取一个未知的属性:". get_called_class()."::{$name}",404);
        }
    }
    
    public function setClient($value){
        if(!in_array(strtolower($value), array_keys($this->_clientKeys))){
            throw new Exception("_client参数错误(202)", 403);
        }
        $this->_client = strtolower($value);
    }
    public function setCmd($value){
        $this->_cmd = $value;
    }
    
    public function setVersion($value){
        if(!in_array($value,$this->_versionList)){
            throw new Exception("_version参数错误", 403);
        }
        $this->_version = $value;
    }
    public function setToken($value){
        $this->_token = $value;
    }
    
    public function setSign($value){
        if($value!=md5("{$this->_client}|{$this->_clientKeys[$this->_client]}|$this->_cmd")){
            throw new Exception("签名错误",403);
        }
        $this->_sign = $value;
    }
     public function getCmd(){
        return $this->_cmd;
    }
    public function getClientKeys(){
        return $this->_clientKeys;
    }
    public function getClient(){
        return $this->_client;
    }
    public function getClientIp(){
        if(empty($this->_clientIp)){
            $this->_clientIp = $_SERVER["REMOTE_ADDR"];
        }
        return $this->_clientIp;
    }
    public function getToken(){
        return $this->_token;
    }
    //获取客户设备信息
    public function getHttpUserAgent(){
        if(empty($this->_httpUserAgent)){
            $this->_httpUserAgent = $_SERVER["HTTP_USER_AGENT"];
        }
        return $this->_httpUserAgent;
    }
    
   //设置model路径名称 
   public function setModePath(){
        $this->modelPath = __DIR__.DIRECTORY_SEPARATOR;
   }
   
   public function autoload($className){
       
        $modelClassFile = $this->modelPath.str_replace("\\","/",$className).".class.php";
        $baseVersionClassFile = $this->modelPath.str_replace("\\","/",$className).".class.php";
        $versionClassFile = $this->modelPath.$this->_version.DIRECTORY_SEPARATOR.str_replace("\\","/",$className).".class.php";
       // dump($baseVersionClassFile);
        if(!class_exists($className) && is_file($versionClassFile)){
            include $versionClassFile;
        }elseif(!class_exists($className) && is_file($baseVersionClassFile)){
            include $baseVersionClassFile;
        }elseif(!class_exists($className) && is_file($modelClassFile)){
            include $modelClassFile;
        }else{
            //允许加载其他目录下文件
            //throw new Exception("请求类名不存在(103)CLASS:{$className}",404);
        }
   }

/*
 * 其他项目调用此version版本内类
 *   eg.
 *      $model = D("service://Common");
 *      $result = $model->InernalCall("businessCompany","add",["one","two"]);//
 *      就相当于:(new businessCompany())->add("one","two");
 *               --调用 service/Lib/Model/version/businessCompany类的add方法，完成新增
 * 
 *      $data作为参数数组,数组的元素位置，对应到响应方法的位置
 */
public function InternalCall($className,$method,$data = []){
    spl_autoload_register([$this,"autoload"]);
    $className = "version\\".$className;
    try{
        if(false==$className || false==$method){
            throw new Exception("请求类名或方法名为空",404);
        }
        $result = call_user_func_array([new $className($this),$method],(array)$data);
        $resultMsg = !empty($this->resultMsg)?$this->resultMsg:"";
        return ["respCode"=>0,"respMsg"=>$resultMsg,"data"=>$result];
    }catch(Exception $e){
       return ["respCode"=>$e->getCode(),"respMsg"=>$e->getMessage()]; 
    }
}

public function getNameSpace(){
    return  "version\\";
}
    
}
