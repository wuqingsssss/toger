<?php
// Version

define('VERSION', '3.0.1');

include_once('../../init.php');

// Router

$request->get['route'] = 'payment/alipay/batchtransnotify';

if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	echo "CaiJun API ".VERSION;
	exit();
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>