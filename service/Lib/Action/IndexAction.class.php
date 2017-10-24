<?php
/*
 * 接口类
 *      接口说明：
 *              URL：service.lingqianzaixian.com
 *              
 *         必传参数：cmd:接口名  eg.member/login,调用登录接口
 *                   client:设备名，android|ios|weixin|h5
 *                   version:接口版本号,1.0
 *                   sign:签名,md5(client|clientKey|cmd)
 * 
 *    登录后必传参数：token：TOKEN
 * 
 */
class IndexAction extends Action {
    function __construct() {
        parent::__construct();
    }

    public function index(){
        try{
            $commonModel = new \CommonModel();
            spl_autoload_register([$commonModel,"autoload"]);//注册自动加载
            $this->setAttribute($commonModel,$_REQUEST);
            $result = $commonModel->run();
            $msg = isset($commonModel->resultMsg)?$commonModel->resultMsg:'';
            return $this->service_success($msg,$result);
        }catch(Exception $e){
            $code = $e->getCode()?$e->getCode():500;
            return $this->service_error($code,$e->getMessage());
        }
    }
    /*
     * 参数批量赋值给对象
     * @param Object $object 赋值的对象
     * @param array  $params 参数数组
     */
    public function setAttribute($object,$params){
        foreach($params as $key=>$value){
            $object->$key = $value;
        }
        return $object;
    }
    
    public function service_error($code,$message,$data=''){
        header("Content-type: application/json");
        exit(json_encode(['respcode'=>$code,'respmsg'=>$message,'dataresult'=>$data,'servertime'=>time()]));
    }
    
    public function service_success($resultmsg = '',$data=''){
        header("Content-type: application/json");
        exit(json_encode(['respcode'=>0,'respmsg'=>$resultmsg,'dataresult'=>$data,'servertime'=>time()]));
    }
    
}