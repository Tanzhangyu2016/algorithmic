<?php
/*
类名：并查集之快速查找
作者：tanzhangyu
日期：2017-04-01
*/
namespace algorithmic\unionFind;
use algorithmic\unionFind\UnionFind;
class QuickFind extends UnionFind
{
  
     private $_counts;
     private $_id;
     
    function __construct(){

         $this->_counts = 0; 
         $this->_id = [];
    }

  // 创建一个并查集的数组元数据
    public function _createId(int $n){
          for ($i = 0; $i < $n; $i++) { 
                $this->_id[]= $i;
                $this->_counts++;
            }  
    }
    // 查找某个节点id的值
   public function _find($p){
         if($p>0 && $p<$this->_counts)
            return $this->_id[$p];
        return;
   }

   // 合并两个节点
   public function _unionElements($p,$q){
      $pId = $this->_find($p);
      $qId = $this->_find($q);
      if(!$pId or !$qId)
        return;
      for ($i = 0; $i < $this->_counts ; $i++) { 
            if($this->_id[$i]==$qId)
                $this->_id[$i] = $pId;
      }
   }

   public function _isConnected($p,$q){
       $pId = $this->_find($p);
       $qId = $this->_find($q);
       if (!$pId or !$qId) 
          return false;
       if($pId == $qId)
          return true;
       return false;   
   }
}

