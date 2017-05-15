<?php
/**
* 
*/
namespace algorithmic\algorithmic_test;
class AT 
{
    
    // 递归方式爬楼梯
    public function _ascendStairs(int $n):int{
           switch ($n>1) {
               case 0:
                   return 1;
               case 1:
                  return $this->_ascendStairs($n-1)+$this->_ascendStairs($n-2);          
           }
    }

    // 迭代爬楼梯
    public function _ascendStairsByIterator(float $n):float{
          $first = 1;
          $secend = 2;
          if($n<=2)
            return $n;
          else{
            for ($i=3; $i <=$n ; $i++) { 
                 $three = $first + $secend;
                 $first = $secend;
                 $secend = $three;
            }
            return $three;
          }
    }

    // 最大回文子窜
    public function _maxPalindromicStr(string $str){
             $length = strlen($str);
             $arr = [];
             $start = 0 ;
             $end = 0;
             $max = $end - $start;
             for ($i = 0; $i < $length ; ++$i) { 
                  if(isset($arr[$str[$i]])){
                       for ($j = $arr[$str[$i]],$k = $i,$bool = true; $j < $k; --$k,++$j) { 
                             if($str[$j] != $str[$k]){
                                 $bool = false;
                                 break;
                             }
                       }
                       if($bool && $i-$arr[$str[$i]] > $max){
                        $start = $arr[$str[$i]];
                        $end = $i;
                        $max = $end - $start;
                       } 
                        $arr[$str[$i]] = $i; 
                  } else
                       $arr[$str[$i]] = $i;
             }
           echo $start,'  ',$end,'<br>';
           return substr($str, $start,$end-$start+1);
    }

        // 最大回文子理论上这个方法效率高，实际上不如方法一
    public function _maxPalindromicStrTwo(string $str){
             $length = strlen($str);
             $arr = [];
             $start = 0 ;
             $end = 0;
             $max = $end - $start;
             for ($i = 0; $i < $length ; ++$i) { 
                  if(isset($arr[$str[$i]])){
                      if($i - $end == 1 && $str[$i] == $str[$start-1]){
                                --$start;
                                ++$end;
                                $max = $end - $start;
                      }else{
                               for ($j = $arr[$str[$i]],$k = $i,$bool = true; $j < $k; --$k,++$j) { 
                                     if($str[$j] != $str[$k]){
                                         $bool = false;
                                         break;
                                     }
                               }
                               if($bool && $i-$arr[$str[$i]] > $max){
                                $start = $arr[$str[$i]];
                                $end = $i;
                                $max = $end - $start;
                               } 
                                $arr[$str[$i]] = $i; 
                    }
                  } else
                       $arr[$str[$i]] = $i;
             }
           return substr($str, $start,$end-$start+1);
    }

    // n个人有两个人同一天（365）生日的概率
    public function _sameBorthday(float $n):float{
        if($n<1)
            return 0;
        if($n>365)
            return 1;
       $allDifferent = 1;
       $all = pow(365, $n);
       for ($i=0; $i < $n; ++$i) { 
             $allDifferent *= (365-$i);
       }
       return 1-$allDifferent/$all;
    }

  
    // 全排列 for循环
    // $arrange = ['location'=>value];
    public function _arrangeLocationToValue(array $arr):array{
          $length = count($arr);
          if($length<2)return $arr;
          $n = $this->_factorial($length);
          $k=0;
          for ($k = 0,$arrange = []; $k < $n; ++$k) { 
              $arrange[] = $arr;
             for ($i = $length-2; $i >0 ; --$i){ 
                   if($arr[$i]<$arr[$i+1])break;
             }
             for ($j = $length-1; $j >$i ; --$j) { 
                   if($arr[$j]>$arr[$i])break;
             }
             $this->_swap($arr[$i],$arr[$j]);
             $this->_reverse($arr,$i+1,$length-1);  
          }
          return $arrange;
    }

     // 全排列 while循环  数组$arr必须是从小到大排序的int索引数组
    // 比for循环的性能好一丢丢
    // $arrange = ['location'=>value];
        public function _arrangeLocationToValueW(array $arr):array{
          $length = count($arr);
          if($length<2)return $arr;
          $arrange = [];
          while (true) {
             $arrange[] = $arr;
             for ($i = $length-2; $i >0 ; --$i){ 
                   if($arr[$i]<$arr[$i+1])break;
             }
             for ($j = $length-1; $j >$i ; --$j) { 
                   if($arr[$j]>$arr[$i])break;
             }
             if($i==0 && $j==0)break;
             $this->_swap($arr[$i],$arr[$j]);
             $this->_reverse($arr,$i+1,$length-1);  
          }
          return $arrange;
    }

    // 全逆序
    public function _opposite(array $arr):array{
        $end = count($arr)-1;
        for ($i = 0,$j = $end; $i < $j; ++$i,--$j) { 
            $tmp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $tmp;
        }
        return $arr;
    }

    // 子数组逆序
    public function _reverse(array &$array,int $start,int $end){
            for ($i=$start,$j = $end; $i < $j ; ++$i,--$j) { 
                  $this->_swap($array[$i],$array[$j]);
            }
    }

   // 交换
    public function _swap(&$a,&$b){
        $a^=$b;
        $b^=$a;
        $a^=$b;
    }
// 阶乘
    public function _factorial(int $n):int{
        for($i = $n,$sum = 1;$n>1;--$n){
              $sum*=$n;
        }
        return $sum;
    }

   
     // 全排列
    // $arrange = ['value'=>location];
    public function _arrangeValueToLocation(array $arr):array{
        $arr = $this->_arrangeLocationToValue($arr);
        $arrange = [];
        $arrangeChild = []; 
        foreach ($arr as $value) {
               foreach ($value as $k => $v) {
                     $arrangeChild[$v] = $k;
               }
               $arrange[] = $arrangeChild;
        }
        return $arrange;
    }

     // 某一道概率问题：1,2,3,4,5;4 不能在中间，5和3不能在一起
    public function _someQuestionOfProbability(array $arr):array{
        $arrange = $this->_arrangeValueToLocation($arr);
        $oldTotal = count($arrange);
        foreach ($arrange as $key => $value) {
             if(abs($value[5]-$value[3])==1)
                unset($arrange[$key]);
        }
        $newTotal = count($arrange);
        $probability = $newTotal/$oldTotal;
        $arrange['total'] = $newTotal;
        $arrange['probability'] = $probability;
        return $arrange;
    }

    //创建回文数
public function createStr(int $len,int $min=0,int $max=9):string{
    $str = '';
    for ($i=0, $k = $min,$turn = true; $i < $len; ++$i) { 
        $str.= $k;
        if($turn){
            ++$k;
            if($k>$max){
                $turn = false;
                $k-=2;
            }
        }else{
            --$k;
            if($k<$min){
                $turn = true;
                $k+=2;
            }  
        }
    }
    return $str;
}

    // 实现标准库strstr()
    public function _strstr(string $find,string $range){
          $lengthOfFind = strlen($find);
          $lengthOfRange = strlen($range);
          if($lengthOfFind>$lengthOfRange)
             return -1;
         for ($i = 0,$j = 0; $i < $lengthOfRange; ++$i) { 
               if($find[$j] === $range[$i]){
                  ++$j;
                  if($j>=$lengthOfFind)
                     return $i-$j+1;
               }
              else
                  $j = 0;
         }
    }
 }  
