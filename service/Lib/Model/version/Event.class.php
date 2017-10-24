<?php
/*
 * 事件处理类
 * 
 */
namespace version;

class Event{
  /*
   * 处理事件的数据
   */
  public $data;
  /*
   * 事件响应结果
   */
  public $result;
  /*
   * 是否继续执行
   *    boolean 当true的时候，继续执行，否则终止
   */
  public $isValid = true;
  
}
