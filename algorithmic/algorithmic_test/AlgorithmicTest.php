<?php
/**
* 
*/
namespace algorithmic\algorithmic_test;
class AlgorithmicTest {
    private $hanoi;
    private $robotSteps;
    public function __construct(){
        $this->hanoi = [];
        $this->hanoi['count'] = 0;
        $this->robotSteps = 0;
    }
    // 递归方式爬楼梯
    public  function _ascendStairs(int $n):int{
           switch ($n>1) {
               case 0:
                   return 1;
               case 1:
                  return self::_ascendStairs($n-1)+self::_ascendStairs($n-2);          
           }
    }

    // 迭代爬楼梯
    public  function _ascendStairsByIterator(float $n):float{
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
    public  function _maxPalindromicStr(string $str){
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
    public  function _maxPalindromicStrTwo(string $str){
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
           echo $start,'  ',$end,'<br>';
           return substr($str, $start,$end-$start+1);
    }

    // n个人有两个人同一天（365）生日的概率
    public  function _sameBorthday(float $n):float{
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
    public  function _arrangeLocationToValue(array $arr){
          $length = count($arr);
          if($length<2)return;
          $n = self::_factorial($length);
          $k=0;
          for ($k = 0; $k < $n; ++$k) { 
              // $arrange[] = $arr;
             for ($i = $length-2; $i >0 ; --$i){ 
                   if($arr[$i]<$arr[$i+1])break;
             }
             for ($j = $length-1; $j >$i ; --$j) { 
                   if($arr[$j]>$arr[$i])break;
             }
             self::_swap($arr[$i],$arr[$j]);
             self::_reverse($arr,$i+1,$length-1);  
          }
    }

     // 全排列 while循环  数组$arr必须是从小到大排序的int索引数组
    // 排几个数时比for循环的性能好一丢丢
    // $arrange = ['location'=>value];
    // 返回$arrange;
        public  function _arrangeLocationToValueW(array $arr):array{
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
             self::_swap($arr[$i],$arr[$j]);
             self::_reverse($arr,$i+1,$length-1);  
          }
          return $arrange;
    }

    // 全逆序
    public  function _opposite(array $arr):array{
        $end = count($arr)-1;
        for ($i = 0,$j = $end; $i < $j; ++$i,--$j) { 
            $tmp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $tmp;
        }
        return $arr;
    }

    // 子数组逆序
    public  function _reverse(array &$array,int $start,int $end){
            for ($i=$start,$j = $end; $i < $j ; ++$i,--$j) { 
                  self::_swap($array[$i],$array[$j]);
            }
    }

   // 交换
    public  function _swap(&$a,&$b){
        $tmp = $a;
        $a = $b;
        $b = $tmp;
    }
// 阶乘
    public  function _factorial(int $n):int{
        for($i = $n,$sum = 1;$n>1;--$n){
              $sum*=$n;
        }
        return $sum;
    }

   
     // 全排列
    // $arrange = ['value'=>location];
    public  function _arrangeValueToLocation(array $arr):array{
        $arr = self::_arrangeLocationToValueW($arr);
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
    public  function _someQuestionOfProbability(array $arr):array{
        $arrange = self::_arrangeValueToLocation($arr);
        $oldTotal = count($arrange);
        foreach ($arrange as $key => $value) {
             if($value[4]==2 || abs($value[5]-$value[3])==1)
                unset($arrange[$key]);
        }
        $newTotal = count($arrange);
        $probability = $newTotal/$oldTotal;
        $arrange['total'] = $newTotal;
        $arrange['probability'] = $probability;
        return $arrange;
    }

    //创建回文数
public  function createStr(int $len,int $min=0,int $max=9):string{
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

    // 实现标准库strpos()
    public  function strpos(string $haystack,$needle,int $offset = 0):int{
          $needle = (string)$needle;
          $lengthOfNeedle = strlen($needle);
          $lengthOfHaystack = strlen($haystack);
          if($lengthOfNeedle>$lengthOfHaystack)
             return -1;
         $offset = ($offset<0)? $lengthOfHaystack+$offset : $offset;
         for ($i = $offset,$j = 0; $i < $lengthOfHaystack; ++$i) { 
               if($needle[$j] === $haystack[$i]){
                  ++$j;
                  if($j>=$lengthOfNeedle)
                     return $i-$j+1;
               }
              else{
                  $j = 0;
                  if($needle[$j] === $haystack[$i]) ++$j;
              }  
         }
         return -1;
    }
   //实现标准库strstr()
    public  function strstr(string $haystack,$needle,bool$before_needle = false){
          $point = self::strpos($haystack,$needle);
          if($point<0) return false; 
          $string = '';
          if($before_needle){
              for($i = 0;$i<$point;++$i){
                  $string.=$haystack[$i];
              }
          }else{
             $len = strlen($haystack);
              for ($i = $point; $i < $len; ++$i) { 
                 $string.=$haystack[$i];
              }
          } 
          return $string;
    }
    /**
     * [infiniteR infinite classify]
     * @param  array       $array [the data that need to classify]
     * @param  int|integer $pid   [the parentid]
     * @param  array       &$res  [the data after each classify]
     * @return [array]             [the data]
     */
    public function infiniteR(array $array,int $pid=0,&$res=[]):array{
    while($row = current($array)){
      if($row['pid']==$pid){
          $row['level']=isset($res[$pid])?$res[$pid]['level']+1:0;
          $res[$row['id']] = $row;
          infiniteR($array,$row['id'],$res);
      }
      next($array);
   }
    return $res;
   }
    
    /**
     * [findWordByPreg quick search the certain word in the file]
     * @param  [string] $find [the word to search]
     * @param  [string] $path [the path of file]
     * @return [array]       [the result of the searching]
     * 返回一个文件里某个单词的信息，包括总数，位置
     */
     public  function findWordByPreg($find,$path){
       $file = fopen($path, 'r');
       $line = 1;
       $count = 0;
       $res = []; //结果集
       $p1 = '/\b'.$find.'\b/i'; //不区分大小写 匹配一个一行string里的特定单词
       $p2 = '/^'.$find.'$/i'; //不区分大小写匹配数组里的特定单词
       $char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";
       $p3 =  array(  //去除所有中英文标点符号
      "/[[:punct:]]/i", //英文标点符号
      '/['.$char.']/u', //中文标点符号
      // '/[ ]{2,}/' //filtering the space
      );
       while (!feof($file)) {
            if(($row = fgets($file))!==false){
               if(preg_match($p1,$row,$match)){
                  $row = preg_replace($p3,' ',$row);
                     $row = explode(' ', $row);
                     $row = array_values(array_filter($row,function($arg){
                            if($arg==='')
                              return false;
                             return true;
                     })); 
                     $row = array_map(function($arg){
                         return trim($arg);
                     },$row);
                     foreach ($row as $key => $value) {
                         if(preg_match($p2,$value,$match)){
                          $res[$line][$key+1] = true;
                          ++$count;
                         }
                     }
               }
            }
            ++$line;
       }
       fclose($file);
       $res['count'] = $count;
       return $res;
     }
    
    /**
     * [getWinnerByEliminating eliminate the num m member each turn,and the last is the winner]
     * @param  int    $m [the member to eliminate]
     * @param  int    $n [the numbers of the members]
     * @return [int]    [the number of the winner ,but the 0 means no winner]
     */
     public function getWinnerByEliminating(int $m,int$n):int{
        if($m>$n || $n<1 || $m<1)
          return 0;
        $arr=[];
        $remain = $n;
        $start = 0;
        for ($i = 1; $i <= $n; ++$i) { 
           $arr[$i] = $i;
        }
        while (null!=($index=current($arr))) {
            ++$start;
            if($start==$m){
              unset($arr[$index]);
              --$remain;
              if($remain==1){
                reset($arr);
                return current($arr);
              }  
              $start = 0;
              prev($arr);
            }
            next($arr);
            if(null==current($arr))
                reset($arr);    
        }
     }
   
    /**
     * [hanoi the game of the hanoi]
     * @param  int    $n [how many disks to move]
     * @param  string $x [the start pillar]
     * @param  string $y [the cache pillar]
     * @param  string $z [the aiming pillar ]
     * @return [array]    [the detail  of the steps]
     */
    public function hanoi(int $n,string $x ='x',string $y='y',string$z='z'):array{
         if($n==1){
              $this->moveHanoi($x,$n,$z);
         }else{
              $this->hanoi($n-1,$x,$z,$y);
              $this->moveHanoi($x,$n,$z);
              $this->hanoi($n-1,$y,$x,$z);
         }
         return $this->hanoi;
     }
     
    /**
     * [moveHanoi the step of the game hanoi]
     * @param  string $x [the pillar where the disk move from]
     * @param  int    $n [the number of the disk]
     * @param  string $z [the pillar where the disk move to]
     * @return [void]    []
     */
     private function moveHanoi(string$x,int $n,string$z):void{
         // echo 'movedisk : ',$n,' from  ',$x,'  to  ',$z,' <br> ';
         ++$this->hanoi['count'];
         $this->hanoi['steps'][$this->hanoi['count']] = [
           'disk'=>$n,
           'from'=>$x,
           'to'=>$z,
         ];
     }
    /**
     * [robotWalk if the robot go from the topleft to the bottom of an x*y 
     *  artrix ,then ,how many way he can walk,this method is much better than the robotWalkTwo which use the graph]
     * @param  int    $x [the grids of the level]
     * @param  int    $y [the grids of the vertical]
     * @return [int]    [the result of how many ways]
     */
     public function robotWalk(int $x,int $y):int{
         if($x<=1 || $y<=1)
            return 1;
         if($x==2 || $y ==2)
           return max($x,$y);
         return $this->robotWalk($x-1,$y)+$this->robotWalk($x,$y-1);
     }
      /* [robotWalk if the robot go from the topleft to the bottom of an x*y 
     *  artrix ,then ,how many way he can walk,this method is  better than the robotWalk which use another more condition]
     * @param  int    $x [the grids of the level]
     * @param  int    $y [the grids of the vertical]
     * @return [int]    [the result of how many ways]
     */
      public function robotWalk2(int $x,int $y):int{
         if($x<=1 || $y<=1)
            return 1;
         return $this->robotWalk($x-1,$y)+$this->robotWalk($x,$y-1);
     }
    
    
    /**
     * [robotWalkTwo if the robot go from the topleft to the bottom of an x*y artrix ,then ,how many ways he can walk by use the graph method]
     * @param  int    $x [the grids of the level]
     * @param  int    $y [the grids of the vertical]
     * @return [int]    [the result of how many ways]
     */
     public function robotWalkTwo(int $x,int $y):int{
        if($x<1 || $y<1) return 0;
        $grids = $this->createGrids($x,$y);
        $this->robotWalkAction($grids,0,0);
        return $this->robotSteps;
     }
     
     /**
      * [robotWalkAction the each action of the robot for robotWalkTwo] 
      * @param  [array] $array [the walking graph]
      * @param  [int] $x     [the x coordinate of start point]
      * @param  [int] $y     [the y coordinate of start point]
      * @return [void]        [return noting but the $this->robotstep may incrementing]
      */
     private function robotWalkAction(array$array,int$x,int$y):void{
        if($array[$x][$y]['right']==null && $array[$x][$y]['down'] == null){
           ++$this->robotSteps;
        }else{
           if(null != $array[$x][$y]['right']){
                 list('x'=>$a,'y'=>$b)= $array[$x][$y]['right'];
                $this->robotWalkAction($array,$a,$b);
           }
           if(null != $array[$x][$y]['down']){
                list('x'=>$a,'y'=>$b)= $array[$x][$y]['down'];
                $this->robotWalkAction($array,$a,$b);
           }
        }
     }
    
    /**
     * [createGrids create an x*y graph for the robotWalkTwo]
     * @param  int    $x [the grids of the x level]
     * @param  int    $y [the grids of the vertical level]
     * @return [array]    [return this x*y graph]
     */
     private function createGrids(int $x,int $y):array{
        $grids = [];
        for ($i=0; $i < $x; ++$i) { 
            for ($j = 0;$j<$y;++$j){
                if($i+1<$x){
                      $grids[$i][$j]['right'] = ['x'=>$i+1,'y'=>$j];
                      if($j+1<$y)
                          $grids[$i][$j]['down'] = ['x'=>$i,'y'=>$j+1];
                      else
                           $grids[$i][$j]['down'] = null;
                }else{
                     $grids[$i][$j]['right'] = null;
                      if($j+1<$y)
                          $grids[$i][$j]['down'] = ['x'=>$i,'y'=>$j+1];
                      else
                           $grids[$i][$j]['down'] = null;
                }
            }
        }
        return $grids;
     }
     /**
      * [combination $many = count($allElements) ,how ways of combination if select $selectMany elements of $allElements]
      * @param  array  $allElements [array must be an index array from $array[0] to $array[$many-1],contains all elements ]
      * @param  [int] $selectMany          [select $selectMany elements to combinate]
      * @return [array]              [the result of combination ,include each ways detail and numbers of way]
      * this way is more effect than the combinationsByTransform($allElements,int $selectMany)
      */
     public  function combinations(array $allElements,int $selectMany){
          $length = count($allElements);
          if($selectMany<1) return false;
          if($selectMany==1) return $allElements;
          $step = $selectMany-1; //区间内最大两个数的最小差
          $maxOfStart = $length-$step;
          $combinations = []; //组合数数组
          //构建仅仅包含两个element的初始组合数组
          for ($i=0; $i < $maxOfStart ; ++$i) { 
              for ($j=$step+$i; $j < $length; ++$j) { 
                  $combinations[] = [$i,$j];
              }
          }
          // get the all combinations;
          for ($i=$step-1,$endIndex = 1; $i>0 ; --$i,++$endIndex) { 
              $combination = [];
              foreach ($combinations as $item) {
                  // sort($item);
                  $max = $item[$endIndex];
                  $secMax = $item[$endIndex-1];
                  for ($j=$secMax+1; $j < $max; ++$j) { 
                      if($max-$j>=$i){
                         $temp = $item;
                         $temp[] = $j;
                         $tmp = $temp[$endIndex];
                         $temp[$endIndex] = $temp[$endIndex+1];
                         $temp[$endIndex+1] = $tmp;
                         $combination[] = $temp;
                         // $combination[] = array_merge($item,[$j]);
                      }
                  }
              }
              $combinations = $combination;
          }
          // transform the value of each combination to the original elements
          foreach ($combinations as &$value) {
               foreach ($value as $k => $v) {
                    $value[$k] = $allElements[$v];
               }
          }
          return $combinations;
     }
          /**
           * [combinations2 optimize based on the combination()]
           * @param  array  $allElements [description]
           * @param  int    $selectMany  [description]
           * @return [type]              [description]
           */
          public  function combinations2(array $allElements,int $selectMany){
          $length = count($allElements);
          if($selectMany<1) return false;
          if($selectMany==1) return $allElements;
          $step = $selectMany-1; //区间内最大两个数的最小差
          $maxOfStart = $step-1;
          $combinations = []; //组合数数组
          //构建仅仅包含两个element的初始组合数组
         for ($i=$length-1; $i > $maxOfStart ; --$i) { 
              for ($j=$i-$step; $j >=0; --$j) { 
                  $combinations[] = [$i,$j];
              }
          }
          // get the all combinations;
          for ($i=$step-1,$endIndex = 1; $i>0 ; --$i,++$endIndex) { 
              $combination = [];
              foreach ($combinations as $item) {
                  $tmp = $item[$endIndex];
                  $item[$endIndex] = $item[$endIndex-1];
                  $item[$endIndex-1] = $tmp;
                  $max = $item[$endIndex];
                  $secMax = $item[$endIndex-1];
                  for ($j=$secMax+1; $j < $max; ++$j) { 
                      if($max-$j>=$i){
                          $combination[] = array_merge($item,[$j]);
                      }
                  }
              }
              $combinations = $combination;
          }
          // transform the value of each combination to the original elements
          foreach ($combinations as &$value) {
               foreach ($value as $k => $v) {
                    $value[$k] = $allElements[$v];
               }
          }
          return $combinations;
     }
     
     /**
      * [combinationsByTransform]
      *for example: an array like arr=[1,2,3,4,5] and select 2 element then howmany combinations there is , to solve this , creating an index array arr1=[1,1,0,0,0](tips:1 for selected ,0 for notseleted,and the first combination must be like arr2 that all 1 in front,then use arr1 to find the arr2,doing like following way:from left to right ,find the first '10',make its index as i,make it '01',and make all '1' before i to the head,just like arr1,so 
      *arr2 = [1,0,1,0,0], by this way until find the arrn = [0,0,0,1,1];create an array to store all combitions
      *for more understand ,like select 3 in 5
      *1 1 1 0 0 //1,2,3
       *    1 1 0 1 0 //1,2,4 
       *    1 0 1 1 0 //1,3,4 
       *    0 1 1 1 0 //2,3,4 
        *   1 1 0 0 1 //1,2,5
        *   1 0 1 0 1 //1,3,5
      *     0 1 1 0 1 //2,3,5 
       *    1 0 0 1 1 //1,4,5
        *   0 1 0 1 1 //2,4,5
      *     0 0 1 1 1 //3,4,5
      * @param  array  $allElements [description]
      * @param  int    $selectMany  [description]
      * @return [type]              [description]
      */
     public  function combinationsByTransform(array$allElements,int$selectMany){
            $count = count($allElements);
            $combination = [];  //each combination
            for ($i=0; $i < $count; ++$i) { 
                   $combination[] = 0;
            }
            //init the first combination
            for ($i=0; $i < $selectMany ; ++$i) { 
                   $combination[$i] = 1;
            }
            $transform = [$combination]; //store combination
            $end = $count-1;
            for ($i=0,$one=-1;$i<$end; ++$i) {
                if($combination[$i]) ++$one;
                if($combination[$i]==1 && $combination[$i+1]==0){
                     $combination[$i] =0;
                     $combination[$i+1] = 1;
                     for ($k=0; $k < $one; ++$k) { 
                         $combination[$k] = 1;
                     }
                     for ($k=$one; $k < $i; ++$k) { 
                         $combination[$k] = 0;
                     }
                     $transform[] = $combination;
                     $i=-1; 
                     $one = -1;
                }
            }
            $combinations = [];  //transform the index to original 
            foreach ($transform as $item) {
                $child = [];
                foreach ($item as $k=>$v) {
                    if($v)
                      $child[]=$k;
                }
                $combinations[] = $child;
            }
         return $combinations;
     }
     
     // 该方法可正确计算的条件是：$sum不存在重复值
     // 时间：N 空间 N
     public function divGroup(array$array){
       $length = count($array);
       for ($i=0,$sum = 0,$sums = []; $i < $length; ++$i) { 
           $sum += $array[$i];
           $sums[$sum] = $i;
       }
       if(isset($sums[$sum/4]) && isset($sums[$sum/4*2]) && isset($sums[$sum/4*3]))
           echo $sums[$sum/4],' ',$sums[$sum/4*2],' ',$sums[$sum/4*3];
       else
           echo 'it is not the available array to div into 4 equal';
     }
     
     /**
      * [div2pairs divide the array into two group ]
      * @param  int    $avg        [dynamic average]
      * @param  int    $ldeliIndex [the left delimeter]
      * @param  array  &$array     [the array to divide by lsum compared rsum]
      * @param  int    $rdeliIndex [the right delimeter]
      * @return [mixed]             [if true return the middle delimeter,or false]
      */
     public function div2pairs(int $avg,int $ldeliIndex,array&$array,int $rdeliIndex){
          $lstart = $ldeliIndex+1;  //the index where begin to calculate the sum from left;
          $rstart = $rdeliIndex-1;  //the index where begin to calculate the sum from right;
          $lsum = $array[$lstart]; //init the $lsum
          $rsum = $array[$rstart]; //init the $rsum
          $gap = $lsum-$rsum;
          while($lstart<$rstart){   //although within two 'while' but its time complexity is truely o(n); 
             while($lsum>$rsum && $lstart<$rstart-2){ //here the number 2 means that there must be 
                    $rsum+=$array[--$rstart];                 //a delimeter index can't not put into calculate
                    if($lsum-$rsum>$gap && $lstart<$rstart-2){ //avoid the gap being bigger;
                         $lsum+=$array[++$lstart]; 
                    }                                   
             }
             while ($lsum<$rsum && $lstart<$rstart-2) {
                    $lsum+=$array[++$lstart]; 
                    if($lsum-$rsum<$gap && $lstart<$rstart-2){
                         $rsum+=$array[--$rstart];
                    }
                    $gap = $lsum-$rsum;
             }
             if($rsum==$lsum){
                 if($rstart ==$lstart+2){
                     if($rsum ==$avg) //true return the dividing delimeter 
                      return $lstart+($rstart-$lstart)/2;
                     else
                      return false;   
                 }else{
                    ++$lstart; //you can also change these two lines like this:
                     $lsum+=$array[$lstart];  //--$rstart;$rsum+=$array[$rstart]
                 }
             }else{
                    if($lsum<$rsum){ //here and 'while' is used to make the $lsum and $rsum more close;
                      ++$lstart;
                     $lsum+=$array[$lstart];
                    }else{
                      --$rstart;
                      $rsum +=$array[$rstart];
                    } 
              }
           $gap = $lsum-$rsum;  //upgrade the gap between $lsum and $rsum;
          }
          return false;
     }
      /**
       * [div4pairs divide the array into 4 pairs with delimeter]
       * @param  array  $array [array to divide]
       * @return [mixed]        [if true then return an array cluding all delimeter]
       */
     public function div4pairs(array$array){
         $length = count($array); //get the lengt of the array;
         if($length<5) return false; //because divide into 4 equals with delimeter
         
         $lsumArray = []; //array to store the lsum which calculate from 0;
         $lsumArray[0][] = -1; //$lsumArray[$lsum] = index, if index<0 ,default -1 
         $end = $length-2; //the 4th pair must be >=0,default $array[$length] = 0 as well as the 
                                     //$array[-1] = 0,thought they do not exist
         for ($i = 0,$lsum = 0; $i < $end; ++$i) {  
             $lsum+=$array[$i];      
             $lsumArray[$lsum][] = $i;  //store the index for each lsum;
         }
         for($j = $length-1,$rsum = 0;$j>3;--$j){ //from right to left ,calculate the rsum;
            if(isset($lsumArray[$rsum])){         //
               while(false!==current($lsumArray[$rsum])){
                  $ldeliIndex = current($lsumArray[$rsum])+1;
                //  if($ldeliIndex>$j-1) break;   //3 elements between ldelimeter and rdelimeter($j);
                  if(($mid=self::div2pairs($rsum,$ldeliIndex,$array,$j))) //try to divide array into 4 equals
                    return [$ldeliIndex,$mid,$j]; //true then return these three delimeters index;
                 next($lsumArray[$rsum]); 
               }
            }
            $rsum+=$array[$j]; 
         }
        return false;
     }
     /**
      * [div4pairsF another simple method to divide array into 4 equals with excluding delimeter index]
      * @param  array  $array [array to divide]
      * @return [mixed]        [if true return delimeters index,or false]
      */
     public function div4pairsF(array$array){
          $length = count($array);
          $ilength = $length-4;
          $jlength = $length-2;
          for ($i=0,$isum = 0;$i < $ilength; ++$i) { 
              for ($j=$i+1,$jsum = 0; $j < $jlength; ++$j) { 
                      $jsum+=$array[$j];
                      if($jsum==$isum){
                        ++$j;
                          for($k = $j+1,$ksum = 0;$k<$length;++$k){
                              $ksum+=$array[$k];
                              if($ksum==$isum){
                                 ++$k;
                                  if($k+1<$length){
                                     for ($n=$k+1,$nsum=0; $n < $length; ++$n) { 
                                         $nsum+=$array[$n];
                                     }
                                     if($nsum==$isum) 
                                       return [$i,$j,$k];
                                  }
                              }
                          }
                      }
              }
              $isum+=$array[$i];
          }
          return false;
     }

         public function createTestingData(int $m){
             for ($i=0,$data=[]; $i < $m; ++$i) { 
                 $in = mt_rand(-$m,$m);
                    $data[] = $in;
            }
            return $data; 
         }
      

 }  

// $n = 1000000;//1000,10000,100000,1000000
// $all = AlgorithmicTest::createTestingData($n);
// $all = [1,2,5,4,-1];  // false
$all = [1,-1,2,-1,4,-3,4,2,1];
echo memory_get_usage(),'<br>';
$s = microtime(true);
$res = AlgorithmicTest::div4pairs($all); 
// $res = AlgorithmicTest::div4pairsF($all);
echo 'times:      ',microtime(true)-$s,'<br>';
echo memory_get_peak_usage(),'<br>';

print_r($res);









