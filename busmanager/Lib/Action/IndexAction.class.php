<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction 
{
    public function index(){
        $this->display();
    }
    //系统统计
    public function system()
    {
    	$params['class'] = "DataStat";
    	$params['method'] = "businessStat";
    	$params['date']['companyId'] = $_SESSION['user']['businessid'];
    	$res = $this->invoke($params);
    	//dump($res);
        $this->assign("info",$res['data']);
    	$this->display();
    }
}