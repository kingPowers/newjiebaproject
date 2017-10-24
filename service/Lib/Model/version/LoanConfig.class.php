<?php
/*
 * 产品基类
 * 
 */
namespace version;
use version\Object;

class LoanConfig extends Object{
    /*
     * 借款期限
     */
    public $loanPeriode;
    /*
     * 还款方式
     */
    public $repayment;
    
  /*
   * 借款金额范围
   *        [最小借款金额-最大借款金额]
   * 
   * 借款金额中间用‘中划线’隔开，默认为0-0  
   */
  private $_loanmoneyScope = "0-0";
  /*
   * 借款期限列表
   *     eg.[
   *            ["periode"=>7,"unit"=>"天","type"=>1],
   *            ["periode"=>15,"unit"=>"天","type"=>1],
   *            ["periode"=>30,"unit"=>"天","type"=>1],
   *            ["periode"=>1,"unit"=>"月","type"=>2],
   *            ["periode"=>3,"unit"=>"月","type"=>2],
   *            ["periode"=>6,"unit"=>"月","type"=>2],
   *            ["periode"=>1,"unit"=>"年","type"=>3],
   *        ]
   */
  private $_loanPeriodes = [];
  
  /*
   * 还款方式
   *        1.付息还本
   *        2.本息到付
   *        3.等额本息
   * 
   *    默认为，本息到付（到期后一次性还本还息）
   */
  private $_loanRepayment = [1=>"付息还本","2"=>"本息到付","3"=>"等额本息"];
  /*
   * 借款利率分类
   *        1.天利率
   *        2.月利率
   *        3.年利率
   */
  public $loanFreeType = 1;
  /*
   * 借款利率范围
   *        [最小借款利率-最大借款利率]
   * 借款利率
   */
  private $_periodeRateScope = "0-0";
  
  /*
   * 逾期利率范围
   *        [最小借款逾期利率-最大借款逾期利率]
   */
  private $_latePeriodeRateScope = "0-0";
  /*
   * 手续费范围
   *        [最小手续费-最大手续费]
   * 
   */
  private $_procedureFreeScope = "0-0";
  /*
   * 平台管理费率，单位：%
   *        [最小平台管理费-最大平台管理费]
   *        eg. 3-5 表示费率为：3%-5%
   */
  private $_platFreeScop = "0-0";
  /*
   * 其他收费项目范围
   *        [其他收费项目最小-其他收费项目最大]
   */
  private $_extendFreeScop = "0-0";
  
  //初始化对象属性
  public function init(){
      $this->setLoanConfig();
  }
  
  private function setLoanConfig(){
      $model = new \LoanConfigModel();
      if(false!=($config = $model->find())){
         $this->_loanmoneyScope = $config["loanmoney"];//借款金额
         $this->_loanPeriodes = $config["loan_periode_ids"];//借款期限
         $this->_latePeriodeRateScope = $config["late_periode_rate"];//逾期利率
         $this->_periodeRateScope = $config["periode_rate"];//借款利率
         $this->_platFreeScop = $config["plat_free"];//平台管理费率
         $this->_procedureFreeScope = $config["procedure_free"];//手续费
         $this->_extendFreeScop = $config["extend_free"];//其他费用
      }
  }
  //借款金额
  public function getLoanmoneyScope(){
      return $this->_loanmoneyScope;
  }
   /*
    * 借款期限列表
    * 
    */
  public function getLoanPeriodes(){
      $model = new \LoanPeriodeModel();
      return $model->getLoanPeriodeList();
  }
  //逾期利率
  public function getLatePeriodeRateScope(){
      return $this->_latePeriodeRateScope;
  }
  //借款利率
  public function getPeriodeRateScope(){
      return $this->_periodeRateScope;
  }
  //平台管理费
  public function getPlatFreeScop(){
      return $this->_platFreeScop;
  }
  //手续费
  public function getProcedureFreeScope(){
      return $this->_procedureFreeScope;
  }
  //其他费用
  public function getExtendFreeScop(){
      return $this->_extendFreeScop;
  }
  //还款方式
  public function getLoanRepayment(){
      return $this->_loanRepayment;
  }
  /*
   * 新增配置信息到数据表中
   *    参数数组示例
   *    eg. $data[
   *            "loanmoney"=>"1000-10000",//借款金额
   *            "loan_periode_ids"=>"7天,15天,1月,1年",  //借款期限
   *            "periode_rate"=>"3-5",//借款利率
   *            "late_periode_rate"=>"3-5",//借款逾期利率
   *            "procedure_free"=>"30-500",//手续费
   *            "plat_free"=>"30-500",//平台管理费
   *            "extend_free"=>"0-0",//其他费用
   *        ];
   */
  public function addConfigToDb($data){
      $model = new \LoanConfigModel();
      $model->commonModel = $this->commonModel;
      if($model->create($data)){
          if($addId = $model->add()){
                $this->success("配置新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，保存失败(".$model->getError().")");
            }
      }else{
          $model->getError();
      }
  }
  
  /*
   * 修改配置信息到数据表中
   *    eg. data=[
   *            "id"=>"2",//主键，必填
   *            "loanmoney"=>"0-1000",//借款金额
   *        ]
   */
  public function saveConfigToDb($data){
      $model = new \LoanConfigModel();
      $model->commonModel = $this->commonModel;
      if($model->create($data)){
          if($addId = $model->save()){
                $this->success("配置修改成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，修改失败(".$model->getError().")");
            }
      }else{
          $model->getError();
      }
  } 
  
  
}
