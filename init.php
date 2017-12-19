<?php
/* 页面应用对象初始化*/
/* Version*/define('VERSION', '3.0.1');

// Config
# first try to load develop conf file which ignore by git, then load default file (on server)
$config_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
$currentDir = dirname(__FILE__) ;
if(!file_exists($currentDir .'/' .$config_name)){
	$config_name='config.php';
}

require_once($config_name);

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');



//die();
// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');
require_once(DIR_SYSTEM . 'library/mcartResult.php');
require_once(DIR_SYSTEM . 'library/jsmin.php');

require_once(DIR_SYSTEM . 'library/geohash.php');

// Registry
$registry = new Registry();

$registry->set('detect', $detect);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);


if(defined('MEM_HOSTNAME')&&MEM_HOSTNAME&&class_exists('MCache')){
	$mem = new MCache(MEM_HOSTNAME, MEM_PORT,$_SERVER['HTTP_HOST']);
	$registry->set('mem', $mem);
}
//未发送邮件缓存
$emails_waitting=array();
// Session
$session = new Session();

// log4php

$log4phpconfig_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
if(!file_exists(DIR_SYSTEM .'/log4php/' .$log4phpconfig_name))
	$log4phpconfig_name='config.php';
Logger::configure(DIR_SYSTEM . 'log4php/'.$log4phpconfig_name);

$log_sys     = Logger::getRootLogger();
$log_db      = Logger::getLogger('database');
$registry->set('log_sys', $log_sys);
$registry->set('log_db', $log_db);

// Error Handler
if(defined('LOG_ERROR_HANDLER')&&LOG_ERROR_HANDLER)
	set_error_handler('error_handler');


$oss=new OSS();
$registry->set('oss', $oss);
// Database
$driver=array('driver'=>DB_DRIVER, 'hostname'=>DB_HOSTNAME, 'username'=>DB_USERNAME, 'password'=>DB_PASSWORD, 'database'=>DB_DATABASE);
$driver_read=array();
if(defined('DB_READ')&&DB_READ)
	$driver_read=array('driver'=>DB_READ_DRIVER, 'hostname'=>DB_READ_HOSTNAME, 'username'=>DB_READ_USERNAME, 'password'=>DB_READ_PASSWORD, 'database'=>DB_READ_DATABASE);

$db = new DB($driver, $driver_read);

$registry->set('db', $db);

//$log_db->debug('start');

/*
 for($i=0;$i<10;$i++){
 $res=$db->query("select * from ts_order order by order_id limit 0,1000");
 //$res=$db->query("INSERT INTO ts_customer_ip SET customer_id = '39413', ip = '100.97.136.115', date_added = NOW()");
 //$res=$db->query("UPDATE ts_product SET viewed = (viewed + 1) WHERE product_id = '295'");


 echo($res->num_rows.":$costtime <br/>");
 }
*/

// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$store_query = $db->query("SELECT store_id FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
	$store_query = $db->query("SELECT store_id FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}


if ($store_query->num_rows) {
	$config->set('config_store_id', $store_query->row['store_id']);
} else {
	$config->set('config_store_id', 0);
}

// Settings
$query = $db->query("SELECT `key`,`value`,serialized FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

if($emails_waitting)
{
	foreach($emails_waitting as $key=> $item)
	{
		sendAlertMailMsg($item['level'],$item['text']);
		unset($emails_waitting[$key]);
	}
}

if (!$store_query->num_rows) {
	$config->set('config_url', HTTP_SERVER);
	$config->set('config_ssl', HTTPS_SERVER);
}

// Url
$url = new Url($config->get('config_url'), $config->get('config_ssl'));
$registry->set('url', $url);


//$Jsmin = new Jsmin();
//$registry->set('jsmin', $Jsmin);


// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->addHeader("Access-Control-Allow-Origin:null");
$response->addHeader("Access-Control-Allow-Credentials: true");//跨域请求发送cookie等用户认证凭据


$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Cache
$cache = new Cache();
$registry->set('cache', $cache);


$registry->set('session', $session);


$log_order   = Logger::getLogger('order');
$log_payment = Logger::getLogger('payment');
$registry->set('log_order', $log_order);
$registry->set('log_payment', $log_payment);

//Geohash lbs
$registry->set('geohash', new Geohash());

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$detectlng = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) {
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			if ($value['status']) {
				$locale = explode(',', $value['locale']);
				if (in_array($browser_language, $locale)) {
					$detectlng = $key;
				}
			}
		}
	}
}

if (isset($request->get['language']) && array_key_exists($request->get['language'], $languages) && $languages[$request->get['language']]['status']) {
	$code = $request->get['language'];
} elseif (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
	$code = $request->cookie['language'];
} elseif ($detectlng) {
	$code = $detectlng;
} else {
	$code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

// Document
$document = new Document();
$registry->set('document', $document);


// Common
//$common=new Common($registry);
//$registry->set('utils', $common);
//$utils->get_openid();

// Customer
$registry->set('customer', new Customer($registry));

// Affiliate
$affiliate = new Affiliate($registry);
$registry->set('affiliate', $affiliate);

if (isset($request->get['tracking']) && !isset($request->cookie['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}

// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));




// Cart
$registry->set('cart', new Cart($registry));

$m = new mCartResult();
$registry->set('m', $m);

// Front Controller
$controller = new Front($registry);
