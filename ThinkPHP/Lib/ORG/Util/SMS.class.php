<?php
/*
 * 短信管理类
 *  
 *   eg. $sms = new SMS($companyID);
 *       $sms->buildverify(mobile);//发送验证码
 */
class SMS {
    
    public  $errcode;
    
    public  $sign = "【借吧】";//短信签名
    
    public $verifySpace = "60";//同一个手机号发送验证码的间隔
    
    public static $debug = 1;//是否开启调试模式，调试模式下，不发送短信
    
    public  $businessCompanyId;//合作公司ID
    
    public function __construct($businessCompanyId) {
        $this->businessCompanyId = $businessCompanyId;
        if(empty($this->businessCompanyId)){
            throw new Exception("合作公司ID为空");
        }
    }

    //验证码短信
    public  function buildverify($mobile) {
        $code = self::$debug?'000000':rand(100000, 999999);
        $content = '您本次操作验证码为：'.$code.'，验证码序号（'.rand(10, 99).'）请妥善保管。';
        session('smscode', md5($code));
        return $this->send($mobile, $content);
        //return $send === true ? true : false;
    }
    /*
     * 发送普通短信
     * @param $mobile:手机号  
     * @param $content:短信内容
     * @return boolean 
     *          
     *   所有短信平台都要返回：
     *          [
     *              "message"=>"",//返回的文本信息
     *              "returnstatus"=>"success|error",短信发送状态   success表示发送成功   error表示发送失败
     *              "smsType"=>"",短信平台标识；eg. luosi:表示由螺丝帽短信平台发送的
     *           ];            
     *  
     */
    public function send($mobile, $content) {
        if(self::$debug)return true;
        $result = $this->api_zhuoyun($mobile, $content);
        
        SmsRecord::add($mobile, $content, $result['returnstatus'], $result['message']);
        return $result['returnstatus'] == "success" ?true  : false;
    }

    public function api_zhuoyun($mobile, $message) {

        $post_data = array();
        $post_data['userid'] = 1471;
        $post_data['account'] = 'ZXCF';
        $post_data['password'] = '123456';
        $post_data['content'] = urlencode($message); //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobile;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://115.29.242.32:8888/sms.aspx?action=send';
//        $url='http://218.244.136.70:8888/sms.aspx?action=send';       //原来短信地址
        $o='';
        foreach ($post_data as $k=>$v)
        {
            //$o.="$k=".urlencode($v).'&';
            $o.="$k=".$v.'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        curl_close($ch);
        $xml = (array)simplexml_load_string($result);

        return $xml;
    }

}

//短信记录
class SmsRecord {

    /**
     * @brief add 
     *
     * @param $mobile  接收人
     * @param $content 内容
     * @param $result 发送状态
     * @param $extend 返回信息
     *
     * @return boolean
     */
 /*
 * 添加短信类型 zwd
 * 2016/06/12
 */
    public static function add($mobile = 0, $content = '', $result = false, $extend = '',$sms_type) {
        $data = array();
        $data['mobile'] = trim($mobile);
        $data['content'] = $content;
        $data['extend'] = $extend;
        $data['status'] = ($result === false) ? 0 : ( $result == 0 ? 1 : 0);
        M('SmsRecord')->add($data);
    }

}
