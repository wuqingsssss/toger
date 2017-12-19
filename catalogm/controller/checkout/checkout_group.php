<?php
class ControllerCheckoutCheckoutGroup extends Controller {
	private $direct_payments= array('cod','cash','cheque','free_checkout','bank_transfer','balance');
	
	 
	/**
	 * 
	 */
	public function index() {		
	    $this->load_language('checkout/checkout_group');
	    // $this->log_sys->trace("");
		if (!$this->customer->isLogged()) {
			//$this->session->data['redirect'] = $this->url->link('checkout/checkout_group&groupbuy_id='.$groupbuy_id,'', 'SSL');
			$this->setback();
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}		
		
		if(!$this->cart->getGoods4Group()){
		    $this->redirect($this->url->link('group/group', '', 'SSL'));
		}
		else {
		    $groupbuy_id = $this->cart->getGoods4Group();
		    $customer_id = $this->customer->getId();
		}
		 
		$groupbuy_id = $this->cart->getGoods4Group();
		$this->data['groupbuy_id']=  $groupbuy_id;
		
		
		// 检查团购合理性
		if (!$this->checkGroupbuyStock($groupbuy_id, $customer_id)) {
			$this->redirect($this->url->link('group/group/info'));
		}	 
		 
		if(!isset($this->session->data['checkout_token'])){
			$this->session->data['checkout_token'] = md5(mt_rand());
		}
		$this->data['token'] = $this->session->data['checkout_token'];
		 
		

		$this->document->setTitle($this->language->get('heading_title'));
		
		// 页面头
		$header_setting =  array('left'    =>  array( href => $this->url->link('group/group'),
		                                            text => $this->language->get("header_left")),
		                         'center'  =>  array( href => "#",
		                                            text => $this->document->getTitle()),
		                         'name'    =>  $this->document->getTitle()
		                         );
			
		$this->data['header'] = $this->getChild('module/header', $header_setting);
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
          	 'text'      => $this->language->get('text_home'),
			 'href'      => $this->url->link('common/home'),
        	 'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
	       'text'      => $this->language->get('text_groupbuy'),
	       'href'      => $this->url->link('group/group'),
	       'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
	       'text'      => $this->language->get('heading_title'),
	       'href'      => $this->url->link('checkout/checkout_group', '', 'SSL'),
	       'separator' => $this->language->get('text_separator')
		);


		//$this->data['groupbuy'] = $this->url->link('checkout/groupbuy');
		//$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = '#TBD';

		// 获取当前地址
		$this->data['address']=$this->shipping_address();
		// 如无法获得地址，暂时地址选择页面
        if(!$this->data['address'] ){
            $this->setback();
            $this->redirect($this->url->link('checkout/shipping_method', '', 'SSL'));
        }
      
        // 获取支付方式
		$this->data['payment_methods']= $this->getChild('checkout/payment');
	
	
		// 加载发票子模块	
		if($this->config->get('config_order_invoice_status')) {
			$this->data['modules'][] = $this->getChild('total/invoice');
		}

		// 支付明细
		$this->data['checkout_detail'] = $this->getChild('checkout/payment/update', true);
		
		// 支付
		$this->data['order_pay'] = $this->getChild('checkout/payment/payment',  array('groupbuy_id' => $groupbuy_id));
		
