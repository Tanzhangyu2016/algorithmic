<?php
namespace algorithmic\graph;
/**
* 类名：有向有权图
  作者：tanzhangyu
  日期：2017-04-10
*/
class WDSG
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

    function __construct(string $name='')
    {
            $this->_nodes = 0;
            $this->_sides = 0;
            $this->_graph = [];
            $this->_visited = [];
            $this->_components = [];
            $this->_components['counts'] = 0;
            $this->_components['components'] = [];
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
         $this->_graph[$index] = $this->_initEdge();
         $this->_visited[$index] = false;
         $this->_parent[$index] = $index;
         $this->_indexGether[$index] = true;
         ++$this->_nodes;
   }

  // 初始化节点 
  private function  _initEdge(){
          $array = [];
          $array ['name'] = ' ';
          $array['inDegree'] = 0;
          $array['outDegree'] = 0;
          $array['outEdges'] = [];
          $array['inEdges'] = [];
          $array['out'] = [];
          $array['in'] = [];
          $array['in_out'] = [];
          return $array;
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
        $info['components']  = $this->_components;
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

// 导入一条从该节点离开的边
 public function _inportOneOutEdge(int $from,int $to,array $weighted):bool{
       $this->_createWeighted($weighted);
       if(!$this->_checkNode($from))
             $this->_addNode($from);
       if(!$this->_checkNode($to))
             $this->_addNode($to);
       $bool = $this->_inportOutEdges($from,$to,$this->_weighted);
       if($bool){
             ++$this->_sides; 
             return true;
       }
        return false; 
 }

// 导入一条进入该节点的边
 public function _inportOneInEdge(int $from,int $to,array $weighted):bool{
       $this->_createWeighted($weighted);
       if(!$this->_checkNode($from))
             $this->_addNode($from);
       if(!$this->_checkNode($to))
             $this->_addNode($to);
       $bool = $this->_inportInEdges($from,$to,$this->_weighted);
     if($bool){
             ++$this->_sides; 
             return true;
       }
        return false; 
 }

// 添加从该节点离开的边
private function _inportOutEdges(int $from,int $to,array $weighted):bool{
            $bool = $this->_addOutEdge($from, $to, $weighted);
             if(!$bool) return false;
             $this->_addInEdge($from, $to, $weighted);
             return true;
}

// 添加进入该节点的边
private function _inportInEdges(int $from,int $to,array $weighted):bool{
            $bool = $this->_addInEdge($from, $to, $weighted);
             if(!$bool) return false;
             $this->_addOutEdge($from, $to, $weighted);
             return true;
}

 // 添加进来的边
     private function _addInEdge(int $from, int $to, array $weighted):bool{
            $weight = crc32(implode($weighted));
            if(isset($this->_graph[$to]['inEdges'][$from][$weight]))
                   return false;
            $this->_graph[$to]['inEdges'][$from][$weight] = $weighted;
             $this->_graph[$to]['out'][$from] = $from;
            ++$this->_graph[$to]['inDegree'];
            return true;
     }

   // 添加离开的边
     private function _addOutEdge(int $from, int $to, array $weighted):bool{
            $weight = crc32(implode($weighted));
            if(isset($this->_graph[$from]['outEdges'][$to][$weight]))
                   return false;
            $this->_graph[$from]['outEdges'][$to][$weight] = $weighted;
            $this->_graph[$from]['out'][$to] = $to;
            ++$this->_graph[$from]['outDegree'];
            return true;
     }

// 取得所有的边
public function _getAllOfOutEdges():array{
       $edges = [];
       foreach ($this->_indexGether as $id =>$value) {
              if($this->_graph[$id]['outDegree'])
              $edges[$id] = $this->_exportOutEdges($id);
       }
       return $this->_edgesArray = &$edges;
}

// 导出一个节点的所有从该节点离开的边
private function _exportOutEdges(int $from):array{
          $outEdges = $this->_graph[$from]['outEdges'];
          $edges = [];
          $export = [];
          foreach ($outEdges as $to => $item) {
                foreach ($item as $weighted) {
                       $edges['from'] = $from;
                       $edges['to'] = $to;
                       $edges['weighted'] = $weighted;
                       $export[] = $edges;
                }
          }
          return $export;
}

// 删除一条从该节点离开的边
  public function _removeOneOutEdge(int $from,int $to,array $weighted):bool{
       $this->_createWeighted($weighted);
       if(!$this->_checkNode($from) or !$this->_checkNode($to))
             return false;
       $bool = $this-> _removeOutEdges($from,$to,$this->_weighted);
      if($bool){
             --$this->_sides; 
             return true;
       }
        return false; 
 }

// 删除一条从该节点离开的边
  public function _removeOneInEdge(int $from,int $to,array $weighted):bool{
       $this->_createWeighted($weighted);
       if(!$this->_checkNode($from) or !$this->_checkNode($to))
             return false;
       $bool = $this-> _removeInEdges($from,$to,$this->_weighted);
      if($bool){
             --$this->_sides; 
             return true;
       }
        return false;   
 }

 // 删除从该节点离开的边
    private function _deleteOutEdge(int $from,int $to,array $weighted):bool{
            $weight = crc32(implode($weighted));
            if(!isset($this->_graph[$from]['outEdges'][$to][$weight]))
                   return false;
            unset($this->_graph[$from]['outEdges'][$to][$weight]);
            if(!$this->_graph[$from]['outEdges'][$to]){
                   unset($this->_graph[$from]['outEdges'][$to]);
                   unset($this->_graph[$from]['out'][$to]);
            }     
            --$this->_graph[from]['outEdges'];
            return true;
    }

  // 删除进入该节点的边
   private  function _deleteInEdge(int $from,int $to,array $weighted):bool{
            $weight = crc32(implode($weighted));
            if(!isset($this->_graph[$to]['inEdges'][$from][$weight]))
                   return false;
            unset($this->_graph[$to]['inEdges'][$from][$weight]);
            if(!$this->_graph[$to]['inEdges'][$from]){
                  unset($this->_graph[$to]['inEdges'][$from]);
                  unset($this->_graph[$to]['out'][$from]);
            }   
            --$this->_graph[to]['inEdges'];
            return true;
   }

   // 删除从该节点离开的边
    private function  _removeOutEdge(int $from,int $to,array $weighted):bool{
            $bool = $this->_deleteOutEdge($from,$to,$weighted);
            if(!$bool)return false;
            $this->_deleteInEdge($from,$to,$weighted);
            return true;
    }
    
   // 删除进入该节点的边
    private function _removeInEdge(int $from,int $to,array $weighted):bool{
           $bool = $this->_deleteInEdge($from,$to,$weighted);
            if(!$bool)return false;
            $this->_deleteOutEdge($from,$to,$weighted);
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
      $graph = file($path,FILE_IGNORE_NEW_LINES) or exit('unable to open this file');
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
public function _isAbleTo(int $nodeA,int $nodeB){
        if(!$this->_checkNode($nodeA) || !$this->_checkNode($nodeB) || $nodeA == $nodeB) 
              return false;
       $this->_resetVisited();
       $this->_resetParent();
       if(!$this->_graph[$nodeA]['outDegree'])
              return;
       if(!$this->_visited[$nodeA]){
             $this->_visited[$nodeA] = true;
             $this->_visitedToReset[$nodeA] = $nodeA;
       }
       $this->_deapthFirstTraver($nodeA,$nodeB);
       if($this->_visited[$nodeB]) return true;
       return false;
}

// 深度优先遍历
private function _deapthFirstTraver($start,$end=false){
      $outEdges = $this->_graph[$start]['out'];
      foreach ($outEdges as $next) {
              if(!$this->_visited[$next]){
                    $this->_parent[$next] = $start;
                    $this->_parentToReset [$next] =$next;
                    $this->_visited[$next] = true;
                    $this->_visitedToReset [$next] = $next;
                     if( $next == $end)return;
                    $this->_deapthFirstTraver($next,$end);
              }
    }  
      // foreach 效率是while循环的十多倍
  }

}