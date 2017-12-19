<?php

require_once('bdpayphp/bfb_sdk.php');
require_once('bdpayphp/bfb_pay.cfg.php');

class ControllerPaymentBdpay extends Controller {
	protected function index($setting) {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['custom'] = $encryption->encrypt($this->session->data['order_id']);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->model('checkout/order');
		

		if(isset($setting['order_id'])&&$setting['order_id'])
		{
			$order_id = $setting['order_id'];
		}else 
		{
		    $order_id = $this->session->data['order_id'];
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);
		$this->load->model('sale/order');
		$order_info['products']= $this->model_sale_order->getOrderProducts($order_id);
		
		
		$currency_code ='CNY';
		
		$total = $order_info['total'];  

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount, 2, '.', '');	
		
		//支付参数设置
		$appid = $this->config->get('bdpay_appid');
		
		/*构造百度数据包,字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档*/
		$bfb_sdk = new bfb_sdk();
		$bfb_sdk->sp_key=$this->config->get('bdpay_apikey');
		
		//print_r($bfb_sdk->sp_key);
		$order_create_time = date("YmdHis",strtotime($order_info['date_added']));//转换时间为百度支持模式
			
	    $expire_time = date('YmdHis', $order_info['expire_time']);//交易过期时间修改时间20150323
		$order_no = $order_info['order_id'];
		$goods_category = '';/*与优惠券相关。在创建优惠券推广计划的时候，可以指定优惠券使用的商品类型。若订单的类型与优惠券的类型一致时，就会出相应的优惠券。没有指定时，出用户可用的全部优惠券。取值由钱包系统分配*/
		$good_name = iconv("UTF-8", "GBK", urldecode('青年菜君－'.$order_info['products'][0]['name'].'···'));
		$good_desc = iconv("UTF-8", "GBK", urldecode('请您在提交订单后30分钟内完成支付，否则订单会自动取消'));
		$goods_url = HTTPS_SERVER;//商品在商户网站上的URL
		$unit_amount = $amount*100;//商品单价以分为单位
		$unit_count = 1;//商品数量
		$transport_amount = 0;//运费
		$total_amount = $amount*100;//总价分为单位
		$buyer_sp_username ='';
		$return_url = HTTPS_SERVER . 'catalog/controller/payment/bdpay_notify.php';//后台通知地址
		$page_url = HTTPS_SERVER . 'catalog/controller/payment/bdpay_return.php';//前台通知地址
		$pay_type = 1;
		$bank_no = 201;/*默认银行 201招行 11银联 101中国工商银行 201中国招商银行 301中国建设银行 401中国农业银行 501中信银行 601浦东发展银行 701中国光大银行 801深圳发展银行 1101交通银行 1201中国银行 13	银联在线UPOP
		1901广发银行 1902中国邮政储蓄银行 1903中国民生银行 1904华夏银行 1905兴业银行 1906上海银行 1907上海农商银行 1908中国银行大额 1909北京银行 1910北京农商银行*/
		$sp_uno = $order_info['customer_id'];//用户在本站的唯一id
		$extra = '';//自定义数据
		
		
		
		
		// 构造商户请求支付接口的表单参数
		$params = array (
				'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
				'sp_no' => $appid,
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
		
		$detect = new Mobile_Detect();
		
		if($detect->isMobile() && !$detect->isTablet()){
			$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_WAP_DIRECT_URL);
		}else{
			$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL);
		}
		
		
		
		
		
		//$this->load->service('payment/bdpay');
		//$action = $this->service_payment_bdpay->create_pay_url($order_id);
		
		if(isset($this->session->data['checkout_token'])) {
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bdpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bdpay.tpl';
		} else {
			$this->template = 'default/template/payment/bdpay.tpl';
		}

		$this->render();
	}
	public function reorder($setting) {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
	
		
		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$order_id = $this->request->get['order_id'];

		//$order_id = $this->session->data['order_id'];
		
		$this->data['custom'] = $encryption->encrypt($order_id);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->model('checkout/order');
		
		
       // 获取订单信息
		$order_info = $this->model_checkout_order->getOrder($order_id);
		//print_r($order_info);
		$this->load->model('sale/order');
		$order_info['products']= $this->model_sale_order->getOrderProducts($order_id);
		
		$currency_code ='CNY';
		$item_name = $this->config->get('config_title');
		$first_name = $order_info['payment_firstname'];
		$last_name = $order_info['payment_lastname'];

		 
		// 订单金额
		$total = $order_info['total'];
		
		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount, 2, '.', '');
		//支付参数设置
		$appid = $this->config->get('bdpay_appid');
		
		/*构造百度数据包,字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档*/
		$bfb_sdk = new bfb_sdk();
		$bfb_sdk->sp_key=$this->config->get('bdpay_apikey');;
		//print_r($bfb_sdk->sp_key);
		$order_create_time = date("YmdHis",strtotime($order_info['date_added']));//转换时间为百度支持模式
		
		
	    $expire_time = date('YmdHis',$order_info['expire_time']);//交易过期时间20150322$order_info['expire_time']
	    
	    $expire_time = date('YmdHis',time()+3600*24);//交易过期时间，测试用
	    
		//echo($order_create_time.'-'.$expire_time);die();
		$order_no = $order_info['order_id'];
		$goods_category = '';/*与优惠券相关。在创建优惠券推广计划的时候，可以指定优惠券使用的商品类型。若订单的类型与优惠券的类型一致时，就会出相应的优惠券。没有指定时，出用户可用的全部优惠券。取值由钱包系统分配*/
		$good_name = iconv("UTF-8", "GBK", '青年菜君－'.$order_info['products'][0]['name'].'···');
		$good_desc = iconv("UTF-8", "GBK", '请您在提交订单后30分钟内完成支付，否则订单会自动取消');
		$goods_url = HTTPS_SERVER;//商品在商户网站上的URL
		$unit_amount = $amount*100;//商品单价分为单位
		$unit_count = 1;//商品数量
		$transport_amount = 0;//运费
		$total_amount = $amount*100;//总价分为单位
		$buyer_sp_username ='';//iconv("UTF-8", "GBK",$order_info['firstname'].$order_info['lastname'])
		$return_url = HTTPS_SERVER . 'catalog/controller/payment/bdpay_notify.php';//后台通知地址
		$page_url = HTTPS_SERVER . 'catalog/controller/payment/bdpay_return.php';//前台通知地址
		$pay_type = 1;
		$bank_no = 201;/*默认银行 201招行 11银联 101中国工商银行 201中国招商银行 301中国建设银行 401中国农业银行 501中信银行 601浦东发展银行 701中国光大银行 801深圳发展银行 1101交通银行 1201中国银行 13	银联在线UPOP
1901广发银行 1902中国邮政储蓄银行 1903中国民生银行 1904华夏银行 1905兴业银行 1906上海银行 1907上海农商银行 1908中国银行大额 1909北京银行 1910北京农商银行*/
		$sp_uno = $order_info['customer_id'];//用户在本站的唯一id
		$extra = '';//自定义数据
		
		
	
		
		// 构造商户请求支付接口的表单参数
		$params = array (
				'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
				'sp_no' => $appid,
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
		
		//pc pay_unlogin
		$detect = new Mobile_Detect();
		if($detect->isMobile() && !$detect->isTablet()){
			$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_WAP_DIRECT_URL);
		}else{
			$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL);
		}
		//$this->load->service('payment/bdpay');
		//$action = $this->service_payment_bdpay->create_pay_url($order_id);
		
		//pc pay_needlogin
		//$action = $bfb_sdk->create_baifubao_pay_order_url($params, sp_conf::BFB_PAY_DIRECT_LOGIN_URL);
		
		//pay wap direct ur
		
		//$action = $bfb_sdk->create_baifubao_pay_order_url($params, sp_conf::BFB_PAY_WAP_DIRECT_URL);
		
		if(false === $action){
			$bfb_sdk->log('create the url for baifubao pay interface failed');
		}
		else {
			$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $action));
		}
		
		
		/*
		//o2o_codecreate
		$action = $bfb_sdk->create_baifubao_o2o_pay_order_url($params,sp_conf::BFB_O2O_CODE_CREATE_URL);
		
		if(false === $action){
			$bfb_sdk->log('create the url for baifubao pay interface failed');
		}
		else {
		// 追加非签名参数code_type、output_type
			$action .=  '&code_type=' . sp_conf::BFB_INTERFACE_O2O_CODE_TYPE;
			$action .=  '&output_type=' . sp_conf::BFB_INTERFACE_O2O_OUTPUT_TYPE;
			
			$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $action));
		}
		
		//oto_codepay
		
		$params['pay_code']= $pay_code;//付款码
		$params['mno']     = $mno;     //实体商户门店号
		$params['mname']   = $mname;   //实体商户门店名称
		$params['tno']     = $tno;     //实体商户终端号
		
		$order_url = $bfb_sdk->create_baifubao_o2o_pay_order_url($params,sp_conf::BFB_O2O_B2C_PAY_URL);
		
		if(false === $order_url){
			$bfb_sdk->log('create the url for baifubao pay interface failed');
		}
		else {
			$bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $order_url));
			echo "<script>window.location=\"" . $order_url . "\";</script>";
		}
		
		*/
		
		//print_r($action);die();

		
		if(isset($this->session->data['checkout_token'])) {
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		$this->data['reorder'] = true;
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bdpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bdpay.tpl';
		} else {
			$this->template = 'default/template/payment/bdpay.tpl';
		}

		
		$this->render();
	}

