<?php
namespace algorithmic\graph;
/**
* 类名：创建有权图节点对象
  作者：tanzhangyu
  日期：2017-04-10
*/
class WeightedSpareObj 
{
    private $_id;   // int 对象id
    private $_name;  // string 对象名字
    private $_outDegree; // int 出度
    private $_inDegree; // int 入度
    private $_relatedNodesId; //与该节点关联的节点id集合
    private $_weighed; //与该节点关联的有权边
   
    // 构造对象基本属性
    function __construct(int $id, string $name = '')
    {
            $this->_id = $id;
            $this->_name = $name;
            $this->_inDegree = 0;
            $this->_outDegree = 0;
            $this->_relatedNodesId = [];
            $this->_relatedNodesId['in'] = [];
            $this->_relatedNodesId['out'] = [];
            $this->_relatedNodesId['in_out'] = [];
            $this->_weighed = [];
            $this->_weighed['in'] = [];
            $this->_weighed['out'] = [];
    }

   // 修改名字
    public function _changeName($name){
            $this->_name = $name;
    }
     
     // 取得节点对象id
     public function _getId():int{
            return $this->_id;
     }
 
   // 添加进来的边
     private function _addInEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $id = $relateObj->_getId();
            $weight = crc32(implode($weighted));
            if(isset($this->_weighed['in'][$id][$weight]))
                   return false;
            if(!isset($this->_relatedNodesId['in'][$id])){
                  $this->_relatedNodesId['in'][$id] = $id;
                  if(isset($this->_relatedNodesId['out'][$id]))
                         $this->_relatedNodesId['in_out'][$id] = $id;
            }
            $this->_weighed['in'][$id][$weight] = $weighted;
            ++$this->_inDegree;
            return true;
     }

   // 添加离开的边
     private function _addOutEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $id = $relateObj->_getId();
            $weight = crc32(implode($weighted));
            if(isset($this->_weighed['out'][$id][$weight]))
                   return false;
            if(!isset($this->_relatedNodesId['out'][$id])){
                  $this->_relatedNodesId['out'][$id] = $id;
                  if(isset($this->_relatedNodesId['in'][$id]))
                         $this->_relatedNodesId['in_out'][$id] = $id;
            }
            $this->_weighed['out'][$id][$weight] = $weighted;
            ++$this->_outDegree;
            return true;
     }

      // 导入从该节点离开的边
    public function _inportOutEdges(WeightedSpareObj &$relateObj, array $weighted):bool{
             $bool = $this->_addOutEdge($relateObj,$weighted);
             if(!$bool) return false;
             $relateObj->_addInEdge($this,$weighted);
             return true;
    }

   // 导入进入该节点的边
    public function _inportInEdges(WeightedSpareObj &$relateObj, array $weighted):bool{
          $bool = $this->_addInEdge($relateObj,$weighted);
           if(!$bool)return false;
          $relateObj->_addOutEdge($this,$weighted);
          return true;
    }

  // 删除从该节点离开的边
    private function _deleteOutEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $id = $relateObj->_getId();
            $weight = crc32(implode($weighted));
            if (!isset($this->_weighed['out'][$id][$weight]))
                   return false;
            unset($this->_weighed['out'][$id][$weight] );
            if(!$this->_weighed['out'][$id]){
                   unset($this->_weighed['out'][$id]);
                   unset($this->_relatedNodesId['out'][$id]);
                   if(isset($this->_relatedNodesId['in_out'][$id]))
                           unset($this->_relatedNodesId['in_out'][$id]);  
            }
            --$this->_outDegree;
            return true;
    }

  // 删除进入该节点的边
   private  function _deleteInEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $id = $relateObj->_getId();
            $weight = crc32(implode($weighted));
            if (!isset($this->_weighed['in'][$id][$weight]))
                   return false;
            unset($this->_weighed['in'][$id][$weight] );
            if(!$this->_weighed['in'][$id]){
                   unset($this->_weighed['in'][$id]);
                   unset($this->_relatedNodesId['in'][$id]);
                   if(isset($this->_relatedNodesId['in_out'][$id]))
                           unset($this->_relatedNodesId['in_out'][$id]);  
            }
            --$this->_inDegree;
            return true;
   }

      // 删除一条从该节点离开的边
      public function _deleteOneOutEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $bool = $this->_deleteOutEdge($relateObj,$weighted);
            if(!$bool)return false;
            $relateObj->_deleteInEdge($this,$weighted);
            return true;
    }
    
  // 删除一条进入该节点的边
    public function _deleteOneInEdge(WeightedSpareObj &$relateObj, array $weighted):bool{
            $bool = $this->_deleteInEdge($relateObj,$weighted);
            if(!$bool)return false;
            $relateObj->_deleteOutEdge($this,$weighted);
            return true;
    }
    
// 导出从该节点出发的边
    public function _exportOutEdges():array{   
            if(!$this->_outDegree)
                 return [];
            $export = [];
            $edges = []; 
            $weighted = $this->_weighed['out'];
            $from = $this->_id;
            foreach ($weighted as $to=>$w) {
                  foreach ($w as $item) {
                  $edges['from'] = $from;
                  $edges['to'] = $to;
                  $edges['distance'] = $item['distance'];
                  $edges['cost'] = $item['cost'];
                  $export[] = $edges;
                  }      
            }
            return $export;
    }

// 导出进入该节点的边
    public function _exportInEdges():array{
             if(!$this->_inDegree)
                 return[];
            $export = [];
            $edges = []; 
            $weighted = $this->_weighed['in'];
            $to = $this->_id;
              foreach ($weighted as $from=>$w) {
                    $edges['from'] = $from;
                    $edges['to'] = $to;
                    $edges['weighted'] = $w;
                    $export[] = $edges;
              }
            return $export;
    }

    // 获取节点的出度
    public function _outDegree():int{
          return $this->_outDegree;
    }

   // 获取节点的入度
    public function _inDegree():int{
         return $this->_inDegree;
    }

    // 返回关联节点id
    public function _relatedNodesId(){    
         return $this->_relatedNodesId;
    }
}