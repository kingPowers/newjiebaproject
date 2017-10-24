<?php
/**
* 客户消息处理
*/
class MemberMsgAction extends CommonAction
{
     /**
     * 首页
     */
    public function index(){
        $userId = $_SESSION["user"]['id'];
        $where['fro_to_id'] = ["like","%\_{$userId}"];
        //$where['type'] = 0;
        $lists = M('welive_msg')->where($where)->field('fro_to_id,max(created) max_created')->group('fro_to_id')->order("type asc,created asc")->select();
        foreach($lists as $list){
            list($from,$to) = explode('_',$list['fro_to_id']);
            $lits = M('member m')
                    ->join('member_info mi on mi.memberid = m.id')
                    ->field('m.id,mi.names,m.username,m.mobile')
                    ->where(array('m.id'=>$from))
                    ->find();
            $lits['num'] = M("welive_msg")->where(["fro_to_id"=>"{$lits["id"]}_{$userId}","type"=>0])->count();
            $lits['max_created'] = date('Y-m-d H:i:s',$list['max_created']);
            $messages[] = $lits;
        }
        array_multisort(array_column($messages,'num'),SORT_DESC,$messages);
        $this->assign('messages',$messages);//dump($messages);
        $this->display();
    }
    /**
     * 初始化面板获取消息
     */
    public function getMessage()
    {
        if ($_POST && ($_POST['is_getjson'] == 1))$this->getMessageJson();
        $parmas['class'] = "CustomerMessage";
        $parmas['method'] = "getCustomerRealTimeList";
        $parmas['data']['fromId'] = $this->_get("id","trim");
        $parmas['data']['toId'] = $_SESSION['user']['id'];
        $res = $this->invoke($parmas);//dump($res);
        $this->assign("new_msg",$res['data']);//dump($parmas);
        $this->display('box_chat_info');
    }
    /**
     *定时获取消息
     */
    public function getMessageJson(){
        $id = $this->_post('id', 'intval', 0);
        $parmas['class'] = "CustomerMessage";
        $parmas['method'] = "getCustomerRealTimeList";
        $parmas['data']['fromId'] = $id;
        $parmas['data']['toId'] = $_SESSION['user']['id'];
        $res = $this->invoke($parmas);
        if ($res['respCode'] === 0) {
            $this->ajaxSuccess("获取成功",$res['data']);
        }
        $this->ajaxError("获取失败");
    }

    /**
     * 记录回复数据
     */
    public function writeMessage(){
        $id = $this->_post('id', 'intval', 0);
        $content = $this->_post('content',"trim");
        $parmas['class'] = "CustomerMessage";
        $parmas['method'] = "addCustomerMessage";
        $parmas['data']['fromId'] = $_SESSION['user']['id'];
        $parmas['data']['toId'] = $id;
        $parmas['data']['content'] = $content;
        $res = $this->invoke($parmas);
        if ($res['respCode'] === 0) {
            $this->ajaxSuccess("发送成功",$res['data']);
        }
        $this->ajaxError($res['respMsg']);
    }

    /**
     * 查看历史聊天记录
     */
    public function historyMessage(){
        $id = $this->_post('id', 'intval', 0);
        $last_id = $this->_post('last_id',"trim");
        $parmas['class'] = "CustomerMessage";
        $parmas['method'] = "getCustomerHistoryList";
        $parmas['data']['fromId'] = $id;
        $parmas['data']['toId'] = $_SESSION['user']['id'];
        $parmas['data']['lastId'] = $last_id?$last_id:'';
        $res = $this->invoke($parmas);//dump($res);
        if ($res['respCode'] === 0) {
            $this->ajaxSuccess("获取成功".json_encode($parmas),$res['data']);
        }
        $this->ajaxError($res['respMsg']);
    }
}