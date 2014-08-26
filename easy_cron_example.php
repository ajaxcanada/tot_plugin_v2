<?php

require_once 'EasycronApi.php';

// Please replace below token with your token
$easyApi = new EasycronApi('07c43058963604741c0843ee45fe7472');

$return = $easyApi->call('timezone');

print_r($return);

$return = $easyApi->call('enable', array('id' => 2107));

print_r($return);