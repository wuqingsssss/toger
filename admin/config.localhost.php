<?php
define('STATIC_VERSION', '3.0.1');

// HTTP
define('HTTP_HOST', $_SERVER['HTTP_HOST']);//'182.92.219.90'
define('WEB_HOST',HTTP_HOST);

define('HTTP_CATALOG', 'http://'.WEB_HOST.'/');
define('SERVER_CATALOG', 'http://'.HTTP_HOST.'/qncj/');

define('DIR_DIR','admin/');
define('DIR_API', 'apiv3/');
define('HTTP_SERVER', SERVER_CATALOG.DIR_DIR);

define('HTTP_IMAGE', SERVER_CATALOG.'image/');

// HTTPS
define('HTTPS_SERVER', SERVER_CATALOG.DIR_DIR);
define('HTTPS_IMAGE',  SERVER_CATALOG.'image/');

define('WEB_SCAN',true);
define('URL_MODEL',3);//utlmodel 0 默认  1pathinfo 2系统伪静态 3兼容模式
// DIR
define('DIR_ROOT', str_replace(DIR_DIR,'',str_replace(DIRECTORY_SEPARATOR,'/',dirname(__FILE__) . DIRECTORY_SEPARATOR)));


define('DIR_APPLICATION', DIR_ROOT.DIR_DIR);
define('DIR_API_NAME', 'apiv3');
define('DIR_SYSTEM', DIR_ROOT.'system/');
define('DIR_DATABASE', DIR_ROOT.'system/database/');
define('DIR_LANGUAGE', DIR_ROOT.DIR_DIR.'language/');
define('DIR_TEMPLATE', DIR_ROOT.DIR_DIR.'view/template/');
define('DIR_CONFIG', DIR_ROOT.'system/config/');
define('DIR_IMAGE', DIR_ROOT.'image/');
define('DIR_CACHE', DIR_ROOT.'system/cache/');
define('DIR_DOWNLOAD', DIR_ROOT.'download/');

define('DIR_LOGS', DIR_ROOT.'system/logs/');
define('DIR_PYTHON'       , '/qncj/bin/python/');
define('LOG_ERROR_HANDLER'       , true);
//define('DIR_LOGS'       , '/qncj/log/www/');
define('DIR_CATALOG', DIR_ROOT.'catalog/'); 
/*
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123456');
define('DB_DATABASE', 'qncjv20324');
define('DB_PREFIX', 'ts_');
/* */

// DB
define('DB_DRIVER', 'mysqlii');
define('DB_HOSTNAME', '192.168.5.128');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'pass@2014@word');
define('DB_DATABASE', 'qncjdb');
define('DB_PREFIX', 'ts_');

// DB
/*
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'qncjdb002.mysql.rds.aliyuncs.com');
define('DB_USERNAME', 'qncj');
define('DB_PASSWORD', 'Qncj0917Pass');
define('DB_DATABASE', 'qncjdb');
define('DB_PREFIX', 'ts_');
/* */
// DB
define('DB_SELECT_CACHE', false);
define('DB_SELECT_CACHE_TIME', 2);
define('ORDER_PREFIX', '9');//订单号统一前缀
define('MEM_CACHE', false);
define('MEM_HOSTNAME', '677c2ab824444086.m.cnbjalicm12pub001.ocs.aliyuncs.com');
define('MEM_PORT', '11211');

//SMS

define('DEBUG', true);

define('SMS_OPEN', 'ON'); //  ON
 
//阿里OSS配置项
define('OSS_ACCESS_ID', 'OfkQKcqQWNaKxPY6');
define('OSS_ACCESS_KEY', 'XDjgCWtPLCof6dOH0cQcMmaGsNYKna');
define('OSS_OPEN_BUCKET', 'web-pic');
define('OSS_ENDPOINT', 'oss-cn-beijing.aliyuncs.com');
//内网地址(免费,但公网不能访问)
//define('OSS_ENDPOINT', 'web-pic.oss-cn-beijing-internal.aliyuncs.com');
?>