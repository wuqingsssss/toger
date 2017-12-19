<?php

/**
 * ����ǵ���bfb_sdk��ͨ���ٸ��������Ų�ѯ�ӿڲ�ѯ������Ϣ��DEMO
 *
 */
if (!defined("BFB_SDK_ROOT"))
{
	define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

require_once(BFB_SDK_ROOT . 'bfb_sdk.php');
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');

$bfb_sdk = new bfb_sdk();

$order_no = $_POST['order_no'];

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
		
$content = $bfb_sdk->query_baifubao_pay_result_by_order_no($order_no);

if(false === $content){
	$bfb_sdk->log('create the url for baifubao query interface failed');
}
else {
	$bfb_sdk->log('create the url for baifubao query interface success');
	echo "��ѯ�ɹ�\n";
	echo $content;
}

?>