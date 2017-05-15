<?php
namespace algorithmic\graph;
use algorithmic\graph\WeightedSpareObj as WSO;
use algorithmic\heap\MIHFG as MIHFG;
/**
* 类名：有向有权图
  作者：tanzhangyu
  日期：2017-04-10
*/
class WeightedDirectedSpareGraph 
{
    private $_nodes;   //节点总数
    private $_sides;   // 边总数
    private $_graph;  //图的数组集合
    private $_graphfile;  // 导出的图的文件的路径
    public $_visited; // 节点的访问状态
    private $_components; // 联通分量
    private  $_parent;  // 节点间的联系
    private $_parentToReset;   //已存在的路径的节点间的联系
    private $_visitedToReset; // 已访问的节点
    private $_index; 
    private $_indexGether;
    private $_nameGether;
    private $_edgesArray;
    private $_name;
    private $_weighted;
    private $_componentsAll;

    function __construct(string $name='')
    {
            $this->_nodes = 0;
            $this->_sides = 0;
            $this->_graph = [];
            $this->_visited = [];
            $this->_components = [];
            $this->_components['in']= [];
            $this->_components['in_out'] = [];
            $this->_components['out'] = [];
            $this->_components['in']['group'] = 0;
            $this->_components['in_out']['group'] = 0;
            $this->_components['out']['group'] = 0;
            $this->_componentsAll = [];
            $this->_componentsAll['in']= [];
            $this->_componentsAll['in_out'] = [];
            $this->_componentsAll['out'] = [];
            $this->_parent = [];
            $this->_parentToReset = [];
            $this->_visitedToReset = [];
            $this->_index = 0;
            $this->_graphfile = '';
            $this->_indexGether = [];
            $this->_nameGether = [];
            $this->_edgesArray = [];
            $this->_name = $name;
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

  // 添加节点
   private function _addNode($index=false){
         $this->_updateIndex();
         $index = ($index)?$index:$this->_index;
         $nodeObj = new WSO($index);
         $this->_graph[$index] = $nodeObj;
         $this->_visited[$index] = false;
         $this->_parent[$index] = $index;
         $this->_indexGether[$index] = true;
         ++$this->_nodes;
   }
  
  // 更新$_index
   private function _updateIndex(){
          while ($this->_checkNode($this->_index)) {
                ++$this->_index;
    }  
   }

   // 创建有权边的权值
   private function _createWeighted(array $weighted){
           $this->_weighted['distance'] = (current($weighted))?current($weighted):(float) 0;
           $this->_weighted['cost'] = (next($weighted))?current($weighted):(float) 0;
   }

  // 添加一个节点
  public function _addOneNode(){
        $this->_addNode();
  }

  // 添加多个节点
  public function _addNodes(int $n){
        for ($i = 0; $i < $n; ++$i) { 
               $this->_addNode();
        }
  }
  
  // 取得图的基本信息
  public function _Info() :array{
        $Info = [];
        $info['nodes'] = $this->_nodes;
        $info['sides'] = $this->_sides;
        $info['graphfile'] = $this->_graphfile;    
        return $info;
  }

  // 取得一张图
  public function _getGraph():array{
         return $this->_graph;
  }
  
  // 取得图中的任意一个节点
  public function _getOneInGraph(int $index) {
        if($this->_checkNode($index))
               return $this->_graph[$index];
        return;     
  }

// 查看图中的节点
  public function _indexGether():array{
         return $this->_indexGether;
  }

  // 检查某个节点是否存在
  public function _checkNode(int $index):bool{
           return  isset($this->_indexGether[$index]);
  }

// 导入一条边
 public function _inportOneOutEdge(int $from,int $to,array $weighted):bool{
       $this->_createWeighted($weighted);
       if(!$this->_checkNode($from))
             $this->_addNode($from);
       if(!$this->_checkNode($to))
            $this->_addNode($to);
       $bool = $this->_graph[$from]->_inportOutEdges($this->_graph[$to],$this->_weighted);
       if($bool)
             ++$this->_sides; 
        return true; 
 }

// 取得所有的边
public function _getAllOfOutEdges():array{
       $edges = [];
       foreach ($this->_indexGether as $id =>$value) {
              if($this->_graph[$id]->_outDegree())
              $edges[$id] = $this->_graph[$id]->_exportOutEdges();
       }
       return $this->_edgesArray = &$edges;
}

// 删除一条边
  public function _deleteOutEdges(int $from,int $to,array $weighted):bool{
        $this->_createWeighted($weighted);
       if(!$this->_checkNode($from) or !$this->_checkNode($to))
             return false;
       $bool = $this->_graph[$from]-> _deleteOneOutEdge($this->_graph[$to],$this->_weighted);
       if($bool)
            --$this->_sides;  
       return true;  
 }

// 将所有边转化为字符串
private function _edgesToString():string{
        if(!$this->_edgesArray)
              $this->_getAllOfOutEdges();
        $string = '';   
        foreach ($this->_edgesArray as $from => $value) {
              foreach ($value as $v) {
                     $weighted = implode(' ', $v['weighted']);
                    $string .= $from.' '.$v['to'].' '.$weighted."\n";
              }
        }
        return $string;
}
 
 // 导出所有的边到文件
public  function _exportToFile(){
        $path = dirname(__FILE__).'/weighted_graph_'.$this->_name.'.txt';
        $file = fopen($path, 'w');
        $header = 'name: '.$this->_name."\nnodes: ".$this->_nodes."\nsides: ".$this->_sides."\nedges:\n"; 
        fwrite($file, $header);
        fwrite($file, $this->_edgesToString());
        fclose($file);
}

// 从文件导入一幅图的初始数据
private function _readFIle(string $path){
      $graph = file($path,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) or exit('unable to open this file');
      if(!$graph)
           return;
      $this->_name = ltrim(strrchr(array_shift($graph), ':'),":\0");
      array_shift($graph);
      array_shift($graph);
      array_shift($graph);         
      return $graph;
}

//导入一副图
public function _inportGraph(string $path){
     $graph = $this->_readFIle($path);
     if(!$graph)
          return false;
     $graph = array_map(function($item){     
          $item = explode(" ", $item);
          $item = array_filter($item,function($ele){
               if($ele === ' ')
                   return false;
               return true;  
          });
          return $item;
     }, $graph);
     foreach ($graph as $item) {
             $from = array_shift($item);
             $to = array_shift($item);
             $this->_inportOneOutEdge($from,$to,$item);
        }
        return true;   
}

// 检测两点是否相连
public function _isLinked(int $nodeA,int $nodeB):bool{
      if(!$this->_isAbleTo($nodeA,$nodeB)){
            if(!$this->_isAbleTo($nodeB,$nodeA))return false;  
      } 
     return true;
}

// 检测A点能否到达B点
public function _isAbleTo($nodeA,$nodeB){
        if(!$this->_checkNode($nodeA) || !$this->_checkNode($nodeB) || $nodeA == $nodeB || !$this->_graph[$nodeA]->_outDegree()) 
              return false;
       $this->_resetVisited();
       $this->_resetParent();
       $pid = $this->_graph[$nodeA]->_getId();
       if(!$this->_visited[$pid]){
             $this->_visited[$pid] = true;
             $this->_visitedToReset[$pid] = $pid;
       }
       $this->_deapthFirstTraver($this->_graph[$nodeA],$pid,$nodeB);
       if($this->_visited[$nodeB]) return true;
       return false;
}

// 深度优先遍历
private function _deapthFirstTraver(WSO $obj,int $pid,$isLink=false){
      $outEdges = $obj->_relatedNodesId()['out'];
      foreach ($outEdges as $index) {
              if(!$this->_visited[$index]){
                    $this->_parent[$index] = $pid;
                    $this->_parentToReset [$index] =$index;
                    $this->_visited[$index] = true;
                    $this->_visitedToReset [$index]= $index;
                     if( $index == $isLink)return;
                    $this->_deapthFirstTraver($this->_graph[$index],$index,$isLink);
              }
    }  
      // foreach 效率是while循环的十多倍
  }

// 深度优先遍历for联通分量
private function _deapthFirstTraverForComponents(WSO $obj,int $pid,string $link='in_out'){
      $outEdges = $obj->_relatedNodesId()[$link];
      foreach ($outEdges as $index) {
              if(!$this->_visited[$index]){
                    // $this->_parent[$index] = $pid;
                    // $this->_parentToReset [$index] =$index;
                    $this->_visited[$index] = true;
                    $this->_visitedToReset [$index]= $index;
                    $this->_components[$link][$index] = $this->_components[$link]['group'];
                    $this->_deapthFirstTraverForComponents($this->_graph[$index],$index,$link);
              }
    }  
      // foreach 效率是while循环的十多倍
  }

  // 查找某一点的联通分量
  private function _buildComponents(int $node,string $link='in_out'):bool{
             if(!$this->_checkNode($node) || !$this->_graph[$node]->_relatedNodesId()[$link])
                    return false;
            if(isset($this->_components[$link][$node]))
                    return true;
           $this->_resetVisited();
           $this->_resetParent();
           if(!$this->_visited[$node]){
                 $this->_visited[$node] = true;
                 $this->_visitedToReset[$node] = $node;
                 $group = $this->_components[$link]['group'];
                 $this->_components[$link][$node] = $group;
                 $this->_deapthFirstTraverForComponents($this->_graph[$node],$node,$link);
                 $this->_componentsAll[$link][$group] = $this->_visitedToReset;
                ++$this->_components[$link]['group'];
                return true;
           }
           return false;
  }
  
  // 查找并返回某一个点的联通分量
  public function _findComponentOfOneNode(int $node,string $link='in_out'):array{
          if($this->_buildComponents($node,$link)){
               $group = $this->_components[$link][$node];
               return $this->_componentsAll[$link][$group];
          }
          return [];
  }

  // prim最小生成树
  public function _minSpanTree(int $node,string $link='out'):array{
           if(!$this->_checkNode($node)) return [];
           if($link =='out')
              return  $this->_outSpanTree($node);
           return $this->_inSpanTree($node);
  }

// 生成从某点出发的最小生成树
  private function _outSpanTree(int $node):array{
           $heap = new  MIHFG;
           $inQueue = [];
           $outQueue = [];
           $inSpanTree = [];
           $inSpanTree['edges'] = 0;
           $inSpanTree['totalDistance'] = 0;
           $inSpanTree['totalCost'] = 0;
           $outQueue[$node] = $node;
 loop: 
           $weighted = $this->_graph[$node]->_exportOutEdges();
           if($weighted){
                 foreach ($weighted as $w) {
                             $to = $w['to'];
                            if(!isset($outQueue[$to])){
                                   if(isset($inQueue[$to])){
                                          if($w['distance'] < $heap->_getOneElementOfArray($to)['distance'])
                                               $heap->_change($w,$to,'distance');
                                   }else{
                                               $heap->_addOne($w,$to,'distance');
                                               $inQueue[$to] = $to;
                                   }
                            }
                     }
           }
  getMin:
            $element = $heap->_getMin('distance'); 
            if(!$element)
                  goto end;
            $node = $element['to'];
            if(isset($outQueue[$node]))
                  goto getMin;
            $outQueue[$node] = $node;
            $inSpanTree[] = $element;
            ++$inSpanTree['edges'];
            $inSpanTree['totalDistance']+=$element['distance'];
            $inSpanTree['totalCost']+=$element['cost'];
            goto loop;  
end:    return $inSpanTree;

  }

  // dijkstra单源最短路劲
  // $nodeA 起点
  // $nodeB 终点
     public function _dijkstragoto(int $nodeA, $nodeB=false):array{
            if(!$this->_checkNode($nodeA))
                    return [];
            if($nodeB!==false){
                    if(!$this->_checkNode($nodeB))
                           return [];
            }
           $heap = new  MIHFG;
           $inQueue = [];
           $outQueue = [];
           $inSpanTree = [];
           $node = $nodeA;
           $outQueue[$node] = $node;
           $start = 0;
 loop: 
           $weighted = $this->_graph[$node]->_exportOutEdges();
           if($weighted){
                 foreach ($weighted as $w) {
                             $to = $w['to'];
                            if(!isset($outQueue[$to])){
                                  $w['minDistance'] = $w['distance'] + $start;
                                   if(isset($inQueue[$to])){
                                          if($w['minDistance'] < $heap->_getOneElementOfArray($to)['minDistance'])
                                               $heap->_change($w,$to,'minDistance');
                                   }else{   
                                               $heap->_addOne($w,$to,'minDistance');
                                               $inQueue[$to] = $to;
                                   }
                            }
                     }
           }
            $element = $heap->_getMin('minDistance'); 
            if(!$element)
                  return $inSpanTree;
            $node = $element['to'];
            if($nodeB && $node ==$nodeB){
                  return $element;
            }
            $start = $element['minDistance'];
            $outQueue[$node] = $node;
            $inSpanTree[$node] = $element;
            goto loop;  
     }

       // dijkstra单源最短路劲
      // $nodeA 起点
     // $nodeB 终点
     // while比goto节省大量内存
     public function _dijkstrawhile(int $nodeA, $nodeB=false):array{
            if(!$this->_checkNode($nodeA))
                    return [];
            if($nodeB!==false){
                    if(!$this->_checkNode($nodeB))
                           return [];
            }
           $heap = new  MIHFG;
           $inQueue = [];
           $outQueue = [];
           $inSpanTree = [];
           $element = ['to'=>$nodeA,'minDistance'=>0];
           while ($element) { 
            $node = $element['to'];
            if($nodeB && $node ==$nodeB)
                  return $element;
            $start = $element['minDistance'];
            $outQueue[$node] = $node;
            $inSpanTree[$node] = $element;
            $weighted = $this->_graph[$node]->_exportOutEdges();
             if($weighted){
               foreach ($weighted as $w) {
                           $to = $w['to'];
                          if(!isset($outQueue[$to])){
                                $w['minDistance'] = $w['distance'] + $start;
                                 if(isset($inQueue[$to])){
                                        if($w['minDistance'] < $heap->_getOneElementOfArray($to)['minDistance'])
                                             $heap->_change($w,$to,'minDistance');
                                 }else{   
                                             $heap->_addOne($w,$to,'minDistance');
                                             $inQueue[$to] = $to;
                                 }
                          }
                  }
            }
            $element = $heap->_getMin('minDistance'); 
           }
           return $inSpanTree;
     }
}