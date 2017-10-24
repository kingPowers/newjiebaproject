<?php
/*
    渠道方公司管理model
 */

class ChannelCompanyModel  extends Model{
    public $commonModel;
    /*
     * 渠道级别
     */
    private $_levels = ["1"=>"1级","2"=>"2级","3"=>"3级"];
    
    //数据表名称
    protected $trueTableName = "channel_company";
    /*
     * 自动验证
     */
    protected $_validate = [
        ["companyname","require","渠道名称不能为空",self::MUST_VALIDATE],
        ["companyname","unique","渠道名称已经存在",self::MUST_VALIDATE,"unique"],
        ["province","require","渠道所属省份不能为空",self::MUST_VALIDATE],
        ["city","require","渠道所属城市不能为空",self::MUST_VALIDATE],
        ["address","require","渠道地址不能为空",self::MUST_VALIDATE],
        ["email","require","邮箱不能为空",self::MUST_VALIDATE],
        ["legalname","require","负责人姓名不能为空",self::MUST_VALIDATE],
        ["mobile","require","手机号不能为空",self::MUST_VALIDATE],
        ["bank_name","require","开户银行不能为空",self::MUST_VALIDATE],
        ["bank_accno","require","开户银行卡号不能为空",self::MUST_VALIDATE],
        ["bank_branch","require","分行名称不能为空",self::MUST_VALIDATE],
        ["bank_account","require","开户名称不能为空",self::MUST_VALIDATE],
        
        ["parent_id","getChannelCompany","所属渠道ID不正确",self::VALUE_VAILIDATE,"callback"],
        ["channel_level","checkChannelLevel","渠道等级不正确",self::VALUE_VAILIDATE,"callback"],
        ["province","checkProvince","渠道所属省份不正确",self::MODEL_BOTH,"callback"],
        ["city","checkCity","渠道所属城市不正确",self::MODEL_BOTH,"callback"],
        ["email","email","邮箱格式不正确",self::MODEL_BOTH],
        ["mobile","isMobile","手机号格式不正确",self::MUST_VALIDATE,"function"],
        ["bank_accno","isAccno","开户银行卡号格式不正确",self::MUST_VALIDATE,"function"],
    ];
    //自动完成
    protected $_auto = [
       ["channel_level","autoChannelLevel",self::MODEL_BOTH,"callback"],
       ["channel_number","createChannelNumber",self::MODEL_INSERT,"callback"],
    ];
    /*
     * 获取渠道公司信息
     */
    public function getChannelCompany($id){
        if(false!=($channelInfo = $this->where(["id"=>$id])->find())){
            return $channelInfo;
        }else{
            return false;
        }
    }
    /*
     * 检查省份名称
     */
    public function checkProvince($provinceName){
        return (new \version\City($this->commonModel))->checkProvince($provinceName);
    }
    /*
     * 检查城市名称
     */
    public function checkCity($cityName){
        return (new \version\City($this->commonModel))->checkCity($cityName);
    }
    /*
     * 检查渠道等级
     */
    public function checkChannelLevel($level){
        $arrLevelKeys = array_keys($this->_levels);
        return (boolean)in_array($level,$arrLevelKeys);
    }
    /*
     * 自动完成--渠道等级
     */
    public function autoChannelLevel($level){
        $parentId = $this->postData['parent_id'];
        $arrLevelKeys = array_keys($this->_levels);
        if($parentId!=false){
            $parentChannelCompany = $this->getChannelCompany($parentId);
            $newLevel = $parentChannelCompany["channel_level"]+1;
            return in_array($newLevel,$arrLevelKeys)?$newLevel:$parentChannelCompany["channel_level"];
        }
        return $level==null || !$this->checkChannelLevel($level)?$arrLevelKeys[0]:$level;
    }
    /*
     * 创建所属渠道编号
     */
    public function createChannelNumber($number = null){
        if($number!==null && !$this->where(["channel_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "Cha".time().rand(111,999).rand(111,999);
            if(false==$this->where(["channel_number"=>$number])->find())break;
        }
        return $number;
    }
    /*
     * 获取渠道等级
     */
   public function getChannelLevel(){
       return $this->_levels;
   } 
}
