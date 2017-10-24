<?php
/**
* 
*/
class TestAction extends Action
{
	public function __construct()
	{
		parent::__construct();
		header("content-type:text/html;charset=utf-8");
	}
	public function t1()
	{
		$plan_params['class'] = 'LoanOrder';
		$plan_params['method'] = 'getAllTenderOrderList';
		$plan_params['data']['params']['where']['id'] = ;
		$plan_params['data']['params']['where']['allotStatus'] = 0;
		$plan_res = $this->invoke($plan_params);dump($plan_res);
		$this->display();
	}
	public function left()
	{
		$this->display();
	}
	public function t2()
	{	
		import("Think.ORG.Util.Login");
		$className = "login";
		$l = new $className();
		dump($l->doLogin());
		dump($l->getError());
		$t = D("Test")->t1("T1");
		dump($t);
	}
	//调用service应用下的业务功能
    //$params : 传递的内容
    //          class:调用的类（必须）
    //          method:调用方法（必须）
    //          data:传递的数据
    public function invoke($params)
    {
        $model = D("service://Common");
        $result = $model->InternalCall($params['class'],$params['method'],$params['data']);
        return $result;
    }
}