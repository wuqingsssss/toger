<?php

/***************************************************************************
 * 
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/

/**
 * @file sdk.php
 *
 * @author yanglong01(com@baidu.com)
 *         @date 2013/08/19 16:39:58
 *         @brief
 *        
 */
if (!defined("BFB_SDK_ROOT"))
{
	define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
 
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');

if (!function_exists('curl_init')) {
	exit('����PHPû�а�װ ����cURL��չ�����Ȱ�װ����cURL�����巽�����������顣');
}

if (!function_exists('json_decode')) {
	exit('����PHP��֧��JSON������������PHP�汾��');
}


class bfb_sdk{
	public $err_msg;
	public $order_no;
	public $sp_key;

	function __construct() {
     
	}

	/**
	 * ���ɰٸ�����ʱ����֧���ӿڶ�Ӧ��URL
	 *
	 * @param array $params	���ɶ����Ĳ������飬���������ȡֵ�μ��ӿ��ĵ�
	 * @param string $url   �ٸ�����ʱ����֧���ӿ�URL
	 * @return string �������ɵİٸ�����ʱ����֧���ӿ�URL
	 */
	function create_baifubao_pay_order_url($params, $url) {
		if (empty($params ['service_code']) || empty($params ['sp_no']) ||
				 empty($params ['order_create_time']) ||
				 empty($params ['order_no']) ||
				 empty($params ['goods_name']) ||
				 empty($params ['total_amount']) ||
				 empty($params ['currency']) ||
				 empty($params ['return_url']) ||
				 empty($params ['pay_type']) ||
				 empty($params ['input_charset']) ||
				 empty($params ['version']) ||
				 empty($params ['sign_method'])) {
			$this->log(sprintf('invalid params, params:[%s]', print_r($params, true)));
			return false;
		}
		
		if (!in_array($url, 
				array (
					sp_conf::BFB_PAY_DIRECT_LOGIN_URL,
					sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL,
					sp_conf::BFB_PAY_WAP_DIRECT_URL,
					sp_conf::BFB_O2O_CODE_CREATE_URL
				))) {
			$this->log(
					sprintf('invalid url[%s], bfb just provide three kind of pay url', 
					$url));
			return false;
		}
		
		$pay_url = $url;
		
		if (false === ($sign = $this->make_sign($params))) {
			return false;
		}
		$this->order_no = $params ['order_no'];
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		$this->log(
				sprintf('the params that create baifubao pay order is [%s]', 
						$params_str));
		
		return $pay_url . '?' . $params_str;
	}
	
	/**
	 * ���ɰٸ�����ʱ����֧���ӿڶ�Ӧ��URL
	 *
	 * @param array $params	���ɶ����Ĳ������飬���������ȡֵ�μ��ӿ��ĵ�
	 * @param string $url   �ٸ�����ʱ����֧���ӿ�URL
	 * @return string �������ɵİٸ�����ʱ����֧���ӿ�URL
	 */
	function create_baifubao_o2o_pay_order_url($params, $url) {
		if (empty($params ['service_code']) || empty($params ['sp_no']) ||
				 empty($params ['order_create_time']) ||
				 empty($params ['order_no']) ||
				 empty($params ['goods_name']) ||
				 empty($params ['total_amount']) ||
				 empty($params ['currency']) ||
				 empty($params ['pay_code']) ||
				 empty($params ['input_charset']) ||
				 empty($params ['version']) ||
				 empty($params ['sign_method'])) {
			$this->log(sprintf('invalid params, params:[%s]', print_r($params, true)));
			return false;
		}
		if (!in_array($url, 
				array (
					sp_conf::BFB_PAY_DIRECT_LOGIN_URL,
					sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL,
					sp_conf::BFB_PAY_WAP_DIRECT_URL,
					sp_conf::BFB_O2O_B2C_PAY_URL
				))) {
			$this->log(
					sprintf('invalid url[%s], bfb just provide three kind of pay url', 
					$url));
			return false;
		}
		
		$pay_url = $url;
		
		if (false === ($sign = $this->make_sign($params))) {
			echo($this->err_msg);
			return false;
		}
		$this->order_no = $params ['order_no'];
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		$this->log(
				sprintf('the params that create baifubao pay order is [%s]', 
						$params_str));
		
		return $pay_url . '?' . $params_str;
	}

	/**
	 * ���յ��ٸ�����֧�����֪ͨ��return_urlҳ����Ҫ����Ԥ��������
	 * �÷��������̻����õ�return_url��ҳ��Ĵ����߼�����յ���ҳ���get����ʱ��
	 * Ԥ�Ƚ��в�����֤��ǩ��У�飬������ѯ��Ȼ������̻��Ըö����Ĵ������̡�
	 *
	 * @return boolean Ԥ�����ɹ�����true�����򷵻�false
	 */
	function check_bfb_pay_result_notify() {
		// �������ı�ѡ����������Ĳ����μ��ӿ��ĵ�
		/*
		$_GET=Array('bank_no' =>'','bfb_order_create_time' => 20150511191819,
				'bfb_order_no' => 2015051110000120571110441912641,
				'buyer_sp_username' => 15810817660,
				'currency' => 1,
				extra =>'',
				fee_amount => 0,
				input_charset=> 1,
				order_no => 15051100671,
				pay_result => 1,
				pay_time => 20150511191819,
				pay_type => 2,
				sign_method => 1,
				sp_no => 1000012057,
				total_amount => 1,
				transport_amount => 0,
				unit_amount => 1,
				unit_count => 1,
				version => 2,
				sign => '5dc59fc617dad3fe220d240bb4a6e310'
		);*/

		if (empty($_GET) || !isset($_GET ['sp_no']) || !isset(
				$_GET ['order_no']) || !isset($_GET ['bfb_order_no']) ||
				 !isset($_GET ['bfb_order_create_time']) ||
				 !isset($_GET ['pay_time']) || !isset($_GET ['pay_type']) ||
				 !isset($_GET ['total_amount']) || !isset($_GET ['fee_amount']) ||
				 !isset($_GET ['currency']) || !isset($_GET ['pay_result']) ||
				 !isset($_GET ['input_charset']) || !isset($_GET ['version']) ||
				 !isset($_GET ['sign']) || !isset($_GET ['sign_method'])) {
			$this->err_msg = 'return_urlmissing the params of return_url page';
			$this->log(
					sprintf('missing the params of return_url page, params[%s]', 
							print_r($_GET)));
		}

		//$data='a:20:{s:7:"bank_no";s:0:"";s:21:"bfb_order_create_time";s:14:"20150702214628";s:12:"bfb_order_no";s:31:"2015070210000120571110532193848";s:17:"buyer_sp_username";s:0:"";s:8:"currency";s:1:"1";s:5:"extra";s:0:"";s:10:"fee_amount";s:2:"12";s:13:"input_charset";s:1:"1";s:8:"order_no";s:11:"15070201013";s:10:"pay_result";s:1:"1";s:8:"pay_time";s:14:"20150702214625";s:8:"pay_type";s:1:"2";s:11:"sign_method";s:1:"1";s:5:"sp_no";s:10:"1000012057";s:12:"total_amount";s:4:"1990";s:16:"transport_amount";s:1:"0";s:11:"unit_amount";s:4:"1990";s:10:"unit_count";s:1:"1";s:7:"version";s:1:"2";s:4:"sign";s:32:"14286e41c86d658fc7dcbf35daac85d6";}';
		
		//$data2='a:20:{s:7:"bank_no";s:0:"";s:21:"bfb_order_create_time";s:14:"20150701191216";s:12:"bfb_order_no";s:31:"2015070110000120571110530087031";s:17:"buyer_sp_username";s:0:"";s:8:"currency";s:1:"1";s:5:"extra";s:0:"";s:10:"fee_amount";s:2:"12";s:13:"input_charset";s:1:"1";s:8:"order_no";s:11:"15070100797";s:10:"pay_result";s:1:"1";s:8:"pay_time";s:14:"20150701191215";s:8:"pay_type";s:1:"2";s:11:"sign_method";s:1:"1";s:5:"sp_no";s:10:"1000012057";s:12:"total_amount";s:4:"1990";s:16:"transport_amount";s:1:"0";s:11:"unit_amount";s:4:"1990";s:10:"unit_count";s:1:"1";s:7:"version";s:1:"2";s:4:"sign";s:32:"a4123b5d6a262b5fa56983aff3d9c3c3";}';
		//$_GET=unserialize($data2);
		
		$arr_params = $_GET;
		$this->order_no = $arr_params ['order_no'];
		

		// ǩ��У��
		if (false === $this->check_sign($arr_params)) {
			$this->err_msg = 'baifubao notify sign failed';
			$this->log('baifubao notify sign failed');
			return false;
		}
		
		$this->log('baifubao notify sign success');
		
		// ͨ���ٸ���������ѯ�ӿ��ٴβ�ѯ����״̬������У��
		// �ò�ѯ�ӿڴ���һ�����ӳ٣��̻����Բ��ö���У�飬���κ�̨��֧�����֪ͨ����
// 		if (false === $this->query_baifubao_pay_result_by_order_no(
// 				$arr_params ['order_no'])) {
// 			$this->err_msg = '���ðٸ���������ѯ�ӿ�ʧ��';
// 			$this->log('call baifubao pay result interface failed');
// 			return false;
// 		}
// 		$this->log('baifubao query pay result by order_no success');
		
		
		
		
		return $arr_params;
	}
	
	/**
	 * ֧��֪ͨ����Ļ�ִ
	 * ���ã�	�յ�֪ͨ������֤ͨ������ٸ��������ִ���ٸ���GET�����̻���return_urlҳ�棬�̻���ߵ���Ӧ
	 * 		�б���������²��֣��ٸ���ֻ�н��յ��ض�����Ӧ��Ϣ�󣬲���ȷ���̻��Ѿ��յ�֪ͨ������֤ͨ��������
	 * 		�ٸ����Ų��������̻�����֧�����֪ͨ
	 */
	function notify_bfb() {
		$rep_str = "<html><head>" . sp_conf::BFB_NOTIFY_META .
				 "</head><body><h1>����һ��return_urlҳ��</h1></body></html>";
		echo "$rep_str";
	}

	/**
	 * ��ѯ����������÷�����Ҫ�̻��Լ�ʵ�֣������ǲ�ѯ�̻��Լ���ϵͳ����֤�ö����Ƿ��Ѿ���������.
	 * ���ڰٸ����ĺ�̨֪ͨ�ӿڿ��ܻ���ö�Σ�����˴��̻��������������ֱ�ӽ��м��˵Ⱥ���������
	 * ���ܻ�һ���������̻���ϵͳ���ظ���¼������̻����ʽ�ȱʧ.
	 *
	 * @param string $order_no        	
	 * @return int ����������ڵȴ�֧��״̬������sp_conf::SP_PAY_RESULT_WAITING
	 *         ��������û�Ҳ�����Լ�����
	 */
	private function query_order_info($order_no) {
		/*
		 * ������Ҫ�̻��Լ�ʵ�ֲ�ѯ�����ҵ���߼�,������ֻ�Ǽ򵥵ķ��صȴ�֧��
		 */
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_no);

		return $order_info;//sp_conf::SP_PAY_RESULT_WAITING;
	}

	/**
	 * ͨ���ٸ��������Ų�ѯ�ӿڲ�ѯ������Ϣ�����ظö����Ƿ��Ѿ�֧���ɹ�
	 *
	 * @param string $order_no        	
	 * @return string | boolean ����֧���ɹ����ض�����ѯ��������������������ѯʧ���Լ�֧��״̬����֧���ɹ������������false
	 */
	function query_baifubao_pay_result_by_order_no($order_no) {
		$params = array (
			'service_code' => sp_conf::BFB_QUERY_INTERFACE_SERVICE_ID, // ��ѯ�ӿڵķ���ID��
			'sp_no' => sp_conf::SP_NO,
			'order_no' => $order_no,
			'output_type' => sp_conf::BFB_INTERFACE_OUTPUT_FORMAT, // �ٸ�������XML��ʽ�Ľ��
			'output_charset' => sp_conf::BFB_INTERFACE_ENCODING, // �ٸ�������GBK����Ľ��
			'version' => sp_conf::BFB_INTERFACE_VERSION,
			'sign_method' => sp_conf::SIGN_METHOD_MD5
		);
	
		// �ٸ��������Ų�ѯ�ӿڲ���������Ĳ���ȡֵ�μ��ӿ��ĵ�
		
		if (false === ($sign = $this->make_sign($params))) {
			$this->log(
					'make sign for query baifubao pay result interface failed');
			return false;
		}
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		
		$query_url = sp_conf::BFB_QUERY_ORDER_URL . '?' . $params_str;
		$this->log(
				sprintf('the url of query baifubao pay result is [%s]', 
						$query_url));
		$content = $this->request($query_url);
		$retry = 0;
		while (empty($content) && $retry < sp_conf::BFB_QUERY_RETRY_TIME) {
			$content = $this->request($query_url);
			$retry++;
		}
		if (empty($content)) {
			$this->err_msg = '���ðٸ��������Ų�ѯ�ӿ�ʧ��';
			return false;
		}
		$this->log(
				sprintf('the result from baifubao query pay result is [%s]', 
						$content));
		$response_arr = json_decode(
				json_encode(simplexml_load_string($content)), true);
		// �Ͼ����xml�ļ�ʱ�����ĳ�ֶ�û��ȡֵʱ���ᱻ������һ���յ����飬����û��ȡֵ���������Ĭ����Ϊ���ַ���
		foreach ($response_arr as &$value) {
			if (empty($value) && is_array($value)) {
				$value = '';
			}
		}
		unset($value);
		// ��鷵�ؽ��
		if (empty($response_arr) || !isset($response_arr ['query_status']) ||
				 !isset($response_arr ['sp_no']) ||
				 !isset($response_arr ['order_no']) ||
				 !isset($response_arr ['bfb_order_no']) ||
				 !isset($response_arr ['bfb_order_create_time']) ||
				 !isset($response_arr ['pay_time']) ||
				 !isset($response_arr ['pay_type']) ||
				 !isset($response_arr ['goods_name']) ||
				 !isset($response_arr ['total_amount']) ||
				 !isset($response_arr ['fee_amount']) ||
				 !isset($response_arr ['currency']) ||
				 !isset($response_arr ['pay_result']) ||
				 !isset($response_arr ['sign']) ||
				 !isset($response_arr ['sign_method'])) {
			$this->err_msg = sprintf('�ٸ����Ķ�����ѯ�ӿڲ�ѯʧ�ܣ���������Ϊ[%s]', 
					print_r($response_arr, true));
			return false;
		}
		// ��鶩����ѯ�ӿڵ���Ӧ�����в�ѯ״̬query_status�Ƿ�Ϊ0��0������ѯ�ɹ�
		if (0 != $response_arr ['query_status']) {
			$this->log(
					sprintf(
							'query the baifubao pay result interface faild, the query_status is [%s]', 
							$response_arr ['query_status']));
			$this->err_msg = sprintf('�ٸ����Ķ�����ѯ�ӿڲ�ѯʧ�ܣ���ѯ״̬Ϊ[%s]', 
					$response_arr ['query_status']);
			return false;
		}
		// ����̻�ID�Ƿ����Լ��������������sp_no�����̻��Լ��ģ���ô˵������ٸ����Ķ�����ѯ�ӿڵ���Ӧ������Ч
		if (sp_conf::SP_NO != $response_arr ['sp_no']) {
			$this->log(
					'the sp_no returned from baifubao pay result interface is invaild');
			$this->err_msg = '�ٸ����Ķ�����ѯ�ӿڵ���Ӧ�������̻�ID��Ч����֪ͨ��Ч';
			return false;
		}
		// ��鶩����ѯ�ӿڵ���Ӧ�����е�֧������Ƿ�Ϊ֧���ɹ�
		if (sp_conf::BFB_PAY_RESULT_SUCCESS != $response_arr ['pay_result']) {
			$this->log(
					sprintf(
							'the pay result returned from baifubao pay result interface is invalid, is [%s]', 
							$response_arr ['pay_result']));
			$this->err_msg = '�ٸ����Ķ�����ѯ�ӿڵ���Ӧ�������̻�֧������쳣����֪ͨ��Ч';
			return false;
		}
		
		// �����ܳ������ĵ��ֶΰ��ղ�ѯ�ӿ��ж���ı��뷽ʽ����ת�룬�˴��������õ�GBK����
		$response_arr ['goods_name'] = iconv("UTF-8", "GBK", 
				$response_arr ['goods_name']);
		if (isset($response_arr ['buyer_sp_username'])) {
			$response_arr ['buyer_sp_username'] = iconv("UTF-8", "GBK", 
					$response_arr ['buyer_sp_username']);
		}
		// У�鷵�ؽ���е�ǩ��
		if (false === $this->check_sign($response_arr)) {
			$this->log(
					'sign the result returned from baifubao pay result interface failed');
			$this->err_msg = '�ٸ���������ѯ�ӿ���Ӧ����ǩ��У��ʧ��';
			return false;
		}
		
		return print_r($response_arr, true);
	}

	/**
	 * ���������ǩ�����������Ϊ���飬�㷨���£�
	 * 1.
	 * �����鰴KEY������������
	 * 2. �������������������̻���Կ������Ϊkey����ֵΪ�̻���Կ
	 * 3. ������ƴ�ӳ��ַ�������key=value&key=value����ʽ����ƴ�ӣ�ע�����ﲻ��ֱ�ӵ���
	 * http_build_query��������Ϊ�÷�����Բ�������URL����
	 * 4. Ҫ�����������е�$params ['sign_method']����ļ����㷨����ƴ�Ӻõ��ַ������м��ܣ����ɵı���ǩ����
	 * $params ['sign_method']����1ʹ��md5���ܣ�����2ʹ��sha-1����
	 *
	 * @param array $params ����ǩ��������
	 * @return string | boolean �ɹ���������ǩ����ʧ�ܷ���false
	 */
	private function make_sign($params) {
		
		if (is_array($params)) {
			// �Բ���������а�key��������
			
			if (ksort($params)) {
				
				
				if(false === ($params ['key'] = $this->get_sp_key())){
					return false;
				}
				
				
				$arr_temp = array ();
				foreach ($params as $key => $val) {
					$arr_temp [] = $key . '=' . $val;
				}
				$sign_str = implode('&', $arr_temp);
				// ѡ����Ӧ�ļ����㷨
				if ($params ['sign_method'] == sp_conf::SIGN_METHOD_MD5) {
					return md5($sign_str);
				} else if ($params ['sign_method'] == sp_conf::SIGN_METHOD_SHA1) {
					return sha1($sign_str);
				} else{
					$this->log('unsupported sign method');
					$this->err_msg = 'ǩ��������֧��';
					return false;
				}
			} else {
				$this->log('ksort failed');
				$this->err_msg = '����������������ʧ��';
				return false;
			}
		} else {
			$this->log('the params of making sign should be a array');
			$this->err_msg = '����ǩ���Ĳ���������һ������';
			return false;
		}
	}

	/**
	 * У��ǩ��������Ĳ���������һ�����飬�㷨���£�
	 * 1. ɾ�������е�ǩ��signԪ��
	 * 2. �������е����м�ֵ����url�����룬���⴫��Ĳ����Ǿ���url�����
	 * 3. �����̻���Կ����������м��ܣ�����ǩ��
	 * 4. �ȶ�����ǩ����������ԭ�е�ǩ��
	 *
	 * @param array $params	����ǩ���Ĳ�������
	 * @return boolean	����ǩ���ɹ�����true, ʧ�ܷ���false
	 */
	private function check_sign($params) {
		$sign = $params ['sign'];
		unset($params ['sign']);
		foreach ($params as &$value) {
			$value = urldecode($value); // URL����Ľ���
		}
		
		
		unset($value);
		$my_sign = $this->make_sign($params);

		if (false !== ($my_sign)) {
		
			if (0 !== strcmp($my_sign, $sign)) {
				
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ��ȡ��Կ�ļ��������̻��İٸ�����Կ
	 * ���ǵ���ȫ�ԣ���Կ��Ҫ�����������ʲ�����Ŀ¼�
	 *
	 * @return string	�����̻��İٸ�����Կ
	 */
	private function get_sp_key() {
		/*$file = BFB_SDK_ROOT.sp_conf::SP_KEY_FILE;	
		if (!file_exists($file)) {
			$this->log(sprintf('can not find the sp key file, file [%s]', $file));
			return false;
		}
		$fh = fopen($file, 'rb');
		$key = trim(fread($fh, filesize($file)));
		fclose($fh);
		�����ļ��洢��Ϊ���ݿ��ȡ*/
		if (empty($this->sp_key)) {
			$this->log('empty of the sp_key bfb');
			return false;
		}
		
		return $this->sp_key;
	}

	/**
	 * ִ��һ�� HTTP GET����
	 *
	 * @param string $url ִ�������url
	 * @return array ������ҳ����
	 */
	function request($url) {
		$curl = curl_init(); // ��ʼ��curl
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false); // ����header
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Ҫ����Ϊ�ַ������������Ļ��
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3); // ���õȴ�ʱ��
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		
		$res = curl_exec($curl); // ����curl
		$err = curl_error($curl);
		
		if (false === $res || !empty($err)) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			
			$this->log(
					sprintf(
							'curl the baifubao pay result interface failed, err_msg [%s]', 
							$info));
			$this->err_msg = $info;
			return false;
		}
		curl_close($curl); // �ر�curl
		return $res;
	}

	/**
	 * ��־��ӡ����
	 * �����bfb_pay.cfg.php�����ļ��ж�������־����ļ�����ô��־��Ϣ�ʹ򵽵����ļ���
	 * ���û�ж��壬����־��Ϣ�����PHP�Դ�����־�ļ�
	 * 
	 * @param string $msg	��־��Ϣ    	
	*/
	function log($msg) {
		
		sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg);
		/*
		if(!define(sp_conf::LOG_FILE)) {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg));
		}
		else {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg), 3, sp_conf::LOG_FILE);
		}
		*/
	} 
}

?>