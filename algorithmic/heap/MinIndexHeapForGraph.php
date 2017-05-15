<?php
namespace algorithmic\heap;
/**
* 类名：用于图的最小索引堆
   作者：tanzhangyu
   日期：2017-04-14
*/
final class MinIndexHeapForGraph
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
                                && $this->_array[$this->_index[$nextPointer]]['distance'] > $this->_array[$this->_index[$nextPointer+1]]['distance']) 
                                    $nextPointer++;
                               if($this->_array[$candidate]['distance'] <= $this->_array[$this->_index[$nextPointer]]['distance'])
                                    break;
                              $this->_index[$pointer] = $this->_index[$nextPointer];
                              $this->_reverse[$this->_index[$pointer]] = $pointer;  //更新索引堆中当前指针在反向堆中的索引
                              $pointer =  $nextPointer;        //指针移动
                     }
                    $this->_index[$pointer] = $candidate;
                    $this->_reverse[$this->_index[$pointer]] = $pointer;  //更新索引堆中当前指针在反向堆中的索引
            }
           
           // 向堆中添加一个元素
            public function _addOne(array $element){
                    $id = $element['to'];
                    $this->_array[$id] = $element;
                    $this->_index[$this->_indexCounts] = $id;
                    $this->_reverse[$id] = $this->_indexCounts;
                    ++$this->_indexCounts;
                    $pointer = $this->_indexCounts-1;
                    if($pointer<=0)return;
                    $this->_shiftUp($pointer);
            }
            
            // 进堆
            private function _shiftUp(int $pointer){
                    $parent = floor(($pointer-1)/2);
                    if($parent<0)return;
                    $candidate = $this->_index[$pointer];
                    $candidateArray = $this->_array[$candidate];
                    while ($candidateArray['distance'] < $this->_array[$this->_index[$parent]]['distance']) {
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
            public function _change(array $newItem){
                    $index = $newItem['to'];
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
           // 返回
            public function _getOneElementOfArray(int $index):array{
                   return $this->_array[$index];
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
            public function _getMin():array{
                  if($this->_indexCounts)
                      return $this->_deleteOne(0);
                    return [];
            }

            // 删除堆中一个元素
            private  function _deleteOne(int $index):array{
                   $delete = $this->_array[$this->_index[$index]];
                   unset($this->_array[$this->_index[$index]]);
                   $needle = $this->_indexCounts-1;
                   unset($this->_reverse[$this->_index[$index]]);
                   $this->_reverse[$this->_index[$needle]] = $index;
                   $this->_index[$index] = $this->_index[$needle];
                   --$this->_indexCounts;
                   if($this->_indexCounts<1)
                          return $delete;
                   $change = $this->_array[$this->_index[$index]];
                    array_pop($this->_index);
                   $this->_change($change); 
                   return $delete;
            }

            // 删除堆中任意一个位置上的元素
            public function _deleteAnyOne(int $index):array{
                 if(!isset($this->_index[$index])) return [];
                 return $this->_deleteOne($index);
            }  
}
