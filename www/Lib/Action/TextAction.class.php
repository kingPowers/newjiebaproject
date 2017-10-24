<?php
/**
* 
*/
class TextAction extends CommonAction
{
	public function _initialize()
	{
		header("content-type:text/html;charset=utf-8");
	}
	public function index()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
        $parmas['cmd'] = "LatterMessage/latterList";
        $parmas['page'] = $p;
        $parmas['number'] = 10;
        $server_res = $this->service($parmas);
        dump($server_res);
		dump($this->member_info);
	}
}