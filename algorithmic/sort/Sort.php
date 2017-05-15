<?php
// 第一版
// 声明一个快速排序的类
class Sort{
         /**
          * [swap change the value of two element]
          * @param  [type] &$a [description]
          * @param  [type] &$b [description]
          * @return [void]     [description]
          */
          private static function swap(&$a,&$b){
                                                         $tmp=$b;
                                                         $b=$a;
                                                         $a=$tmp;
                                                }
        /**
         * [checkSort check the result of the sorting method]
         * @param  [array]  $array [description]
         * @param  boolean $asc   [description]
         * @return [int]         [-1:do not need to check; 0: has wrong ;1:all right]
         */
          public static function checkSort(array$array,bool $asc=true):int{
              $count = count($array)-1;
              if($count<1) return -1;
              if($asc){
                   for ($i=0; $i < $count; ++$i) { 
                        if($array[$i]>$array[$i+1]) return 0;
                   }
              }else{
                 for ($i = 0; $i < $count; ++$i) { 
                        if($array[$i]<$array[$i+1]) return 0;
                 }
              }
               return 1;
          }                                      

                 // 以下是选择排序法
                public static function selectionSort($array){
                    $arrayLength=count($array);
                    for($i=0;$i< $arrayLength;++$i){
                      $minIndex=$i;
                      for($j=$i+1;$j< $arrayLength;++$j){
                          if($array[$minIndex]>$array[$j]){
                              $minIndex=$j;
                          }
                      }
                      self::swap($array[$i],$array[$minIndex]);
                }
                return $array;
                }

                // 以下是插入排序法
                public static function insertionSort($array){
                     $arrayLength=count($array);
                    for ($i=1;$i< $arrayLength;$i++){
                        $needle=$array[$i];
                        for ($j=$i; $j>0 &&$needle<$array[$j-1]; $j--) {
                            $array[$j]=$array[$j-1];   
                        }  
                            $array[$j]=$needle;
                    }
                    return $array;
                }

                // 以下是希尔排序法
                public static function  shellSort(&$array){//$gCount是一个比数组长度小的奇数
                    if (!is_array($array))return;
                    $arrayLength=count($array);
                    for ($gap=floor($arrayLength/2);$gap>0;$gap=floor($gap/=2)){
                        for ($i=$gap;$i<$arrayLength;$i++){
                            $needle=$array[$i];
                            for ($j=$i-$gap; $j >0&&$needle<$array[$j]; $j-=$gap) { 
                                $array[$j+$gap]=$array[$j];
                            }
                            $array[$j+$gap]=$needle;
                        }
                    }
                }

                // 以下是冒泡排序法
                public static function  bubbleSort($array){
                     $arrayLength=count($array);
                    for($i=0,$maxSelectableIndex= $arrayLength-1;$i<$arrayLength;++$i,--$maxSelectableIndex){
                //    每一次最大可排序的索引  
                    for($j=0;$j<$maxSelectableIndex;++$j){
                        if($array[$j]>$array[$j+1]){
                //            交换相邻两个指针的值
                            self::swap($array[$j],$array[$j+1]);
                        }
                    }
                }
                return $array;
                }

          // 声明一个合并的算法
            private static function mergeMethod(&$array,$offsetLeft,$middle,$offsetRight){
                 $arrOfLeft=[];
                 $arrOfRight=[];
                 for ($n=$offsetLeft; $n <=$middle ; $n++) { 
                     $arrOfLeft[]=$array[$n];
                 }
                 for ($n=$middle+1; $n <=$offsetRight ; $n++) { 
                     $arrOfRight[]=$array[$n];
                 }
                 $indexOfLeft=0;
                 $indexOfRight=0;
                 $endOfLeft=$middle-$offsetLeft;
                 $endOfRight=$offsetRight-$middle-1;
                 for ($i=$offsetLeft; $i <$offsetRight+1; $i++) {
                   if ($indexOfLeft> $endOfLeft) {
                        $array[$i]=$arrOfRight[$indexOfRight];
                        $indexOfRight++;
                   }elseif ($indexOfRight>$endOfRight) {
                        $array[$i]=$arrOfLeft[$indexOfLeft];
                        $indexOfLeft++;
                   }else{
                          if ($arrOfLeft[$indexOfLeft]<=$arrOfRight[$indexOfRight]) {
                          $array[$i]=$arrOfLeft[$indexOfLeft];
                          $indexOfLeft++;
                       }else{
                        $array[$i]=$arrOfRight[$indexOfRight];
                        $indexOfRight++;
                       }
                   } 
                 }
            }

