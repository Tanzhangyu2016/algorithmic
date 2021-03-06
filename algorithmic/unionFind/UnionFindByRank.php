<?php
namespace algorithmic\unionFind;
use algorithmic\unionFind\UnionFind;
/**
* 类名:并查集之rank优化
  作者：tanzhangyu
  日期：2017-04-01
*/
class UnionFindByRank extends UnionFind
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
            $this->_array[] = ['parent' => $i,'rank' => 1 ];
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
                 $p = $parent['parent'];
                 $parent = $this->_find($p);
            }
           return $parent;
    }

    public function _unionElements($p,$q){
        $pParent = $this->_find($p);
        $qParent = $this->_find($q);
        if(!$pParent or !$qParent)
            return;
        if ($pParent['rank'] > $qParent['rank']) {
                $this->_array[$qParent['parent']]['parent'] = $pParent['parent'];
        }elseif($pParent['rank'] < $qParent['rank']){
               $this->_array[$pParent['parent']]['parent'] = $qParent['parent']; 
        }else{
             $this->_array[$qParent['parent']]['parent'] = $pParent['parent'];
             $this->_array[$pParent['parent']]['rank']++;
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