<?php
require_once("alipay_function.php");
require_once("alipay_notify.php");
require_once("alipay_service.php");

class ControllerPaymentAlipay extends Controller {
	public function index($setting) {
		//require_once DIR_SYSTEM . 'library/Mobile_Detect.php';

		$detect = new Mobile_Detect();
	
		if($detect->isMobile() && !$detect->isTablet()){ 
			$this->Alipay_wap($setting);
		}else{
			$this->Alipay_web($setting);
		}
	}
	
	public function Alipay_wap($setting) {
		require_once("alipay_wap/alipay.config.php");
		require_once("alipay_wap/lib/alipay_submit.class.php");
		
		/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
			
		//返回格式
		$format = "xml";
		//必填，不需要修改
		
		//返回格式
		$v = "2.0";
		//必填，不需要修改
		
		//请求号
		$req_id = date('Ymdhis');
		//必填，须保证每次请求都是唯一
		
		//**req_data详细信息**
		
		//服务器异步通知页面路径
		
		$notify_url     =HTTP_SERVER . 'catalog/controller/payment/alipay_wap_callback.php';
		$call_back_url	=HTTP_SERVER . 'index.php?route=checkout/success';
		
		//$notify_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/notify_url.php';
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//页面跳转同步通知页面路径
		//$call_back_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/call_back_url.php';
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//操作中断返回地址
		$merchant_url = HTTP_SERVER ;
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = $this->config->get('alipay_seller_email');
		//必填
		if(isset($setting['order_id'])&&$setting['order_id'])
		{
			$order_id = $setting['order_id'];
		}else 
		{
		    $order_id = $this->session->data['order_id'];
		}
		
		$this->load->model('checkout/order');
		
		//TODO:修改订单状态位未支付
		//$this->model_checkout_order->updateOrderStatus($order_id,$this->config->get('config_order_nopay_status_id'));

		//$order_info = $this->model_checkout_order->getOrder($order_id);
		
		//商户订单号
		$out_trade_no = $order_id;
		//商户网站订单系统中唯一订单号，必填
		
		//订单名称 商店名称+订单编号
		$subject = $this->config->get('config_name').$this->language->get('text_order_no') . $order_id;
		//必填
		
		//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
		
		$total = $payment_info['value'];
		//付款金额
		$total_fee = $total;
		//必填
		
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//必填
		
		/************************************************************/
		
		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($this->config->get('alipay_partner')),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//自定义超时,wap
		if(defined('ORDER_PAY_TIMEOUT_MINS')){
			$para_token['pay_expire']=ORDER_PAY_TIMEOUT_MINS.'m';
		}


		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		
		//获取request_token
		$request_token = $para_html_text['request_token'];
		
		
		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($this->config->get('alipay_partner')),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '立即支付');
		
		if(isset($this->session->data['checkout_token'])){
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		
		$this->data['action'] = $html_text;
		$this->id = 'payment';
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipaym.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/alipaym.tpl';
		} else {
			$this->template = 'default/template/payment/alipaym.tpl';
		}
		
