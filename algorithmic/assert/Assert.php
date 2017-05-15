<?php
/**
* 
*/
namespace algorithmic\assert;
class Assert 
{
    
            // 定义一个计时函数
            public static function  _timingAndMemoryUsage(&$array,$funcName) {
                    $time_start=microtime('get_as_float');
                    $memory_start=memory_get_usage();
                    call_user_func($funcName,$array);
                    $memory_end=memory_get_usage();
                    $time_end=microtime('get_as_float');
                    $pause_start=microtime('get_as_float');
                    $mem=memory_get_usage();
                    $pause_end=microtime('get_as_float');
                    $result=[];
                    $result['timing']=$time_end-$time_start-($pause_end-$pause_start)*2;
                    $result['memoryUsage']=$memory_end-$memory_start;
                    return $result;
            }

            // 定义一个创建随机数组的函数
            public static function _createMyArray($n){
                $array=[];
                for($i=0;$i<$n;$i++){
                    $array[]=mt_rand(0,10);
                }
                shuffle($array);
                return $array;
            }

           // 创建某一范围内的有序数组
            public static function _createArrayOrdered($start,$end,$counts){
                $arr = [];
                for ($i=0; $i < $counts; $i++) { 
                     $array[] = $start;
                     if ($start<$end) 
                        $start=mt_rand($start,$start+2); 
                        if ($start>$end)$start = $end;     
                }
                return $arr;
     }

            // 定义一个近乎有序的数组

            public static function _createNearOrderedArray($n,$ordering){
                $array=[];
                for ($i=0; $i <$n ; $i++) { 
                    $array[]=$i;
                }
                for ($j=0;$j<$ordering;$j++){
                    $index=rand(1,$n-1);
                    $tmp=$array[$index];
                    $array[$index]=$array[$index-1];
                    $array[$index-1]=$tmp;
                }
                return $array;
            }
            // 我的打印方式
            public static function _myprint($array){
                echo '//打印数组结果：<br>';
                $len=count($array);
                for ($i=0;$i<$len;$i++){
                    print_r($array[$i]);
                    echo ' ';
                }
            }

            private static function _edges($n,$node){
                   $header = "name: map\nnodes: 15\nsides: 1000\nedges:\n";
                   $string = '';
                    for ($i=0; $i < $n; $i++) { 
                          $from = mt_rand(0,$node);
                          $to = mt_rand(0,$node);
                          $distance = mt_rand(1,1000);
                          $cost = mt_rand(0,20);
                          if($from==$to){
                              $distance = 0;
                              $cost = 0;
                          }
                          $string .= $from.' '.$to.' '.$distance.' '.$cost."\n"; 
                    }
                    $string = $header.$string;
                    return $string;
}
            public static function _createGraph($path,$n,$node){
                    $file = fopen($path, 'w');
                    $string = self::_edges($n,$node);
                    fwrite($file, $string);
                    fclose($file);
            }

}