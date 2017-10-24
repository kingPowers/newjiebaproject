<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2799 2012-03-05 07:18:06Z liu21st $

/**
  +------------------------------------------------------------------------------
 * Think 基础函数库
  +------------------------------------------------------------------------------
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>
 * @version  $Id: common.php 2799 2012-03-05 07:18:06Z liu21st $
  +------------------------------------------------------------------------------
 */

// 记录和统计时间（微秒）
function G($start,$end='',$dec=4) {
    static $_info = array();
    if(is_float($end)) { // 记录时间
        $_info[$start]  =  $end;
    }elseif(!empty($end)){ // 统计时间
        if(!isset($_info[$end])) $_info[$end]   =  microtime(TRUE);
        return number_format(($_info[$end]-$_info[$start]),$dec);
    }else{ // 记录时间
        $_info[$start]  =  microtime(TRUE);
    }
}

// 设置和获取统计数据
function N($key, $step=0) {
    static $_num = array();
    if (!isset($_num[$key])) {
        $_num[$key] = 0;
    }
    if (empty($step))
        return $_num[$key];
    else
        $_num[$key] = $_num[$key] + (int) $step;
}

/**
  +----------------------------------------------------------
 * 字符串命名风格转换
 * type
 * =0 将Java风格转换为C的风格
 * =1 将C风格转换为Java的风格
  +----------------------------------------------------------
 * @access protected
  +----------------------------------------------------------
 * @param string $name 字符串
 * @param integer $type 转换类型
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function parse_name($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

// 优化的require_once
function require_cache($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists_case($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}

// 区分大小写的文件存在判断
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN && C('APP_FILE_CASE')) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
  +----------------------------------------------------------
 * 导入所需的类库 同java的Import
 * 本函数有缓存功能
  +----------------------------------------------------------
 * @param string $class 类库命名空间字符串
 * @param string $baseUrl 起始路径
 * @param string $ext 导入的文件扩展名
  +----------------------------------------------------------
 * @return boolen
  +----------------------------------------------------------
 */
function import($class, $baseUrl = '', $ext='.class.php') {
    static $_file = array();
    $class = str_replace(array('.', '#'), array('/', '.'), $class);
    if ('' === $baseUrl && false === strpos($class, '/')) {
        // 检查别名导入
        return alias_import($class);
    }
    if (isset($_file[$class . $baseUrl]))
        return true;
    else
        $_file[$class . $baseUrl] = true;
    $class_strut = explode('/', $class);
    if (empty($baseUrl)) {
        if ('@' == $class_strut[0] || APP_NAME == $class_strut[0]) {
            //加载当前项目应用类库
            $baseUrl = dirname(LIB_PATH);
            $class = substr_replace($class, basename(LIB_PATH).'/', 0, strlen($class_strut[0]) + 1);
        }elseif ('think' == strtolower($class_strut[0])){ // think 官方基类库
            $baseUrl = CORE_PATH;
            $class = substr($class,6);
        }elseif (in_array(strtolower($class_strut[0]), array('org', 'com'))) {
            // org 第三方公共类库 com 企业公共类库
            $baseUrl = LIBRARY_PATH;
        }else { // 加载其他项目应用类库
            $class = substr_replace($class, '', 0, strlen($class_strut[0]) + 1);
            $baseUrl = APP_PATH . '../' . $class_strut[0] . '/'.basename(LIB_PATH).'/';
        }
    }
    if (substr($baseUrl, -1) != '/')
        $baseUrl .= '/';
    $classfile = $baseUrl . $class . $ext;
    if (!class_exists(basename($class),false)) {//dump(basename($class));
        // 如果类不存在 则导入类库文件
        return require_cache($classfile);
    }
}

/**
  +----------------------------------------------------------
 * 基于命名空间方式导入函数库
 * load('@.Util.Array')
  +----------------------------------------------------------
 * @param string $name 函数库命名空间字符串
 * @param string $baseUrl 起始路径
 * @param string $ext 导入的文件扩展名
  +----------------------------------------------------------
 * @return void
  +----------------------------------------------------------
 */
function load($name, $baseUrl='', $ext='.php') {
    $name = str_replace(array('.', '#'), array('/', '.'), $name);
    if (empty($baseUrl)) {
        if (0 === strpos($name, '@/')) {
            //加载当前项目函数库
            $baseUrl = COMMON_PATH;
            $name = substr($name, 2);
        } else {
            //加载ThinkPHP 系统函数库
            $baseUrl = EXTEND_PATH . 'Function/';
        }
    }
    if (substr($baseUrl, -1) != '/')
        $baseUrl .= '/';
    require_cache($baseUrl . $name . $ext);
}

