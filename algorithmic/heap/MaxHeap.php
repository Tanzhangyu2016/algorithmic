<?php
/**
* 类名：最大堆
   作者：tanzhangyu
   日期：2017-04-14
*/
namespace algorithmic\heap;
class MaxHeap 
{ 
          private $_array = [];
          private $_arrayLength = 0;

         public    function __construct()
            {

            }

        private function _swap(&$a,&$b){
                  $tmp = $a;
                  $a = $b;
                  $b = $tmp;
        }

        private function _exchangForShiftUp(){
               $index = $this->_arrayLength+1;
               $needle = $this->_array[$index];
               while($index > 1){
                $indexOfParent = floor($index/2);
                if ($needle > $this->_array[$indexOfParent]) {
                    $this->_array[$index] = $this->_array[$indexOfParent];
                    $index = $indexOfParent;
                }else{
                    break;
                }
               }
               $this->_array[$index] = $needle;  
        }

         private function _exchangForShiftDown($start=1){
                $needle = $start;
                $nextNeedle =$needle;
                $candidate=$this->_array[$this->_arrayLength];
                unset($this->_array[$this->_arrayLength]);
                $this->_arrayLength--;
                while(2*$needle <= $this->_arrayLength) {
                         $nextNeedle = $needle*2;
                           if ($nextNeedle+1 <= $this->_arrayLength  ) {
                               if ($candidate >= $this->_array[$nextNeedle]&&$candidate >= $this->_array[$nextNeedle+1]) 
                                break; 
                               if ($this->_array[$nextNeedle]<$this->_array[$nextNeedle+1]) 
                                  $nextNeedle++;       
                               if ($candidate >= $this->_array[$nextNeedle]) 
                                 break;                                
                           }
                         $this->_array[$needle] = $this->_array[$nextNeedle];
                         $needle = $nextNeedle;     
                 }  
               $this->_array[$needle] = $candidate; 
    }

        public function _shiftUp($value){
                $this->_array[$this->_arrayLength+1]=$value;
                $this-> _exchangForShiftUp();
                $this->_arrayLength++; 
        }

        public function _shiftDown(){
                $outElement = $this->_array[1];
                $this->_exchangForShiftDown();

                return $outElement;
        }
        public function _getTree(){
            return $this->_array;
        }

        public function __destruct() {}

}
$obj=new MaxHeap;
$obj->_shiftUp(16);
$obj->_shiftUp(1);
$obj->_shiftUp(18);
$obj->_shiftUp(122);
$obj->_shiftUp(25);
$obj->_shiftUp(0);
$obj->_shiftUp(8);
$obj->_shiftUp(12);
$obj->_shiftUp(9);
$arr = $obj->_getTree();
var_dump($arr);
echo $obj->_shiftDown();
$arr = $obj->_getTree();
var_dump($arr);