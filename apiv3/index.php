<?php
// Version

define('VERSION', '3.0.1');

require_once('init.php');

// Router
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