            // 声明一个不断二分的函数
            private static function infiniteBisetion(&$array,$offsetLeft,$offsetRight){
                    // 如果切割对象长度为零，递归结束
                     if ($offsetRight-$offsetLeft<7) // 这里可以提高100%的性能   经过测试这里7最优
                      return  self::subInsertionSort($array,$offsetLeft,$offsetRight);
                     // 否则递归递归本身
                    $offsetLeft=$offsetLeft;
                    $offsetRight=$offsetRight;
                    $middle=floor(($offsetRight+$offsetLeft)/2);
                    self:: infiniteBisetion($array,$offsetLeft,$middle);
                    self::infiniteBisetion($array,$middle+1,$offsetRight);
                    if ($array[$middle]>$array[$middle+1])   //这里只有当数组长度足够大的情况，尤其对近乎有序的数组非常有用这里至关重要，否则便是阻碍
                    self::mergeMethod($array,$offsetLeft,$middle,$offsetRight);
            }

            // 声明一个归并排序接口函数
            public static function mergeSort(&$array){
                  if (!is_array($array)) return;
                   $offsetLeft=0;
                   $offsetRight=count($array)-1;
                   self::infiniteBisetion($array,$offsetLeft,$offsetRight);
            }

            // 声明一个自底向上的归并排序
            public static function mergeSortBU(&$array){
                     if (!is_array($array)) return;
                     $arrayLength=count($array);
                     for ($size=1; $size <=$arrayLength ; $size+=$size) { 
                            for ($i=0; $i +$size<$arrayLength ; $i+=$size*2) { 
                                if ($size<8) {//这一步只针对无序数组有一丁点的优化作用，但对于对于近乎有序的数组这一步是阻碍，完全是多余的
                                    self:: subInsertionSort($array,$i,min($arrayLength-1,$i+$size*2-1));
                                }else{
                                        if ($array[$i+$size-1]>$array[$i+$size])  //这里只有当数组长度足够大的情况，尤其对近乎有序的数组非常有用这里至关重要，否则便是阻碍
                                         self::mergeMethod($array,$i,$i+$size-1,min($arrayLength-1,$i+$size*2-1));
                                }
                            }
                     }
            }
          // 声明另一个自底向上的归并排序  
            public static function mergeSortBUBU(&$array){
                     if (!is_array($array)) return;
                     $arrayLength=count($array);
                     for ($size=1; $size <=$arrayLength ; $size+=$size) { 
                            for ($i=0; $i +$size<$arrayLength ; $i+=$size*2) { 
                                        if ($array[$i+$size-1]>$array[$i+$size])  //这里只有当数组长度足够大的情况，尤其对近乎有序的数组非常有用这里至关重要，否则便是阻碍
                                     self:: mergeMethod($array,$i,$i+$size-1,min($arrayLength-1,$i+$size*2-1));
                            }
                     }
            }

         // 声明一个用于辅助高级排序的插入排序方法
          private  static function subInsertionSort(&$array,$offsetLeft,$offsetRight){
                                                        for ($i=$offsetLeft+1;$i<$offsetRight+1;$i++){
                                                            $needle=$array[$i];
                                                            for ($j=$i; $j >$offsetLeft&&$needle<$array[$j-1]; $j--) { 
                                                                 $array[$j]=$array[$j-1];                                                   
                                                            }
                                                            $array[$j]=$needle;
                                                        }
                                                    }

          //  声明一个一路排序的具体方法                                            
         private static function partitionForQuickSort(&$array,$indexStart,$indexEnd){
                                                        $neddle=mt_rand($indexStart,$indexEnd);
                                                        $elementForComparision=$array[$neddle];
                                                        self::swap($array[$neddle],$array[$indexStart]);
                                                        for ($i=$indexStart+1,$j=$indexStart+1; $i <=$indexEnd ; ++$i) { 
                                                            if ($array[$i]<$elementForComparision) {
                                                                self::swap($array[$i],$array[$j]);
                                                                ++$j;
                                                            }
                                                        }
                                                        self::swap($array[$indexStart],$array[$j-1]);
                                                        return $j;
                                                    }
      //  声明一个二路排序的具体方法
 private static function partitionForQuickSort2(&$array,$indexStart,$indexEnd){
                                                        $neddle=mt_rand($indexStart,$indexEnd);
                                                        $elementForComparision=$array[$neddle];
                                                         self::swap($array[$neddle],$array[$indexStart]);
                                                        $left=$indexStart+1;
                                                        $right=$indexEnd;
                                                        while (true) {
                                                                while ($left<=$indexEnd&&$array[$left]<$elementForComparision) {$left++;}
                                                                while ($right>$indexStart&&$array[$right]>$elementForComparision) {$right--;}
                                                                if ($left>$right)break; 
                                                                self::swap($array[$left],$array[$right]);
                                                                $left++;
                                                                $right--;
                                                        }
                                                        self::swap($array[$indexStart],$array[$right]);
                                                        return $right;
                                                    }

