<?php
/*
 * 短信管理类
 */
namespace version;
use version\Object;

class Sms extends Object{
    /*
     * 手机验证码有效期
     *      默认为10分钟
    */
   public static $smsValidDate = 600;
   /*
    * 发送验证码时间间隔
    *        同一个手机号再次发送验证码的
    */
   public static $verifyDateSpace = 60;
   
   public static $staticCommonModel;//静态commonModel
   /*
     * 发送手机验证码
    *       验证码只能使用一次
    *   @param string $mobile:接收验证码的手机号
    *   @param string  $cacheKey:验证码标识key
     */
    public static function sendVerify($mobile,$cacheKey){
        $object = new static(self::$staticCommonModel);
        if(!isMobile($mobile))$object->error("手机号格式不正确");
        if(false!=$object->getCache($cacheKey."Flag"))$object->error("您点击的太频繁了");
        import('Think.ORG.Util.SMS');
        if(false==(new \SMS(self::$staticCommonModel->business_company_id))->buildverify($mobile)){
            return $object->error('验证码发送失败');
        }
        $object->setCache($cacheKey.$mobile,session("smscode"),self::$smsValidDate);
        $object->setCache($cacheKey."Flag", 1,self::$verifyDateSpace);
        return true;
    }
    /*
     * 校验手机验证码是否正确
     *  @param string $mobile 接收验证码的手机号
     *  @param string $cacheKey:验证码标识key
     *  @return boolean
     */
    public static function  checkVerify($mobile,$smsCode,$cacheKey){
        $object = new static(self::$staticCommonModel);
        if(md5($smsCode)==$object->getCache($cacheKey.$mobile)){
            $object->setCache($cacheKey."Flag");
            $object->setCache($cacheKey.$mobile);
            return true;
        }
        return false;
    }

    
}
