<?php


namespace version;
/*
 * Excel工具类
 */
class Excel {
   public $excelName;//Excel路径
   
   private $error;
   //列
   private $col = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",];
   /*
    * 获取上传的Excel内容
    * return array
    */
   public function  getExcelContent(){
       if($this->excelName===null){//上传文件
           $uploadFile = new UploadFile();
           $saveName = $uploadFile->uploadExcel();
           if(false==$saveName){
               $this->error = $uploadFile->getError();
               return [];
           }
           $this->excelName = $saveName;
       }
       //读取文件
       return $this->readExcel();
       
   }
    /*
     * 读取Excel内容
     *    return array
     */
    public function readExcel(){
        vendor("PHPExcel.PHPExcel");
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        //vendor('PHPExcel.PHPExcel.Shared.ZipStreamWrapper');
        $file_name = $this->excelName;
        if($file_name===null || !is_file($file_name)){
            $this->error = "Excel文件不存在";
            return [];
        }
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        if(!$objReader->canRead($file_name)){
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            if(!$objReader->canRead($file_name)){
                $this->error = "Excel不可读";
                return [];
            }
        }
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $totalRow = $sheet->getHighestRow(); // 取得总行数
        $excelContent = [];
        for($start = 2;$start<=$totalRow;$start++){
            foreach($this->col as $col){
                $excelContent[$start][$col] = $objPHPExcel->getActiveSheet()->getCell($col.$start)->getValue();
            }
        }
        return $excelContent;
    }
    
    public function getError(){
        return $this->error;
    }
    
}
