<?php
/*
 * 基类
 *      接口类的基类
 * 
 */
namespace version;

class Object{
   /*
    * CommonModel对象
    *           
    */
   public $commonModel;
   /*
    * 允许APP手机端访问的方法
    *     通过接口方式(service.jieba360.com)外部访问的方法集合
    *     不在此集合中的方法不允许访问
    *     eg.  $allowAppMethods = [];
    * 
    */
   public $allowAppMethods = [];
   /*
    * 事件数组
    */
   private $_event;
   
   /*
    * 构造方法，初始化对象
    * 
    *           注：子类必须继承此方法，不可以覆盖此方法
    *           eg. public function __construct($params,$params,……，$commonModel){
    *                   ...(your code)
    *                   parent::__construct($commonModel);
    *               }
    */
   public function __construct($commonModel,$config = []) {
       if($commonModel instanceof \CommonModel){
           $this->commonModel = $commonModel;
       }else{
           $this->error("commonModel对象参数错误class:". get_called_class());
       }
       
       
       //属性赋值
        foreach($this->commonModel as $key=>$value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }//foreach
        foreach($config as $k=>$v){
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        }
        $this->init();
       $this->isAllowApp();
   }
   
   public function __call($name, $arguments) {
       $this->error("请求接口名不存在({$name}102)");
   }
   
    public function __set($name, $value) {
        $setter = "set{$name}";
        if(method_exists($this,$setter)){
            return $this->$setter($value);
        }elseif(method_exists($this,"get{$name}")){
            throw new Exception("设置一个只读的参数:{$name}", 403);
        }
    }
    
    public function __get($name) {
        $getter = "get{$name}";
        if(method_exists($this, $getter)){
            return $this->$getter();
        }elseif(method_exists($this,"set{$name}")){
            throw new \Exception("读取一个只写的属性：". get_called_class()."::{$name}", 403);
        }else{
            throw new \Exception("读取一个未知的属性:". get_called_class()."::{$name}",404);
        }
    }
   
  /*
   * 错误返回
   *   
   */
  public function error($message,$code=null){
      throw new \Exception($message,$code===null?"500":$code);
  } 
  /*
   * 成功返回
   * @param string $message:响应成文本消息
   * @param mixed  $data:响应数据
   * @return mixed $data;
   */
  public function success($message,$data = ''){
      $this->commonModel->resultMsg = $message;
      return $data;
  }
  //初始化方法
  public function init(){
      
  }
  
 /*
  * 参数属性赋值
  */
 public function load(){
     
 }
  /*
   * 设置缓存
   * @param string $key:缓存键值
   * @param mixed  $value:缓存的值
   * @param int  $time:缓存时间，单位：秒
   * 
   * @return void
   */
  public function setCache($key,$value=null,$time = 0){
      $key = $this->buildKey($key);
      $value = serialize($value);
      $time = $time>=0?$time:0;
      if($time>0){
          S($key,$value,$time);
      }else{
          S($key,$value);
      }
      
  }
  /*
   * 获取缓存
   */
  public function getCache($key){
      $key = $this->buildKey($key);
      return unserialize(S($key));
  }
  
  private function buildKey($key){
      if(is_string($key)){
          return strlen($key)<32?$key:md5($key);
      }else{
          return md5(json_encode($key));
      }
  }
  
  /*
   * 是否允许APP接口访问
   */
  protected function isAllowApp(){
      $commonModel = $this->commonModel;
      $space = $commonModel::CLASS_METHOD_SPACE;
      if(false!=($cmd = $commonModel->getCmd())){
          list($class,$method) = explode($space,$cmd);
          if(strpos(get_class($this),$class) && !in_array($method,$this->allowAppMethods)){
              $this->error("Method:({$method})禁止APP访问", 403);
          }
      }
      return true;
  }
  
     /*
    * 绑定事件
    *       绑定自定义事件，备触发时使用
    *       eg. $model->on("afterRegister",[$obj,"sendSms"]);//注册成功后绑定发送短信
    *           $model->trigger("afterRegister");
    * 
    *   @param string $name:事件名称
    *   @param mixed $handler:回调方法句柄
    *   @param array $data:回调方法参数
    *   @return boolean
    */
   public function on($name,$handler,$data = []){
        $this->_event[$name][] = [$handler,$data];
        return true;
   }
   /*
    * 解绑事件
    *       解绑自定义事件
    */
   public function off($name){
       if(!empty($this->_event[$name])){
           unset($this->_event[$name]);
           return true;
       }
       return false;
   }
   /*
    * 触发事件
    *       触发绑定的事件
    */
   public function trigger($name,Event $event){
       if(empty($this->_event[$name])){
           return false;
       }
       if($event===null){
           $event = new Event;
       }
       foreach($this->_event[$name] as $handler){
           $event->data = $handler[1];
           $event->result = call_user_func($handler[0], $event);
           if(!$event->isValid){
               return;
           }
       }
       
   }
   /*
    * 日志对象
    *   返回日志对象
    */
   public function getLog($name){
       import("Think.ORG.Util.Logger");
       $savePath = realpath(FRAME_PATH."../service/Runtime/Logs/");
       $log= \Logger::getLogger($name,$savePath,false);
       return $log;
   }

 
}
