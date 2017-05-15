<?php
namespace algorithmic\btree;
/**
*  类名：二叉树
   时间：2017-03-31
   作者：Tanzhangyu
*/
class BinaryTree 
{
        private $_btree;
        private $_counts;

    function __construct()
    {
                $this->_btree = [];
                $this->_counts = 0;
    }

    // 构造新节点
    private function _createNode(array $array){
               $node = (array) [];
               $node['key'] = $array['key'];
               $node['value'] = $array['value'];
               $node['counts'] = (int) 0;
               $node['left'] = null;
               $node['right'] = null;
               return $node;
    }

    // 创建一个根节点
    private  function _createRoot(array $array){
               $this->_btree['key'] = $array['key'];
               $this->_btree['value'] = $array['value'];
               $this->_btree['counts'] = (int) 1;
               $this->_btree['left'] = null;
               $this->_btree['right'] = null;
               $this->_counts++;
    }

    //插入节点的内部操作 
    private function _insert($array,&$node){
               if ($node==null) {
                    $node = $this->_createNode($array);
                    $this->_counts++;
                    return;
               }
               if ($array['key'] < $node['key']) {
                    return $this->_insert($array,$node['left']);
               }elseif($array['key'] > $node['key']){
                   return $this->_insert($array,$node['right']);
               }else{
                   $node['value'] = $array['value'];
                   return;
               }
    }

    // 插入一个节点 
    public function _insertOneNode(array $array){
                if(!is_array($array))return;
                if($this->_counts==0)
                    return $this->_createRoot($array);
                return $this->_insert($array,$this->_btree);
    }

  //创建或插入多个节点
    public function _arrayToBtree(array $array){
               if(!is_array($array))return;
               $counts = count($array);
               for ($i = 0; $i < $counts ; $i++) { 
                    $this->_insertOneNode($array[$i]);
               }
    }

  // 获取最大值
    public function _getMax(){
             if($this->_counts==0)return;
             $node = $this->_btree['right'];
             while($node['right']!=null){
                    $node = $node['right'];
            }
            return $node;
    }

    // 获取最小值
    public function _getMin() {
            if($this->_counts==0)return;
            $node = $this->_btree['left'];
            while ($node['left']!=null) {
                  $node = $node['left'];
            }
           return $node;
    }

    // 查找某个元素
    public function _findOne($key){
        return $this->_find($key,$this->_btree);
    }

   // 查找的内部实现方法
    private function _find($key,&$node) {
            if($key == $node['key'])return true;
            if($key > $node['key'] ) {
                if($node['right'] == null)
                    return false;
                return $this->_find($key,$node['right']);
            }
            else{
                if($node['left'] == null)
                    return false;
                return $this->_find($key,$node['left']);
            }
    }
   
   // 前序遍历的内部实现方法
    private function _traverseForPreTraverse($node){
                if($node == null) return;
                echo $node['key'].'  ';
                if($node['left'] != null)
                     $this->_traverseForPreTraverse($node['left']);
                if($node['right'] != null)
                     $this->_traverseForPreTraverse($node['right']);
    }

    // 前序遍历
    public function _preTraverse(){
              $this->_traverseForPreTraverse($this->_btree);
    }
    
    // 中序遍历的内部实现方法
    private function _traverseForInterTraverse($node){
              if($node == null)return;
              if($node['left'] != null)
                    $this->_traverseForInterTraverse($node['left']);
              echo $node['key'].'  ';
              if($node['right'] != null)
                     $this->_traverseForInterTraverse($node['right']); 
    }

    // 中序遍历
    public function _interTraverse(){
             $this->_traverseForInterTraverse($this->_btree);
    }
    
    // 后序遍历的内部实现方法
    private function _traverseForPostTraverse($node){
              if($node == null) return;
              if($node['left'] != null)
                     $this->_traverseForPostTraverse($node['left']);
              if($node['right'] != null)
                     $this->_traverseForPostTraverse($node['right']);
              echo $node['key'].'   ';   
    }

    // 后序遍历
    public function _postTraverse(){
             $this->_traverseForPostTraverse($this->_btree);
    }

    // 获取二叉树
    public function _getBtree(){
             return $this->_btree;
    }

    // 获取二叉树节点数目

    public function _getCounts(){
        return $this->_counts;
    }
    
    // 广度优先遍历
    public function _breadthFirstTraverse(){
            $queue = [];
            array_push($queue, $this->_btree);
            while (current($queue)) {
                      $element = current($queue);
                      if($element['left'] != null)
                          array_push($queue, $element['left']);
                      if($element['right'] != null)
                          array_push($queue, $element['right']);
                      echo $element['key'].'   ';
                      array_shift($queue); 
            }
    }

    // 引用方式查找最大值节点
    private function _findMaxAndDelete(&$node){
            if($node['right'] != null)
                return $this->_findMaxAndDelete($node['right']);
            return $this->_dealWithMaxNode($node);
    }
    
    // 删除最大值的节点处理方法
    private function _dealWithMaxNode(&$node){
            if($node['left'] != null)
                $node = $node['left'];
            else
                $node = null;
           
    }
    // 删除最大值
    public function _deleteMax(){
            $this->_findMaxAndDelete($this->_btree);
             $this->_counts--;
    }
   
   // 删除最小值的节点处理方法
    private function _dealWithMinNode(&$node){
             if($node['right'] != null)
                  $node = $node['right'];
              else
                  $node = null;
    }

    // 引用方式查找最小值节点
    private function _findMinAndDelete(&$node){
            if($node['left'] != null)
                return $this->_findMinAndDelete($node['left']);
           $this->_dealWithMinNode($node);
         
    }

   // 删除最小值
    public function _deleteMin(){
            $this->_findMinAndDelete($this->_btree);
            $this->_counts--;

    }
  
  // 交换两个节点的非子节点的值
    private function _exchange(&$node,$copy){
            foreach ($copy as $key => $value) {
                   $node[$key] = $value;
            }
            unset($copy);
    }
  
  // 找到任意一颗子树的最小值
    private function _findMinAndDeal(&$node){
            if($node['left'] != null)
                return $this->_findMinAndDeal($node['left']);
            return $this->_copyPredecessorAndDelete($node);
    }
    
    // 找到复制某个节点的前驱元并删除
    private function _copyPredecessorAndDelete(&$node){
           $copy = $node;
           if($node['right'] != null)
              $node = $node['right'];
           else
              $node = null;
          unset($copy['left']);
          unset($copy['right']);
          return $copy;
  }

   //处理即将删除的节点
    private function _dealWithOneNode(&$node){
            if($node['left'] == null && $node['right'] == null){
                  $node = null;
            }elseif($node['left'] != null && $node['right'] == null){
                  $node = $node['left'];
            }elseif($node['left'] == null && $node['right'] != null){
                  $node = $node['right'];
            }else{
                    $copy = $this->_findMinAndDeal($node['right']);
                    $this->_exchange($node,$copy);
            }
            $this->_counts--;
            return true;
    }

   // 找到某个节点并删除
    private function _findAndDelete($key,&$node) {
            if($key == $node['key'])
                return $this->_dealWithOneNode($node);
            if($key > $node['key'] ) {
                if($node['right'] == null)
                    return false;
                return $this->_findAndDelete($key,$node['right']);
            }
            else{
                if($node['left'] == null)
                    return false;
                return $this->_findAndDelete($key,$node['left']);
            }
    }

    // 删除任意节点
    public function _deleteNode(int $key){
           return $this->_findAndDelete($key,$this->_btree); 
    }

    public function __destruct(){
         unset($this->_btree);
         unset($this->_counts);
    }

}