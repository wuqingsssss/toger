<?php 
class ControllerCheckoutSuccess extends Controller { 
	public function index() { 
		
		//ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
		/* bdpay
		 * http://test.qingniancaijun.com.cn/index.php?route=checkout/success&bank_no=&bfb_order_create_time=20150726074048&bfb_order_no=2015072610000120571110583691588&buyer_sp_username=&currency=1&extra=&fee_amount=0&input_charset=1&order_no=15072600607&pay_result=1&pay_time=20150726074113&pay_type=1&sign_method=1&sp_no=1000012057&total_amount=1&transport_amount=0&unit_amount=1&unit_count=1&version=2&sign=3b54c8babc1fd195ae2a9aee5d6480bd
		 * 
		 * alipay
		 * http://test.qingniancaijun.com.cn/index.php?route=checkout/success&out_trade_no=15072600608&request_token=requestToken&result=success&trade_no=2015072600001000790061863432&sign=ccb0b274797f50037abf0329271e416c&sign_type=MD5
		 * 
		 * */

		if (isset($this->session->data['order_id'])) {
			
			
			
			
			$order_id=$this->session->data['order_id'];
			
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
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
			unset($this->session->data['pay_bank']);
			
		} elseif(isset($this->request->get['order_id'])&&$this->request->get['order_id']){
			
			$order_id=$this->request->get['order_id'];

		}elseif(isset($this->request->get['order_no'])&&$this->request->get['order_no']){
			
			$order_id=$this->request->get['order_no'];
			
		}	
		elseif(isset($this->request->get['out_trade_no'])&&$this->request->get['out_trade_no']){
				
			$order_id=$this->request->get['out_trade_no'];
				
		}
		
		
		
		
		$this->log_sys->info(print_r($this->session->data,1));
		$this->log_sys->info($order_id);
		
		
		if( isset($this->session->data['salesman'])){
		    unset($this->session->data['salesman']);
		}
		if( isset($this->session->data['discount'])){
		    unset($this->session->data['discount']);
		}
									   
		$this->load_language('checkout/success');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
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
		
		
		/* 判断是否由微信内置浏览器浏览*/
		/*
		if($order_id){
			$this->load->model('checkout/order');
			$order_info=$this->model_checkout_order->getOrder($order_id);
			$this->log_sys->info(print_r($order_info,1));
			
			$this->data['order_id']=$order_id;
			if($order_info['order_status_id']=='2'){//限定只能是已付款状态才可以生活优惠券	
		     $detect = new Mobile_Detect();
		if($detect->is_weixin_browser()||1){
			require_once(DIR_SYSTEM . 'helper/WeixinHelp.php');//加载微信接口文件
			$wx = new WeixinHelp($this->registry);
			$wx_appid=$this->config->get('wxpay_appid');
			$wx_appsecret=$this->config->get('wxpay_appsecret');
			$this->data['wx_appid']=$wx_appid;
		
			$access_token=$wx->get_weixin_access_token($wx_appid,$wx_appsecret,true);
			//$userlist=$wx->hget_all_weixin_users($access_token);
		
			//  print_r($wx->hget_weixin_userinfo($userlist['data']['openid'][0],$access_token));
		
			$ticket      =$wx->hget_jsapi_ticket($access_token);
								
			$signPackage['jsapi_ticket']    =$ticket['ticket'];
			$signPackage['noncestr']        =str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			$signPackage['timestamp']       =time();
			$signPackage['curl']=htmlspecialchars_decode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//
			$string1="jsapi_ticket=$signPackage[jsapi_ticket]&noncestr=$signPackage[noncestr]&timestamp=$signPackage[timestamp]&url=$signPackage[curl]";
			$signPackage['signature']=sha1($string1);
			$this->data['assign']=$signPackage;
			
			//$couponcode='code001';//获取优惠劵代码
			$this->load->model('account/coupon');
			
			$data['order_id']=$order_id;
			
			if(! $coupon_code=$this->model_account_coupon->existCoupon($data))
			{
			$coupondata[]=array(
				 'name'=>'满18元减5元',
				 'type'=>'F',
				 'discount'=>'5',
				 'total'=>'18'
					
			);
			$coupondata[]=array(
					'name'=>'满29元减8元',
					'type'=>'F',
					'discount'=>'8',
					'total'=>'29'
			);
			$coupondata[]=array(
					'name'=>'满39元减15元',
					'type'=>'F',
					'discount'=>'15',
					'total'=>'39'
			);
			$coupondata[]=array(
					'name'=>'满50元减20元',
					'type'=>'F',
					'discount'=>'20',
					'total'=>'50'
			);
			
			$scn=mt_rand(0, 3);
			
		  	
			
		
			$data['name']=$coupondata[$scn]['name'];
			$data['type']=$coupondata[$scn]['type'];
			$data['discount']=$coupondata[$scn]['discount'];
			$data['total']=$coupondata[$scn]['total'];
			
			
			$data['date_start']=date('Y-m-d', time());
			$data['date_end']=date('Y-m-d', time()+3600*24*3);
	
			$data['uses_total']='10';
			$data['uses_customer']='1';
		    $data['usage']='活动规则
1、登陆青年菜君账号到 ”个人中心—特权 券“即可看到优惠劵
2、优惠券有效期请到个人中心查看
3、优惠券有效期3天
4、特惠套餐不享受优惠劵
5、本活动最终解释权归青年菜君所有';
			
			$coupon_code=$this->model_account_coupon->createCoupon($data);
			}
			$this->log_sys->info($coupon_code);
			
			
			$this->data['couponcode']=$coupon_code;
	
			//print_r($_SERVER);
			//print_r($signPackage);
		}
		}
		}*/
		
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
			'common/footer',
			'common/header'			
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
			'common/footer',
			'common/header'			
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