    //  声明一个三路排序的具体方法
        private static function partitionForQuickSort3(&$array,$indexStart,$indexEnd){
                                                   $neddle=mt_rand($indexStart,$indexEnd);
                                                   $elementForComparision=$array[$neddle];
                                                   self::swap($array[$neddle],$array[$indexStart]);
                                                   $cousore=$indexStart+1;
                                                   $lt=$indexStart+1;
                                                   $gt=$indexEnd;
                                                   while ($cousore<=$gt) {
                                                       if ($array[$cousore]<$elementForComparision) {
                                                              self::swap($array[$cousore],$array[$lt]);
                                                              $cousore++;
                                                              $lt++;
                                                       }
                                                       elseif($array[$cousore]==$elementForComparision){$cousore++;}
                                                       else {
                                                              self::swap($array[$cousore],$array[$gt]);
                                                              $gt--;
                                                       }
                                                   }
                                                   self::swap($array[$indexStart],$array[$lt-1]);
                                                   return ['lt'=>$lt-2,'gt'=>$gt+1];
                                                 }
        // 声明一个一路快速排序的子函数
        private static function subQuickSort(&$array,$indexStart,$indexEnd){
                                                   // if ($indexStart>=$indexEnd) return;
                                                   if ($indexEnd-$indexStart<8) //  大概可以提高10%的性能
                                                    return self::subInsertionSort($array,$indexStart,$indexEnd);
                                                    $periphery=self::partitionForQuickSort($array,$indexStart,$indexEnd);
                                                    self::subQuickSort($array,$indexStart,$periphery-2);
                                                    self::subQuickSort($array,$periphery,$indexEnd);
                                                }

          // 声明一个二路快速排序的子函数
      private static function subQuickSort2(&$array,$indexStart,$indexEnd){
                                               // if ($indexStart>=$indexEnd) return;
                                                if ($indexEnd-$indexStart<8) //  大概可以提高10%的性能
                                                return self::subInsertionSort($array,$indexStart,$indexEnd);
                                                $periphery=self::partitionForQuickSort2($array,$indexStart,$indexEnd);
                                                self::subQuickSort2($array,$indexStart,$periphery-1);
                                                self::subQuickSort2($array,$periphery+1,$indexEnd);
                                            }    
       
          // 声明一个三路快速排序的子函数
       private static function  subQuickSort3(&$array,$indexStart,$indexEnd){
                                                  // if ($indexStart>=$indexEnd) return;
                                                 if ($indexEnd-$indexStart<8) //  大概可以提高10%的性能
                                                   return self::subInsertionSort($array,$indexStart,$indexEnd);
                                                  $periphery=self::partitionForQuickSort3($array,$indexStart,$indexEnd);
                                                  self::subQuickSort3($array,$indexStart,$periphery['lt']);
                                                  self::subQuickSort3($array,$periphery['gt'],$indexEnd);
                                             }
        // 声明一个一路快速排序接口
        public static function quickSortOne(&$array){
                                                        if (!is_array($array))return;
                                                        $arrayLength=count($array);
                                                        self::subQuickSort($array,0,$arrayLength-1);
                                                    }
        // 声明一个二路快速排序接口
      public static function quickSortTwo(&$array){
                                                if (!is_array($array))return;
                                                $arrayLength=count($array);
                                                self::subQuickSort2($array,0,$arrayLength-1);
                                            } 
         // 声明一个三路快速排序接口
      public static function quickSortThree(&$array){
                                                  if (!is_array($array))return;
                                                  $arrayLength=count($array);
                                                  self::subQuickSort3($array,0,$arrayLength-1);
                                               }        
    /**
     * [cockTailSort one way to sort by size the min and max element each turn]
     * @param  [array] $array [array to sort]
     * @return [array]        [this sorted array]
     *///鸡尾酒排序 已优化
     public static function cocktailSort($array){
             for ($i = 0,$minIndex =$i,$maxIndex = $i,$len = count($array); $i < $len; ++$i) { 
                     for ($j = $i+1; $j < $len; ++$j) {  
                          if($array[$j]<$array[$minIndex])
                                 $minIndex = $j;
                          elseif($array[$j]>$array[$maxIndex])
                                  $maxIndex = $j;
                     }
                     self::swap($array[$i],$array[$minIndex]);
                     if($maxIndex == $i)
                           self::swap($array[$len-1],$array[$minIndex]);
                     elseif($array[$len-1]<$array[$maxIndex])
                         self::swap($array[$len-1],$array[$maxIndex]);
                     --$len;
                     $minIndex = $i+1;
                     $maxIndex = $i+1;
             }
             return $array;
     } 

