<?php
class ControllerPaymentWxpay extends Controller {
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

//		$order_info = $this->model_checkout_order->getOrder($order_id);

		$currency_code ='CNY';
		$item_name = $this->config->get('config_title');
//		$first_name = $order_info['payment_firstname'];
//		$last_name = $order_info['payment_lastname'];

		$appid = $this->config->get('wxpay_appid');
		$partnerid = $this->config->get('wxpay_partnerid');
		$partnerkey = $this->config->get('wxpay_partnerkey');
		$apikey = $this->config->get('wxpay_apikey');
		
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'wxpay');
		$total = $payment_info['value'];  

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount, 2, '.', '');	
	
		$notify_url = HTTPS_SERVER . 'catalog/controller/payment/wxpay_notify.php';
		$return_url	= HTTPS_SERVER . 'index.php?route=checkout/success';
		
		$data = array(
			'appid'      => $appid,
			'partnerid'  => $partnerid,
			'partnerkey' => $partnerkey,
			'apikey'     => $apikey,
			'order_id'   => $order_id,
			'total_fee'  => $amount * 100, // 单位为分
			'store'      => $item_name,  
			'notify_url' => $notify_url, 
			'return'     => $return_url
		);

		$action = $this->pay($data);
		
		if(isset($this->session->data['checkout_token'])) {
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/wxpay.tpl';
		} else {
			$this->template = 'default/template/payment/wxpay.tpl';
		}

		$this->render();
	}
	
	/**
	 * 
	 * @param unknown $setting
	 */
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
		$this->data['custom'] = $encryption->encrypt($order_id);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->model('checkout/order');

//		$order_id = $this->session->data['order_id'];

//		$order_info = $this->model_checkout_order->getOrder($order_id);

		$currency_code ='CNY';
		$item_name = $this->config->get('config_title');
