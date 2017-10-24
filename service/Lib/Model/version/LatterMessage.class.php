<?php
/*
   站内信管理
 */

namespace version;

class LatterMessage extends Message{
    public $allowAppMethods = ["latterList","latterDetail"];
    public $member;//member类对象
    /*
     * 站内信列表
     * 
     *      参数：  page:分页
     *              number:每页条数
     *              status:站内信状态  0：未读   1：已读
     * 
     */
    public function latterList(){
        if(false==($memberInfo = $this->getMember()->getMemberInfo())){
            $this->error("用户未登录");
        }
        $page = intval($this->commonModel->page)>0?intval($this->commonModel->page):1;
        $number = intval($this->commonModel->number)>0?intval($this->commonModel->number):10;
        $status = $this->commonModel->status;
        if($status!==null && !in_array($status,array(1,0)))
            $this->error('status参数错误');
        if($number>100)
            $this->error('每页的条数不得大于100条');
        $where_sql = "s.status=1 and (s.touserid='0' or s.touserid='{$memberInfo['id']}') ";
        $where_sql .= $status===null?" ":' and m.id is null ';//未读：0  全部：1

        $count = M('system')
                ->table('system s')
                ->field("s.*,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read")
                ->join("middle m on m.system_id=s.id and m.memberid='{$memberInfo['id']}'")
                ->where($where_sql)
                ->count();
        if($count>0){
            $list = M('system')
                    ->table('system s')
                    ->field("s.id,s.title,s.keywords,s.touserid,s.content,s.code,s.type,s.sort,if(CHAR_LENGTH(s.summary)>27,concat(substring(s.summary,'0','27'),'...'),s.summary) as summary,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read,if(m.id is null,'0','1') as is_read_sort")
                    ->join("middle m on m.system_id=s.id and m.memberid='{$memberInfo['id']}'")
                    ->where($where_sql)
                    ->group("s.id")
                    ->limit((($page-1)*$number).",{$number}")
                    ->order("is_read_sort asc,s.sort desc,s.timeadd desc")
                    ->select();
        }
        return ["count"=>(int)$count,"list"=>(array)$list];
    }
    /*
     * 站内信详情
     *      
     *      参数：latterId  站内信ID
     * 
     */
    public function latterDetail(){
        if(false==($memberInfo = $this->getMember()->getMemberInfo())){
            $this->error("用户未登录");
        }
        $id = $this->commonModel->latterId;
        if(empty($id))$this->error("latterId为空");
        $number = M('middle mi')->join('system s on mi.system_id= s.id')->where("system_id='{$id}'and memberid='{$memberInfo['id']}'")->find();
        if(empty($number)){
            $number = M('middle')->where(" memberid='{$memberInfo['id']}'")->find();
            $member['system_id'] = $id;
            if(empty($number)){
                $member['memberid'] = $memberInfo['id'];
                $came = M('middle')->add($member);
            }
            if($number){
                $member['memberid'] = $memberInfo['id'];
                $came = M('middle')->add($member);
            }
        }
        $count = M('system')->where("id='{$id}'")->count();
        if($count>0){
            //先判断middle表中是否有这个消息id
            $number = M('middle mi')->join('system s on mi.system_id= s.id')->where("system_id='{$id}' and memberid='{$memberInfo['id']}'")->find();
            if($number){
                //根据消息id查找这个两个表的信息
                $systemList = M('system s')->join('middle mi on mi.system_id= s.id')->field("s.id,s.title,s.keywords,s.summary,s.content,s.timeadd,s.code,mi.memberid,mi.status,mi.system_id")->where("s.id='{$id}' and mi.memberid='{$memberInfo['id']}'")->find();
            }
            if($systemList['status'] == 0){
                $system['status'] = 1;
                $numberone = M('middle')
                    ->where("system_id ='{$id}' and memberid='{$memberInfo['id']}'")
                    ->save($system);
            }
        }
        return ["count"=>(int)$count,"list"=>(array)$systemList];
    }
    
    public function getMember(){
        if($this->member===null){
            $this->member = new Member($this->commonModel);
        }
        return $this->member;
    }
}
