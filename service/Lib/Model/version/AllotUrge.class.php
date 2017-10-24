<?php

namespace version;
/*
 * 催款记录管理
 *  
 */
class AllotUrge extends Object{
    //新增催收记录
    public function addUrge($data){
        $model = new \AllotUrgeModel();
        $model->postData = $data;
        if($model->create($data)){
            if($addId = $model->add()){
                $this->success("新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，没保存成功(".$model->getError().")");
            }
        }else{
           $this->error($model->urgeError?$model->urgeError:$model->getError());
        }
    }
    
    /*
     * 催收列表
     * $tenderId:贷款订单ID
     * @return array 
     */
    public function urgeList($tenderId){
        return (array)(new \AllotUrgeModel())->where(["tender_id"=>$tenderId])->order("timeadd asc")->select();
    }
    /*
     * 催收结果
     *      @param     tenderid   loan_tender主键
     * 
     *      @return   “催收成功”  催收失败   待处理
     */
    public function urgeResult($tenderId){
        $list = $this->urgeList($tenderId);
        if(false==$list){//待处理
            return 1;
        }elseif(in_array(2, array_column($list,"urge_type"))){//催收成功
            return 2;
        }else{//催收失败
            return 3;
        }
        
    }
    
    //催收结果分类
    public function getUrgeType(){
        return (new \AllotUrgeModel())->urgeType;
    }
    
}