    /**
     * [cockTailSort one way to sort by size the min and max element each turn]
     * @param  [array] $array [array to sort]
     * @return [array]        [this sorted array]
     *///鸡尾酒排序2
     public static function cocktailSort2($array){
    //将最大值排到队尾
        $len = count($array);
        $len12 = $len/2;
        for($i = 0 ; $i < $len12; ++$i){
            for( $j = $i ; $j < $len-$i-1 ; ++$j) {
                if($array[$j] > $array[$j+1])
                    self::swap($array[$j],$array[$j+1]);
            }
            //将最小值排到队头
            for($j = $len-1-($i+1); $j > $i ; --$j){
                if($array[$j] < $array[$j-1])
                    self::swap($array[$j],$array[$j-1]);
            }
        }
        return $array;
      }      

        /**
     * [cockTailSort one way to sort by size the min and max element each turn]
     * @param  [array] $array [array to sort]
     * @return [array]        [this sorted array]
     *///鸡尾酒排序3 基于cocktailSort2 优化
     public static function cocktailSort3($array){
    //将最大值排到队尾
        $len = count($array);
        $len12 = $len/2;
        for($i = 0 ; $i < $len12; ++$i){
            for( $j = $i,$maxIndex = $j; $j < $len-$i-1 ; ++$j) {
                if($array[$maxIndex] < $array[$j+1])
                    $maxIndex = $j+1;
            }
            self::swap($array[$maxIndex],$array[$j]);
            //将最小值排到队头
            for($j = $len-1-($i+1),$minIndex = $j; $j > $i ; --$j){
                if($array[$minIndex] > $array[$j-1])
                    $minIndex = $j-1;
            }
             self::swap($array[$minIndex],$array[$j]);
        }
        return $array;
      } 
      /**
       * [radixSort 基数排序]
       * @param  [array] $array [description]
       * @return [array]        [description]
       */
      public static function radixSort(array $array):array{
          if(count($array)<2) return $array;
          // 最大值假设为第一个元素
          $max = current($array);
          // 重置数组指针，获取最大元素并将数组中的元素全部转化为字符串
          foreach ($array as $key=>$value) {
               if($max<$value)
                  $max = $value;
                $array[$key] = (string)$value;
          }
          // 获取需要循环的次数
          $radix = strlen((string) $max);
          // $contain = [0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null,7=>null, 8=>null,9=>null,];
          // 设置空桶
          $contain = [null,null,null,null,null,null,null,null,null,null,];
          // 进行第一次桶操作，并将原一维数组（已经转化为字符串）转换二维数组
          $queue = $contain;
                foreach ($array as $v) {
                     $queue[$v[strlen($v)-1]][] = $v;
                }
          // 最大数小于10，跳过此步骤
          if($radix<2) goto end;
          // 否则进行radix-1（radix>1）次桶操作
          for ($i = 1; $i < $radix; ++$i) {
              $tmp = $contain; 
              foreach ($queue as $item) {
                  if($item){
                      foreach ($item as $v) {
                         $index = strlen($v)-$i-1;
                         $index = $index<0?0:$v[$index];
                         $tmp[$index][] = $v;
                     }
                  }
              }
              $queue = $tmp;
          }
   end:
           $array = [];
           foreach ($queue as $item) {
               if($item){
                   foreach ($item as $v) {
                   $array[] = (int)$v;
                   }
               }  
           }
           return $array;
      } 
     