//		$first_name = $order_info['payment_firstname'];
//		$last_name = $order_info['payment_lastname'];

		$appid = $this->config->get('wxpay_appid');
		$partnerid = $this->config->get('wxpay_partnerid');
		$partnerkey = $this->config->get('wxpay_partnerkey');
		$apikey = $this->config->get('wxpay_apikey');

	//	$total = $order_info['total'];
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'wxpay');
		$total = $payment_info['value'];

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount, 2, '.', '');

		$notify_url = HTTPS_SERVER . 'catalog/controller/payment/wxpay_notify.php';
		$return_url	= HTTPS_SERVER . 'index.php?route=checkout/success';

		$data = array(
			'appid'      => $appid,
			'partnerid'  => $partnerid,
			'partnerkey' => $partnerkey,
			'apikey'     => $apikey,
			'order_id'   => $order_id,
			'total_fee'  => $amount * 100, // 单位为分
			'store'      => $item_name,
			'notify_url' => $notify_url,
			'return'     => $return_url
		);

		$this->log_payment->info($data);
		
		$action = $this->pay($data);

		if(isset($this->session->data['checkout_token'])) {
			$this->data['token'] =$this->session->data['checkout_token'];
		}
		$this->data['reorder'] = true;
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/wxpay.tpl';
		} else {
			$this->template = 'default/template/payment/wxpay.tpl';
		}

		$this->render();
	}

	/**
	 * 
	 * @param unknown $data
	 * @return string
	 */
	private function pay($data=array()) {
		//请求的URL
		//return HTTPS_SERVER . 'catalog/controller/payment/wxpay_class/pay.php?order_id=' . $data['order_id'];
		return HTTPS_SERVER . 'index.php?route=payment/wxpay/goToOauth&order_id=' . $data['order_id'];
	}

	/**
	 * 
	 */
	// step 1
	public function goToOauth() {
		require_once("wxpay_class/WxPayHelper.php");

		$appid = $this->config->get('wxpay_appid');
		$appsecret = $this->config->get('wxpay_appsecret');
		$partnerid = $this->config->get('wxpay_partnerid');
		$apikey = $this->config->get('wxpay_apikey');

		//使用jsapi接口
		$jsApi = new JsApi($appid, $appsecret, $partnerid, $apikey);

		//=========步骤1：网页授权获取用户openid============
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(HTTPS_SERVER . 'index.php?route=payment/wxpay/afterOauth&order_id=' . $_GET['order_id']);
		$this->log_payment->info('wx pay auth url: '.$url,'wxpay.log');
		Header("Location: $url");
	}

	// step 2
	public function afterOauth() {
		require_once("wxpay_class/WxPayHelper.php");
		//获取code码，以获取openid
	    $code = $_GET['code'];

		$appid = $this->config->get('wxpay_appid');
		$appsecret = $this->config->get('wxpay_appsecret');
		$partnerid = $this->config->get('wxpay_partnerid');
		$apikey = $this->config->get('wxpay_apikey');

	    $jsApi = new JsApi($appid, $appsecret, $partnerid, $apikey);
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenid();

		// order info
		$this->load->model('checkout/order');

		$order_id = $_GET['order_id'];

	//	$order_info = $this->model_checkout_order->getOrder($order_id);

		$currency_code ='CNY';
		$item_name = $this->config->get('config_title');
	//	$first_name = $order_info['payment_firstname'];
	//	$last_name = $order_info['payment_lastname'];
		
		$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'wxpay');
		
		$total = $payment_info['value'];  

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount, 2, '.', '');	
	
		$notify_url = HTTPS_SERVER . '/catalog/controller/payment/wxpay_notify.php';
		$return_url	= HTTPS_SERVER . 'index.php?route=checkout/success';

		// order info end

		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder($appid, $appsecret, $partnerid, $apikey);
		
		//设置统一支付接口参数
		//设置必填参数
		//appid已填,商户无需重复填写
		//mch_id已填,商户无需重复填写
		//noncestr已填,商户无需重复填写
		//spbill_create_ip已填,商户无需重复填写
		//sign已填,商户无需重复填写
		$unifiedOrder->setParameter("openid", "$openid");
		$unifiedOrder->setParameter("body", $item_name);//商品描述
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no", "$order_id");//商户订单号 
		$unifiedOrder->setParameter("total_fee", $amount * 100);//总金额
		$unifiedOrder->setParameter("notify_url", $notify_url);//通知地址 
		$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		$unifiedOrder->setParameter("time_expire",date('YmdHis',(time()+ORDER_PAY_TIMEOUT_MINS*60)));//交易结束时间
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
		//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
		//$unifiedOrder->setParameter("openid","XXXX");//用户标识
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);

		$this->data['jsApiParameters'] = $jsApi->getParameters();
		$this->data['return_url'] = $return_url;
		$this->data['fail_url'] = HTTPS_SERVER . 'index.php?route=payment/wxpay/failure';


		//$this->log_payment->info($this->data);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay_confirm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/wxpay_confirm.tpl';
        } else {
			$this->template = 'default/template/payment/wxpay_confirm.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	public function failure2() {
		/*$this->language->load('payment/wxpay');
	
		$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$this->data['base'] = HTTP_SERVER;
		} else {
			$this->data['base'] = HTTPS_SERVER;
		}
	
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
	
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		//$this->data['text_response'] = $this->language->get('text_response');
		//$this->data['text_success'] = $this->language->get('text_success');
		//$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$this->data['text_failure'] = $this->language->get('text_failure');
		$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout', '', 'SSL'));

		$this->data['continue'] = $this->url->link('checkout/cart');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay_failure.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/wxpay_failure.tpl';
		} else {
			$this->template = 'default/template/payment/wxpay_failure.tpl';
		}
		
		$this->response->setOutput($this->render());*/

	}

	/**
	 *  支付出错跳转
	 */
	public function failure() { 
		$this->load_language('payment/wxpay');
		$this->log_sys->trace();

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['coupon_product_id']);
		unset($this->session->data['freepromotion']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);
		unset($this->session->data['checkout_token']);
		if( isset($this->session->data['salesman'])){
		    unset($this->session->data['salesman']);
		}
		if( isset($this->session->data['discount'])){
		    unset($this->session->data['discount']);
		}
		
		if(isset($this->request->get['order_no'])&&$this->request->get['order_no']){
		     
		    $order_id=$this->request->get['order_no'];
		     
		}  
		elseif(isset($this->request->get['out_trade_no'])&&$this->request->get['out_trade_no']){
		
		    $order_id=$this->request->get['out_trade_no'];
		
		}
		else
	    {
	        $this->redirect($this->url->link('common/home', '', 'SSL'));
	    }
	    
	    
	    
	    $this->document->setTitle($this->language->get('heading_title'));
	   
	    // 页面头
	    $header_setting =  array('left'    =>  array( href => $this->url->link('account/order'),
	        text => $this->language->get("header_left")),
	        'center'  =>  array( href => "index.php?route=checkout/cart",
	            text => $this->document->getTitle()),
	        'name'    =>  $this->document->getTitle()
	    );
	    $this->data['header'] = $this->getChild('module/header', $header_setting);
	 		
		$this->load->model('account/order');
		$order_info = $this->model_account_order->getOrder($order_id);
		$this->data['order_info']=$order_info;
		
		$this->log_sys->info(print_r($this->session->data,1));
		$this->log_sys->info($order_id);
		
	
		$this->document->setTitle($this->language->get('heading_title'));
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
    		$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/order', '', 'SSL'),  $this->url->link('information/contact'));
		} else {
    		$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
		
    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay_failure.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/wxpay_failure.tpl';
		} else {
			$this->template = 'default/template/payment/wxpay_failure.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer35',
			'common/header35'			
		);
				
		$this->response->setOutput($this->render());
  	}

  	
  	
  	/**
  	 * 微信通知接口
  	 */
	public function notify() {

        $this->log_payment->info('wxpay.notify.start');
		require_once("wxpay_class/WxPayHelper.php");

		$appid = $this->config->get('wxpay_appid');
		$appsecret = $this->config->get('wxpay_appsecret');
		$partnerid = $this->config->get('wxpay_partnerid');
		$apikey = $this->config->get('wxpay_apikey');

		$notify = new Notify($appid, $appsecret, $partnerid, $apikey);

		//存储微信的回调
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

        $this->log_payment->info('wxpay.notify.xml=' . $xml);
		$notify->saveData($xml);
		
		//验证签名，并回应微信。
		//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if($notify->checkSign() == TRUE) {
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
			} elseif ($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
			} else { 
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
				$this->load->model('checkout/order');
				$order_id = $notify->data["out_trade_no"];
				$pay_code = 'wxpay';
				//金额,以分为单位
				$total_fee = $notify->data["total_fee"];
				$payment_info = $this->model_checkout_order->getOrderPayment($order_id, $pay_code);
				
				$order_info = $this->model_checkout_order->getOrder($order_id);
				if ($payment_info && ((int)($payment_info['value'] * 100+0.05) == (int)$total_fee))  {
					$order_status_id = $order_info["order_status_id"];
					//交易单号
					$transaction_id = $notify->data["transaction_id"];
					
					//------------------------------
					//处理业务开始
					//------------------------------
						
					if($payment_info['status']== '0' )
					{
					    $this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $transaction_id);
					    //$this->model_checkout_order->confirm($order_id, $this->config->get('wxpay_order_status_id'),'wxpay::trade_no:'.$transaction_id,array('trade_no'=>$transaction_id));
					    $this->log_payment->info('wxpay.notify[order_id= ' . $order_id . ';total=' . (int)($order_info['total']*100) . '],$trade_no:'.$transaction_id.'状态更新');
					}
					else 
					{
					    $this->log_payment->info('wxpay.notify[order_id= ' . $order_id . ';total=' . (int)($order_info['total']*100) . '],$trade_no:'.$transaction_id .'重复通知');
					
					}
						
					//注意交易单不要重复处理
					//注意判断返回金额
					
					//------------------------------
					//处理业务完毕
					//------------------------------
					$notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
					$this->log_payment->info('wxpay.notify[return_code:SUCCESS]');
				} else {
					$notify->setReturnParameter("return_code", "FAIL");//返回状态码
					$notify->setReturnParameter("return_msg", "订单失效或金额错误");//返回信息
					$this->log_payment->info('wxpay.notify[order_id= ' . $order_id . ';total=' .(int)($order_info['total']*100) . ']; (int)($order_info[total] * 100) == (int)$total_fee=('. (int)($order_info['total'] * 100) .'=='. (int)$total_fee.').;serialize(orderinfo)=' . (serialize($order_info)) . '订单失效或金额错误');
					
				}
			}

		} else {
			$notify->setReturnParameter("return_code", "FAIL");//返回状态码
			$notify->setReturnParameter("return_msg", "签名失败");//返回信息
			$this->log_payment->info('wxpay.notify[order_id= ' . $order_id . ';total=' . (int)($order_info['total']*100) . '].order_status_id=' . $order_status_id . '签名失败' );
		}
		$returnXml = $notify->returnXml();
		$this->log_payment->info('wxpay.notify.returnXml='.$returnXml);
		$this->log_payment->info('wxpay.notify.end');
		echo $returnXml;
	}

	public function alarm() {
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$this->log_payment->info('wxpay.alarm.xml=' . $xml);
		
	}
	
	/**
	 * 获取支付URL
	 * @param unknown $setting
	 * @return string
	 */
	public function getPaymentURL($setting) {
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
	
	    $currency_code ='CNY';
	    $item_name = $this->config->get('config_title');
	    $first_name = $order_info['payment_firstname'];
	    $last_name = $order_info['payment_lastname'];
	
	    $appid = $this->config->get('wxpay_appid');
	    $partnerid = $this->config->get('wxpay_partnerid');
	    $partnerkey = $this->config->get('wxpay_partnerkey');
	    $apikey = $this->config->get('wxpay_apikey');
	
	    $payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'wxpay');
	    $total = $payment_info['value'];
	
	    $currency_value = $this->currency->getValue($currency_code);
	    $amount = $total * $currency_value;
	    $amount = number_format($amount, 2, '.', '');
	
	    $notify_url = HTTPS_SERVER . 'catalog/controller/payment/wxpay_notify.php';
	    $return_url	= HTTPS_SERVER . 'index.php?route=checkout/success';
	
	    $data = array(
	        'appid'      => $appid,
	        'partnerid'  => $partnerid,
	        'partnerkey' => $partnerkey,
	        'apikey'     => $apikey,
	        'order_id'   => $order_id,
	        'total_fee'  => $amount * 100, // 单位为分
	        'store'      => $item_name,
	        'notify_url' => $notify_url,
	        'return'     => $return_url
	    );
	
	    $action = $this->pay($data);
	   
	    $ret = array();
	    $ret['redirect']  = $action;
	    return $ret;   
	}

}

?>