<?php
class ControllerCheckoutReorder extends Controller {
	private $direct_payments= array('cod','cash','cheque','free_checkout','bank_transfer','balance');
	
	
	/**
	 * 
	 */
	public function index() {		
	   // $this->log_sys->trace("");    
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('checkout/reorder', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}		
			
		if (($this->request->server['REQUEST_METHOD'] != 'GET') || !isset($this->request->get['order_id'])){
		    $this->redirect($this->url->link('account/order'));
		}	 
		 
		$order_id = $this->request->get['order_id'];
		$this->session->data['reorder_id'] = $order_id;
		
		if(!isset($this->session->data['checkout_token'])){
			$this->session->data['checkout_token'] = md5(mt_rand());
		}
		$this->data['token'] = $this->session->data['checkout_token'];
		$this->data['order_id'] = $order_id;

		// 取消原来的支付
		$this->load->model('checkout/order');
		$this->model_checkout_order->clearOrderPayments($order_id);
		
		
		$this->load_language('checkout/reorder');

		$this->document->setTitle($this->language->get('heading_title'));
		
		
		// 页面头
		$header_setting =  array('left'    =>  array( href => $this->url->link('account/order'),
		                                            text => $this->language->get("header_left")),
		                         'center'  =>  array( href => "index.php?route=account/order",
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

		$this->data['logged'] = $this->customer->isLogged();
	
        // 获取支付方式
		$this->data['payment_methods']= $this->getChild('checkout/payment');
		
		// 支付
		$this->data['order_pay'] = $this->getChild('checkout/payment/payment', array('order_id' => $order_id, 'reorder'=>true, 'url'=>'javascript:_.go();'));
		
		// Modules END

//		$this->data['order_confirm']=$this->confirm();
		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/reorder.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/reorder.tpl';
		} else {
			$this->template = 'default/template/checkout/reorder.tpl';
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
	 *  校验订单信息
	 */
	public function validate(){
		
		$json = array();

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
	        $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
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
	        $order_id=$this->request->post['order_id'];
	        /*
	         * 构造支付方法*/
	        $data = array();
	        //支付方式检测        
	        $pay_code       = $this->customer->getPaymentMethod();
	        if(empty($pay_code))
	        {
	            $json ['error'] ['warning'] = $this->language->get ( 'error_payment' );
	            return false;
	        }
	        
	        // 计算金额
	        $order_info = $this->model_checkout_order->getOrder($order_id);
	        $total = $order_info['total'];
	         
	        // 支付信息
	        $charge  = $this->model_checkout_order->checkOrderType($order_id);
	        $payinfo = $this->getChildMethod('checkout/payment/calPayment', array('total' => $total, 'charge'=>$charge==2));

	        $payments = array();
	         
	        if($total < EPSILON) // 金额为零
	        {
	            $payments[] = array(
	                'code'    =>  'free_checkout',
	                'value'   =>  $total
	            );
	        }
	        else{
	            if ($payinfo['balance']['valid'] && $payinfo['balance']['selected'] && $payinfo['balance']['pay_value']>=EPSILON){ // 储值支付
	                $payments[] = array(
	                    'code'    =>  'balance',
	                    'value'   =>  $payinfo['balance']['pay_value']
	                );
	 	        }
	             
	            if ($payinfo['otherpay']['valid'] && $payinfo['otherpay']['selected'] && $payinfo['otherpay']['pay_value']>=EPSILON){ // 混合支付
	                $payments[] = array(
	                    'code'    =>  $pay_code,
	                    'value'   =>  $payinfo['otherpay']['pay_value']
	                );
	            }
	        }
	 
	        // 更新支付方法
	        $this->model_checkout_order->updateOrderPayments($order_id, $payments);
	
    	    if($order_id)
    	    {//创建订单（初始状态为未支付
				$order_info=$this->model_checkout_order->getOrder($order_id);
								
				$json['order_id']=$order_id;
				
				//直接支付
				$payments = $this->model_checkout_order->getOrderPayments($order_id);
				if($payments){
				    foreach ($payments as $payment){
				        if(in_array($payment['payment_code'],$this->direct_payments)){
				            $result = $this->getChildMethod('payment/'.$payment['payment_code'].'/confirm', $order_id);
				            if($result['success']){
				                $json['redirect']=$this->url->link('checkout/success&order_no='.$order_id, '' , 'SSL');
				                break;
				            }
				            elseif ($result['error']){
				                $json ['error'] ['warning'] = $result['msg'];
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
}
?>