<?php
namespace algorithmic\graph;
/**
* 类名：稠密图
作者：tanzhangyu
日期：2017-04-07
*/
final class DenseGraph  extends Graph
{
    private $_nodes;   //节点总数
    private $_sides;   // 边总数
    private $_directed;  //true or false
    private $_edges; //一个图的集合
    private $_graphfile;  // 导出的图的文件的路径
    private $_visited; // 节点的访问状态
    private $_components; // 联通分量
    private  $_parent;  // 节点间的联系
    private $_parentToReset;   //已存在的路径的节点间的联系
    private $_visitedToReset; // 已访问的节点

    // 构造并初始化一个稠密图
    function __construct(int $nodes=0,bool $directed=false)
    {
       $this->_nodes =    $nodes;
       $this->_directed = $directed;
       $this->_edges = [];
       $this->_visited = [];
       $this->_parent = [];
       $this->_parentToReset = [];
       $this->_visitedToReset = [];
       $this->_init();
    }
    
    // 初始化一个稠密图
    private function _init(){
       $this->_components = [];
       $this->_components['counts'] =  0;
       for ($i = 0; $i < $this->_nodes; $i++) { 
           $this->_group[$i] = -1;
           $this->_parent[$i] = $i;
           $this->_visited[] = false;
           for ($j = 0; $j < $this->_nodes; $j++) { 
               $this->_edges[$i][$j] =  false;
           }
       }
    }

// 重置已访问的节点状态
private function _resetVisited(){
        foreach ($this->_visitedToReset as $value) {
            $this->_visited[$value] = false;
        }
        $this->_visitedToReset = [];
}

// 重置已访问的路径的节点联系
private function _resetParent(){
        foreach ($this->_parentToReset as $value) {
            $this->_parent[$value] = $value;
        }
        $this->_parentToReset = [];
}
// 检测添加的边的是否合法或已存在
    private function _checkEdge(int $nodeA, int $nodeB):bool{
        if(!$this->_checkNode($nodeA))return false;
        if(!$this->_checkNode($nodeB))return false;
        if($this->_edges[$nodeA][$nodeB])return false;    
        return true;
    }

    //检测所给的节点是否越界
    private function _checkNode(int $node):bool{
           if($node >= 0 && $node < $this->_nodes)
              return true;
            return false;
    }

  // 添加边的实现方法
    private function _addOne(int $nodeA,int $nodeB){
         if(!$this->_checkEdge($nodeA,$nodeB))
               return;
         $this->_edges[$nodeA][$nodeB] = 1; 
          if(!$this->_directed)
               $this->_edges[$nodeB][$nodeA] = 1;
           $this->_sides++;   
    }

    // 添加一条边的公共接口
    public function _addOneEdge(int $nodeA, int $nodeB){
         $this->_addOne($nodeA,$nodeB);
    }

    // 通过数组添加一系列的边
    public function _addEdgesByArray(array $array){
        while(current($array)){
            $node = current($array);
            $this->_addOne($node[0],$node[1]);
            next($array);
        }
    }

    // 查看图的基本信息
    public function _info():array{
        $info = [];
        $info['nodes'] = $this->_nodes;
        $info['sides'] = $this->_sides;
        $info['directed'] = $this->_directed;
        return $info;
    }

    // 查看某条边是否存在
    public function _hasEdge($nodeA,$nodeB):bool{
        if($nodeA < 0 or $nodeA >= $this->_nodes)
              return false;
        if($nodeB < 0 or $nodeB >= $this->_nodes)
              return false;
        return $this->_edges[$nodeA][$nodeB];
    }

    // 删除一条边
    public function _deleteOne(int $nodeA, int $nodeB):bool{
        if (!$this->_hasEdge($nodeA,$nodeB)) 
             return false;
        $this->_edges[$nodeA][$nodeB] = false;
        if (!$this->_directed) 
        $this->_edges[$nodeB][$nodeA] = false;
        $this->_sides--;
        return true;
    }

    // 取得一个图的所有边
    public function _getEdges():array{
           return $this->_edges;
    }

    // 导出一个图
    public function _exportGraph(){

        $path = dirname(__FILE__).'/graph'.md5(microtime()).'.txt';
        $this->_graphfile = $path;
        $file = fopen($path,'w');
        $msg = $this->_nodes.' '.$this->_sides.' '.$this->_directed."\n";
        if($this->_directed){
                for ($v = 0; $v < $this->_nodes; $v++) { 
                    for ($w = 0; $w < $this->_nodes; $w++) { 
                      if($this->_edges[$v][$w]){
                        $msg .= $v.' '.$w."\n";
                  }
            }
        }
        }else{
                 $visited = [];
                 for ($v = 0; $v < $this->_nodes; $v++) { 
                    for ($w = 0; $w < $this->_nodes; $w++) { 
                      if($this->_edges[$v][$w] && !isset($visited[$w][$v])){
                        $msg .= $v.' '.$w."\n";
                        $visited[$v][$w] = 1;
                  }
            }
        }
        }
        fwrite($file, $msg);
        fclose($file);
    }

