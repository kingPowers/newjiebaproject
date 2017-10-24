<?php
namespace version;
//额度管理
class Quota extends Object{
      public $memberId;
      
      /*
       * 获取'现金贷'额度
       *     return ['minMoney'=>$minMoney,'maxMoney'=>$maxMoney];
       *    
       *    eg.$quota = new Quota($commonModel,['memberid'=>$memberid]);
       *        $quota->getCreditQuota();
       */
      public function  getCreditQuota(){
          $minMoney = $maxMoney = 0;
          if($this->memberId===null){
              return ["minMoney"=>$minMoney,"maxMoney"=>$maxMoney];
          }
          $minMoney = 1000;
          $maxMoney = 10000;
         return ["minMoney"=>$minMoney,"maxMoney"=>$maxMoney];
          
      }
    }
?>