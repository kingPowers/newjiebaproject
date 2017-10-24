<?php
/**
* 渠道管理
*/
class ChannelAction extends CommonAction
{	
	private $page_num = 10;//每页显示数量
	public $channel_level;//渠道级别
	public function _initialize()
	{
		$params['class'] = 'ChannelCompany';
		$params['method'] = "getChannelLevels";
		$res = $this->invoke($params);
		$this->channel_level = $res['data'];
		$province_list = $this->getProvince();
		$this->assign("province_list",$province_list);
		$this->assign('channel_level',$this->channel_level);
	}
	//渠道列表
	public function index()
	{
		$value = $this->_get('v',"trim");
		$map = [];
		$pagepa = '';
		if ($value)
		{
			$map['where']['companyname'] = $value;
			$pagepa .= "v/{$value}";
		}
		$params['class'] = 'ChannelCompany';
		$params['method'] = 'getAllChannelCompanyList';
		$params['data']['params'] = $map;
		$params['data']['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['data']['number'] = $this->page_num;
		$res = $this->invoke($params);//dump($res);
		//设置分页
		$this->page['no'] = $params['data']['page'];
        $this->page['num'] = $this->page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Channel/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	//编辑渠道
	public function edit()
	{
		if($_POST && $_POST['channel_name'])$this->dataToDb();
		$channelid = $this->_get('id',"trim");
		$params['class'] = 'ChannelCompany';
		$params['method'] = 'getChannelDetail';
		$params['data']['id'] = $channelid;
		$res = $this->invoke($params);//dump($res);	
		$this->assign("is_sub",$this->_get("is_sub","trim"));
		$this->assign('info',$res['data']);
		$this->display();
	}
	//添加渠道
	public function add()
	{
		$this->assign("is_sub",1);
		$this->display('edit');
	}
	//数据入库
	public function dataToDb()
	{
		$channelid = $this->_post("channelid","trim");
		$data['parent_id'] = $this->_post("channel_belong",'trim');
		$data['companyname'] = $this->_post("channel_name",'trim');
		$data['province'] = $this->_post("province",'trim');
		$data['city'] = $this->_post("city",'trim');
		$data['address'] = $this->_post("address",'trim');
		$data['legalname'] = $this->_post("legalname",'trim');
		$data['email'] = $this->_post("email",'trim');
		$data['mobile'] = $this->_post("mobile",'trim');
		$data['channel_level'] = $this->_post("channel_level",'trim');
		$data['bank_name'] = $this->_post("bank_name",'trim');
		$data['bank_branch'] = $this->_post("bank_branch",'trim');
		$data['bank_account'] = $this->_post("bank_account",'trim');
		$data['bank_accno'] = $this->_post("bank_accno",'trim');
		$data['status'] = $this->_post("status",'trim');
		$params['class'] = 'ChannelCompany';
		if (!empty($channelid))
		{
			$data['id'] = $channelid;
			$params['method'] = 'saveChannelCompany';
		} else {
			$params['method'] = 'addChannelCompany';
		}	
		$params['data']['data'] = $data;
		$res = $this->invoke($params);
		if($res['respCode'] === 0)
		{
			$this->operateToDb(1,$_SESSION['user']['id'],"编辑渠道【" . $data['channelname'] . "】");
			$this->ajaxSuccess("操作成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	//相关渠道公司列表
	public function relevance()
	{
		$channelid = $_REQUEST['id'];
		//合作公司列表
		$params['class'] = 'BusinessCompany';
		$params['method'] = 'allCompanyList';
		$params['data']['params']['where']['channel_company_id'] = $channelid;
		$res = $this->invoke($params);//dump($res); 
	    //相关渠道列表
	    $params1['class'] = 'ChannelCompany';
		$params1['method'] = 'getAllChannelCompanyList';
		$params1['data']['params']['where']['parent_id'] = $channelid;
		$res1 = $this->invoke($params1);//dump($res1);
		$this->assign("channelList",$res1['data']);
		$this->assign("businessList",$res['data']['list']);
		$this->display();
	}
}