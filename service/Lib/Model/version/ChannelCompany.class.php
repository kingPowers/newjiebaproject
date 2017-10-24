<?php
/*
 * 渠道公司管理类
 * 
 */
namespace version;
use version\Object;

class ChannelCompany extends Object{
    /*
     * 新增渠道公司
     * @param array $data:渠道公司数据
     */
    public function addChannelCompany($data){
        $model = new \ChannelCompanyModel();
        $model->commonModel = $this->commonModel;
        $model->postData = $data;
        if($model->create($data)){
            if($addId = $model->add()){
                $this->success("渠道公司新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，没保存成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    
    /*
     * 保存渠道公司
     */
    public function saveChannelCompany($data){
        $model = new \ChannelCompanyModel();
        $model->commonModel = $this->commonModel;
        $model->postData = $data;
        if($model->create($data)){
            if($saveId = $model->save()){
                $this->success("渠道方修改成功",["add_id"=>$saveId]);
            }else{
                $this->error("哎呀，没修改成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    /*
     * 获取渠道列表
     *      根据公司名称查询渠道公司列表
     *   @param string $companyName 渠道公司名称
     *   @return array
     */
    public function getChannelCompanyList($companyName){
        $model = new \ChannelCompanyModel();
        if(!empty($companyName))$where["companyname"] = ["like","%{$companyName}%"];
        return (array)$model->where($where)->limit(5)->select();
    }
    /*
     * 渠道详情
     * @param $id 主键
     * @return array
     */
    public function getChannelDetail($id){
        $model = new \ChannelCompanyModel();
        if(empty($id))return [];
        $businessInfo = $model->getChannelCompany($id);
        if(false!=$businessInfo && $businessInfo["parent_id"]>0){
           $businessInfo["p_companyname"] = $model->getChannelCompany($businessInfo["parent_id"])["companyname"];
        }
        return (array)$businessInfo;
    }
     /*
        * 所有渠道列表
        *        根据指定的条件查询所有渠道公司列表
        *  @param array $params  查询条件,包括where,order
        *  @param  int   $page    页数， 规定：-1表示不分页
        *  @param  int   $number  每页的条数  默认为12
        *  
        *  @return array
        * 
        *        eg.
        *        $params = [
        *            "where"=>["companyname"=>$companyName,],//where查询条件
        *            "order"=>"",//排序方式
        *        ];
        */
    public function getAllChannelCompanyList($params = [],$page = -1,$number = 12){
        $model = new \ChannelCompanyModel();
        $ChannelTableName = $model->getTableName();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        $fields = "c.*,p.companyname as p_companyname";
        //渠道公司名称
        if(isset($paramWhere["companyname"]) && !empty($paramWhere["companyname"])){
            $where["c.companyname"] = ["like","%{$paramWhere["companyname"]}%"];
        }
        //父级渠道ID
        if(isset($paramWhere["parent_id"]) && !empty($paramWhere["parent_id"])){
            $where["c.parent_id"] = $paramWhere["parent_id"];
        }
        
        if(-1===intval($page)){//不分页
            return (array)$model->table("{$ChannelTableName} c")
                                ->join("{$ChannelTableName} p on p.id=c.parent_id")
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->select();
        }else{
            $count = $model->table("{$ChannelTableName} c")
                            ->join("{$ChannelTableName} p on p.id=c.parent_id")
                            ->where($where)
                            ->count();
            $list = (array)$model->table("{$ChannelTableName} c")
                                ->join("{$ChannelTableName} p on p.id=c.parent_id")
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->limit(($page-1)*$number.",".$number)
                                ->select();
            return ["count"=>intval($count),"list"=>$list];                    
        }
    }
    
    /*
     * 渠道等级
     */
    public function getChannelLevels(){
        $model = new \ChannelCompanyModel();
        return $model->getChannelLevel();
    }
    
    /*
     * 获取子渠道列表
     *  @param $channelId 渠道ID
     * @return array
     */
    public function getChannelGradeList($channelId){
        $model = new \ChannelCompanyModel();
        $list = $model->where(["parent_id"=>$channelId])->select();
        foreach($list as $val){
            $children = $this->getChannelGradeList($val["id"]);
            $list = array_merge ($list,$children);
        }
        return (array)$list;
    }
    
    
    
    /*
     * 获取渠道ID的祖父级渠道
     *  @param $channelId 渠道ID
     *  
     */
    public function getChannelParents($channelId,$grade = 1){
        $totalGrade = 2;$list = [];//总的级别
        if($grade>$totalGrade)return $list;
        $model = new \ChannelCompanyModel();
        $one = (array)$model->where(["id"=>$channelId])->find();
        if(false!=$one){
            $one["grade"] = $grade;
            $list[] = $one;
            $parents = $this->getChannelParents($one["parent_id"],++$grade);
            if(false!=$parents)$list = array_merge($list,$parents);
        }
        return (array)$list;
    }
    
    /*
     * 渠道分润规则公司列表
     *      --规则：
     *              1.合作公司对应的渠道   -父级  -祖级(父级的父级)   两级渠道
     *              2.峡谷渠道作为顶级渠道
     *              3.拥有渠道规则的渠道，且分配规则才有资格分润
     *  @param $channelCompanyId  渠道id
     *  return array
     */
    public function getChannelRuleCompany($channelCompanyId){
        $xiaGuName = "上海峡古网络科技有限公司";
        $list = $this->getChannelParents($channelCompanyId);
        foreach($list as &$val)$val["grade"] = count($list)-$val["grade"]+1;
        if(!in_array($xiaGuName,array_column($list,"companyname"))){
           foreach($list as &$val){$val["grade"]+=1;}
           $xiaGuInfo = (array)(new \ChannelCompanyModel())->where(["parent_id"=>0,"companyname"=>$xiaGuName])->find();
           $xiaGuInfo["grade"] = 1;
           $list[] = $xiaGuInfo;
        }
        array_multisort(array_column($list,"grade"),SORT_ASC,$list);
        return $list;
    }
    
}
