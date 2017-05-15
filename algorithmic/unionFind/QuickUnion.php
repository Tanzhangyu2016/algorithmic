<?php
namespace algorithmic\unionFind;
use algorithmic\unionFind\UnionFind;
/**
* 类名:并查集之快速合并
  作者：tanzhangyu
  日期：2017-04-01
*/
class QuickUnion extends UnionFind
{
     private $_id;
     private $_counts;
    function __construct()
    {
        $this->_id = [];
        $this->_counts = 0;
    }


  // 创建一个并查集的数组元数据
    public  function _createId(int $n){
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

   //查找某个点的根 
   private function _getRoot(int $p){
        while ($p != $this->_find($p) ) {
            if(!is_integer($p))
                break;
             $p = $this->_find($p);
        }
        return $p; 
   }

   // 合并两个点
   public function _unionElements($p,$q){
        $pRoot = $this->_getRoot($p);
        $qRoot = $this->_getRoot($q);
        if(!$qRoot or !$pRoot)
            return;
        $this->_id[$qRoot] = $pRoot;
   }
  
  // 检查两个点是否连接
   public function _isConnected($p,$q){
        $pRoot = $this->_getRoot($p);
        $qRoot = $this->_getRoot($q);
        if(!$pRoot or !$qRoot)
            return false;
        if($pRoot == $qRoot)
            return true;
        return false;
   }
}