		//$this->shipping_method();
		// 商品清单
		$this->data['order_confirm']=$this->confirm();

			
		$this->load->service('baidu/point');
		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout_group.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/checkout_group.tpl';
		} else {
			$this->template = 'default/template/checkout/checkout_group.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer35',
			'common/headersimple'
		);
		
		$this->response->setNocache(); //这个no-store加了之后，Firefox下有效
		$this->response->setOutput($this->render());
	}
	
	/**
	 * 更换配送地址
	 */
	public function changeShippingMethod(){
	    $this->setback(true, $this->url->link('checkout/checkout_group','','SSL'));

	    $this->redirect($this->url->link('checkout/shipping_method', '', 'SSL'));
	}


	/**
	 * 更新取菜时间
	 */
     public function updateAdditionalDate(){
     	
     	if (isset($this->request->post['date']) && $this->request->post['date']) {
     		$current_time = $this->request->post['date'];
     		//用session记录选择的菜品提菜时间
     		
     		if(strtotime(date("Y-m-d",strtotime($current_time)))>time())
     		{
     		    $this->cart->setAdditionalDate4Group($current_time);
     		    $json['error']=0;
     		    $json['current_time']=$current_time;
     		}
     		else 
     		{
     		    $json['error']=1;
     		    $json['message']='选择的取菜时间有误，请重新选择';
     		}
     	} 
     }

     /**
      * 取得当前配送地址设置
      * @return string
      */
      public function shipping_address() {
          
		$this->load->model('account/address');
		$this->data['type'] = 'shipping';
	
		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
			$this->log_sys->debug('IlexDebug:: session   : '.$this->session->data['shipping_address_id']);
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
			$this->session->data['shipping_address_id']=$this->data['address_id'];
			$this->log_sys->debug('shipping_address:: default address_id   : '.$this->session->data['shipping_address_id']);
		}

		//$this->data['addresses'] = $this->model_account_address->getAddresses();
		$address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			
		return $address;
	}
	 
	
	/*
	private function shipping_new_address() {
		$this->load_language('checkout/checkout');
		 
		$this->load->model('account/address');
		 
		$this->data['type'] = 'shipping';
		 
		$this->data['country_id'] = $this->config->get('config_country_id');
			
		$this->load->model('localisation/country');
			
		$this->data['countries'] = $this->model_localisation_country->getCountries();
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address_new.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/address_new.tpl';
		} else {
			$this->template = 'default/template/checkout/address_new.tpl';
		}
		 
		return $this->render();
	}*/
	 
	/**
	 * 设置取菜日期
	 * @param unknown $json
	 * @return Ambigous <multitype:NULL string , multitype:>
	 */

	private function set_supply_periods($json){
	    $additionaldate=array();
	    //获取商品信息
	    $this->load->model('sale/group_buy');
	    // 获取拼团信息
	    $groupbuy_info = $this->model_sale_group_buy->getGroupbuyInfo($this->cart->getGoods4Group());

	    $period['picktime'][]= $groupbuy_info['shipping_date'];

	    $time['min']=17;
	    $time['max']=19;
	    	
	    $deliverys=$this->config->get('delivery_express');
	    foreach($deliverys as $item)
	    	$deliverys[$item['code']]=$item;
	    
	    $this->load->model('catalog/pointdelivery');
	    $delivery=$this->model_catalog_pointdelivery->getDeliveryByName($this->data['address']['shipping_code'],$this->data['address']['shipping_data']);
	    	
	    //print_r($deliverys[$this->data['address']['shipping_code']]);
	    	
	    //此处从数据库配重中读取三方物流和配送站的配送时间设置 站点优先于三方物流优先于系统
	    if(!$delivery['business_hour'])$delivery['business_hour']=$deliverys[$this->data['address']['shipping_code']]['business_hour'];
	    if($delivery['business_hour']){
	    	$business_hours=json_decode(htmlspecialchars_decode($delivery['business_hour']),1);}
	    	else {//系统默认配置详细结构见后台设置
	    		$business_hours=array(array('start'=>'17:00','end'=>'20:00','setup'=>60));
	    }

	   if($business_hours){
	    		$time['min']=explode(':', $business_hours[0]['start'])[0];
	    		$time['max']=explode(':', $business_hours[0]['end'])[0];
	   }
	    	
	  foreach($period['picktime'] as $key=>$date)
	    {
	    		$this->data['dates'][$date]['title']=($date==date("Y-m-d")?'今天':($date==date("Y-m-d",strtotime("+1 day"))?'明天':($date==date("Y-m-d",strtotime("+2 day"))?'后天':
	    				(strtotime('+1 week last monday')<strtotime($date)?$date:cnWeek($date))
	    		)));
	    
	    		$times=array();
	    
	    		foreach($business_hours as $hours){
	    			for($i=(strtotime($date.' '.$hours['start'])+$hours['setup']*60);$i<=strtotime($date.' '.$hours['end']);$i=$i+(int)$hours['setup']*60)
	    			{
	    				if($i>time())
	    					$times[date('Y-m-d H:s',$i)]=date('H:s',$i-$hours['setup']*60).'-'.date('H:s',$i);
	    			}}
	    			$this->data['dates'][$date]['times']=$times;
	    	}
	    
	    
	    
	    
		$additionaldate[0] = $groupbuy_info['shipping_date']; 
	    $additionaldate[1] = '17:00';
	    $this->cart->setAdditionalDate4Group($additionaldate[0].' '.$additionaldate[1]);
		return $additionaldate;
	}
	
	/**
	 *  获取配送信息
	 */
	public function shipping_method() {
		$this->load_language('checkout/checkout');
		
		$additionaldate=$this->set_supply_periods($this->data);

		
		if (isset($this->session->data['shipping_address_id'])){
				
			$this->data['shipping_address_id'] = $this->session->data['shipping_address_id'];
				
		}
		elseif($this->config->get('pickupaddr_status')&&isset($this->session->data['shipping_point_id']))
		{
			$this->data['shipping_point_id'] = $this->session->data['shipping_point_id'];
			
		}
		elseif($address_id=$this->customer->getAddressId())
		{
			$this->data['shipping_address_id']=$address_id;
			$this->session->data['shipping_address_id']=$this->data['shipping_address_id'];
			$this->data['shipping_address_id'] = $this->session->data['shipping_address_id'];
		}
		elseif($this->config->get('pickupaddr_status')&&$shipping_point_id= $this->customer->getShippingPointId())
		{
			$this->session->data['shipping_point_id']=$this->data['shipping_point_id'];
			$this->data['shipping_point_id'] = $this->session->data['shipping_point_id'];
		} 
		
		if($this->config->get('pickupaddr_status')&&$this->data['shipping_point_id']>0){
			
			$this->data['select_date']=$additionaldate[0];
		}
		elseif($this->data['shipping_address_id']>0)
		{
			$this->data['select_date']=$additionaldate[0].' '.$additionaldate[1];
	
		}
		else 
		{  
			$this->data['shipping_point_id']=0;
		    $this->data['shipping_address_id']=0;
			$this->data['select_date']=$additionaldate[0].' '.$additionaldate[1];
		}
		
		/* 可用的收获地址*/
		$this->load->model('account/address');	
		$this->load->model('catalog/point');
		
		$this->data['addresses'] = $this->model_account_address->getAddresses('meishisong');


		if($this->config->get('pickupaddr_status')&&$this->data['shipping_point_id']>0)
		{	
			$point_info=$this->model_catalog_point->getPoint($this->data['shipping_point_id']);
			if($point_info){
			$this->data['shipping_point_info']=$point_info;
			$this->session->data['shipping_method']['title']='自提:'.$point_info['name'].'['.$point_info['address'].']';
			}
			else
			{
				$this->data['shipping_point_id']=0;
				$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
				
			}
		}
		elseif($this->data['shipping_address_id']>0){//如果有宅配地址信息
			$shipping_address = $this->model_account_address->getAddress($this->data['shipping_address_id']);
			if($shipping_address){$this->data['shipping_address']=$shipping_address;	
			$this->session->data['shipping_method']['title']='宅配:'.$shipping_address['address_1'].$shipping_address['address_2'];}
			else {
				
				$this->data['shipping_address_id']=0;
				$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
				
			}
		}
		else 
		{
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		}
		
	}
	
	public  function shipping_method_load(){

		$json=array();
	
	  	if($this->request->server['REQUEST_METHOD'] == 'POST'){//开始处理post请求
	  		$this->load_language('checkout/checkout');
	  		$this->load->model('account/address');
	  		$this->load->model('catalog/point');
	  		
	  
	  		if($this->config->get('pickupaddr_status')&&isset($this->request->post['point_id'])&&!empty($this->request->post['point_id']))
	  		{
	  			$point_info=$this->model_catalog_point->getPointByCode($this->request->post['point_id']);
	  		
	  			if($point_info){
	  			    $this->session->data['shipping_point_id']=$point_info['point_id'];
	  			      unset($this->session->data['shipping_address_id']);
	  			}
	
	  		}elseif ($this->request->post['address_id'] == '0') {
	  			  if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
	  				$json['error_warning']['firstname'] = $this->language->get('error_firstname');
	  				$json['status']=1;
	  			  }
	  			  
	  			  
	  			if ((strlen(utf8_decode($this->request->post['address_1'])) < 1) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
	  				$json['error_warning']['address_1'] = $this->language->get('error_address_1');
	  				$json['status']=2;
	  			}
	  			$this->request->post['address_1']=$this->request->clean_address($this->request->post['address_1']);
	  			$this->request->post['address_2']=$this->request->clean_address($this->request->post['address_2']);
	  			

	  			if ($this->request->is_address($this->request->post['address_1'])==false) {
	  				$json['error_warning']['address_1'] = '地址格式不正确，只能填写汉字字母下划线（）【】#';
	  				$json['status']=2;
	  			}
	  			/*
	  			if ((strlen(utf8_decode($this->request->post['mobile'])) < 1)) {
	  				$json['error_warning']['mobile']  = $this->language->get('error_mobile');
	  				$json['status']=3;
	  			}else if(strlen(utf8_decode($this->request->post['mobile'])) != 11){
	  				$json['error_warning']['mobile'] =$this->language->get('error_mobile_length');
	  				$json['status']=4;
	  			}*/
	  			
	  			if(!$this->request->is_phone(utf8_decode($this->request->post['mobile']))){//只允许输入手机号
	  				
	  				$json['error_warning']['mobile'] =$this->language->get('error_mobile_length').utf8_decode($this->request->post['mobile']);
	  				$json['status']=4;
	  			}
	  			  			  		
	  			if (!$json) {
					
	  				if(!$this->model_account_address->existAddress($this->request->post)){
	
	  					$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);
	  					$this->customer->setAddress($this->session->data['shipping_address_id']);	
	  				
	  				}
	  				else
	  				{	$json['status']=5;
	  					$json['error_warning']['address_1'] ='该地址已经存在';
	  				}
	  			}
	  			
	  		}
	  		elseif($this->request->post['address_id']>0)
	  		{
	  			if(isset($this->request->post['act'])&&$this->request->post['act']=='delete'){
	  				   $this->model_account_address->deleteAddress($this->request->post['address_id']);
	  			}
	  			else {//更新
	  				$this->session->data['shipping_address_id'] = $this->request->post['address_id'];
	  				$this->customer->setAddress($this->session->data['shipping_address_id']);
	  			}
	  			
	  		}	
	  	}  	
	  	
	  
	  	if(!$json){
	  	$json['status']=0;
	    $json['data']['shipping_method']=$this->shipping_method();
	    $json['data']['shipping_address_id']=$this->session->data['shipping_address_id'];
	  	}
	  	
	     $this->response->setOutput(json_encode($json));
	}

	public function default_shipping_method() {
		$this->load_language('checkout/checkout');
			
		$shipping_address['country_id']=$this->config->get('config_country_id');
		$shipping_address['zone_id']=$this->config->get('config_zone_id');
		$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);

		$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
			
		$quote_data = array();
			
		$this->load->model('setting/extension');
			
		$results = $this->model_setting_extension->getExtensions('shipping');
			
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('shipping/' . $result['code']);
					
				$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);
					
				if ($quote) {
					$quote_data[$result['code']] = array(
  	  					'title'          => $quote['title'],
  	  					'quote'          => $quote['quote'], 
               		    'description'    => $quote['description'],
  	  					'sort_order'     => $quote['sort_order'],
  	  					'error'          => $quote['error']
					);
				}
			}
		}
			
		$sort_order = array();
			
		foreach ($quote_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
			
		array_multisort($sort_order, SORT_ASC, $quote_data);
			
		$this->session->data['shipping_methods'] = $quote_data;

		$this->data['error_warning'] = '';

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$this->data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$this->data['shipping_code'] = $this->session->data['shipping_method']['code'];
			$this->customer->setShippingMethod($this->session->data['shipping_method']['code']);
		} else {
			$this->data['shipping_code'] =  $this->customer->getShippingMethod();
		}
		 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping.tpl';
		}

		return $this->render();
	}

	
	/**
	 * 订单商品确认
	 * @return string
	 */
	public function confirm() {
	    $this->load_language('checkout/checkout_group');
 
	    //获取商品信息
		$this->load->model('sale/group_buy');
		// 获取拼团信息		 
		$groupbuy_info = $this->model_sale_group_buy->getGroupbuyInfo($this->cart->getGoods4Group());
		
		$this->load->model ( 'catalog/product' );
	    $product_info = $this->model_catalog_product->getProduct ( $groupbuy_info['product_id'] );   
		
		$json = array();
		
		
		/*获取拼团周期及时间 */
	
		$additionaldate=$this->set_supply_periods($json);
		
		
		if (!$json) {
	
	
			// 商品列表
			$product_data = array();		

			$product_data[] = array(
						'product_id' => $product_info['product_id'],
						'href' 		=> $this->url->link('product/product', '&product_id=' . $product_info['product_id']),
						'name'       => $groupbuy_info['name'],
						'model'      => '',
			            'promotion'  => '',
						'additional' => $additional,
						'option'     => '',
						'download'   => '',
						'quantity'   => '1',
						'subtract'   => $product_info['subtract'],
						'price'      => $groupbuy_info['sell_price'],
						'total'      => $groupbuy_info['sell_price'],
						'tax'        => ''//$this->tax->getRate($product['tax_class_id'])
			);
				
		
			$this->data['groups']=array();
				
			foreach($product_data as $result){
				$this->data['groups'][0][]=$result;
			}
			
			
			$data['products'] = $product_data;
			
			$this->log_order->info($product_data);
			
			$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/confirm.tpl';
			} else {
				$this->template = 'default/template/checkout/confirm.tpl';
			}

		}

		return $this->render();
	}
	
	
	/**
	 * 更新订单信息
	 */
	public function update() {
		
		$json = array();
 
		  $json['error']['warning'] = '改操作已废除请检查';
		   $json['redirect'] = $this->url->link('checkout/cart');
		  /* */
		
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	 
	/**
	 * 追加订单
	 * @param unknown $json
	 * @return boolean|unknown
	 */
	public function addorder(&$json) {
        $json = array();
        $this->load_language('checkout/checkout');

		$this->load->model('catalog/point');

		$cid = $this->cart->getGoods4Group();
		
		// 获取商品信息
		$this->load->model('sale/group_buy');
		$product =$this->model_sale_group_buy->getProductInfo($cid);		
		// 是否配送 
		$shipping_required = $product['shipping'];
		

		//支付方式检测
		
		$pay_code       = $this->customer->getPaymentMethod();
	    if(empty($pay_code))
		{
				$json ['error'] ['warning'] = $this->language->get ( 'error_payment' );
				return false;
		}
		
		/*
		 * 构造订单数据*/
		$data = array();
		
		// 计算金额
	    $data = $this->getChildMethod('checkout/payment/calGroupbuyTotals');
	    
	    // 支付信息
	    $payinfo = $this->getChildMethod('checkout/payment/calPayment', array('total'=>$data['total']));
	    
	    
	    $payment_code   = '';
	    $payment_method = '';
	    $payments = array();
	    
	    if($data['total'] < EPSILON) // 金额为零
	    {
	        $payments[] = array(
	            'code'    =>  'free_checkout',
	            'value'   =>  $data['total']
	        );
	        
	        $this->load->model('payment/free_checkout');
		    $method = $this->model_payment_free_checkout->getMethod();
		    $payment_code    = 'free_checkout';
		    $payment_method  = $method['title'];	    
	    }
	    else{
	        if ($payinfo['balance']['valid'] && $payinfo['balance']['selected'] && $payinfo['balance']['pay_value']>=EPSILON){ // 储值支付
	            $payments[] = array(
	                'code'    =>  'balance',
	                'value'   =>  $payinfo['balance']['pay_value']
	            );
	            
	            $this->load->model('payment/balance');
	            $method = $this->model_payment_balance->getMethod();
	            $payment_code    = 'balance';
	            $payment_method  = $method['title'];
	        }
	        
	        if ($payinfo['otherpay']['valid'] && $payinfo['otherpay']['selected'] && $payinfo['otherpay']['pay_value']>=EPSILON){ // 混合支付
	            $payments[] = array(
	                'code'    =>  $pay_code,
	                'value'   =>  $payinfo['otherpay']['pay_value']
	            );
	            $this->load->model('payment/'. $pay_code);
	            $method = $this->{'model_payment_' . $pay_code}->getMethod();

	            if(empty($payment_method)){
	                $payment_code    = $method['code'];
	                $payment_method  = $method['title'];	 
	            }
	            else{
	                $payment_code   .= '+' . $method['code'];
	                $payment_method .= '+' . $method['title'];
	            }           
	        }
	    }
	    
	    $data['payments'] = $payments;
	    
	    $data['payment_method'] = $payment_method;
	    $data['payment_code']   = $payment_code;
/*	    if (isset($this->session->data['payment_method'])) {
	        $data['payment_code'] = $this->session->data['payment_method']['code'];
	        $this->customer->setPaymentMethod($this->data['payment_code']);
	    } else if($this->customer->getPaymentMethod()!=''&&$this->customer->getPaymentMethod()!='free_checkout'){
	        $data['payment_code'] =  $this->customer->getPaymentMethod();
	    } else{
	        $data['payment_code']=$this->config->get('config_default_payment');
	    }
*/	    
		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');	
		if ($data['store_id']) {//如果存在id则读取配置url否则默认读取当前访问的url
			$data['store_url'] = $this->config->get('config_url');
		} else {
			$data['store_url'] = HTTP_SERVER;
		}
	
		$data['customer_id'] = $this->customer->getId();
		$data['customer_group_id'] = $this->customer->getCustomerGroupId();
		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getMobile();
		$data['fax'] = $this->customer->getFax();	
		
		$data['shipping_required']=$shipping_required;
		
		$data['order_type']  = '100'; //拼团
		$data['addition_info'] = $this->cart->getGoods4Group();
		
		
		if ($shipping_required) {
		//need shipping需要配送
						
			if ($this->session->data['shipping_address_id']>0){
					
				$data['shipping_address_id'] = $this->session->data['shipping_address_id'];
				$this->load->model('account/address');
				$shipping_address = $this->model_account_address->getAddress ($data['shipping_address_id']);

				$data['shipping_code']=$shipping_address['shipping_code'];
				$data['shipping_data']=$shipping_address['shipping_data'];
				$data['poi']=$shipping_address['poi'];
												
				$this->data['address']=$shipping_address;
				
				$data['shipping_time']=$this->cart->getAdditionalDate4Group();
				
				if(!$data['shipping_time']){
				    $additionaldate = $this->set_supply_periods($json);
				    $data['shipping_time'] = $additionaldate[0].$additionaldate[1];
				}
				
			
				/* 传统配送信息*/
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];
				$data['shipping_mobile'] = $shipping_address['mobile'];
				$data['shipping_phone'] = $shipping_address['phone'];
				$data['shipping_company'] = $shipping_address['company'];
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
			}
			elseif($this->config->get('pickupaddr_status')&&$this->session->data['shipping_point_id']>0)
			{
				$data['shipping_point_id']=$this->session->data['shipping_point_id'];
				$point_info=$this->model_catalog_point->getPoint($data['shipping_point_id']);
				
				$additionaldate=$this->set_supply_periods($json);
				if($json)
				{
					return false;
				}
				
				$data['pdate']=$additionaldate[0];
				$data['device_code']=$point_info['device_code'];
			}
			else
			{   
				$json ['error'] ['warning'] = $this->language->get ('error_shipping' );
         	    $json ['error']['session1']=$this->session->data;
             	return false;
			}
		}
		else {
			$data['shipping_time']= date('Y-m-d H:i:s',time()+1800);
		}
		
		/* 支付信息*/
		$this->load->model('account/address');
		if(isset($this->session->data['payment_address_id'])){
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];
			$data['payment_company'] = $payment_address['company'];
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
				
		}
		else
		{
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_zone'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_country'] = '';
			$data['payment_country_id'] = '';
			$data['payment_address_format'] = '';
		}		
		

		if ($this->cart->hasShipping()) {//如果需要配送，电子卡不需要配送
			$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
		} else {
			$this->tax->setZone($payment_address['country_id'], $payment_address['zone_id']);
		}
		
					
        /* 获取购物车内商品*/
        $product_data = array();
        
		if (isset($product['additional']) && $product['additional']) {
			$additional =$product['additional'];
		} else {
			$additional = array(
					'date'=>$this->data['select_date']//date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")))已废弃，修改于20150402
			);
		}

		$promotion = array(
		    'promotion_price' => (float)$product['sell_price'],
            'promotion_code'  => 'GROUPBUY:'.$cid
		);
		    
		$product_data[] = array(
				'product_id' => $product['product_id'],
				'href' 		=> $this->url->link('product/product', '&product_id=' . $product['product_id']),
				'name'       => $product['name'],
				'model'      => $product['model'],
				'prod_type'      => $product['prod_type'],
				'shipping'      => $product['shipping'],
		        'promotion'  => $promotion,
				'additional'  => $additional,
				'option'     => '',
				'download'   => '',
				'quantity'   => $product['quantity'],
				'subtract'   => '0',
				'price'      => $product['price'],
				'total'      => $product['sell_price'],
				'rule_code'  => '0',
		        'combine'    => $product['combine'],     //套餐
		        'packing_type'=> $product['packing_type'],     //包装
				'tax'        => $this->tax->getRate($product['tax_class_id'])
		);
	
	
		$data['products'] = $product_data;

        //订单备注信息
		if(isset($this->session->data['comment'])){
			$data['comment'] = $this->session->data['comment'];
		}else{
			$data['comment'] = '';
		}
        //积分信息
		$data['reward'] = $this->cart->getTotalRewardPoints();

		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$data['commission'] = ($total / 100) * $affiliate_info['commission'];
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}

		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		//增加订单来源
		$detect = new Mobile_Detect();
		if($detect->isMobile()){
			$source_from =EnumOrderSourceFrom::MOBILE;
		}else if($detect->isTablet()){
			$source_from =EnumOrderSourceFrom::TABLET;
		}else{
			$source_from=EnumOrderSourceFrom::DESKTOP;
		}

		if (isset($this->session->data['reference'])&&$this->session->data['reference']) {
			 
			$data['re_code']=$this->session->data['reference'];
		
		}
		if (!empty($this->session->data['promo'])) {
		
		    $data['promo']=$this->session->data['promo'];
		
		}
		
		$data['source_from']  =$source_from;
		$data['user_agent']   =$detect->getUserBrowser();
		$data['min_pre_times']=3600*24;
		
		//获取用户来源
		$data['partner_code']=isset($this->session->data ['platform']['platform_code'])&&$this->session->data ['platform']['platform_code']?$this->session->data ['platform']['platform_code']:$this->request->cookie['partner'];
		
		$this->load->model('checkout/order');

		$this->log_sys->addLogData($data);
		$this->log_sys->info('order->checkout->addorder:serialize(data):'.serialize($data));
		$order_id= $this->model_checkout_order->create($data);
			
		if($order_id){		
		   // Gift Voucher
			if (isset($this->session->data['vouchers']) && is_array($this->session->data['vouchers'])) {
				$this->load->model('checkout/voucher');
				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->model_checkout_voucher->addVoucher($order_id, $voucher);
				}
			}
			
		}
		else 
		{
			$json['data']=$data;
			//$json['session']=$this->session->data;
			$json['address']=$shipping_address;
			$json['error'] ['warning'] = $this->language->get('error_order');
			return false;
				
		}
    
    	return $order_id;
	}
	
	
	/**
	 *  校验订单信息
	 */
	public function validate(){
		
		$json = array();
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
		
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		$this->load_language('checkout/checkout');

		if (!$json && !$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
			$json['redirect'] =$this->url->link('account/login', '', 'SSL');
		}

		/*
		 * 结账时令牌错误，则认为订单被修改了
		 * */
	    if(!$json&&!isset($this->session->data['checkout_token']) || $this->request->get['token'] != $this->session->data['checkout_token']){
			$json['error']['warning']= $this->language->get("error_token");
			
			$this->log_sys->warn($json['error']['warning']);
		}
		
		//配送校验
		$shipping_required=$this->cart->hasShipping ();
			
		if ($shipping_required) {
		    //need shipping需要配送
		    if (!isset($this->session->data['shipping_address_id'])&&!isset($this->session->data['shipping_point_id'])){
		        $json ['error'] ['warning'] = $this->language->get ('error_shipping' );
		        $json ['error']['session1']=$this->session->data;
		    }	    	
		
		    	
		    if ($this->session->data['shipping_address_id']>0){
		        	
				$data['shipping_address_id'] = $this->session->data['shipping_address_id'];
				$this->load->model ('account/address' );
				$shipping_address = $this->model_account_address->getAddress ($data['shipping_address_id']);
				$this->log_sys->info('checkout_shipping_address'.serialize($shipping_address));
				 
				/* */
				if (!$shipping_address ['poi']||empty($shipping_address['shipping_code'])||empty($shipping_address['shipping_data'])){
				    $json['error']['warning'] = $this->language->get('error_address');
				}

				$this->load->model('catalog/pointdelivery');
				$query_pd=$this->model_catalog_pointdelivery->getDeliveryByName($shipping_address['shipping_code'],$shipping_address['shipping_data']);

				if(!$query_pd)
				{
				    $json['error']['warning'] = $this->language->get('error_shipping');
				}
		    }
		    else
		    {
				$json ['error'] ['warning'] = $this->language->get ('error_shipping' );
				$json ['error']['session1']=$this->session->data;
		    }
		}
	
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}

	/**
	 * 判断微信浏览器
	 * @return boolean
	 */
	private function is_weixin_browser(){
	    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
	    } else {
	    	return false;
	    }
	}  

	/**
	 * 判断菜君浏览器（销售线下现金支付用）
	 * @return boolean
	 */
	private function is_caijun_browser(){
	    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'QncjMessenger') !== false ) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	/**
	 * 立即支付（支付跳转）
	 */
	public function paysubmit(){
	    $json = array();
	    $this->load_language('checkout/payment');
	
	    if (!$json && !$this->customer->isLogged()) {
	        $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
	        $json['redirect'] =$this->url->link('account/login', '', 'SSL');
	    }
	
	
	    /*
	     * 结账时令牌错误，则认为订单被修改了
	     * */
	    if(!isset($this->session->data['checkout_token']) || $this->request->get['token'] != $this->session->data['checkout_token']){
	        $json['error']['warning']= $this->language->get ('error_token');
	
	        $this->log_sys->warn($json['error']['warning']);
	        $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
	        $json['redirect'] =$this->url->link('account/login', '', 'SSL');	        
	    }
	
	    $payment_method  = $this->customer->getPaymentMethod();
	    $balance_setting = $this->customer->getBalanceSetting();
	    if ($this->request->server['REQUEST_METHOD'] != 'POST' || empty($payment_method) || $balance_setting=='')
	    {
	        $json['error']['warning'] = $this->language->get ('error_payment');
	    }
	
	    if(!isset($json['error']))
	    {
	        $this->load->model('checkout/order');
	
	        //cheout提交订单唯一入口
	        $order_id=$this->addOrder($json);
	
    	    if($order_id)
    	    {//创建订单（初始状态为未支付
				$order_info=$this->model_checkout_order->getOrder($order_id);
							
				//if($order_info['payment_code']&&!in_array($order_info['payment_code'],$this->direct_payments)){						
					//修改订单状态为未支付状态
				//	$this->model_checkout_order->updateOrderStatus($order_id,$this->config->get('config_order_nopay_status_id'));
				//}
				//FIXED #333:增加优惠券处理逻辑，订单一旦被确认，该优惠券就记录使用
				
				//$order_info=$this->model_checkout_order->getOrder($order_id);
				
				$sql="SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . $order_id . "'";
				
				$order_total_query = $this->db->query($sql);
				
				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);
					if($order_total['code']=='coupon'){
						if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
							$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
						}
					}
				}
				//END:
				$this->cart->clear();
				$json['order_id']=$order_id;
				if( isset($this->session->data['salesman'])){
					$json['salesman']=$this->session->data['salesman'];
				}
				//$this->session->data['order_id']=$order_id;
				//$json['payment']=$this->payment($order_id);
				
				unset($this->session->data['shipping_point_id']);
				unset($this->session->data['shipping_address_id']);
				unset($this->session->data['shipping_code']);
				unset($this->session->data['shipping_data']);
				unset($this->session->data['shipping_ctiy']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				unset($this->session->data['comment']);
				//unset($this->session->data['order_id']);
				unset($this->session->data['coupon']);
				unset($this->session->data['coupon_product_id']);
				unset($this->session->data['freepromotion']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
				unset($this->session->data['order_id']);
				unset($this->session->data['checkout_token']);
				unset($this->session->data['pay_bank']);
				if( isset($this->session->data['salesman'])){
					unset($this->session->data['salesman']);
				}
				if( isset($this->session->data['discount'])){
					unset($this->session->data['discount']);
				}
				
				//直接支付
				$payments = $this->model_checkout_order->getOrderPayments($order_id);
				if($payments){
				    foreach ($payments as $payment){
				        if(in_array($payment['payment_code'],$this->direct_payments)){
				            $result = $this->getChildMethod('payment/'.$payment['payment_code'].'/confirm', $order_id);
				            
				            $json['trace1'] = $result;
				            if($result['success']){
				                $json['redirect']=$this->url->link('checkout/success&order_no='.$order_id, '' , 'SSL');
				                break;
				            }
				            elseif ($result['error']){
				                $json ['error']['warning'] = $result['msg'];
				                break;
				            }	           
				        }
				    }
				}
				
				//其他支付
				if(!isset($json['error']) && !isset($json['redirect'])){
	
    	            $json['order_id']=$order_id;
    	            $ret = $this->getPayment($payment_method, $order_id);
    	
    	            if(isset($ret['payment']))
    	                $json['payment']= $ret['payment'];
    	            if(isset($ret['redirect']))
    	                $json['redirect']=$ret['redirect'];
				}
	        }
	        else
	        {
	            if($this->model_checkout_order->error['create']){
	                foreach($this->model_checkout_order->error['create'] as  $error){
	                    $json ['error'] ['warning'] .= $this->language->get ($error);
	                }
	            }
	
	        }
	    }
	
	    $this->load->library('json');
	    $this->response->setOutput(Json::encode($json));
	}
	
	/**
	 * 调用支付模块
	 * @param string $order_id
	 */
	private function getPayment($payment_method, $order_id='') {
	    return $this->getChildMethod('payment/' . $payment_method .'/getPaymentURL' , array('order_id'=>$order_id));
	}
	
	/**
	 * 检查团购合理性
	 * @param unknown $groupbuy_id
	 * @param unknown $customer_id
	 */
	private function checkGroupbuyStock($groupbuy_id, $customer_id){
	    return true;
	}
}
?>