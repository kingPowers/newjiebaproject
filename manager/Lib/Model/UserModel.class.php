<?php
/**
* 
*/
class UserModel extends BaseModel
{
	
	public function getUsrRecord($params,$page = -1,$number = 12)
	{//return $params;
		if (empty($params['where']))return [];
		$where = $params['where'];
		if ($where['userid']){
			$map['uo.userid'] = $where['userid'];
		} else {
			return [];
		}
		$starttime = $where['starttime'];
		$endtime = $where['endtime'];
		if ($starttime && !$endtime){
            $map["uo.timeadd"] = array('egt',$starttime);
        } else if (!$starttime && $endtime){
            $map["uo.timeadd"] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        } else if ($starttime && $endtime){
            $map["uo.timeadd"] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        $field = "uo.*,u.username"; 
        if ($page == -1)
        {
			return M('user_operate uo')
			->join("user u on u.id=uo.userid")
			->field($field)
			->where($map)
			->select();
        }
        $count = M('user_operate uo')
			->join("user u on u.id=uo.userid")
			->where($map)
			->count();
		$list = M('user_operate uo')
			->join("user u on u.id=uo.userid")
			->where($map)
			->field($field)
			->limit(($page-1)*$number.",".$number)
			->order("uo.timeadd desc")
			->select();
		//return M('user_operate uo')->getLastSql();
		return ['count'=>$count,'list'=>$list];
		   
	}
}