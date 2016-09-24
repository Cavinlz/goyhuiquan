<?php

ini_set('display_errors', 'On');
$mc = new Memcache();

$mc -> connect('127.0.0.1', 11211);
//$mc -> set('cavin1st','yeah',0,10);

$val = $mc -> get('cavin1st');
echo $val;
?>
