<?php
/**
* 
*/
class TestModel extends Model
{
	
	public function t1($className)
	{
		$a = new \version\T1();
		// $className = "\version\\".$className;
		// return call_user_func([new $className($this),'add']);
	} 
}