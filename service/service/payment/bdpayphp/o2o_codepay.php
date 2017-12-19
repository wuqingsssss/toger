<?php

/**
 * ����ǵ���bfb_sdk�����ɰٸ�����ʱ����֧���ӿ�URL(����Ҫ��¼)��DEMO
 *
 */
if (!defined("BFB_SDK_ROOT"))
{
	define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

require_once(BFB_SDK_ROOT . 'bfb_sdk.php');
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');

$bfb_sdk = new bfb_sdk();
$order_create_time = date("YmdHis");
$expire_time = date('YmdHis', strtotime('+2 day'));
$order_no = $order_create_time . sprintf ( '%06d', rand(0, 999999));
$good_name = $_GET['goods_name'];
$good_desc = $_GET['goods_desc'];
$goods_url = $_GET['goods_url']; 
$total_amount = $_GET['total_amount'];
$return_url = $_GET['return_url'];
$pay_code = $_GET['pay_code'];
$mno = $_GET['mno'];
$mname = $_GET['mname'];
$tno = $_GET['tno'];
$extra = $_GET['extra'];

/*
 * �ַ�����ת�����ٸ���Ĭ�ϵı�����GBK���̻���ҳ�ı���������ǣ���ת�롣�漰�����ĵ��ֶ���μ��ӿ��ĵ�
 * ���裺
 * 1. URLת��
 * 2. �ַ�����ת�룬ת��GBK
 * 
 * $good_name = iconv("UTF-8", "GBK", urldecode($good_name));
 * $good_desc = iconv("UTF-8", "GBK", urldecode($good_desc));
 * 
 */

// ���ڲ��Ե��̻�����֧���ӿڵı�����������ı���������Ķ����ȡֵ�μ��ӿ��ĵ�
$params = array (
		'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
		'sp_no' => sp_conf::SP_NO,
		'order_create_time' => $order_create_time,
		'order_no' => $order_no,
		'goods_name' => $good_name,
		'goods_desc' => $good_desc,
		'goods_url' => $goods_url,
		'total_amount' => $total_amount,
		'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
		'return_url' => $return_url,
		'pay_code' => $pay_code,
		'mno' => $mno,
		'mname' => $mname,
		'tno' => $tno,
		'expire_time' => $expire_time,
		'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
		'version' => sp_conf::BFB_INTERFACE_VERSION,
		'sign_method' => sp_conf::SIGN_METHOD_MD5,
		'extra' =>$extra
);

$order_url = $bfb_sdk->create_baifubao_o2o_pay_order_url($params,sp_conf::BFB_O2O_B2C_PAY_URL);

if(false === $order_url){
	$bfb_sdk->log('create the url for baifubao pay interface failed');
}
else {
	$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $order_url));
	echo "<script>window.location=\"" . $order_url . "\";</script>";
}

?>