		$this->render();
	}
	
	public function Alipay_web($setting) {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['return'] = $this->url->link('checkout/success', '', 'SSL'); 

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['cancel_return'] =  $this->url->link('checkout/cart', '', 'SSL');
		}

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['custom'] = $encryption->encrypt($this->session->data['order_id']);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = $this->url->link('checkout/cart', '', 'SSL');
		} else {
			$this->data['back'] = $this->url->link('checkout/cart', '', 'SSL');
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

		$seller_email = $this->config->get('alipay_seller_email');
		$security_code = $this->config->get('alipay_security_code');
		$trade_type = $this->config->get('alipay_trade_type');
		$partner = $this->config->get('alipay_partner');
		$currency_code ='CNY';
		$item_name = $this->config->get('config_name');
		$first_name = $order_info['payment_firstname'];
		$last_name = $order_info['payment_lastname'];

		
		//$total = $order_info['total'];
		//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
		$total = $payment_info['value'];

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount,2,'.','');

		$_input_charset = "utf-8";
		$sign_type      = "MD5";
		$transport      = "http";
		$notify_url     = HTTP_SERVER . 'catalog/controller/payment/alipay_callback.php';
		$return_url		=HTTPS_SERVER . 'index.php?route=checkout/success';
		$show_url       = "";
		
		$parameter = array(
			"service"        => $trade_type,
			"partner"        => $partner,
			"return_url"     => $return_url,
			"notify_url"     => $notify_url,
			"_input_charset" => $_input_charset,
			"subject"        => $item_name.$this->language->get('text_order_no') . $order_id ,
			"body"           => $item_name,
			"out_trade_no"   => $order_id,
			"price"          => $amount,
			"payment_type"   => "1",
			"quantity"       => "1",
			"logistics_fee"      =>'0.00',
			"logistics_payment"  =>'BUYER_PAY',
			"logistics_type"     =>'EXPRESS',
			"show_url"       => $show_url,
			"seller_email"   => $seller_email
		);
		//自定义超时
		if(defined('ORDER_PAY_TIMEOUT_MINS')){
			$parameter['it_b_pay']=ORDER_PAY_TIMEOUT_MINS.'m';
		}

		$alipay = new alipay_service($parameter,$security_code,$sign_type);
		$action=$alipay->build_url();

		
		if(isset($this->session->data['checkout_token'])){
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/alipay.tpl';
		} else {
			$this->template = 'default/template/payment/alipay.tpl';
		}

		$this->render();
	}
	
	//  本站二次付款入口
	public function reorder() {
		
		if($this->detect->isMobile() && !$this->detect->isTablet()){ 
			require_once("alipay_wap/alipay.config.php");
			require_once("alipay_wap/lib/alipay_submit.class.php");
			
			/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
				
			//返回格式
			$format = "xml";
			//必填，不需要修改
			
			//返回格式
			$v = "2.0";
			//必填，不需要修改
			
			//请求号
			$req_id = date('Ymdhis');
			//必填，须保证每次请求都是唯一
			
			//**req_data详细信息**
			
			//服务器异步通知页面路径
			
			$notify_url     =HTTP_SERVER . 'catalog/controller/payment/alipay_wap_callback.php';
			$call_back_url	=HTTP_SERVER . 'index.php?route=checkout/success';
			
			//$notify_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/notify_url.php';
			//需http://格式的完整路径，不允许加?id=123这类自定义参数
			
			//页面跳转同步通知页面路径
			//$call_back_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/call_back_url.php';
			//需http://格式的完整路径，不允许加?id=123这类自定义参数
			
			//操作中断返回地址
			$merchant_url = HTTP_SERVER ;
			//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
			
			//卖家支付宝帐户
			$seller_email = $this->config->get('alipay_seller_email');
			//必填
			if(isset($setting['order_id'])&&$setting['order_id'])
			{
				$order_id = $setting['order_id'];
			}else
			{
				$order_id = $this->session->data['order_id'];
			}
			
			$this->load->model('checkout/order');
			
			//TODO:修改订单状态位未支付
			//$this->model_checkout_order->updateOrderStatus($order_id,$this->config->get('config_order_nopay_status_id'));
			
			//$order_info = $this->model_checkout_order->getOrder($order_id);
			
			//商户订单号
			$out_trade_no = $order_id;
			//商户网站订单系统中唯一订单号，必填
			
			//订单名称 商店名称+订单编号
			$subject = $this->config->get('config_name').$this->language->get('text_order_no') . $order_id;
			//必填
			
			//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
			$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
			
				
			$total = $payment_info['value'];
			//付款金额
			$total_fee = $total;
			//必填
			
			//请求业务参数详细
			$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
			//必填
			
			/************************************************************/
			
			//构造要请求的参数数组，无需改动
			$para_token = array(
					"service" => "alipay.wap.trade.create.direct",
					"partner" => trim($this->config->get('alipay_partner')),
					"sec_id" => trim($alipay_config['sign_type']),
					"format"	=> $format,
					"v"	=> $v,
					"req_id"	=> $req_id,
					"req_data"	=> $req_data,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			//自定义超时,wap
			if(defined('ORDER_PAY_TIMEOUT_MINS')){
				$para_token['pay_expire']=ORDER_PAY_TIMEOUT_MINS.'m';
			}
			
			
			
			//建立请求
			$alipaySubmit = new AlipaySubmit($alipay_config);
			$html_text = $alipaySubmit->buildRequestHttp($para_token);
			
			//URLDECODE返回的信息
			$html_text = urldecode($html_text);
			
			//解析远程模拟提交后返回的信息
			$para_html_text = $alipaySubmit->parseResponse($html_text);
			
			//获取request_token
			$request_token = $para_html_text['request_token'];
			
			
			/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
			
			//业务详细
			$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
			//必填
			
			//构造要请求的参数数组，无需改动
			$parameter = array(
					"service" => "alipay.wap.auth.authAndExecute",
					"partner" => trim($this->config->get('alipay_partner')),
					"sec_id" => trim($alipay_config['sign_type']),
					"format"	=> $format,
					"v"	=> $v,
					"req_id"	=> $req_id,
					"req_data"	=> $req_data,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			
			//建立请求
			$alipaySubmit = new AlipaySubmit($alipay_config);
			//$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '立即支付');
			
		    $html_text="<a href=\"".HTTP::buildURL($alipaySubmit->alipay_gateway_new."_input_charset=".trim(strtolower($alipaySubmit->alipay_config['input_charset'])),$alipaySubmit->buildRequestPara($parameter))."\">立即支付</a>";
			
			
			
			if(isset($this->session->data['checkout_token'])){
				$this->data['token'] =$this->session->data['checkout_token'];
			}
			$this->data['reorder'] = true;
			$this->data['action'] = $html_text;
			$this->id = 'payment';

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipaym.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/alipaym.tpl';
			} else {
				$this->template = 'default/template/payment/alipaym.tpl';
			}
		}
		else 
		{
			
			$this->data['button_confirm'] = $this->language->get('button_reoder_confirm');
			$this->data['button_back'] = $this->language->get('button_back');
			
			$this->data['return'] = $this->url->link('checkout/success', '', 'SSL');
			
			$this->load->library('encryption');
			
			$encryption = new Encryption($this->config->get('config_encryption'));
			$order_id=$this->request->get['order_id'];
			$this->data['custom'] = $encryption->encrypt($order_id);
			
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$seller_email = $this->config->get('alipay_seller_email');
			$security_code = $this->config->get('alipay_security_code');
			$trade_type = $this->config->get('alipay_trade_type');
			$partner = $this->config->get('alipay_partner');
			$currency_code ='CNY';
			$item_name = $this->config->get('config_name');
			$first_name = $order_info['payment_firstname'];
			$last_name = $order_info['payment_lastname'];
			
			//$total = $order_info['total'];
			//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
			$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
			$total = $payment_info['value'];
			
			$currency_value = $this->currency->getValue($currency_code);
			$amount = $total * $currency_value;
			$amount = number_format($amount,2,'.','');
			
			$_input_charset = "utf-8";
			$sign_type      = "MD5";
			$transport      = "http";
			$notify_url     = HTTP_SERVER . 'catalog/controller/payment/alipay_callback.php';
			$return_url		=HTTPS_SERVER . 'index.php?route=account/paysuccess';
			$show_url       = "";
			
			$parameter = array(
					"service"        => $trade_type,
					"partner"        => $partner,
					"return_url"     => $return_url,
					"notify_url"     => $notify_url,
					"_input_charset" => $_input_charset,
					"subject"        => $item_name.$this->language->get('text_order_no') . $order_id ,
					"body"           => $item_name,
					"out_trade_no"   => $order_id,
					"price"          => $amount,
					"payment_type"   => "1",
					"quantity"       => "1",
					"logistics_fee"      =>'0.00',
					"logistics_payment"  =>'BUYER_PAY',
					"logistics_type"     =>'EXPRESS',
					"show_url"       => $show_url,
					"seller_email"   => $seller_email
			);
			//自定义超时
			if(defined('ORDER_PAY_TIMEOUT_MINS')){
				$parameter['it_b_pay']=ORDER_PAY_TIMEOUT_MINS.'m';
			}
			
			$alipay = new alipay_service($parameter,$security_code,$sign_type);
			$action=$alipay->build_url();
			
			
			$this->data['reorder'] = true;
			$this->data['action'] = $action;
			$this->id = 'payment';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/alipay.tpl';
			} else {
				$this->template = 'default/template/payment/alipay.tpl';
			}
			
			
		}
	
	
		$this->render();
	}

	public function wapcallback() {
		require_once("alipay_wap/alipay.config.php");
		require_once("alipay_wap/lib/alipay_notify.class.php");
		
		
		//trade_create_by_buyer 双接口 ,create_direct_pay_by_user 直接到帐，create_partner_trade_by_buyer 担保接口
		$trade_type = $this->config->get('alipay_trade_type');
		$this->log_payment->info("Alipay Wap ::post ".serialize($_POST));
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		$pay_code = 'alipay';
	
		// Order status TODO we need a config page to set these.
		$order_status = array(
				"Canceled"        => 7,
				"Canceled_Reversal"   => 9,
				"Chargeback"     	=> 13,
				"Complete"     		=> 5,
				"Denied" 			=> 8,
				"Failed"        	=> 10 ,
				"Pending"           => 1,
				"Processing"  		 => 2,
				"Refunded"        	  => 11,
				"Reversed"  		 => 12,
				"Shipped"     	  => 3
		);
	
	
		$this->log_payment->info("Alipay Wap :: trade_type ".$trade_type." :: verify_result  = ".$verify_result." ,partner=".$alipay_config['partner']);
	
		if($verify_result) {
			$doc2 = new DOMDocument();
			
			$notify_data="".$_POST['notify_data'];
			
			if($alipay_config['sign_type']=='0001'){
			  $notify_data = $alipayNotify->decrypt($_POST['notify_data']);
			}

			//urldecode
			$notify_data=str_replace('&gt;','>',str_replace('&lt;','<',$notify_data));
			

			$doc2->loadXML($notify_data);
//			$doc2->encoding = "utf-8";
			$this->log_payment->info("Alipay notify_data :: ".$notify_data);
			$this->log_payment->info("Alipay out_trade_no :: ".$doc2->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue);
			if( ! empty($doc2->getElementsByTagName( "notify_id" )->item(0)->nodeValue) ) {
				//商户订单号
				$order_id = $doc2->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc2->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc2->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
			
				if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
					$this->load->model('checkout/order');
					//$order_info = $this->model_checkout_order->getOrder($order_id);
					//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
					$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
					
					$this->log_payment->info("Alipay order_id :: ".$order_id);
					
/*					if ($order_info) {
						$order_status_id = $order_info["order_status_id"];
		
						$this->log_payment->info("Alipay order_id :: ".$order_id." order_status_id = ".$order_status_id." , trade_status :: ".$trade_status);
						
						// 确定订单没有重复支付
						if ($order_status_id != $this->config->get('alipay_order_status_id')&&$order_status_id==$this->config->get('config_order_nopay_status_id')) {//仅待付款状态订单可以付款，其它状态屏蔽并返回错误20150403
								// 根据接口类型动态使用支付方法
								if($trade_type=='trade_create_by_buyer'){
									$this->func_trade_create_by_buyer($order_id,  $pay_code,$order_status_id,$order_status,$trade_status,$trade_no);
									echo "success";
								}else if($trade_type=='create_direct_pay_by_user'){
									$this->func_create_direct_pay_by_user($order_id, $pay_code, $order_status,$trade_status,$trade_no);
									echo "success";
								}else if($trade_type=='create_partner_trade_by_buyer'){
									$this->func_create_partner_trade_by_buyer($order_id, $pay_code, $order_status_id,$order_status,$trade_status,$trade_no);
									echo "success";
								}
						$this->log_payment->info("Alipay success order_id :: ".$order_id." order_status_id = ".$order_status_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
								
						}else {
							echo "fail";
						$this->log_payment->info("Alipay fail order_id :: ".$order_id." order_status_id = ".$order_status_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
								
						}
					}*/
					if ($payment_info) {
					    $this->log_payment->info("Alipay order_id :: ".$order_id." order_payment_status = ".$payment_info['status']." , trade_status :: ".$trade_status);
					
					    // 确定订单没有重复支付
					    if ($payment_info['status'] == 0) {//仅待付款状态订单可以付款
					        // 根据接口类型动态使用支付方法
					        if($trade_type=='trade_create_by_buyer'){
					            $this->func_trade_create_by_buyer($order_id,  $pay_code,$trade_status,$trade_no);
					            echo "success";
					        }else if($trade_type=='create_direct_pay_by_user'){
					            $this->func_create_direct_pay_by_user($order_id, $pay_code,$trade_status,$trade_no);
					            echo "success";
					        }else if($trade_type=='create_partner_trade_by_buyer'){
					            $this->func_create_partner_trade_by_buyer($order_id, $pay_code,$trade_status,$trade_no);
					            echo "success";
					        }
					        $this->log_payment->info("Alipay success order_id :: ".$order_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
					
					    }else {
					        echo "fail";
					        $this->log_payment->info("Alipay fail order_id :: ".$order_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
					
					    }
					}
					else{
						$this->log_payment->info("Alipay No Order Found order_id:".$order_id);
						
						echo "fail";
					}	
				}else{
					$this->log_payment->info("Alipay fail Status=".$trade_status);
					echo "fail";
				}
			}
		}
	}
	
	public function callback() {
		//trade_create_by_buyer 双接口 ,create_direct_pay_by_user 直接到帐，create_partner_trade_by_buyer 担保接口
		$trade_type = $this->config->get('alipay_trade_type');

		$this->load->library('encryption');

		$seller_email = $this->config->get('alipay_seller_email'); // 商家邮箱
		$partner = $this->config->get('alipay_partner'); //合作伙伴ID
		$security_code = $this->config->get('alipay_security_code'); //安全检验码

		$_input_charset = "utf-8";
		//$_input_charset = "GBK";
		$sign_type = "MD5";
		$transport = 'http';

		$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
//		$verify_result = $alipay->notify_verify();
		$verify_result = 1;
		
		$pay_code = 'alipay';

		// Order status TODO we need a config page to set these.
		$order_status = array(
			"Canceled"        => 7,
			"Canceled_Reversal"   => 9,
			"Chargeback"     	=> 13,
			"Complete"     		=> 5,
			"Denied" 			=> 8,
			"Failed"        	=> 10 ,
			"Pending"           => 1,
			"Processing"  		 => 2,
			"Refunded"        	  => 11,
			"Reversed"  		 => 12,
			"Shipped"     	  => 3
		);

		
		$this->log_payment->info("Alipay :: trade_type ".$trade_type." :: verify_result  = ".$verify_result);
		$this->log_payment->info("resp: ".	json_encode($_POST));
		
		if($verify_result) {
			$order_id   = $_POST['out_trade_no'];   //$_POST['out_trade_no'];
			$trade_status=$_POST['trade_status'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			$this->load->model('checkout/order');
			//$order_info = $this->model_checkout_order->getOrder($order_id);
		
			$this->log_payment->info("Alipay order_id :: ".$order_id);
			
			//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
			$payment_info = $this->model_checkout_order->getOrderPayment($order_id, $pay_code);

			if ($payment_info) {
				if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
					//$order_status_id = $order_info["order_status_id"];
					
					$this->log_payment->info("Alipay order_id :: ".$order_id." , trade_status :: ".$trade_status);
					// 确定订单没有重复支付
    			    if ($payment_info['status']==0) {//仅待付款状态订单可以付款
    				    $currency_code = 'CNY';
    				    $total = $payment_info['value'];
    				    $currency_value = $this->currency->getValue($currency_code);
    				    $amount = $total * $currency_value;
    				    $total  =  $_POST['total_fee'];    //$_POST['total_fee'];
    				    // 确定支付和订单额度一致
    				    $this->log_payment->info("Alipay total :: ".$_POST['total_fee'].",amount :: ".$amount,'alipay.log');
    				    if(abs($total -$amount)>EPSILON){
    				   //     $this->model_checkout_order->confirm($order_id, $pay_code, $order_status['Canceled']);
    				
    				        $this->log_payment->info("Alipay order_id :: ".$order_id." total <> amount");
    				    }
    				    
    			        // 根据接口类型动态使用支付方法
    			        if($trade_type=='trade_create_by_buyer'){
    			            $this->func_trade_create_by_buyer($order_id,  $pay_code,$trade_status,$trade_no);
    			            echo "success";
    			        }else if($trade_type=='create_direct_pay_by_user'){
    			            $this->func_create_direct_pay_by_user($order_id,  $pay_code,$trade_status,$trade_no);
    			            echo "success";
    			        }else if($trade_type=='create_partner_trade_by_buyer'){
    			            $this->func_create_partner_trade_by_buyer($order_id,  $pay_code,$trade_status,$trade_no);
    			            echo "success";
    			        }
    			        $this->log_payment->info("Alipay order_id :: ".$order_id." success trade_type:".$trade_type.',$trade_no:'.$trade_no);
    				    
    				}else {
    				    $this->log_payment->info("Alipay fail order_id :: ".$order_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
    				    echo "fail";
    				}
    			}	
    			else{
    					$this->log_payment->info("Alipay No Order Found order_id:".$order_id);
    				echo "fail";
    			}
    		}else{
    			$this->log_payment->info("Alipay No Order Found.");
    			echo "fail";
    		}
		}
	}
    
    // 双接口
	private function func_trade_create_by_buyer($order_id,  $pay_code, $trade_status, $trade_no){
			if($trade_status == 'WAIT_BUYER_PAY') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." WAIT_BUYER_PAY");
				$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}
			/*else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_payment_status");
				//$this->model_checkout_order->confirm($order_id,  $pay_code, $this->config->get('alipay_order_status_id'),'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
				$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}
			else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS,update order_payment_status");
		        $this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");

			}
			else if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED / TRADE_SUCCESS, update order_payment_status");
				$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}*/
	}
	
	// 直接到帐
	private function func_create_direct_pay_by_user($order_id, $pay_code, $trade_status, $trade_no){
			if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status ==TRADE_FINISHED / TRADE_SUCCESS,  update order_payment_status");
				$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
				$this->log_payment->debug("Alipay order_id :: ".$order_id." update order_payment_status");
			}
	}
	
	// 双接口
	private function func_create_partner_trade_by_buyer($order_id, $pay_code,$trade_status,$trade_no){
			if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id."  trade_status ==  WAIT_BUYER_PAY,  update order_payment_status");
				$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
			    $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}
