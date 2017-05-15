<?php
namespace algorithmic\btree;
/**
* 
*/
class SimpleFind 
{
         private $_array = [];  

        function __construct()
        {

        }

        // 创建某一范围内的有序数组
        public static function _createArrayOrdered($start,$end,$counts){
            $arr = [];
            for ($i=0; $i < $counts; $i++) { 
                 $this->_array[] = $start;
                 if ($start<$end) 
                    $start=mt_rand($start,$start+2); 
                    if ($start>$end)$start = $end;     
            }
 }

    //迭代方式的二分法查找一个元素 
     public function _findNumber(int $int){
        $right = count($this->_array)-1;
        $left = 0;
        $mid = $left + floor(($right-$left)/2);
        while($left<$right){
            if ($this->_array[$mid]==$int) 
                 return $mid;
            if ($this->_array[$mid]>$int) {
                 $right = $mid-1;
            }else{
                $left = $mid+1;
            }
            $mid = $left + floor(($right-$left)/2);
        }
        return -1;
     }
   
   // 声明一个递归的二分查找的内部实现方法
     private function _recursionForFindNumber($int, $left,$right){

           while($left<$right){
            $mid = $left + floor(($right-$left)/2);
            if ($this->_array[$mid]==$int) 
               return $mid;
            if ($this->_array[$mid]>$int) {
               return $this->_recursionForFindNumber($int,$left,$mid-1);
            } else{
               return $this->_recursionForFindNumber($int,$mid+1,$right);
            }
        }

        return -1;
     }

  // 递归方式的二分法查找一个元素
     public function _findNumberInRecursionWay( int $int){
         $right = count($this->_array)-1;
         $left = 0;
         $a = $this->_recursionForFindNumber($int,$left,$right);
         return $a;
     }   
        
}