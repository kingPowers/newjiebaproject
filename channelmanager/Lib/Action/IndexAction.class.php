<?php

class IndexAction extends CommonAction {
    public function index(){
        header("Content-Type:text/html; charset=utf-8");
        //dump($_SESSION);
        //R("Public/logOut");'
        $this->display();
    }
    public function system()
    {
        $params['class'] = "DataStat";
        $params['method'] = "channelStat";
        $params['data']['company_id'] = $_SESSION['user']['channelid'];
        $res = $this->invoke($params);//dump($res);
        foreach ($res['data'] as &$value) {
            if (!$value) $value = 0;
        }
        $this->assign("data",$res['data']);
    	$this->display();
    }
}