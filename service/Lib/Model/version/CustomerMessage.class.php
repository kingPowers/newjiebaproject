<?php
/*
 * 在线客服消息
 */
namespace version;

class CustomerMessage  extends Message{
    public $allowAppMethods = ["getMemberHistoryList","getMemberRealTimeList","getCustomerUserList","addMemberMessage"];
    public $page = 1;
    public $number = 12;
    public $lastId;//上次收消息时间
    /*
     * 获取客服消息列表
     *          ---历史消息 
     *          ---客服发给客户的消息 - 客户发给客服的消息
     *       @param toId:客服ID
     */
    public function getMemberHistoryList(){
        $member = new Member($this->commonModel);
        if(false==($memberInfo = $member->getMemberInfo())){
            $this->error("用户未登录");
        }
        if($this->toId===null)$this->error("toId不能为空");
        return $this->customerMessageList(["fromId"=>$memberInfo["id"],"toId"=>$this->toId,"lastId"=>$this->lastId,"allUser"=>1],$this->page,$this->number);
    }
    /*
     * 获取客服人员列表
     *      --在线客服
     */
    public function getCustomerUserList(){
        $member = new Member($this->commonModel);
        if(false==($memberInfo = $member->getMemberInfo())){
            $this->error("用户未登录");
        }
        M()->query("insert into welive_user(user_id,nickname) select id,names from user where id not in(select user_id from welive_user)  and businessid='{$memberInfo["business_company_id"]}'");
        $list = M("welive_user wu,user u")->where("wu.user_id=u.id and u.businessid='{$memberInfo["business_company_id"]}'")->order("wu.online_time desc")->select();
        foreach($list as &$val){
            if(time()>strtotime($val["online_time"])+10*60){
                $val["isOnline"] = 0;
            }else{
                $val["isOnline"] = 1;
            }
        }
        return $list;
    }
    /*
     * 客户获取客服消息列表
     *          ---实时消息
     *          ---客服发给客户的消息
     *    @param $toId：客服ID
     */
    public function getMemberRealTimeList(){
        $member = new Member($this->commonModel);
        if(false==($memberInfo = $member->getMemberInfo())){
            $this->error("用户未登录");
        }
        $list = $this->customerMessageList(["fromId"=>$this->toId,"toId"=>$memberInfo["id"],"isRead"=>0],-1);
        M("welive_msg")->where(["id"=>["in", array_column($list,"id")]])->save(["type"=>1]);
        return $list;
    }
    
    /*
     * 客服获取消息列表
     *          ---历史消息
     *  @param $fromId:客服ID
     *  @param $lastTime:上次消息id
     */
    public function getCustomerHistoryList($fromId,$toId,$lastId,$page = 1,$number = 12){
        if($fromId===null)$this->error("会员ID不能为空");
        return $this->customerMessageList(["fromId"=>$fromId,"toId"=>$toId,"lastId"=>$lastId,"allUser"=>1],$page,$number);
    }
    /*
     * 客服获取客户消息列表
     *          ---实时消息
     *          ---客户发给客服的消息
     *    @param $fromId:客户ID
     *    @param $toId:客服ID
     */
    public function getCustomerRealTimeList($fromId,$toId){
        $list = $this->customerMessageList(["fromId"=>$fromId,"toId"=>$toId,"isRead"=>0],-1);
        M("welive_msg")->where(["id"=>["in", array_column($list,"id")]])->save(["type"=>1]);
        return $list;
    }
    
    /*
     * 会员添加文本消息
     *          --会员添加消息
     *          发消息人：会员
     *          收消息人：客服ID
     */
    public function addMemberMessage(){
        $member = new Member($this->commonModel);
        if(false==($memberInfo = $member->getMemberInfo())){
            $this->error("用户未登录");
        }
        if($this->toId===null)$this->error("客服人员ID不能为空");
        if($this->content===null)$this->error("请输入消息内容");
        if(false!=($id = $this->addMessage($memberInfo["id"], $this->toId, $this->content))){
            return $this->success("消息发送成功",["id"=>$id]);
        }else{
            $this->error("消息发送失败，请稍后再试");
        }
    }
    
    /*
     * 客服添加文本消息
     *          --客服添加消息
     *          发消息人：客服ID
     *          收消息人：会员
     */
    public function addCustomerMessage($fromId,$toId,$content){
        if($fromId===null)$this->error("客服人员ID不能为空");
        if($toId===null)$this->error("会员ID不能为空");
        if($content===null)$this->error("请输入消息内容");
        if(false!=($id = $this->addMessage($fromId,$toId, $content))){
            return $this->success("消息发送成功",["id"=>$id]);
        }else{
            $this->error("消息发送失败，请稍后再试");
        }
    }
    
    
    protected function addMessage($from,$to,$content){
        $data["fro_to_id"] = "{$from}_{$to}";
        $data["type"] = 0;
        $data["created"] = time();
        $data["msg"] = $content;
        if($addId = M("welive_msg")->add($data))return $addId;
        return false;
    }
    
    /*
     * 消息列表
     */
    public function customerMessageList($params = [],$page = -1,$number = 12){
        $where = [];
        //消息是否已读  0：未读  1：已读
        if(isset($params["isRead"])){
            $where["msg.type"] = $params["isRead"];
        }
       /*
        * 会员消息ID， 用户ID或者客服ID
        *       --取出别人发给‘我’的消息
         */
        if(isset($params["fromId"]) && empty($params["toId"])){
            $where["msg.fro_to_id"] = ["like","{$params["fromId"]}\_%"];
        }elseif(empty($params["fromId"]) && isset($params["toId"])){
            $where["msg.fro_to_id"] = ["like","%\_{$params["toId"]}"];
        }elseif(isset($params["fromId"]) && isset($params["toId"])){
            $where["msg.fro_to_id"] = $params["allUser"]!=1?
                                      ["like","{$params["fromId"]}_{$params["toId"]}"]:
                                      ["exp","='{$params["fromId"]}_{$params["toId"]}' or msg.fro_to_id='{$params["toId"]}_{$params["fromId"]}' "]
                                      ;
            
        }
        //上次收消息时间
        if(!empty($params["lastId"])){
            $where["msg.id"] = ["lt",$params["lastId"]];
        }
        $page = (int)$page;$number = (int)$number;
        $limit = $page=== -1? "":(($page-1)*$number).",".$number;
        $list = M("welive_msg msg")
                ->where($where)
                ->order("msg.created desc")
                ->limit($limit)
                ->select();
        //dump(M()->getLastSql());
        if(!empty($list)){
            array_multisort(array_column($list,'created'),SORT_ASC,$list);
        }
        $member = new Member($this->commonModel);
        foreach($list as &$val){
            list($from,$to) = explode("_",$val["fro_to_id"]);
            $val["from"] = $from;
            $val["to"] = $to;
            $val["timeadd"] = date("Y-m-d H:i",$val["created"]);
            if($memberInfo = $member->getAllMemberList(["where"=>["memberId"=>$from]],-1)){//会员消息
                $val["names"] = $memberInfo["list"][0]["names"];
                $val["username"] = $memberInfo["list"][0]["username"];
                $val["mobile"] = $memberInfo["list"][0]["mobile"];
            }else{//客服消息
                $userInfo = M("user")->where(["id"=>$from])->find();
                $val["names"] = $userInfo["names"];
            }
        }
        return (array)$list;
    }
}
