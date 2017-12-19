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
$goods_category = $_POST['goods_category'];
$good_name = $_POST['goods_name'];
$good_desc = $_POST['goods_desc'];
$goods_url = $_POST['goods_url']; 
$unit_amount = $_POST['unit_amount'];
$unit_count = $_POST['unit_count'];
$transport_amount = $_POST['transport_amount'];
$total_amount = $_POST['total_amount'];
$buyer_sp_username = $_POST['buyer_sp_username'];
$return_url = $_POST['return_url'];
$page_url = $_POST['page_url'];
$pay_type = $_POST['pay_type'];
$bank_no = $_POST['bank_no'];
$sp_uno = $_POST['sp_uno'];
$extra = $_POST['extra'];

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

// ���ڲ��Ե��̻�����֧���ӿڵı�������������ı�����������Ķ����ȡֵ�μ��ӿ��ĵ�
$params = array (
		'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
		'sp_no' => sp_conf::SP_NO,
		'order_create_time' => $order_create_time,
		'order_no' => $order_no,
		'goods_category' => $goods_category,
		'goods_name' => $good_name,
		'goods_desc' => $good_desc,
		'goods_url' => $goods_url,
		'unit_amount' => $unit_amount,
		'unit_count' => $unit_count,
		'transport_amount' => $transport_amount,
		'total_amount' => $total_amount,
		'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
		'buyer_sp_username' => $buyer_sp_username,
		'return_url' => $return_url,
		'page_url' => $page_url,
		'pay_type' => $pay_type,
		'bank_no' => $bank_no,
		'expire_time' => $expire_time,
		'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
		'version' => sp_conf::BFB_INTERFACE_VERSION,
		'sign_method' => sp_conf::SIGN_METHOD_MD5,
		'extra' =>$extra
);

$order_url = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_O2O_CODE_CREATE_URL);

// ׷�ӷ�ǩ������code_type��output_type
$order_url = $order_url . '&code_type=' . sp_conf::BFB_INTERFACE_O2O_CODE_TYPE;
$order_url = $order_url . '&output_type=' . sp_conf::BFB_INTERFACE_O2O_OUTPUT_TYPE;

if(false === $order_url){
	$bfb_sdk->log('create the url for baifubao pay interface failed');
}
else {
	$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $order_url));
	echo "<script>window.location=\"" . $order_url . "\";</script>";
}

?>