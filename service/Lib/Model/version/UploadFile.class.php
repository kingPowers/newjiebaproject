<?php
/*
 * 文件管理类
 * 
 */
namespace version;

class UploadFile {
  /*
   * 文件保存基目录
   *    不可更改文件保存根目录
   */  
  private $_saveBasePath;
  
  /*
   * 文件保存目录
   */
  public $savePath;
  /*
   * 文件大小最大最大值
   */
  public $maxSize = "10485760";//10M
  /*
   * 图片后缀名称
   */
  public $imageExts = ["jpg","gif","png","jpeg"];
  
  //上传文件的错误返回
  private $_error;
  
  public function __construct($commonModel = null) {
      if($commonModel!=null && $commonModel instanceof \CommonModel){
          foreach($commonModel as $key=>$value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }//foreach
      }
      
      $this->_saveBasePath = FRAME_PATH."../static/Upload/";
      $this->savePath=$this->_saveBasePath.$this->savePath;
      
      import('ORG.Net.UploadFile');
  }
  
    /*
     * 上传‘单张’图片
     *   @param string $fileName:文件名称， $_FILE[$fileName];
     *   @param string $savePath:文件保存路径  eg."channelCompany/"  表示会保存到/static/Upload/channelCompany/  目录中
     *   return boolean|string  上传成功返回保存成功的文件名称，否则返回false
     * 
     *    上传图片用法：<input type="file" name="pic"/>
     *                  <?php   $upload = new version\UploadFile();
     *                          $upload->maxSize = "1024*1024*100";//文件大小最大值为100M
     *                          $upload->uploadImage("pic","channelCompany");
     */
    public function uploadImage($fileName){
        if(!empty($_FILES[$fileName]["name"])){
            $uploadObject = new \UploadFile();
            $uploadObject->maxSize = $this->maxSize;
            $uploadObject->allowExts = $this->imageExts;
            $uploadObject->savePath = $this->savePath;
            $uploadObject->uploadReplace = true;
            $uploadObject->saveRule = time().rand(111,999).rand(11,99);
            if(false==($info = $uploadObject->uploadOne($_FILES[$fileName]))){
                $this->_error = $uploadObject->getErrorMsg();
                return false; 
            }else{
                //substr($uploadObject->savePath,strpos($uploadObject->savePath,"Upload"))
                return $info[0]["savepath"].$info[0]['savename'];
            }
        }
        $this->_error = "未选择上传文件";
        return false;
    }
    
    public function getError(){
        return $this->_error;
    }
    /*
     * 上传Excel  
     *  @return string|false
     *          上传成功，返回Excel绝对路径； 上传失败，返回错误
     */
    public function uploadExcel(){
        $uploadObject = new \UploadFile();
        $uploadObject->allowExts  = array('xls', 'xlsx');// 设置附件上传类型
        $uploadObject->savePath =  $this->savePath."businessCompany/memberImport/";// 设置附件上传目录
        $uploadObject->maxSize  = 8145728;// 设置附件上传大小
        $uploadObject->saveRule = 'time';
        if(!$uploadObject->upload()) {// 上传错误提示错误信息
            $this->_error = $uploadObject->getErrorMsg();
            return false;
        }
        $info =  $uploadObject->getUploadFileInfo();
        return $info[0]['savepath'].$info[0]['savename'];
    }
}
