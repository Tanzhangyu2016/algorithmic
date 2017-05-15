<?php
namespace algorithmic\unionFind;
/**
* 类名：并查集基类
 作者：tanzhangyu
 日期：2017-04-01
*/
abstract  class unionFind 
{ 
    // abstract public function _createId(int $n);
    abstract public function _isConnected($p,$q);
    abstract public function _find($p);
    abstract public function _unionElements($p,$q);

}