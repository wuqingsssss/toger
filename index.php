<?php

require_once('init.php');
if($_SERVER['HTTP_HOST']!=WEB_HOST){

	$session->data['errormsg']='请访问<a href="'.HTTP_SERVER.'">青年菜君</a>';

	$controller->dispatch(new Action('error/msg'), new Action('error/not_found'));
	$response->output();
}

// Rewrite
$rewrite= new Rewrite($registry);
$url->addRewrite($rewrite);


// Maintenance Mode
$controller->addPreAction(new Action('common/maintenance'));

// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));	

//$controller->addPreAction(new Action('common/reset'));

//if(time()<strtotime('2016-02-05')||time()>strtotime('2016-02-17')){

if($detect->is_andorid_app()){
	$document->addScript("assets/js/cordova/android/cordova.js");
}elseif($detect->is_ios_app()){
	$document->addScript("assets/js/cordova/ios/cordova.js");
}


if(!$detect->isMobile() &&!$detect->isTablet()){

	$unlimintroute=array('campaign/expiredtips');
	
	if(! in_array($request->get['route'], $unlimintroute))
		$request->get['route']='common/entrypage';
	
	$action = new Action($request->get['route']);
		
}elseif (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$query = $db->query("SELECT `route` FROM " . DB_PREFIX . "layout_route WHERE store_id = '".(int)$config->get('config_store_id')."' AND layout_id = '" . (int)$config->get('config_layout_id') . "'");
	if($query->row['route']) {
		$action = new Action($query->row['route']);
		$request->get['route']=$query->row['route'];
	}
	else{
     $action = new Action('common/home');
     $request->get['route']='common/home';
	}
}
//}else{
//	$action = new Action('common/entrypage');
//	$request->get['route']='common/entrypage';
//}

if(!isset($session->data['enter_route']))$session->data['enter_route']=$request->get['route'];

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));


// Output
$response->output();

?>