public function get_baifubao_pay_order_url(){
	
	
	
}
  	
//服务器后台通知接口
	public function notify() {
		
		$this->log_payment->info('bdpay.notify.start');
		

		$bfb_sdk = new bfb_sdk();
		$bfb_sdk->sp_key=$this->config->get('bdpay_apikey');
		
		$this->log_payment->info(sprintf('get the notify from baifubao, the request is [%s]', serialize($_GET)));
		
		$arr_params=$bfb_sdk->check_bfb_pay_result_notify();
		if (false ===$arr_params) {
			$this->log_payment->info('get the notify from baifubao, but the check work failed'.$bfb_sdk->err_msg);
			return;
		}
		
		
		$this->log_payment->info('get the notify from baifubao and the check work success');
		
		// 检查商户ID是否是自己，如果传过来的sp_no不是商户自己的，那么说明这个百付宝的支付结果通知无效
		if ($this->config->get('bdpay_appid') != $arr_params ['sp_no']) {
			$bfb_sdk->err_msg = '百付宝的支付结果通知中商户ID无效，该通知无效';

			$this->log_payment->info('the id in baifubao notify is wrong, this notify is invaild'.$bfb_sdk->err_msg);
			
		}
		// 检查支付通知中的支付结果是否为支付成功
		elseif (sp_conf::BFB_PAY_RESULT_SUCCESS != $arr_params ['pay_result']) {
			$bfb_sdk->err_msg = '百付宝的支付结果通知中商户支付结果异常，该通知无效';
			$this->log_payment->info('the pay result in baifubao notify is wrong, this notify is invaild'.$bfb_sdk->err_msg.serialize($arr_params));
		}
		else 
		{
		
		/*
		 * 此处是商户收到百付宝支付结果通知后需要做的自己的具体业务逻辑，比如记账之类的。 只有当商户收到百付宝支付 结果通知后，
		 * 所有的预处理工作都返回正常后，才执行该部分
		*/

		// 查询订单在商户自己系统的状态
		$order_no = $arr_params ['order_no'];
		$bfb_order_no = $arr_params ['bfb_order_no'];
		
		
		$total_fee = $arr_params ['total_amount'];
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_no);
		$this->log_payment->info('order_info');
		$this->log_payment->info($order_info);
		

		$this->log_payment->info(sprintf('order state in sp server is [%s]', $order_info["order_status_id"]));
		
		if ($this->config->get('config_order_nopay_status_id') == $order_info["order_status_id"]) {

			$this->log_payment->info('the order state is right, the order is waiting for pay');
			if($order_info && abs((int)($order_info['total'] * 100) - (int)$total_fee)<=1)
			{
				$this->model_checkout_order->confirm($order_no, $this->config->get('bdpay_order_status_id'),'bdpay::trade_no:'.$bfb_order_no,array('trade_no'=>$bfb_order_no));
				$this->log_payment->info('bdpay.notify[order_id= ' . $order_info['order_id'] . ';total_fee=' . $total_fee . '].order_status_id=' . $order_info["order_status_id"] . ' to ' .$this->config->get('bdpay_order_status_id').',$trade_no:'.$bfb_order_no);
			}
			else
			{
				$bfb_sdk->err_msg = '订单[%s]金额错误';
				$this->log_payment->info('the order total is wrong'.$bfb_sdk->err_msg);
					
			}
				
		} elseif (sp_conf::SP_PAY_RESULT_SUCCESS == $order_info["order_status_id"]) {

			$bfb_sdk->err_msg = '订单[%s]已经处理，此百付宝后台支付通知为重复通知';
			$this->log_payment->info('the order state is wrong, this order has been paid'.$bfb_sdk->err_msg);
		} else {
			$bfb_sdk->err_msg = '订单[%s]状态异常';
			$this->log_payment->info(sprintf('the order state is wrong, it is [%s]',
							$order_state).$bfb_sdk->err_msg);
		}
		}
		
		// 向百付宝发起回执
		$bfb_sdk->notify_bfb();
		
		$this->log_payment->info('bdpay.notify.end');
	}

	
	protected function query_order_by_no($order_no)
	{
		$bfb_sdk = new bfb_sdk();
		
		$content = $bfb_sdk->query_baifubao_pay_result_by_order_no($order_no);
		
		if(false === $content){
			$this->log_payment->warn('create the url for baifubao query interface failed');
		}
		else {
			$this->log_payment->info('create the url for baifubao query interface success');
		}
		return $content;
		
	}

}

?>