/*			else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_payment_status");
				$this->model_checkout_order->confirm($order_id,  $pay_code, $this->config->get('alipay_order_status_id'),'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}
			else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS, update order_payment_status");
				$this->model_checkout_order->confirm($order_id,  $pay_code, $order_status['Shipped'],'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}
			else if($trade_status == 'WAIT_BUYER_PAY' ) {
				$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED ,update order_payment_status");
				$this->model_checkout_order->confirm($order_id,  $pay_code, $order_status['Complete'],'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
				$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
			}*/
	}
	
	/**
	 * 获取支付URL
	 * @param unknown $setting
	 */
	public function getPaymentURL($setting) {
	    require_once("alipay_wap/alipay.config.php");
	    require_once("alipay_wap/lib/alipay_submit.class.php");
	    
	    /**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
	    	
	    //返回格式
	    $format = "xml";
	    //必填，不需要修改
	    
	    //返回格式
	    $v = "2.0";
	    //必填，不需要修改
	    
	    //请求号
	    $req_id = date('Ymdhis');
	    //必填，须保证每次请求都是唯一
	    
	    //**req_data详细信息**
	    
	    //服务器异步通知页面路径
	    
	    $notify_url     =HTTP_SERVER . 'catalog/controller/payment/alipay_wap_callback.php';
	    $call_back_url	=HTTP_SERVER . 'index.php?route=checkout/success';
	    
	    //$notify_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/notify_url.php';
	    //需http://格式的完整路径，不允许加?id=123这类自定义参数
	    
	    //页面跳转同步通知页面路径
	    //$call_back_url = HTTP_SERVER . 'catalog/controller/payment/alipay_wap/call_back_url.php';
	    //需http://格式的完整路径，不允许加?id=123这类自定义参数
	    
	    //操作中断返回地址
	    $merchant_url = HTTP_SERVER ;
	    //用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
	    
	    //卖家支付宝帐户
	    $seller_email = $this->config->get('alipay_seller_email');
	    //必填
	    if(isset($setting['order_id'])&&$setting['order_id'])
	    {
	        $order_id = $setting['order_id'];
	    }else
	    {
	        $order_id = $this->session->data['order_id'];
	    }
	    
	    $this->load->model('checkout/order');
	    
	    //TODO:修改订单状态位未支付
	    //$this->model_checkout_order->updateOrderStatus($order_id,$this->config->get('config_order_nopay_status_id'));
	    
	    $order_info = $this->model_checkout_order->getOrder($order_id);
	    
	    //商户订单号
	    $out_trade_no = $order_id;
	    //商户网站订单系统中唯一订单号，必填
	    
	    //订单名称 商店名称+订单编号
	    $subject = $this->config->get('config_name').$this->language->get('text_order_no') . $order_id;
	    //必填
	    
    	//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
					
		$total = $payment_info['value'];
	    //付款金额
	    $total_fee = $total;
	    //必填
	    
	    //请求业务参数详细
	    $req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
	    //必填
	    
	    /************************************************************/
	    
	    //构造要请求的参数数组，无需改动
	    $para_token = array(
	        "service" => "alipay.wap.trade.create.direct",
	        "partner" => trim($this->config->get('alipay_partner')),
	        "sec_id" => trim($alipay_config['sign_type']),
	        "format"	=> $format,
	        "v"	=> $v,
	        "req_id"	=> $req_id,
	        "req_data"	=> $req_data,
	        "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
	    );
	    //自定义超时,wap
	    if(defined('ORDER_PAY_TIMEOUT_MINS')){
	        $para_token['pay_expire']=ORDER_PAY_TIMEOUT_MINS.'m';
	    }
	    
	    
	    
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		
		//获取request_token
		$request_token = $para_html_text['request_token'];
		
		
		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($this->config->get('alipay_partner')),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
	    //建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestURL($parameter, 'get');
		
	//	if(isset($this->session->data['checkout_token'])){
	//		$this->data['token'] =$this->session->data['checkout_token'];
	//	}
		
	//	$this->data['action'] = $html_text;
	    $data = array();
	    
	    $data['payment'] = $html_text;
	    return $data;	    
	}
}

?>