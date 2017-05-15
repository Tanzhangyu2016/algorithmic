<?php
namespace algorithmic\heap;
/**
* 
*/
final class IndexHeap
{
            private $_index = [];  //储存堆的索引
            private $_array = [];  //原数组
            private $_reverse = []; //用于储存$_index的索引的反向堆
            private $_indexCounts = 0; //索引数组长度
             public function __construct()
            {
                
            }
           // 更新索引数组
            private function _shiftDown(int $pointer=0){
                     $candidate = $this->_index[$pointer];
                     while (2*$pointer+1 < $this->_indexCounts) {
                               $nextPointer = 2*$pointer+1;
                               if ($nextPointer+1 < $this->_indexCounts
                                && $this->_array[$this->_index[$nextPointer]] > $this->_array[$this->_index[$nextPointer+1]]) 
                                    $nextPointer++;
                               if($this->_array[$candidate] <= $this->_array[$this->_index[$nextPointer]])
                                    break;
                              $this->_index[$pointer] = $this->_index[$nextPointer];
                              $this->_reverse[$this->_index[$pointer]] = $pointer;  //更新索引堆中当前指针在反向堆中的索引
                              $pointer =  $nextPointer;        //指针移动
                     }
                    $this->_index[$pointer] = $candidate;
                    $this->_reverse[$this->_index[$pointer]] = $pointer;  //更新索引堆中当前指针在反向堆中的索引

            }

            // 对新加的元素进行位置更新
            private function _arrayToShift(int $pointer=0){
                    for ($i = $pointer;$i >= 0;$i--){
                          $this->_shiftDown($i);
                    }
            }
           
           // 以数组方式向堆中添加新的元素
            public function _shiftUpByArray(array $array){
                    $index = [];
                    $reverse = [];
                    $counts = $this->_indexCounts+count($array);
                    for($i = $this->_indexCounts;$i < $counts;$i++){
                          $index[] = $i;
                          $reverse[] = $i;
                    }
                    $this->_index = array_merge($this->_index,$index);
                    $this->_reverse = array_merge($this->_reverse,$reverse);
                    $this->_array = array_merge($this->_array,$array);
                    $this->_indexCounts = $counts;
                    if($this->_indexCounts<2)
                          return;
                    $pointer = floor(($this->_indexCounts-2)/2);
                    $this->_arrayToShift($pointer);
            }
           
           // 向堆中添加一个元素
            public function _addOne(array $element){
                    $this->_array[] = $element[0];
                    $this->_index[] = $this->_indexCounts;
                    $this->_reverse[] = $this->_indexCounts;
                    ++$this->_indexCounts;
                    $pointer = $this->_indexCounts-1;
                    if($pointer==0)return;
                    $this->_shiftUp($pointer);
            }
            
            // 进堆
            private function _shiftUp(int $pointer){
                    $parent = floor(($pointer-1)/2);
                    $candidate = $this->_index[$pointer];
                    $candidateArray = $this->_array[$candidate];
                    while ($candidateArray < $this->_array[$this->_index[$parent]]) {
                            $this->_index[$pointer] = $this->_index[$parent];
                            $this->_reverse[$this->_index[$pointer]] = $pointer;
                            $pointer = $parent;
                            if($pointer==0)break;
                            $parent = floor(($pointer-1)/2);
                    }
                    $this->_index[$pointer] = $candidate;
                    $this->_reverse[$this->_index[$pointer]] = $pointer;
            }
            
            // 改变堆中某个元素的位置
            public function _change(int $index, int $newItem){
                    if (!isset($this->_array[$index])){
                        echo  '请输入正确的索引';
                        return;
                    }
                     $this->_array[$index] = $newItem;
                     $pointer = $this->_reverse[$index];
                     $this->_shiftUp($pointer);
                     $this->_shiftDown($pointer); 
            }
           
           // 返回原数组
            public function _getArray():array{
                   return $this->_array;
            }
           
           // 返回数组在堆中的位置
            public function _getIndex():array{
                  return $this->_index;
            }
           
           // 返回返回堆的索引对应的原数组的位置
            public function _getReverse(): array{
                return $this->_reverse;
            }
            
            // 按索引堆的位置依次取出数组
            public function _getArrayInShift():array{
                  $array = [];
                  for ($i=0; $i <$this->_indexCounts ; $i++) { 
                    $array[] = $this->_array[$this->_index[$i]];
                  }
                 return $array;
            }
           
           // 取得堆中的第一个元素
            public function _getMin():int{
                   return $this->_deleteOne(0);
            }

            // 删除堆中一个元素
            private  function _deleteOne(int $index):int{
                   $delete = $this->_array[$this->_index[$index]];
                   $needle = $this->_indexCounts-1;
                   $last = $this->_array[$this->_index[$needle]];
                   unset($this->_array[$this->_index[$needle]]);
                   unset($this->_reverse[$this->_index[$needle]]);
                   --$this->_indexCounts;
                   array_pop($this->_index);
                   $this->_change($this->_index[$index],$last);  
                   return $delete;
            }

            // 删除堆中任意一个位置上的元素
            public function _deleteAnyOne(int $index){
                 if(!isset($this->_index[$index])) return;
                 return $this->_deleteOne($index);
            }  
}


