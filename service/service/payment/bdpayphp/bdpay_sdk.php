<?php

/***************************************************************************
 * 
 * Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/

/**
 * @file sdk.php
 *
 * @author wuxiaofang(com@baidu.com)
 *         @date 2014/08/14 16:39:58
 *         @brief
 *        
 */
if (!defined("bdpay_sdk_ROOT"))
{
	define("bdpay_sdk_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
 
require_once(bdpay_sdk_ROOT . 'bdpay_refund.cfg.php');

if (!function_exists('curl_init')) {
	exit('���PHPû�а�װ ����cURL��չ�����Ȱ�װ����cURL�����巽����������顣');
}

if (!function_exists('json_decode')) {
	exit('���PHP��֧��JSON���������PHP�汾��');
}


class bdpay_sdk{
	public $err_msg;
	public $order_no;
	public $sp_key;
	function __construct() {
	}

	/**
	 * ��ɰٶ�Ǯ���˿�ӿڶ�Ӧ��URL
	 *
	 * @param array $params	����˿�����Ĳ������飬��������ȡֵ�μ�ӿ��ĵ�
	 * @param string $url   �ٶ�Ǯ���˿�ӿ�URL
	 * @return string ������ɵİٶ�Ǯ���˿�ӿ�URL
	 */
	function create_baifubao_Refund_url($params, $url) {
		if (empty($params ['service_code']) || empty($params ['input_charset']) ||
				 empty($params ['sign_method']) ||
				 empty($params ['output_type']) ||
				 empty($params ['output_charset']) ||
				 empty($params ['return_url']) ||
				 empty($params ['return_method']) ||
				 empty($params ['version']) ||
				 empty($params ['sp_no']) ||
			     empty($params ['order_no']) ||
				 empty($params ['cashback_amount']) ||
				 empty($params ['cashback_time']) ||
				 empty($params ['currency'])||
                   empty($params ['sp_refund_no'])
			 ) {
			$this->log(sprintf('invalid params, params:[%s]', print_r($params, true)));
			print_r("create_baifubao_Refund_url");
			return false;
		}

		if (!in_array($url, 
				array (
					sp_conf::BFB_REFUND_URL,
					sp_conf::BFB_REFUND_QUERY_URL,
				))) {
			$this->log(
					sprintf('invalid url[%s], bfb just provide three kind of pay url', 
					$url));
			return false;
		}
		

		if (false === ( $sign = $this->make_sign($params))) {
			return false;
		}
		$this->order_no = $params ['order_no'];
		$params ['sign'] = $sign;
		
		$res=json_decode(json_encode(simplexml_load_string(HTTP::getGET($url,$params))), true);
		return $res;
	}

	/**
	 * ���յ��ٶ�Ǯ����˿���֪ͨ��return_urlҳ����Ҫ����Ԥ���?��
	 * �÷��������̻����õ�return_url��ҳ��Ĵ����߼�����յ���ҳ���get����ʱ��
	 * Ԥ�Ƚ��в�����֤��ǩ��У�飬������ѯ��Ȼ������̻��Ըö����Ĵ������̡�
	 *
	 * @return boolean Ԥ����ɹ�����true�����򷵻�false
	 */
	function check_bfb_refund_result_notify() {
		// �������ı�ѡ�������Ĳ���μ�ӿ��ĵ�
		if (empty($_GET) || !isset($_GET ['bfb_order_no']) || !isset(
				$_GET ['cashback_amount']) || !isset($_GET ['order_no']) ||
				 !isset($_GET ['ret_code']) ||
				 !isset($_GET ['sign_method']) || !isset($_GET ['sp_no']) ||
				 !isset($_GET ['sp_refund_no']) || !isset($_GET ['sign'])) {
			$this->err_msg = 'return_urlҳ�������ı�ѡ������';
			$this->log(
					sprintf('missing the params of return_url page, params[%s]', 
							print_r($_GET)));
			return false;
		}
		$arr_params = $_GET;
		$this->order_no = $arr_params ['order_no'];
		// ����̻�ID�Ƿ����Լ�����������sp_no�����̻��Լ��ģ���ô˵������ٶ�Ǯ���֧�����֪ͨ��Ч
		if (sp_conf::SP_NO != $arr_params ['sp_no']) {
			$this->err_msg = '�ٶ�Ǯ���˿�֪ͨ���̻�ID��Ч����֪ͨ��Ч';
			$this->log(
					'the id in baifubao notify is wrong, this notify is invaild');
			return false;
		}
		// ǩ��У��
		if (false === $this->check_sign($arr_params)) {
			print_r('ǩ��ʧ��');
			$this->err_msg = '�ٶ�Ǯ���̨֪ͨǩ��У��ʧ��';
			$this->log('baifubao notify sign failed');
			return false;
		}
		$this->log('baifubao notify sign success');
		
		// ͨ��ٶ�Ǯ���˿��ѯ�ӿ��ٴβ�ѯ����״̬������У��
		// �ò�ѯ�ӿڴ���һ�����ӳ٣��̻�Ҳ���Բ��ö���У�飬���κ�̨��֧�����֪ͨ����
		
		// ����query_order_state($order_no)������ѯ�������̻��Լ�ϵͳ��״̬
		//���ص�ret_code״̬��1��2��3��1�˿�ɹ�,2�˿�ʧ�ܣ�3�˿������״̬�Ѿ��޸ģ����������ظ�֪ͨ
	    //query_order_state($order_no);

		return true;
	}
	
	/**
	 * �˿�֪ͨ���Ļ�ִ
	 * ���ã�	�յ�֪ͨ������֤ͨ����ٶ�Ǯ�����ִ���ٶ�Ǯ��GET�����̻���return_urlҳ�棬�̻���ߵ���Ӧ
	 * 		�б�������²��֣��ٶ�Ǯ��ֻ�н��յ��ض�����Ӧ��Ϣ�󣬲���ȷ���̻��Ѿ��յ�֪ͨ������֤ͨ������
	 * 		�ٶ�Ǯ��Ų��������̻�����֧�����֪ͨ
	 */
	function notify_bfb() {
		$rep_str = "<html><head>" . sp_conf::BFB_NOTIFY_META .
				 "</head><body><h1>����һ��return_urlҳ��</h1></body></html>";
		echo "$rep_str";
	}

	/**
	 * ͨ��ٶ�Ǯ����Ų�ѯ�˿���Ϣ�����ظö����Ƿ��Ѿ��˿�ɹ�
	 *
	 * @param string $order_no        	
	 * @return string | boolean ����ɹ����ض�����ѯ����������
	 ��������ѯʧ���Լ�ǩ���������������false
	 */
	function query_baifubao_refund_result_by_order_no($order_no) {
		$params = array (
			'service_code' => sp_conf::BFB_QUERY_INTERFACE_SERVICE_ID, // ��ѯ�ӿڵķ���ID��
			'sp_no' => sp_conf::SP_NO,
			'order_no' => $order_no,
			'output_type' => sp_conf::BFB_INTERFACE_OUTPUT_FORMAT, // �ٶ�Ǯ���XML��ʽ�Ľ��
			'output_charset' => sp_conf::BFB_INTERFACE_ENCODING, // �ٶ�Ǯ���GBK����Ľ��
			'version' => sp_conf::BFB_INTERFACE_VERSION,
			'sign_method' => sp_conf::SIGN_METHOD_MD5
		);
	
		// �ٶ�Ǯ����˿��ѯ�ӿڲ������Ĳ���ȡֵ�μ�ӿ��ĵ�
		
		if (false === ($sign = $this->make_sign($params))) {
			$this->log(
					'make sign for query baifubao refund result interface failed');
			return false;
		}
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		
		$query_url = sp_conf::BFB_REFUND_QUERY_URL . '?' . $params_str;
		$this->log(
				sprintf('the url of query baifubao refund result is [%s]', 
						$query_url));
		$content = $this->request($query_url);
		$retry = 0;
		while (empty($content) && $retry < sp_conf::BFB_QUERY_RETRY_TIME) {
			$content = $this->request($query_url);
			$retry++;
		}
		if (empty($content)) {
			$this->err_msg = '���ðٶ�Ǯ���˿��ѯ�ӿ�ʧ��';
			return false;
		}
		$this->log(
				sprintf('the result from baifubao query pay result is [%s]', 
						$content));
		$response_arr = json_decode(
				json_encode(simplexml_load_string($content)), true);
		// �Ͼ����xml�ļ�ʱ�����ĳ�ֶ�û��ȡֵʱ���ᱻ������һ���յ����飬����û��ȡֵ���������Ĭ����Ϊ���ַ�
		foreach ($response_arr as &$value) {
			if (empty($value) && is_array($value)) {
				$value = '';
			}
		}
		unset($value);
		
		return print_r($response_arr, true);
	}
	/**
	 * ͨ��ٶ�Ǯ���˿���ˮ�źŲ�ѯ�˿���Ϣ�����ظö����Ƿ��Ѿ��˿�ɹ�
	 *
	 * @param string $order_no        	
	 * @return string | boolean ����ɹ����ض�����ѯ����������
	 ��������ѯʧ���Լ�ǩ���������������false
	 */
	function query_baifubao_refund_result_by_sprefund_no($order_no,$sp_refund_no) {
		$params = array (
			'service_code' => sp_conf::BFB_QUERY_INTERFACE_SERVICE_ID, // ��ѯ�ӿڵķ���ID��
			'sp_no' => sp_conf::SP_NO,
			'order_no' => $order_no,
			'sp_refund_no' => $sp_refund_no,
			'output_type' => sp_conf::BFB_INTERFACE_OUTPUT_FORMAT, // �ٶ�Ǯ���XML��ʽ�Ľ��
			'output_charset' => sp_conf::BFB_INTERFACE_ENCODING, // �ٶ�Ǯ���GBK����Ľ��
			'version' => sp_conf::BFB_INTERFACE_VERSION,
			'sign_method' => sp_conf::SIGN_METHOD_MD5
		);
	
		// �ٶ�Ǯ����˿��ѯ�ӿڲ������Ĳ���ȡֵ�μ�ӿ��ĵ�
		
		if (false === ($sign = $this->make_sign($params))) {
			$this->log(
					'make sign for query baifubao refund result interface failed');
			return false;
		}
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		
		$query_url = sp_conf::BFB_REFUND_QUERY_URL . '?' . $params_str;
		$this->log(
				sprintf('the url of query baifubao refund result is [%s]', 
						$query_url));
		$content = $this->request($query_url);
		$retry = 0;
		while (empty($content) && $retry < sp_conf::BFB_QUERY_RETRY_TIME) {
			$content = $this->request($query_url);
			$retry++;
		}
		if (empty($content)) {
			$this->err_msg = '���ðٶ�Ǯ���˿��ѯ�ӿ�ʧ��';
			return false;
		}
		$this->log(
				sprintf('the result from baifubao query pay result is [%s]', 
						$content));
		$response_arr = json_decode(
				json_encode(simplexml_load_string($content)), true);
		// �Ͼ����xml�ļ�ʱ�����ĳ�ֶ�û��ȡֵʱ���ᱻ������һ���յ����飬����û��ȡֵ���������Ĭ����Ϊ���ַ�
		foreach ($response_arr as &$value) {
			if (empty($value) && is_array($value)) {
				$value = '';
			}
		}
		unset($value);
		
        // �����ܳ������ĵ��ֶΰ��ղ�ѯ�ӿ��ж���ı��뷽ʽ����ת�룬�˴��������õ�GBK����
		if (isset($response_arr ['ret_details'])) {
			$response_arr ['ret_details'] = iconv("UTF-8", "GBK", 
					$response_arr ['ret_details']);
		}

		return print_r($response_arr, true);
	}

	/**
	 * ���������ǩ�������Ϊ���飬�㷨���£�
	 * 1.
	 * �����鰴KEY������������
	 * 2. ������������������̻���Կ������Ϊkey����ֵΪ�̻���Կ
	 * 3. ������ƴ�ӳ��ַ���key=value&key=value����ʽ����ƴ�ӣ�ע�����ﲻ��ֱ�ӵ���
	 * http_build_query��������Ϊ�÷�����Բ������URL����
	 * 4. Ҫ���������е�$params ['sign_method']����ļ����㷨����ƴ�Ӻõ��ַ���м��ܣ���ɵı���ǩ��
	 * $params ['sign_method']����1ʹ��md5���ܣ�����2ʹ��sha-1����
	 *
	 * @param array $params ���ǩ�������
	 * @return string | boolean �ɹ��������ǩ��ʧ�ܷ���false
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
					$this->err_msg = 'ǩ����֧��';
					return false;
				}
			} else {
				$this->log('ksort failed');
				$this->err_msg = '�?������������ʧ��';
				return false;
			}
		} else {
			$this->log('the params of making sign should be a array');
			$this->err_msg = '���ǩ��Ĳ��������һ������';
			return false;
		}
	}

	/**
	 * У��ǩ����Ĳ��������һ�����飬�㷨���£�
	 * 1. ɾ�������е�ǩ��signԪ��
	 * 2. �������е����м�ֵ����url�����룬���⴫��Ĳ����Ǿ���url�����
	 * 3. �����̻���Կ����������м��ܣ����ǩ��
	 * 4. �ȶ����ǩ���������ԭ�е�ǩ��
	 *
	 * @param array $params	���ǩ��Ĳ�������
	 * @return boolean	���ǩ��ɹ�����true, ʧ�ܷ���false
	 */
	private function check_sign($params) {
		$sign = $params ['sign'];
		unset($params ['sign']);
		foreach ($params as &$value) {
			$value = urldecode($value); // URL����Ľ���
		}
		unset($value);
		if (false !== ($my_sign = $this->make_sign($params))) {
			print_r('�̻��Լ�ƴ�ӵ�ǩ��'.$my_sign);
			print_r('�ٶ�Ǯ��ص�ǩ��'.$sign);
			if (0 !== strcmp($my_sign, $sign)) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ��ȡ��Կ�ļ��������̻��İٶ�Ǯ����Կ
	 * ���ǵ���ȫ�ԣ���Կ��Ҫ����������ʲ�����Ŀ¼�
	 *
	 * @return string	�����̻��İٶ�Ǯ����Կ
	 */
	private function get_sp_key() {
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
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Ҫ����Ϊ�ַ����������Ļ��
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
	 * �����bdpay_refund.cfg.php�����ļ��ж�������־����ļ�����ô��־��Ϣ�ʹ򵽵����ļ���
	 * ���û�ж��壬����־��Ϣ�����PHP�Դ����־�ļ�
	 * 
	 * @param string $msg	��־��Ϣ    	
	 */
	function log($msg) {
		if(define(sp_conf::LOG_FILE)) {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg));
		}
		else {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg), 3, sp_conf::LOG_FILE);
		}
	}
}

?>