<?php
// Version

define('VERSION', '3.0.1');

# config
# first try to load develop conf file which ignore by git, then load default file (on server)
$config_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
$currentDir = getcwd();
if(file_exists($currentDir .'/' .$config_name)){
	require_once($config_name);
}else{
	require_once('config.php');
}

// Install 
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}
//print_r(DIR_SYSTEM);die();
// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/seo.php');
require_once(DIR_SYSTEM . 'library/geohash.php');
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);


if(defined('MEM_HOSTNAME')&&MEM_HOSTNAME&&class_exists('MCache')){
	$mem = new MCache(MEM_HOSTNAME, MEM_PORT,WEB_HOST);
	$registry->set('mem', $mem);
}

// Session
$session = new Session();

// log4php
$log4phpconfig_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
if(!file_exists(DIR_SYSTEM .'/log4php/' .$log4phpconfig_name))
	$log4phpconfig_name='config.php';
Logger::configure(DIR_SYSTEM . 'log4php/'.$log4phpconfig_name);


$log_admin   = Logger::getLogger('admin');
$log_order   = Logger::getLogger('order');
$log_payment = Logger::getLogger('payment');
$log_sys     = Logger::getRootLogger();
$log_db      = Logger::getLogger('database');
$registry->set('log_admin', $log_admin);
$registry->set('log_order', $log_order);
$registry->set('log_payment', $log_payment);
$registry->set('log_sys', $log_sys);
$registry->set('log_db', $log_db);
//Geohash lbs
$registry->set('geohash', new Geohash());
// Error Handler
if(defined('LOG_ERROR_HANDLER')&&LOG_ERROR_HANDLER)
set_error_handler('error_handler');

$oss=new OSS($registry);
$registry->set('oss', $oss);

// Database
$driver=array('driver'=>DB_DRIVER, 'hostname'=>DB_HOSTNAME, 'username'=>DB_USERNAME, 'password'=>DB_PASSWORD, 'database'=>DB_DATABASE);
$driver_read=array();
if(defined('DB_READ')&&DB_READ)
$driver_read=array('driver'=>DB_READ_DRIVER, 'hostname'=>DB_READ_HOSTNAME, 'username'=>DB_READ_USERNAME, 'password'=>DB_READ_PASSWORD, 'database'=>DB_READ_DATABASE);

$db = new DB($driver, $driver_read);
$registry->set('db', $db);
		
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
 
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

// Url
$url = new Url(HTTP_SERVER, HTTPS_SERVER);	
$registry->set('url', $url);
$url_web = new Url(HTTP_CATALOG, HTTP_CATALOG);
$registry->set('url_web', $url_web);
		
		
// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$registry->set('session', $session);


// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

if (isset($request->get['language']) && array_key_exists($request->get['language'], $languages) && $languages[$request->get['language']]['status']) {
	$code = $request->get['language'];
} elseif (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
	$code = $request->cookie['language'];
} else {
	$code = $config->get('config_admin_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {	  
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
//$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language	
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);	

$registry->set('language', $language); 		

// Document
$document = new Document();
$registry->set('document', $document); 		
		
// Currency
$registry->set('currency', new Currency($registry));		
		
// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));
						
// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

// Front Controller
$controller = new Front($registry);

if($_SERVER['HTTP_HOST']!=HTTP_HOST){
	
	$session->data['errormsg']='非法来源禁止登录<a href="'.SERVER_CATALOG.'admin">!</a>';

	$controller->dispatch(new Action('error/msg'), new Action('error/not_found'));
	$response->output();
}

// Login
$controller->addPreAction(new Action('common/home/login'));

// Permission
$controller->addPreAction(new Action('common/home/permission'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>