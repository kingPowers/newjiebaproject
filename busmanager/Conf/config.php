<?php

return array(
    'APP_STATUS' => 'dev',
    'URL_MODE' => 2,
    'DEFAULT_THEME' => THEME_NAME,
    'LOAD_EXT_CONFIG' => 'mysql',
    'SECURE_KEY' => 'JHF234as527648waz2V574rcG6FjsHSuGSu2867',
    'URL_HTML_SUFFIX' => '.html',
    'URL_CASE_INSENSITIVE' => false,
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        '/^login$/' => 'Public/login',
    ),
    'TMPL_PARSE_STRING' => array(
        '_MANAGER_' => 'http://manager.newjieba.com',
        '_CHANNEL_' => 'http://channelmanager.newjieba.com',
        '_BUSINESS_' => 'http://busmanager.newjieba.com',
        '_STATIC_' => 'http://static.newjieba.com',
        '_WWW_' => 'http://www.newjieba.com',
        // '_MANAGER_' => 'http://jiemanager.lingqianzaixian.com',
        // '_CHANNEL_' => 'http://jiechannelmanager.lingqianzaixian.com',
        // '_BUSINESS_' => 'http://jiebusmanager.lingqianzaixian.com',
        // '_STATIC_' => 'http://jiestatic.lingqianzaixian.com',
        // '_WWW_' => 'http://jiewww.lingqianzaixian.com',
    ),
    'TMPL_ACTION_ERROR' => 'Tpl/' . THEME_NAME . '/Public/notice.html',
    'TMPL_ACTION_SUCCESS' => 'Tpl/' . THEME_NAME . '/Public/notice.html',
);
