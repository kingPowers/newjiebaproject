<?php
/*
 * 商户公司处理类
 * 
 */

class BusinessCompanyModel extends Model{
    //数据表名称
    protected $trueTableName = "business_company";
    /*
     * 公司状态
     */
    private $_companyStatus = ["1"=>"启用","2"=>"禁用"];
    
     /*
     * 合作公司类型
     */
    public $companyType = ["合作公司类型1","合作公司类型2","合作公司类型三"];
    
    /*
     * 保存当前已上传成功的图片
     */
    public $saveUploadPicture = [];
    
    public $postData;
    /*
     * 自动验证
     */
    protected $_validate = [
        ["channel_company_id","require","所属渠道ID不能为空",self::MUST_VALIDATE],
        ["companyname","require","合作公司名称不能为空",self::MUST_VALIDATE],
        ["company_type","require","合作公司类型不能为空",self::MUST_VALIDATE],
        ["company_address","require","合作公司详细地址不能为空",self::MUST_VALIDATE],
        ["company_trade","require","行业类型不能为空",self::MUST_VALIDATE],
        ["legal_name","require","负责人姓名不能为空",self::MUST_VALIDATE],
        ["legal_certinumber","require","负责人身份证号不能为空",self::MUST_VALIDATE],
        ["legal_mobile","require","负责人手机号不能为空",self::MUST_VALIDATE],
        ["legal_bank","require","开户银行不能为空",self::MUST_VALIDATE],
        ["legal_accno","require","银行卡号不能为空",self::MUST_VALIDATE],
        ["legal_branch","require","分行不能为空",self::MUST_VALIDATE],
        ["legal_openname","require","开户名称不能为空",self::MUST_VALIDATE],
        
        ["companyname","unique","合作公司名称已经存在",self::MUST_VALIDATE,"unique"],
        ["legal_certinumber","checkCertiNumber","负责人身份证号不正确",self::MUST_VALIDATE,"function"],
        
        ["channel_company_id","checkChannelCompany","所属渠道ID不正确",self::MODEL_BOTH,"callback"],
        ["company_type","checkCompanyType","合作公司类型不正确",self::MODEL_BOTH,"callback"],
        ["legal_mobile","isMobile","负责人手机号格式不正确",self::MODEL_BOTH,"function"],
        ["legal_accno","isAccno","银行卡号不正确",self::MODEL_BOTH,"function"],
        
        ["legal_certinumber_pic","uploadPic","负责人身份证正面图片没上传成功",self::MUST_VALIDATE,"callback",self::MODEL_INSERT,"legal_certinumber_pic"],
        ["license_number_pic","uploadPic","营业执照图片没上传成功",self::MUST_VALIDATE,"callback",self::MODEL_INSERT,"license_number_pic"],
        ["agreement1_pic","uploadPic","协议1图片没上传成功",self::MUST_VALIDATE,"callback",self::MODEL_INSERT,"agreement1_pic"],
        ["agreement2_pic","uploadPic","协议2图片没上传成功",self::MUST_VALIDATE,"callback",self::MODEL_INSERT,"agreement2_pic"],
        
        ["legal_certinumber_pic","uploadPic","负责人身份证正面图片没上传成功",self::VALUE_VAILIDATE,"callback",self::MODEL_UPDATE,"legal_certinumber_pic"],
        ["license_number_pic","uploadPic","营业执照图片没上传成功",self::VALUE_VAILIDATE,"callback",self::MODEL_UPDATE,"license_number_pic"],
        ["agreement1_pic","uploadPic","协议1图片没上传成功",self::VALUE_VAILIDATE,"callback",self::MODEL_UPDATE,"agreement1_pic"],
        ["agreement2_pic","uploadPic","协议2图片没上传成功",self::VALUE_VAILIDATE,"callback",self::MODEL_UPDATE,"agreement2_pic"],
    ];
    
     //自动完成
    protected $_auto = [
        ["business_number","createBusinessNumber",self::MODEL_INSERT,"callback"],
        ["legal_certinumber_pic","getUploadedPicName",self::MODEL_BOTH,"callback","legal_certinumber_pic"],
        ["license_number_pic","getUploadedPicName",self::MODEL_BOTH,"callback","license_number_pic"],
        ["agreement1_pic","getUploadedPicName",self::MODEL_BOTH,"callback","agreement1_pic"],
        ["agreement2_pic","getUploadedPicName",self::MODEL_BOTH,"callback","agreement2_pic"],
    ];
    
    /*
     * 获取商户公司信息
     * @param string $id 商户公司ID
     * @return boolean|array
     */
    public function getBusinessCompany($id){
        if(false!=($businessCompanyInfo=M($this->trueTableName)->where("id='{$id}'")->find())){
            return $businessCompanyInfo;
        }else{
            return false;
        } 
    }
    
    /*
     * 商户所属渠道ID是否合法
     */
    public function checkChannelCompany($id){
        return (new \ChannelCompanyModel())->getChannelCompany($id);
    }
    /*
     * 上传图片
     *      保存成功后的图片路径到缓存中，新增合作公司中途中断时清除图片
     *  @param string $picName  图片名称
     */
    public function uploadPic($picValue,$picName){
        $fileObj = new version\UploadFile();
        $fileObj->savePath.= "businessCompany/company/";//保存路径
        $id = $this->postData["id"];
        $delName = $id?$this->where(["id"=>$id])->getField($picName):false;
        $savePathName = $fileObj->uploadImage($picName);
        if(false!=$savePathName){
            $this->saveUploadPicture[$picName] = $savePathName;
            @unlink($fileObj->savePath.$id."/".$delName);
            return $savePathName;
        }
        return $id?true:false;
        //return false;
    }
    /*
     * 取图片绝对路径的 图片名称存于数据库
     * @param $picName:图片名称
     */
    public function getUploadedPicName($picNameValue,$picName){
        if(empty($picName))$picName = $picNameValue;
        return !empty($this->saveUploadPicture[$picName])?basename($this->saveUploadPicture[$picName]):$picNameValue;
    }
    //合作公司状态
    public function getCompanyStatus(){
        return $this->_companyStatus;
    }
    /*
     * 创建编号
     */
    public function createBusinessNumber($number = null){
        if($number!==null && !$this->where(["business_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "Bus".time().rand(111,999).rand(111,999);
            if(false==$this->where(["business_number"=>$number])->find())break;
        }
        return $number;
    }
    
    public function checkCompanyType($value){
        return (boolean)in_array($value,$this->companyType);
    }
}