// 快速导入第三方框架类库
// 所有第三方框架的类库文件统一放到 系统的Vendor目录下面
// 并且默认都是以.php后缀导入
function vendor($class, $baseUrl = '', $ext='.php') {
    if (empty($baseUrl))
        $baseUrl = VENDOR_PATH;
    return import($class, $baseUrl, $ext);
}

// 快速定义和导入别名
function alias_import($alias, $classfile='') {
    static $_alias = array();
    if (is_string($alias)) {
        if(isset($_alias[$alias])) {
            return require_cache($_alias[$alias]);
        }elseif ('' !== $classfile) {
            // 定义别名导入
            $_alias[$alias] = $classfile;
            return;
        }
    }elseif (is_array($alias)) {
        $_alias   =  array_merge($_alias,$alias);
        return;
    }
    return false;
}

/**
  +----------------------------------------------------------
 * D函数用于实例化Model 格式 项目://分组/模块
 +----------------------------------------------------------
 * @param string name Model资源地址
  +----------------------------------------------------------
 * @return Model
  +----------------------------------------------------------
 */
function D($name='') {
    if(empty($name)) return new Model;
    static $_model = array();
    if(isset($_model[$name]))
        return $_model[$name];
    if(strpos($name,'://')) {// 指定项目
        $name   =  str_replace('://','/Model/',$name);
    }else{
        $name   =  C('DEFAULT_APP').'/Model/'.$name;
    }
    
    import($name.'Model');
    $class   =   basename($name.'Model');
    if(class_exists($class)) {
        $model = new $class();
    }else {
        $model  = new Model(basename($name));
    }
    $_model[$name]  =  $model;
    return $model;
}

/**
  +----------------------------------------------------------
 * M函数用于实例化一个没有模型文件的Model
  +----------------------------------------------------------
 * @param string name Model名称 支持指定基础模型 例如 MongoModel:User
 * @param string tablePrefix 表前缀
 * @param mixed $connection 数据库连接信息
  +----------------------------------------------------------
 * @return Model
  +----------------------------------------------------------
 */
function M($name='', $tablePrefix='',$connection='') {
    static $_model = array();
    if(strpos($name,':')) {
        list($class,$name)    =  explode(':',$name);
    }else{
        $class   =   'Model';
    }
    if (!isset($_model[$name . '_' . $class]))
        $_model[$name . '_' . $class] = new $class($name,$tablePrefix,$connection);
    return $_model[$name . '_' . $class];
}

/**
  +----------------------------------------------------------
 * A函数用于实例化Action 格式：[项目://][分组/]模块
  +----------------------------------------------------------
 * @param string name Action资源地址
  +----------------------------------------------------------
 * @return Action
  +----------------------------------------------------------
 */
function A($name) {
    static $_action = array();
    if(isset($_action[$name]))
        return $_action[$name];
    if(strpos($name,'://')) {// 指定项目
        $name   =  str_replace('://','/Action/',$name);
    }else{
        $name   =  '@/Action/'.$name;
    }
    import($name.'Action');
    $class   =   basename($name.'Action');
    if(class_exists($class,false)) {
        $action = new $class();
        $_action[$name]  =  $action;
        return $action;
    }else {
        return false;
    }
}

// 远程调用模块的操作方法
// URL 参数格式 [项目://][分组/]模块/操作 
function R($url,$vars=array()) {
    $info =  pathinfo($url);
    $action  =  $info['basename'];
    $module =  $info['dirname'];
    $class = A($module);
    if($class)
        return call_user_func_array(array(&$class,$action),$vars);
    else
        return false;
}

// 获取和设置语言定义(不区分大小写)
function L($name=null, $value=null) {
    static $_lang = array();
    // 空参数返回所有定义
    if (empty($name))
        return $_lang;
    // 判断语言获取(或设置)
    // 若不存在,直接返回全大写$name
    if (is_string($name)) {
        $name = strtoupper($name);
        if (is_null($value))
            return isset($_lang[$name]) ? $_lang[$name] : $name;
        $_lang[$name] = $value; // 语言定义
        return;
    }
    // 批量定义
    if (is_array($name))
        $_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));
    return;
}

// 获取配置值
function C($name=null, $value=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name))   return $_config;
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : null;
            $_config[$name] = $value;
            return;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtolower($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }
    // 批量设置
    if (is_array($name)){
        return $_config = array_merge($_config, array_change_key_case($name));
    }
    return null; // 避免非法参数
}

