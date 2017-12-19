<?php
// Configuration

$config_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
$currentDir = getcwd();
$currentDir=str_replace('/catalog/controller/payment','',str_replace('\\','/',$currentDir));
if(!file_exists($currentDir .'/' .$config_name)){
	$config_name='config.php';
}
require_once('../../../'.$config_name);

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

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
// Database
$driver=array('driver'=>DB_DRIVER, 'hostname'=>DB_HOSTNAME, 'username'=>DB_USERNAME, 'password'=>DB_PASSWORD, 'database'=>DB_DATABASE);
$driver_read=array();
if(defined('DB_READ')&&DB_READ)
$driver_read=array('driver'=>DB_READ_DRIVER, 'hostname'=>DB_READ_HOSTNAME, 'username'=>DB_READ_USERNAME, 'password'=>DB_READ_PASSWORD, 'database'=>DB_READ_DATABASE);

$db = new DB($driver, $driver_read);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");

foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

// Store
$query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE url = '" . $db->escape('http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "' OR url = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");

foreach ($query->row as $key => $value) {
	$config->set('config_' . $key, $value);
}


$config->set('config_url', HTTP_SERVER);
$config->set('config_ssl', HTTPS_SERVER);

// Url
$url = new Url($config->get('config_url'), $config->get('config_ssl'));
$registry->set('url', $url);


// Request
$request = new Request();
$registry->set('request', $request);

// Log
$log = new Log($config->get('config_error_filename'),true);
$registry->set('log', $log);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Cache
$registry->set('cache', new Cache());

// Session
$session = new Session();
$registry->set('session', $session);

$log4phpconfig_name = 'config.'.$_SERVER['HTTP_HOST'].'.php';
if(!file_exists(DIR_SYSTEM .'/log4php/' .$log4phpconfig_name))
	$log4phpconfig_name='config.php';
Logger::configure(DIR_SYSTEM . 'log4php/'.$log4phpconfig_name);


$log_order   = Logger::getLogger('order');
$log_payment = Logger::getLogger('payment');
$log_sys     = Logger::getRootLogger();
$log_db      = Logger::getLogger('database');
$registry->set('log_order', $log_order);
$registry->set('log_payment', $log_payment);
$registry->set('log_sys', $log_sys);
$registry->set('log_db', $log_db);

// Document
$registry->set('document', new Document());

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language");

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) {
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			$locale = explode(',', $value['locale']);

			if (in_array($browser_language, $locale)) {
				$detect = $key;
			}
		}
	}
}

if (isset($_GET['language']) && array_key_exists($_GET['language'], $languages)) {
	$code = $_GET['language'];
} elseif (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
	$code = $request->cookie['language'];
} elseif ($detect) {
	$code = $detect;
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

// Customer
$registry->set('customer', new Customer($registry));

// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));

// Log
$log = new Log($config,$config->get('config_error_filename'),'time_log.txt');
$registry->set('log', $log);

// Front Controller
$controller = new Front($registry);

// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));

// Router
$route = 'payment/tenpay/callback';
$action = new Action($route);


// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>