<?php
// 首页
class IndexAction extends CommonAction 
{
    public function index()
    {
        $parmas['cmd'] = "LoanOrder/creditIndex";
        $server_res = $this->service($parmas);
        $msg_par['cmd'] = "LatterMessage/latterList";
        $msg_par['status'] = 0;
        $msg_res = $this->service($msg_par);
        $this->assign('count',$msg_res['dataresult']['count']);
        //dump($server_res);
        $this->assign("data",$server_res['dataresult']);
        $this->display();
    }
    public function getProductList()
    {
        $parmas['cmd'] = "LoanOrder/getBusinessLoanList";
        $server_res = $this->service($parmas);
        if ($server_res['respcode'] === 0) {
            $this->ajaxSuccess('获取成功',array_slice($server_res['dataresult'],0,3));
        }
        $this->ajaxError($server_res['respmsg']);
    }
    public function getPayMoney()
    {
        $parmas['cmd'] = 'LoanOrder/calculateFee';
        $parmas['loanId'] = $this->_post("pro_id","trim");
        $parmas['money'] = $this->_post("money","trim");
        $server_res = $this->service($parmas);
        $this->ajaxSuccess("".$parmas['money'].$parmas['loanId'],$server_res['dataresult']);
    }
    public function realname()
    {
        if ($_POST && ($_POST['is_bind'] == 1)) {
            if (!$this->valid_session("_bindcard_"))$this->ajaxError("页面失效，请刷新重试");
            $parmas['cmd'] = "Member/bindBankCardSubmit";
            $parmas['sms_code'] = $this->_post("verify","trim");
            $server_res = $this->service($parmas);
            if ($server_res['respcode'] === 0) {
                unset($_SESSION['_bindcard_']);
                $this->ajaxSuccess("认证成功");
            }
            $this->ajaxError($server_res['respmsg']);
        }
        $parmas['cmd'] = "Member/getBankNames";
        $server_res = $this->service($parmas);
        //dump($server_res);
        $this->assign("bank_list",$server_res['dataresult']);
        $this->assign("_bindcard_",$this->set_session("_bindcard_"));
    	$this->display();
    }
    public function bindverify()
    {
        $mobile = $this->_post("mobile","trim");
        $parmas['cmd'] = "Member/preBindBankCard";
        $parmas['mobile'] = $mobile;
        $parmas['acc_no'] = $this->_post("bank_acc","trim");
        $parmas['id_card'] = $this->_post("certiNumber","trim");
        $parmas['bank_name'] = $this->_post("bank_name","trim");
        $parmas['names'] = $this->_post("names","trim");
        $server_res = $this->service($parmas);
        if ($server_res['respcode'] === 0) {
            $this->ajaxSuccess("验证码发送成功");
        }
        $this->ajaxError($server_res['respmsg']);
    }
    public function message()
    {
        $p = $_REQUEST['p']?$_REQUEST['p']:1;
        $parmas['cmd'] = "LatterMessage/latterList";
        $parmas['page'] = $p;
        $parmas['number'] = 10;
        $server_res = $this->service($parmas);//dump($server_res);
        $list = $server_res['dataresult']['list'];
        if ($_POST && ($_POST['is_ajax'] == 1)) {
            if (empty($list)) $this->ajaxError("没有更多内容了");
            $this->ajaxSuccess('请求成功',$list);
        }      
        $this->assign("list",$list);
    	$this->display();
    }
    public function msgcontent()
    {
        $msg_id = $this->_get("id","trim");
        $parmas['cmd'] = "LatterMessage/latterDetail";
        $parmas['latterId'] = $msg_id;
        $server_res = $this->service($parmas);
        //dump($server_res);
        $this->assign("info",$server_res['dataresult']['list']);
        $this->display();
    }
    public function service_list()
    {
        $parmas['cmd'] = "CustomerMessage/getCustomerUserList";
        $server_res = $this->service($parmas);//dump($server_res);
        $this->assign("list",$server_res['dataresult']);
        $this->display();
    }
    public function service_online()
    {
        $to_id = $_REQUEST['id'];
        $parmas['cmd'] = "CustomerMessage/getMemberRealTimeList";
        $parmas['toId'] = $to_id;
        $server_res = $this->service($parmas);//dump($server_res);
        $this->assign("new_message",$server_res['dataresult']);
        $this->assign("id",$to_id);
        $this->display();
    }
    public function writeMessage()
    {
        $parmas['cmd'] = "CustomerMessage/addMemberMessage";
        $parmas['toId'] = $this->_post("id","trim");
        $parmas['content'] = $this->_post("content","trim");
        $server_res = $this->service($parmas);
        if ($server_res['respcode'] === 0) {
            $this->ajaxSuccess($server_res['respmsg'],$server_res['dataresult']);
        }
        $this->ajaxError("发送失败");
    }
    public function historyMessage()
    {
        $parmas['cmd'] = "CustomerMessage/getMemberHistoryList";
        $parmas['toId'] = $this->_post("id","trim");
        $parmas['lastId'] = $this->_post("last_id","trim")?$this->_post("last_id","trim"):'';
        $server_res = $this->service($parmas);
        if ($server_res['respcode'] === 0) {
            $this->ajaxSuccess("获取成功",$server_res['dataresult']);
        }
        $this->ajaxError($server_res['respmsg']);
    }
    public function getMessageJson()
    {
        $parmas['cmd'] = "CustomerMessage/getMemberRealTimeList";
        $parmas['toId'] = $this->_post("id","trim");
        $server_res = $this->service($parmas);
        if ($server_res['respcode'] === 0) {
            $this->ajaxSuccess("获取成功",$server_res['dataresult']);
        }
        $this->ajaxError($server_res['respmsg']);
    }
}