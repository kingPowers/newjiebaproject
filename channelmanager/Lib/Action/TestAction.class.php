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
		$this->display();
	}
	public function left()
	{
		$this->display();
	}
	public function t2()
	{

	}
}