// 处理标签扩展
function tag($tag, &$params=NULL) {
    // 系统标签扩展
    $extends = C('extends.' . $tag);
    // 应用标签扩展
    $tags = C('tags.' . $tag);
    if (!empty($tags)) {
        if(empty($tags['_overlay']) && !empty($extends)) { // 合并扩展
            $tags = array_unique(array_merge($extends,$tags));
        }elseif(isset($tags['_overlay'])){ // 通过设置 '_overlay'=>1 覆盖系统标签
            unset($tags['_overlay']);
        }
    }elseif(!empty($extends)) {
        $tags = $extends;
    }
    if($tags) {
        if(APP_DEBUG) {
            G($tag.'Start');
            Log::record('Tag[ '.$tag.' ] --START--',Log::INFO);
        }
        // 执行扩展
        foreach ($tags as $key=>$name) {
            if(!is_int($key)) { // 指定行为类的完整路径 用于模式扩展
                $name   = $key;
            }
            B($name, $params);
        }
        if(APP_DEBUG) { // 记录行为的执行日志
            Log::record('Tag[ '.$tag.' ] --END-- [ RunTime:'.G($tag.'Start',$tag.'End',6).'s ]',Log::INFO);
        }
    }else{ // 未执行任何行为 返回false
        return false;
    }
}

// 动态添加行为扩展到某个标签
function add_tag_behavior($tag,$behavior,$path='') {
    $array   =  C('tags.'.$tag);
    if(!$array) {
        $array   =  array();
    }
    if($path) {
        $array[$behavior] = $path;
    }else{
        $array[] =  $behavior;
    }
    C('tags.'.$tag,$array);
}

// 过滤器方法
function filter($name, &$content) {
    $class = $name . 'Filter';
    require_cache(LIB_PATH . 'Filter/' . $class . '.class.php');
    $filter = new $class();
    $content = $filter->run($content);
}

// 执行行为
function B($name, &$params=NULL) {
    $class = $name.'Behavior';
    G('behaviorStart');
    $behavior = new $class();
    $behavior->run($params);
    if(APP_DEBUG) { // 记录行为的执行日志
        G('behaviorEnd');
        Log::record('Run '.$name.' Behavior [ RunTime:'.G('behaviorStart','behaviorEnd',6).'s ]',Log::INFO);
    }
}

// 渲染输出Widget
function W($name, $data=array(), $return=false) {
    $class = $name . 'Widget';
    require_cache(LIB_PATH . 'Widget/' . $class . '.class.php');
    if (!class_exists($class))
        throw_exception(L('_CLASS_NOT_EXIST_') . ':' . $class);
    $widget = Think::instance($class);
    $content = $widget->render($data);
    if ($return)
        return $content;
    else
        echo $content;
}

