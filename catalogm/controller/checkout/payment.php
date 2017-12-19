<?php
class ControllerCheckoutPayment extends Controller {
    /**
     * 
     * @param string $charge  true：充值，false：普通支付
     */
	public function index($charge = false) {
		// Payment Methods
		$method_data = array();
			
		$this->load->model('setting/extension');
			
		$results = $this->model_setting_extension->getExtensions('payment');
			
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				if ($this->is_weixin_browser()) {
					// hidden alipay in WeChat Browser
					if ($result['code'] == 'alipay') {
						continue;
					}
					else if ($result['code'] == 'cash') {
					    continue;
					}
					elseif( $result['code'] == 'balance'){
					    continue;
					}
				} 
			    else if ($this->is_caijun_browser()) {
					// hidden WeChat  in 销售Browser
					if ($result['code'] == 'wxpay') {
					    continue;
					}
				} 
				elseif ($result['code'] == 'wxpay') {
					continue;
				}
				elseif ( $result['code'] == 'cash') {
					continue;
				}
				elseif(  $result['code'] == 'balance'){
				    continue;
				}

				$this->load->model('payment/' . $result['code']);

				$method = $this->{'model_payment_' . $result['code']}->getMethod();
					
				if ($method) {
					$method_data[$result['code']] = $method;
//					if($result['code'] == 'balance'){
//					    $method_data['balance']['title'] = $method['title']."(余".$this->currency->format($this->customer->getBalance()).")";
//					}
				}
			}
		}

		/*
		// added for alipay-bank
		if($this->config->get('alipay_trade_bank')=='bank'){
			$this->load->service('payment/alipaybank');
			$method = $this->{'service_payment_alipaybank'}->getMethod($payment_address, $total['total']);
			if ($method) {
				$method_data['alipaybank'] = $method;
			}
		}*/
		
		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
			
		array_multisort($sort_order, SORT_ASC, $method_data);
			
		$this->session->data['payment_methods'] = $method_data;

		if (isset($this->session->data['payment_methods']) && !$this->session->data['payment_methods']) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$this->data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$this->data['payment_methods'] = array();
		}
		$paymentcode=$this->customer->getPaymentMethod();
		
		
		if (!empty($paymentcode)) {
			$this->data['payment_code'] = $paymentcode;
			//$this->customer->setPaymentMethod($this->data['payment_code']);
		} else {
		    $this->data['payment_code'] = '';
		}

		//增加payment_code检测，如果当前支付方式不存在该payment_code，则默认使用第一个payment code
		if($this->data['payment_methods']){
			$payment_method_exist=false;
			
			$first_payment='';
			
			foreach($this->data['payment_methods'] as $key => $payment_method){
				if($this->data['payment_code']==$payment_method['code']){
					$payment_method_exist=true;
					
					break;
				}
				
				if(!$first_payment){
					$first_payment=$key;
				}
			}
			
			if(!$payment_method_exist){
				$this->data['payment_code']=$this->data['payment_methods'][$first_payment]['code'];
				$this->customer->setPaymentMethod($this->data['payment_code']);
			}
		}

		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment_method.tpl';
		} else {
			$this->template = 'default/template/checkout/payment_method.tpl';
		}
		
		$this->render();
	}
	
	/**
	 * 拼团订单明细
	 * @return multitype:multitype: Ambigous <number>
	 */
	public function calGroupbuyTotals(){
	    $total_data = array();
	    $total=array();
	    $total['promotion'] = 0;
	    $total['general'] = 0;
	    $total['fee']=0;
	    $total['discount']=0;
	    $total['total']=0;
	    $taxes = $this->cart->getTaxes();
	    /*
	     * 构造订单数据*/
	    $data = array();
	    
	    $this->load->model('sale/group_buy');
	    // 获取拼团信息
	    
	    $groupbuy_id = $this->cart->getGoods4Group();
	    
	    $groupbuy_info = $this->model_sale_group_buy->getGroupbuyInfo($groupbuy_id);
	    
	    if(!$groupbuy_info){
	        return false;
	    }
	    
	    $total['total'] = $groupbuy_info['sell_price'];

	    $this->load->language('total/sub_total');
	    $total_data[] = array(
	        'code'       => 'sub_total',
	        'title'      => $this->language->get('text_sub_total'),
	        'text'       => $this->currency->format($total['total']),
	        'value'      => $total['total'],
	        'sort_order' => $this->config->get('sub_total_sort_order')
	    );
	    
	    $this->load->language('total/total');
	    $total_data [] = array (
				'code' => 'total',
				'title' => $this->language->get ( 'text_total' ),
				'text' => $this->currency->format ( max ( 0, $total ['total'] ) ),
				'value' => max ( 0, $total ['total'] ),
				'sort_order' => $this->config->get ( 'total_sort_order' ) 
		);
	    
	    $data['totals'] = $total_data;
	    $data['total']  = $total['total'];
	    
	    return $data;
	    
	}
	
	/**
	 * 计算当前订单明细
	 * @return multitype:multitype: Ambigous <number>
	 */
	public function calTotals(){
	    $total_data = array();
	    $total=array();
	    $total['promotion'] = 0;
	    $total['general'] = 0;
	    $total['fee']=0;
	    $total['discount']=0;
	    $total['total']=0;
	    $taxes = $this->cart->getTaxes();
	    /*
	     * 构造订单数据*/
	    $data = array();
	       
	    $this->load->model('setting/extension');
	    $sort_order = array();
	    // 获取配置的跟结算相关的接口
	    $results = $this->model_setting_extension->getExtensions('total');
	    foreach ($results as $key => $value) {
	        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
	    }
	    //根据后台设置重新设定结算计算顺序
	    array_multisort($sort_order, SORT_ASC, $results);
	    
	    foreach ($results as $result) {
	        if ($this->config->get($result['code'] . '_status')) {
	            $this->load->model('total/' . $result['code']);
	            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
	        }
	    }
	    $sort_order = array();
	    foreach ($total_data as $key => $value) {
	        $sort_order[$key] = $value['sort_order'];
	    }
	    
	    array_multisort($sort_order, SORT_ASC, $total_data);
	    
	    $data['totals'] = $total_data;
	    $data['total']  = $total['total'];	 

	    return $data;
	}
	
	/**
	 * 计算混合支付
	 */
	public function calPayment($data){
	    $payinfo = array();
	   
	    // 储值余额为0
	    $balance = $this->customer->getBalance();
	    if($balance< EPSILON || (isset($data['charge']) && $data['charge'])){  // 储值无效
	        $payinfo['balance'] = array(
	            'valid'    => false,
	            'selected' => false,
	            'value'    => $this->currency->format($balance),
	            'pay'      => $this->currency->format(0),
	            'pay_value'=> 0);
	        
	        $payinfo['otherpay'] = array(
	            'valid'    => true,
	            'selected' => true,
	            'value'    => $this->currency->format(0),
	            'pay'      => $this->currency->format($data['total']),
	            'pay_value'=> $data['total']);
	    }
	    else if($this->customer->getBalanceSetting()=='0'){  // 储值关闭
	        $payinfo['balance'] = array(
	            'valid'    => true,
	            'selected' => false,
	            'value'    => $this->currency->format($balance),
	            'pay'      => $this->currency->format(0),
	            'pay_value'=> 0);
	        $payinfo['otherpay'] = array(
	            'valid'    => true,
	            'selected' => true,
	            'value'    => $this->currency->format(0),
	            'pay'      => $this->currency->format($data['total']),
	            'pay_value'=> $data['total']);
	    }
	    else{  // 使用储值	       
	        if($data['total'] - $balance < EPSILON ){
    	        $payinfo['balance'] = array(
    	            'valid'    => true,
    	            'selected' => true,
    	            'value'    => $this->currency->format($balance),
    	            'pay'      => $this->currency->format($data['total']),
    	            'pay_value'=> $data['total']
    	        );
    	        $payinfo['otherpay'] = array(
    	            'valid'    => false,
    	            'selected' => true,
    	            'value'    => $this->currency->format(0),
    	            'pay'      => $this->currency->format(0),
    	            'pay_value'=> 0
    	        );
	        }
	        else{
   	            $payinfo['balance'] = array(
    	            'valid'    => true,
    	            'selected' => true,
    	            'value'    => $this->currency->format($balance),
    	            'pay'      => $this->currency->format($balance),
   	                'pay_value'=> $balance
   	            );
    	        $payinfo['otherpay'] = array(
    	            'valid'    => true,
    	            'selected' => true,
    	            'value'    => $this->currency->format(0),
    	            'pay'      => $this->currency->format($data['total'] - $balance),
    	            'pay_value'=> $data['total'] - $balance
    	        );
	        }
	    }
	    
	    return $payinfo;	    
	}
	
	/**
	 * 更改支付方式
	 */
	public function changemethod(){
	    $this->load_language('checkout/payment');
		 
	    $json = array();

	    if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->session->data['checkout_token']) && $this->request->get['token'] == $this->session->data['checkout_token']) {      	    
            
	        $total_value = 0;
	    	$paymentcode=$this->customer->getPaymentMethod();
	    	if (empty($paymentcode)) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } else {
                $this->customer->setPaymentMethod($this->request->post['payment_method'], $this->request->post['balance']);
            }
            
            $this->load->model('checkout/order');
            if(isset($this->request->post['order_id'])){
                $order_id = $this->request->post['order_id'];
                
                $this->load->model('checkout/order');
                $order_info = $this->model_checkout_order->getOrder($order_id);
                $total_value = $order_info['total'];
            }
            else{
                $total = $this->calTotals();
                $total_value = $total['total'];
            }
            
            $charge = $this->model_checkout_order->checkOrderType($order_id);
              
            $json['payinfo'] = $this->calPayment(array('total' => $total_value, 'charge'=>$charge==2));
	    }else {
	        $json['error']['warning'] = $this->language->get('error_token');
	    }
	  
	    $this->load->library('json');
	    
	    $this->response->setOutput(Json::encode($json));
	}
	
	/**
	 * 更改支付方式
	 */
	public function changemethod4group(){
	    $this->load_language('checkout/payment');
	    	
	    $json = array();
	
	    if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->session->data['checkout_token']) && $this->request->get['token'] == $this->session->data['checkout_token']) {
	
	        $total_value = 0;
	        $paymentcode=$this->customer->getPaymentMethod();
	        if (empty($paymentcode)) {
	            $json['error']['warning'] = $this->language->get('error_payment');
	        } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
	            $json['error']['warning'] = $this->language->get('error_payment');
	        } else {
	            $this->customer->setPaymentMethod($this->request->post['payment_method'], $this->request->post['balance']);
	        }
	
	        $this->load->model('checkout/order');
	        if(isset($this->request->post['order_id'])){
	            $order_id = $this->request->post['order_id'];
	
	            $this->load->model('checkout/order');
	            $order_info = $this->model_checkout_order->getOrder($order_id);
	            $total_value = $order_info['total'];
	        }
	        else{
	            $total = $this->calGroupbuyTotals();
	            $total_value = $total['total'];
	        }
	
	        $charge = $this->model_checkout_order->checkOrderType($order_id);
	
	        $json['payinfo'] = $this->calPayment(array('total' => $total_value, 'charge'=>$charge==2));
	    }else {
	        $json['error']['warning'] = $this->language->get('error_token');
	    }
	     
	    $this->load->library('json');
	     
	    $this->response->setOutput(Json::encode($json));
	}
	
	
	/**
	 * 更新支付列表模板
	 */
	public function update($groupbuy =false) {
	  		$this->load_language('checkout/payment');
	
	  		$json = array();
	
	  		/*
	  		$total_data = array();
	  		$total=array();
	  		$total['promotion'] = 0;
	  		$total['general'] = 0;
	  		$total['fee']=0;
	  		$total['discount']=0;
	  		$total['total']=0;
	  		$taxes = $this->cart->getTaxes();
	
	  		$this->load->model('setting/extension');
	  			
	  		$sort_order = array();
	  			
	  		$results = $this->model_setting_extension->getExtensions('total');
	  			
	  		foreach ($results as $key => $value) {
	  		    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
	  		}
	  			
	  		array_multisort($sort_order, SORT_ASC, $results);
	  			
	  		foreach ($results as $result) {
	  		    if ($this->config->get($result['code'] . '_status') && (!$groupbuy || ($result['code']!= 'coupon' && $result['code']!='reference'))) {
	  		        $this->load->model('total/' . $result['code']);
	
	  		        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
	
	  		    }
	  		}
	  			
	  		$sort_order = array();
	
	  		foreach ($total_data as $key => $value) {
	  		    $sort_order[$key] = $value['sort_order'];
	  		}
	
	  		array_multisort($sort_order, SORT_ASC, $total_data);
	*/
	  		
	  		if($groupbuy){
	  		    $totals = $this->calGroupbuyTotals();
	  		    $this->data['totals'] = $totals['totals'];
	  		    $total = $totals['total'];
	  		}
	  		else{
	  		    $totals = $this->calTotals();
	  		    $this->data['totals'] = $totals['totals'];
	  		    $total = $totals['total'];
	  		}
	
	  		//更新支付模块, 如果0元，采用0元支付
	  		$json['total'] = $total['total'];
	  		if ( $total['total'] < EPSILON) {
	  		    $this->data['payment'] = $this->getChild('payment/free_checkout');
	  		    //  		    $this->session->data['payment_method']['code'] = 'free_checkout';
	
	  		}else{
	  		    if($this->customer->getPaymentMethod()){
	  		        $this->data['payment'] = $this->getChild('payment/' . $this->customer->getPaymentMethod());
	  		    }
	  		}
	
	  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_button.tpl')) {
	  		    $this->template = $this->config->get('config_template') . '/template/checkout/payment_button.tpl';
	  		} else {
	  		    $this->template = 'default/template/checkout/payment_button.tpl';
	  		}
	  		$json['payment']=$this->data['payment'];
	  		$json['output'] = $this->render();
	
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
	 * 支付页面
	 */
	public function payment($data=null){
	    $json = array();
	    // 获取支付方式
	    $this->load_language('checkout/payment');
	    
	    if($data){
	        $url = $data['url'];
	    }
	    else{
	        $url = "javascript:pages.switchTo(0)";
	    }
	    // 页面头
	    $header_setting =  array('left'    =>  array( href => $url,
	        text => $this->language->get("header_left")),
	        'center'  =>  array( href => "#",
	            text => $this->language->get('payment_title')),
	        'name'    =>  $this->language->get('payment_title')
	    );
	
	    $this->data['header'] = $this->getChild('module/header', $header_setting);
	
	    $this->data['total'] = $this->currency->format($this->customer->getBalance());
	
	    $this->data['action'] = $this->url->link('account/transaction/paysubmit', '', 'SSL');
	     
	    // 获取支付方式
	    $this->data['payment_methods']= $this->getChild('checkout/payment');
	    $balance['value']       = $this->customer->getBalance();
	    $balance['selected']    = $this->customer->getBalanceSetting();
	
	    $this->data['balance']['value']    = $this->currency->format($balance['value']);
	    $this->data['balance']['selected'] = $balance['selected']=='1'? true: false;
	    
	    if($data['reorder']){ // 再支付
	        $this->load->model('checkout/order');
	        
	        //读取订单信息
	        $order_info = $this->model_checkout_order->getOrder($data['order_id']);
	        // 支付类型，充值等
	        $charge  = $this->model_checkout_order->checkOrderType($data['order_id']);
	        
	        $this->data['payinfo'] = $this->calPayment(array('total'=>$order_info['total'], 'charge'=>$charge==2));
	        //清除原有支付方法记录，用户提交支付时会重新生成
	        $this->model_checkout_order->clearOrderPayments($data['order_id']);
	    }
	    else{      //当前订单支付
	        // 拼团
	        if(isset($data['groupbuy_id'])){
	            $total = $this->calGroupbuyTotals();
	            $this->data['payinfo'] = $this->calPayment(array('total'=>$total['total']));
	        }
	        else{ // 正常订单
    	        $total = $this->calTotals();
    	        $this->data['payinfo'] = $this->calPayment(array('total'=>$total['total']));
	        }
	    }    
	     
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment.tpl')) {
	        $this->template = $this->config->get('config_template') . '/template/checkout/payment.tpl';
	    } else {
	        $this->template = 'default/template/checkout/payment.tpl';
	    }
	     
	    $json['output'] = $this->render();
	     
	    $this->load->library('json');
	
	    $this->response->setOutput(Json::encode($json));
	}
}
?>