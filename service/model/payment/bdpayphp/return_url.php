<?php

/**
 * ����̻���returl_urlҳ��ʵ�ֵ�ģ��
 * ��ҳ���ҵ���߼��ǣ�
 * 1. ���̻��յ��ٸ���֧���ɹ���֪ͨ�󣬵���sdk��Ԥ�������ȷ���ö���֧���ɹ�
 * 2. ȷ��֧���ɹ����̻��Լ���ҵ���߼����������֮��ġ�
 * ע�⣬sdk�е�query_order_state()�����������̻��Լ�ʵ�֣�
 * ����������յ���ΰٸ�����֧�����֪ͨ�������̻��Լ������ʽ�Ĳ�һ�¡�
 */
require_once 'bfb_sdk.php';

$bfb_sdk = new bfb_sdk();

$bfb_sdk->log(sprintf('get the notify from baifubao, the request is [%s]', print_r($_GET, true)));

if (false === $bfb_sdk->check_bfb_pay_result_notify()) {
	$bfb_sdk->log('get the notify from baifubao, but the check work failed');
	return;
}
$bfb_sdk->log('get the notify from baifubao and the check work success');


/*
 * �˴����̻��յ��ٸ���֧�����֪ͨ����Ҫ�����Լ��ľ���ҵ���߼����������֮��ġ� ֻ�е��̻��յ��ٸ���֧�� ���֪ͨ��
 * ���е�Ԥ�����������������󣬲�ִ�иò���
 */



// ��ٸ��������ִ
$bfb_sdk->notify_bfb();


?>