// 去除代码中的空白和注释
function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                case T_START_HEREDOC:
                    $stripStr .= "<<<THINK\n";
                    break;
                case T_END_HEREDOC:
                    $stripStr .= "THINK;\n";
                    for($k = $i+1; $k < $j; $k++) {
                        if(is_string($tokens[$k]) && $tokens[$k] == ';') {
                            $i = $k;
                            break;
                        } else if($tokens[$k][0] == T_CLOSE_TAG) {
                            break;
                        }
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

// 循环创建目录
function mk_dir($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

//[RUNTIME]
// 编译文件
function compile($filename) {
    $content = file_get_contents($filename);
    // 替换预编译指令
    $content = preg_replace('/\/\/\[RUNTIME\](.*?)\/\/\[\/RUNTIME\]/s', '', $content);
    $content = substr(trim($content), 5);
    if ('?>' == substr($content, -2))
        $content = substr($content, 0, -2);
    return $content;
}

// 根据数组生成常量定义
function array_define($array,$check=true) {
    $content = "\n";
    foreach ($array as $key => $val) {
        $key = strtoupper($key);
        if($check)   $content .= 'defined(\'' . $key . '\') or ';
        if (is_int($val) || is_float($val)) {
            $content .= "define('" . $key . "'," . $val . ');';
        } elseif (is_bool($val)) {
            $val = ($val) ? 'true' : 'false';
            $content .= "define('" . $key . "'," . $val . ');';
        } elseif (is_string($val)) {
            $content .= "define('" . $key . "','" . addslashes($val) . "');";
        }
        $content    .= "\n";
    }
    return $content;
}

function array_map_recursive($filter, $data) {
	$result = array();
	foreach ($data as $key => $val) {
		$result[$key] = is_array($val)
		? array_map_recursive($filter, $val)
		: call_user_func($filter, $val);
	}
	return $result;
}
//[/RUNTIME]




function create_sn($code = null, $extends = '') {
    $codearr = array('loan' => 1, 'issue' => 2, 'tender' => 3, 'allot' => 4, 'detail' => 5, 'cashin' => 6, 'cashout' => 7, 'redpacket' => 8, 'sina' => 9,'ratecoupon'=>10, 'transfer' => 12, 'refund' => 13, 'goldingot' => 14, 'borrowloan' => 15, 'borrowrepay' => 16);
    $code = strtolower($code);
    if (empty($codearr[$code])) {
        return;
    }
    $sn = $codearr[$code] . (($code == 'loan') ? time() : (microtime(true) * 10000));
    $sn = empty($extends) ? $sn . rand(100, 999) : $sn . $extends;
    return $sn;
}
//转换为大写人民币
function cny($ns) {
	static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
	$cnyunits=array("圆","角","分"),
	$grees=array("拾","佰","仟","万","拾","佰","仟","亿");
	list($ns1,$ns2)=explode(".",$ns,2);
	$ns2=array_filter(array($ns2[1],$ns2[0]));
	$ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
	$ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
	return str_replace(array_keys($cnums),$cnums,$ret);
}
//转换为大写人民币
function _cny_map_unit($list,$units)
{
	$ul = count($units);
	$xs = array();
	foreach (array_reverse($list) as $x)
	{
		$l = count($xs);
		if($x!="0" || !($l%4))
		{
			$n=($x=='0'?'':$x).($units[($l-1)%$ul]);
		}
		else
		{
			$n=is_numeric($xs[0][0]) ? $x : '';
		}
		array_unshift($xs, $n);
	}
	return $xs;
}


//检测手机
function isMobile($mobile = '') {
    return preg_match("/^1[3|4|5|7|8|9][0-9]{9}$/", $mobile) ? true : false;
}

/*
 * 添加短信类型 
 *
 */
function sendsms($mobile, $content,$sms_type) {
    if (!isMobile($mobile) || empty($content)) {
        return false;
    }
    import('Think.ORG.Util.SMS');
    return SMS::send($mobile, $content,$sms_type);
}

//发送验证码
/*
 * 添加短信类型 
 * 2016/06/07
 */
function sendverify($mobile,$type=1,$content=false,$sms_type) {
    if (!isMobile($mobile)) {
        return false;
    }
    import('Think.ORG.Util.SMS');
    return SMS::buildverify($mobile,$content,$type,$sms_type);
}

//检测邮箱
function isEmail($email = '') {
    return preg_match('/^[a-z0-9_\.]{1,}@[a-z0-9-\.]{1,}\.[a-z]{2,}$/', $email) ? true : false;
}

//发送邮件
function sendemail($email, $title, $content) {
    if (empty($email) || empty($title) || empty($content)) {
        return false;
    }
    if (!isEmail($email)) {
        return false;
    }
    import('Think.ORG.Util.Mail');
    return Mail::send($email, $title, $content);
}


function get_mobile_location($mobile) {
	if (!isMobile($mobile)) {
		return false;
	}
	$return = array();
	$api = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel={$mobile}";
	$string = iconv('gb2312', 'utf-8', file_get_contents($api));
	$string = str_replace(array("\r", "\n", "\r\n", "'"), array('', '', '', ''), $string);
	$string = substr($string, strpos($string, '{') + 1);
	$string = substr($string, 0, strpos($string, '}'));
	$array = explode(',', $string);
	foreach ($array as $var) {
		$ex = explode(':', $var);
		$key = trim($ex[0]);
		$val = trim($ex[1]);
		$return[$key] = $val;
	}
	return $return['province'] . '-' . $return['catName'];
}
//检测银行卡号
function isAccno($cardNo){return true;
    if(!is_numeric($cardNo))return false;
    $arr_no = str_split($cardNo);
    $last_n = $arr_no[count($arr_no)-1];
    krsort($arr_no); $i = 1;$total = 0;
    foreach ($arr_no as $n){
            $total += $i%2==0?($n*2>=10?(1+($n*2)%10):$n*2):$n;
            $i++;
    }
    $total -= $last_n;
    $total *= 9;
    if($last_n != ($total%10)){
            return false;
    }
    return true;
}

    /*
    * 检验身份证号格式
    * @param $certiNumber:身份证号
    * @return boolean
    * */
    function checkCertiNumber($certiNumber){
        $vCity = array(
                        '11','12','13','14','15','21','22',
                        '23','31','32','33','34','35','36',
                        '37','41','42','43','44','45','46',
                        '50','51','52','53','54','61','62',
                        '63','64','65','71','81','82','91'
        );

        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $certiNumber)) return false;

        if (!in_array(substr($certiNumber, 0, 2), $vCity)) return false;

        $certiNumber = preg_replace('/[xX]$/i', 'a', $certiNumber);
        $vLength = strlen($certiNumber);

        if ($vLength == 18)
        {
                $vBirthday = substr($certiNumber, 6, 4) . '-' . substr($certiNumber, 10, 2) . '-' . substr($certiNumber, 12, 2);
        } else {
                $vBirthday = '19' . substr($certiNumber, 6, 2) . '-' . substr($certiNumber, 8, 2) . '-' . substr($certiNumber, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18)
        {
                $vSum = 0;

                for ($i = 17 ; $i >= 0 ; $i--)
                {
                $vSubStr = substr($certiNumber, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
                }
                if($vSum % 11 != 1) return false;
        }
        return true;
}
//用户密码加密函数
function gen_password($original){
    return md5($original.C('SECURE_KEY'));
}