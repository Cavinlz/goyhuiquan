<?php
ini_set('display_errors', 'On');
//require_once("memcached-client.php");

$options = array(
        'servers' => array('127.0.0.1:11211'), //memcached 服务的地址、端口，可用多个数组元素表示多个 memcached 服务
        'debug' => false,  //是否打开 debug
        'compress_threshold' => 10240,  //超过多少字节的数据时进行压缩
        'persistant' => false  //是否使用持久连接
);
$mc = new Memcache();
//$mc = new memcached();
$mc ->connect('127.0.0.1',11211);
$key = 'cavin1st';
// 往 memcached 中写入对象
//$mc->add($key, 'yeah');
/*删除memcached中对象*/
//$mc->delete($key);
/*替换标识符key对象的内容*/
//$mc->replace($key,"这是新的内容");
$mc -> set($key, 'YEear');

$val = $mc -> get($key);

echo $val;
?>
