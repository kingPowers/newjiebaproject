<?php
return array(
    'APP_STATUS' => APP_STATUS,
    'URL_MODE' => 2,
    'DEFAULT_THEME' => THEME_NAME,
    'TMPL_ACTION_ERROR' => 'Tpl/' . THEME_NAME . '/Public/notice.php',
    'TMPL_ACTION_SUCCESS' => 'Tpl/' . THEME_NAME . '/Public/notice.php',
    'TMPL_PARSE_STRING' => array(
        '_WWW_' => 'http://www.newjieba.com',
        '_STATIC_' => 'http://static.newjieba.com',  
        '_SERVICE_'=>'http://service.newjieba.com',
     //    '_WWW_' => 'http://jiewww.lingqianzaixian.com',
	    // '_STATIC_' => 'http://jiestatic.lingqianzaixian.com',  
     //    '_SERVICE_'=>'http://jieservice.lingqianzaixian.com',
    ),
    'TMPL_TEMPLATE_SUFFIX' => '.php',
    'URL_HTML_SUFFIX' => '.html',
    'URL_CASE_INSENSITIVE' => true,
    'LOAD_EXT_CONFIG' => 'mysql',//非调试模式无法自动加载自定义模式配置
    //'DEFAULT_MODULE' => DEFAULT_MODULE,
    //'LAYOUT_ON' => true,
    'SINA_PAYEE_ID' => 1,
	//'SHOW_PAGE_TRACE'=>ture,
    //商户公司子域名
    "COMPANY_DOMAIN"=>[
        "http://jiewww.lingqianzaixian.com"=>"35",
        "http://www.newjieba.com"=>"35",
        "http://jiewww.zxcf.cn"=>"35",
    ],
);