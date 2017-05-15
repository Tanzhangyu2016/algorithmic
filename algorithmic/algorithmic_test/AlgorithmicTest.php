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
    public static function _ascendStairs(int $n):int{
           switch ($n>1) {
               case 0:
                   return 1;
               case 1:
                  return self::_ascendStairs($n-1)+self::_ascendStairs($n-2);          
           }
    }

    // 迭代爬楼梯
    public static function _ascendStairsByIterator(float $n):float{
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
    public static function _maxPalindromicStr(string $str){
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
    public static function _maxPalindromicStrTwo(string $str){
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
    public static function _sameBorthday(float $n):float{
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
    public static function _arrangeLocationToValue(array $arr){
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
        public static function _arrangeLocationToValueW(array $arr):array{
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
    public static function _opposite(array $arr):array{
        $end = count($arr)-1;
        for ($i = 0,$j = $end; $i < $j; ++$i,--$j) { 
            $tmp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $tmp;
        }
        return $arr;
    }

    // 子数组逆序
    public static function _reverse(array &$array,int $start,int $end){
            for ($i=$start,$j = $end; $i < $j ; ++$i,--$j) { 
                  self::_swap($array[$i],$array[$j]);
            }
    }

   // 交换
    public static function _swap(&$a,&$b){
        $tmp = $a;
        $a = $b;
        $b = $tmp;
    }
// 阶乘
    public static function _factorial(int $n):int{
        for($i = $n,$sum = 1;$n>1;--$n){
              $sum*=$n;
        }
        return $sum;
    }

   
     // 全排列
    // $arrange = ['value'=>location];
    public static function _arrangeValueToLocation(array $arr):array{
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
    public static function _someQuestionOfProbability(array $arr):array{
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
public static function createStr(int $len,int $min=0,int $max=9):string{
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
    public static function strpos(string $haystack,$needle,int $offset = 0):int{
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

    public static function strstr(string $haystack,$needle,bool$before_needle = false){
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
     */
     public static function findWordByPreg($find,$path){
       $file = fopen($path, 'r');
       $line = 1;
       $count = 0;
       $res = [];
       $p = '/\b'.$find.'\b/'.'i';
       $pt = '/^'.$find.'$/i';
       $char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";
       $pattern =  array(
      "/[[:punct:]]/i", //英文标点符号
      '/['.$char.']/u', //中文标点符号
      // '/[ ]{2,}/' //filtering the space
      );
       while (!feof($file)) {
            if(($row = fgets($file))!=="\n"){
               if(preg_match($p,$row,$match)){
                  $row = preg_replace($pattern,' ',$row);
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
                         if(preg_match($pt,$value,$match)){
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
 }  