    // 导入一个图
    public function _importGraph(string $path){
         $array = file($path,FILE_IGNORE_NEW_LINES) or exit('unable to open this file');
         $info = array_shift($array);
         $info = explode(' ', $info);
         $this->_nodes = $info[0];
         $this->_directed = $info[2];
         $this->_init();
         $array = array_map(function($element){
                 $arr = explode(' ', $element);
                 $elements = [];
                 $elements [0] = $arr[0];
                 $elements[1] = $arr[1];
                 return $elements;
         }, $array);
         $this->_addEdgesByArray($array);
    }

    // 广度优先遍历
    private function _traverInBreadthWay(int $nodeA,$nodeB=false){
         $queue = [];  //必须有一个数组作为队列
         $result = []; // 必须有一个数组用于回收结果
          if (!$this->_visited[$nodeA]) {
                 array_push($queue, $nodeA);
                 $this->_visited[$nodeA] = true;
                 $this->_visitedToReset [] = $nodeA;
               }
                $node = array_shift($queue);
                $result[] = $node;
                 while (null!==$node) {
                      for ($i = 0; $i < $this->_nodes; $i++) { 
                              if(!$this->_visited[$i] && $this->_edges[$node][$i]){
                                 array_push($queue, $i);
                                 $this->_visited[$i] = true;
                                $this->_visitedToReset [] = $i;
                                 $result[] = $i;
                                 $this->_parent[$i] = $node;
                                 $this->_parentToReset[] = $i;
                                 if($nodeB && $i == $nodeB)
                                   return;
                              }                      
                         }   
                 
                 $node =  array_shift($queue);
                 } 
          return $result;
    }

   // 广度优先遍历接口
    public function _breadthFirstTraver(int $node):array{
            if(!$this->_checkNode($node))return [];
            $this->_resetVisited();
           return  $this->_traverInBreadthWay($node);
    }
   
   // 广度优先遍历与最短路径
    public function _findShortestWay(int $nodeA,int $nodeB){
            if(!$this->_checkNode($nodeA))return;
            if(!$this->_checkNode($nodeB))return;
            $this->_resetVisited();
            $this->_resetParent();
            $this->_traverInBreadthWay($nodeA,$nodeB);
            if($this->_parent[$nodeB] != $nodeB)
                  return $this->_getRoad($nodeA,$nodeB);
            return false;
    }


  // 深度优先遍历与联通分量
    public function _ComponentGetByDeapthWay():array{
          $this->_resetVisited();
          for ($i=0; $i < $this->_nodes; $i++) { 
                 if(!$this->_visited[$i]){
                  $this-> _traverInDeapthWay($i);
                  $this->_components['counts']++;
                 }
          }
          return $this->_components;
    }

    // 深度优先遍历与检测节点是否相连
    public function _isLinked(int $nodeA, int $nodeB){
            if(!$this->_checkNode($nodeA))return false;
            if(!$this->_checkNode($nodeB))return false;
            $this->_resetVisited();
            $this->_resetParent();
            $this->_traverInDeapthWay($nodeA,$nodeB);
            return $this->_visited[$nodeB];
    }

    // 寻路
    public function _findWayByDeapth(int $nodeA, int $nodeB){
          if($this->_isLinked($nodeA,$nodeB))
               return $this->_getRoad($nodeA,$nodeB);
          return  false;    
    }
   
   // 取得一条路径
   private function _getRoad(int$nodeA, int $nodeB):array{
          $road = [];
          $road[] = $nodeB;
           while ($this->_parent[$nodeB] != $nodeA ) {
                   $nodeB = $this->_parent[$nodeB]; 
                   $road[] = $nodeB;
           }
          $road[] = $nodeA;
           return $road;
   }

    // 对某一个节点进行深度优先遍历
    private function _traverInDeapthWay(int $node,$nodeB=false){
            $index = 0; 
            if(!$this->_visited[$node]){
              $this->_components['components'][$this->_components['counts']][] = $node;
              $this->_visited[$node] = true;
              $this->_visitedToReset[] = $node;
            }
            while ($index < $this->_nodes) {
               if($this->_edges[$node][$index] && !$this->_visited[$index]){
                      $this->_components['components'][$this->_components['counts']][] = $index;
                      $this->_visited[$index] = true;
                      $this->_visitedToReset[] = $index;
                      $this->_parent[$index] = $node;
                      $this->_parentToReset[] = $index;
                      $this->_traverInDeapthWay($index,$nodeB);
                      if($nodeB){
                              if($index == $nodeB)
                                return;
                      }
               }   
               $index++;
            }  
    }
}