     /**
       * [radixSort2 基数排序]
       * @param  [array] $array [description]
       * @return [array]        [description]
       * 基于radixSort优化理论上优于radixSort，但实际上在（数据10000以上）不如radixSort
       */
      public static function radixSort2(array $array):array{
          if(count($array)<2) return $array;
          // 最大值假设为第一个元素
          $max = current($array);
          $outPut = [];//输出数组
          // 重置数组指针，获取最大元素并将数组中的元素全部转化为字符串
          foreach ($array as $key=>$value) {
               if($max<$value)
                  $max = $value;
                $array[$key] = (string)$value;
          }
          // 获取需要循环的次数
          $radix = strlen((string) $max);
          // $contain = [0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null,7=>null, 8=>null,9=>null,];
          // 设置空桶
          $contain = [null,null,null,null,null,null,null,null,null,null,];
          // 进行第一次桶操作，并将原一维数组（已经转化为字符串）转换二维数组
          $queue = $contain;
                foreach ($array as $v) {
                     $queue[$v[strlen($v)-1]][] = $v;
                }
          // 最大数小于10，跳过此步骤
          if($radix<2) goto end;
          // 否则进行radix-1（radix>1）次桶操作
          
          for ($i = 1; $i < $radix; ++$i) {
              $tmp = $contain; 
              foreach ($queue as $item) {
                  if($item){
                      foreach ($item as $v) {
                         $index = strlen($v)-$i-1;
                            if($index<0)
                                $outPut[] = (int)$v;
                              else
                               $tmp[$v[$index]][] = $v;
                     }
                  }
              }
              $queue = $tmp;
          }
   end:
           $array = [];
           foreach ($queue as $item) {
               if($item){
                   foreach ($item as $v) {
                   $array[] = (int)$v;
                   }
               }  
           }
           return array_merge($outPut,$array);
      } 
     /**
      * [countSort sorting by counting]
      * @param  array  $array [array to sort]
      * @return [array]        [sorted array]
      */
      public static function  countSort(array $array){
         $length = count($array); 
         $max = current($array); //初始化最大最小值
         $min = $max;
         $count = []; //计数数组
         $outPut = []; //输出数组
         // 初始化输出数组
         for ($i=0; $i < $length; ++$i){ 
             $outPut[$i]=null;
         }
        //获取最大最小值
          foreach ($array as $value) {
             if($value>$max)
                $max= $value;
              elseif($value<$min)
                $min = $value;
          }
      //初始化计数数组
        for ($i = $min; $i<=$max; ++$i) { 
               $count[$i] = 0;
        }
        //统计等于$array[$i]的element个数
         foreach ($array as $item) {
             ++$count[$item];
         }
       // 统计小于等于$array[$i]的element个数
         for ($i=$min; $i<$max; ++$i) { 
              $count[$i+1]+=$count[$i];
         }
       //将element放在它所在的最后一个位置上
         foreach ($count as $k => $v) {
             $outPut[$v-1] = $k;
         }
      //填补重复element的位置
         for ($i=$length-1; $i >=0 ; --$i) { 
            if(null==$outPut[$i])
                $outPut[$i] = $outPut[$i+1];
         }
         return $outPut;
      }


}
 
 function createArray($n){
    $array = [];
    for ($i=0; $i < $n; ++$i) { 
        $array[] = mt_rand(0,$n);
    }
    shuffle($array);
    return $array;
 }
 
//以下是测试

// $array = createArray(100000);
// $array1 = $array;
// $array2 = $array;
// $array3 = $array;
// $array4 = $array;
// $array5 = $array;
// $array6 = $array;
// $array7 = $array;
// $s = microtime(true);
// Sort::cocktailSort2($array);
// echo 'cocktailSort2: ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::cocktailSort3($array);
// echo 'cocktailSort3:    ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::cocktailSort($array);
// echo 'cocktailSort:   ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::bubbleSort($array);
// echo 'bubbleSort:    ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::insertionSort($array);
// echo 'insertionSort: ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::selectionSort($array);
// echo 'selectionSort: ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::radixSort($array);
// echo 'radixSort:      ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::radixSort2($array);
// echo 'radixSort2:      ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::countSort($array);
// echo 'countSort:      ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::shellSort($array1);
// echo 'shellSort:      ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::mergeSort($array2);
// echo 'mergeSort:    ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::mergeSortBU($array3);
// echo 'mergeSortBU: ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::mergeSortBUBU($array4);
// echo 'mergeSortBUBU:',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::quickSortOne($array5);
// echo 'quickSortOne:  ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::quickSortTwo($array6);
// echo 'quickSortTwo:  ',microtime(true)-$s,'<br>';
// $s = microtime(true);
// Sort::quickSortThree($array7);
// echo 'quickSortThree:',microtime(true)-$s,'<br>';
// $n = 5000000;
// $array = createArray($n);
// echo memory_get_usage(),'<br>';
// $s = microtime(true);
// $array = Sort::radixSort($array);
// $array = Sort::radixSort2($array);
// $array=Sort::countSort($array);
// Sort::quickSortOne($array);
// echo 'countSort2:      ',microtime(true)-$s,'<br>';
// echo memory_get_peak_usage(),'<br>';
// echo Sort::checkSort($array),'<br>';













