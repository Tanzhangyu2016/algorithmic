<?php
namespace algorithmic\heap;
/**
*  
*/
class MinHeap extends Heap
{
        private $_array=[];
        private $_counts=0;

       public   function __construct()
        {
          
        }
        
        protected function _shiftDown(int $pointer=0){
                    $candidate = $this->_array[$pointer];
                    $anchorPointer = $pointer;
                     while ($anchorPointer*2+1 < $this->_counts) {
                             $nextPointer = $anchorPointer*2+1;
                             if ($nextPointer+1 < $this->_counts && $this->_array[$nextPointer] > $this->_array[$nextPointer+1]) 
                                $nextPointer++;
                            if ($candidate <= $this->_array[$nextPointer])break;
                             $this->_array[$anchorPointer] = $this->_array[$nextPointer];
                             $anchorPointer = $nextPointer;
                     }
                     $this->_array[$anchorPointer] = $candidate;
        }
        protected function _arrayToShift($pointer=0){

                 for ($i=$pointer; $i>=0  ; $i--) {
                       $this->_shiftDown($i);
                 }
        }

        public function _shiftUp(array $array){
                    $this->_array = array_merge($this->_array,$array);
                    $this->_counts += count($array);
                    $pointer=floor(($this->_counts-1)/2);
                    $this->_arrayToShift($pointer);
        }
         
        public function _getMin(){
            $min = $this->_array[0];
            $this->_array[0] = $this->_array[$this->_counts-1];
            unset($this->_array[$this->_counts-1]);
            $this->_counts--;
            $this->_shiftDown();
            return $min;
        }

        public function _getArray(){
            return  $this->_array;
        }

        public function _minToMax($start,$counts){
          
        }
        public function __destruct(){

        }

}
$obj=new MinHeap;
$obj->_shiftUp([2,23,11,5,0,89,222,89,12,58]);
$obj->_shiftUp([21,2,12,5,1,891,22,8,11,51]);
$arr=$obj->_getArray();
echo $obj->_getMin();
echo '<pre>';
print_r($arr);
echo '</pre>';
$arr=$obj->_getArray();
echo '<pre>';
print_r($arr);
echo '</pre>';