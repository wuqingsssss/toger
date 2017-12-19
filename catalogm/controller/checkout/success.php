<?php 
class ControllerCheckoutSuccess extends Controller { 
	public function index() { 
		ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
		/* bdpay
		 * http://test.qingniancaijun.com.cn/index.php?route=checkout/success&bank_no=&bfb_order_create_time=20150726074048&bfb_order_no=2015072610000120571110583691588&buyer_sp_username=&currency=1&extra=&fee_amount=0&input_charset=1&order_no=15072600607&pay_result=1&pay_time=20150726074113&pay_type=1&sign_method=1&sp_no=1000012057&total_amount=1&transport_amount=0&unit_amount=1&unit_count=1&version=2&sign=3b54c8babc1fd195ae2a9aee5d6480bd
		 * 
		 * alipay
		 * http://test.qingniancaijun.com.cn/index.php?route=checkout/success&out_trade_no=15072600608&request_token=requestToken&result=success&trade_no=2015072600001000790061863432&sign=ccb0b274797f50037abf0329271e416c&sign_type=MD5
		 * 
		 * */
		$chkuid=true;
		$this->log_payment->info('checkout/success：'.serialize($this->request->get));
		if (isset($this->session->data['order_id'])) {
			
			$order_id=$this->session->data['order_id'];
			
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
//			unset($this->session->data['payment_method']);
//			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
			unset($this->session->data['coupon_product_id']);
			unset($this->session->data['freepromotion']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['order_id']);
			unset($this->session->data['checkout_token']);
//			unset($this->session->data['pay_bank']);
			
		}
	    elseif(isset($this->request->get['order_hash'])&&$this->request->get['order_hash']){
			
			$order_id=http::decodeHash($this->request->get['order_hash'], 'qncj14070810');
			$this->log_payment->info('异步回调checkout/success：'.$order_id.'::'.$this->request->get['order_hash']);
			
			$chkuid=false;
			
		}elseif(isset($this->request->get['order_no'])&&$this->request->get['order_no']){
			
			$order_id=$this->request->get['order_no'];
			
		}	
		elseif(isset($this->request->get['out_trade_no'])&&$this->request->get['out_trade_no']){
				
			$order_id=$this->request->get['out_trade_no'];
				
		}
		else 
		{
			$this->redirect($this->url->link('account/order', '', 'SSL'));
		}
	

		if( isset($this->session->data['salesman'])){
		    unset($this->session->data['salesman']);
		}
		if( isset($this->session->data['discount'])){
		    unset($this->session->data['discount']);
		}
		
		
		$this->load_language('checkout/success');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		// 页面头
		$header_setting =  array('left'    =>  array( href => $this->url->link('common/home'),
		    text => $this->language->get("header_left")),
		    'center'  =>  array( href => "#",
		        text => $this->document->getTitle()),
		    'name'    =>  $this->document->getTitle()
		);
			
		$this->data['header'] = $this->getChild('module/header', $header_setting);
		
		$this->load->model('account/order');
		$order_info = $this->model_account_order->getOrder($order_id,$chkuid);
		$this->data['order_info']=$order_info;
		
		$this->log_sys->info('$this->session->data'.serialize($this->session->data));
		$this->log_sys->info($order_id);
	
		// 如果是拼团，跳转到拼团页面
		if($order_info['order_type'] == '100'){ //拼团
		    $this->redirect($this->url->link('group/group/info&cid='.$order_info['addition_info'],'','SSL'));
		}
		
		$this->data['breadcrumbs'] = array(); 

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 
		
      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);	
					
      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/success'),
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
    			$text = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'));
    			// 每周三显示抽奖
	    		/*轮盘关闭 20150401
	    		 * if(date('w') == 3){
	    		    $text .= sprintf($this->language->get('text_campaign'),$this->url->link('campaign/lottery','', 'SSL'));
	    		    $this->session->data["campaign"] = "ON";
	    		}*/
    			 //领取菜票
    			//$text .= sprintf($this->language->get('text_coupon'),$this->url->link('promotion/coupon','', 'SSL'));
    			$this->data['text_message'] = $text;

		} else {
    		$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
	
		
		$this->data['template']=$this->config->get('config_template');
    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/success.tpl';
		} else {
			$this->template = 'default/template/checkout/success.tpl';
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
  	
  	public function payment(){
  		$this->load_language('checkout/success');
  		
  		if(isset($this->request->get['order_id'])){
  			$order_id=$this->request->get['order_id'];
  		}else if(isset($this->session->data['order_id'])){
  			$order_id=$this->session->data['order_id'];
  		}else{
  			$order_id=0;
  		}
  		
  		
  		$this->load->model('account/order');
			
		$order_info = $this->model_account_order->getOrder($order_id);
		
  		$this->document->setTitle($this->language->get('heading_title'));
  		
  		if(!$order_info){
  			$this->redirect($this->url->link('error/not_found'));
  		}
  		// 订单是否超时判断，如果超时提示错误
  		if(time()>$order_info['expire_time']){
  			;
  		}
  		
  		$this->data['order_id']=$order_id;
  		
  		$this->load->model('mail/order');
  		$this->model_mail_order->send($order_id);
  		
  		
  		$this->load->model('sms/order');
  	//	$this->model_sms_order->sendNewOrder($order_id);
  		
  		$this->data['total']=$this->currency->format($order_info['total']);
  		
  		$this->data['payment'] = $this->getChild('payment/' . $order_info['payment_code'].'/reorder');
  		
  		$this->data['payment_code']=$order_info['payment_code'];
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success_payment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/success_payment.tpl';
		} else {
			$this->template = 'default/template/checkout/success_payment.tpl';
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
  	
  	public function do_payment(){
  		if(isset($this->request->get['order_id'])){
  			$order_id=$this->request->get['order_id'];
  		}else{
  			$order_id=0;
  		}
  		
  		$this->data['order_id']=$order_id;
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/do_payment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/do_payment.tpl';
		} else {
			$this->template = 'default/template/checkout/do_payment.tpl';
		}
		
		$this->response->setOutput($this->render());
  	}
}
?>