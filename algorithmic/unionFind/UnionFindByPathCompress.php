<?php
namespace algorithmic\unionFind;
use algorithmic\unionFind\UnionFind;
/**
* 类名:并查集之路径压缩
  作者：tanzhangyu
  日期：2017-04-01
*/
class UnionFindByPathCompress extends UnionFind
{
     private $_array;
     private $_counts;
    function __construct()
    {
        $this->_array = [];
        $this->_counts = 0;
    }

    public function _createArray(int $n){
        for ($i = 0; $i < $n; $i++) { 
            $this->_array[] = ['parent' => $i,'size' => 1 ];
            $this->_counts++;
        }
    }

    public function _find($p){
            if($p>=0 && $p<$this->_counts)
                return $this->_array[$p];
            return;
    }

    private function _getRoot(int $p){
           $parent = $this->_find($p);
            while($p != $parent['parent']){
                 $this->_array[$p]['parent'] = $this->_array[$parent['parent']]['parent'];
                 $p = $this->_array[$p]['parent'];
                 $parent = $this->_find($p);
            }
           return $parent;
    }

    public function _unionElements($p,$q){
        $pParent = $this->_find($p);
        $qParent = $this->_find($q);
        if(!$pParent or !$qParent)
            return;
        if ($pParent['size'] > $qParent['size']) {
                $this->_array[$qParent['parent']]['parent'] = $pParent['parent'];
                $this->_array[$pParent['parent']]['size'] += $qParent['size'];
        }else{
               $this->_array[$pParent['parent']]['parent'] = $qParent['parent'];
              $this->_array[$qParent['parent']]['size'] += $pParent['size'];
        }
    }

    public function _isConnected($p,$q){
         $pParent = $this->_find($p);
         $qParent = $this->_find($q);
         if(!$pParent or !$qParent)
             return false;
         if($pParent['parent'] == $qParent['parent'])
             return true;
         return false;
    }
}