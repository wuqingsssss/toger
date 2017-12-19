<?php
class ControllerAccountTransaction extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			//$this->session->data['redirect'] = $this->url->link('account/transaction', '', 'SSL');
			$this->clearback();
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}		
		
		$this->load_language('account/transaction');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST'||$this->request->get['trans_code']) ) {
		
			$trancecode=$this->request->post['trans_code'].$this->request->get['trans_code'];

            if($trancecode){
               $this->load->model('sale/transaction');
               $ret = $this->model_sale_transaction->addTransaction($this->customer->getId(), $trancecode);
               if(!$ret){
                   $this->data['error'] = $this->language->get('error_transaction');
               }
            }
		    else
		    {
		        $this->data['error'] = $this->language->get('error_empty');
		    }
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_transaction'),
			'href'      => $this->url->link('account/transaction', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->load->model('account/transaction');

		$this->data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));
				
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}		
		
		$this->data['transactions'] = array();
		
		$data = array(				  
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$transaction_total = $this->model_account_transaction->getTotalTransactions($data);
	
		$results = $this->model_account_transaction->getTransactions($data);
 		
    	foreach ($results as $result) {
			$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
			    'order_id'    => $result['order_id'],
			    'reference'   => $result['reference']
			);
		}	

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/transaction', 'page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['total'] = $this->currency->format($this->customer->getBalance());
		
		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		
		$this->data['action'] = $this->url->link('account/transaction', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/transaction.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/transaction.tpl';
		} else {
			$this->template = 'default/template/account/transaction.tpl';
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
	
	/*
	 * 充值页面
	 */
	public function charge(){
	    $this->load_language('account/transaction');
	    
	    $this->document->setTitle($this->language->get('heading_charge'));
	    
	    $this->data['total'] = $this->currency->format($this->customer->getBalance());
	        
	    $this->data['action'] = $this->url->link('account/transaction', '', 'SSL');
	    
	    $this->load->model('account/transaction');
	    
	    $products = $this->model_account_transaction->getChargeProducts();
	    
	    $this->data['products']=array();
	    if($products){
	        foreach ($products as $product){
	             $this->data['products'][] = array(
	                'price'        =>   $this->currency->format($product['price']),
	                'product_id'   =>   $product['product_id'],
	                'value'        =>   (int)$product['value']
	            );
	        }
	    }
	    
	    //$this->data['products'] =$products;
	    
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/transaction_charge.tpl')) {
	        $this->template = $this->config->get('config_template') . '/template/account/transaction_charge.tpl';
	    } else {
	        $this->template = 'default/template/account/transaction_charge.tpl';
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
	 * 充值支付页面
	 */
	public function payment(){
	    $json = array();
	    // 获取支付方式
	    $this->data['payment_methods']= $this->getChild('checkout/payment');
	    
	    $this->load_language('account/transaction');
	     
	    $this->document->setTitle($this->language->get('heading_charge'));
	     
	    $this->data['total'] = $this->currency->format($this->customer->getBalance());
	     
	    $this->data['action'] = $this->url->link('account/transaction/paysubmit', '', 'SSL');
	    
	    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
	        $product_id = $this->request->post['data-id'];
	        $this->load->model('account/transaction');
	         
	        $product = $this->model_account_transaction->getChargeProduct($product_id);
	        $this->session->data['charge'] = $product;
	        
	        $this->data['product'] = array(
                'price'        =>   $this->currency->format($product['price']),
                'product_id'   =>   $product['product_id'],
                'value'        =>   (int)$product['value']
	        );
	        // 获取支付方式
	        $this->data['payment_methods']= $this->getChild('checkout/payment', true);
	        
	        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/transaction_pay.tpl')) {
	            $this->template = $this->config->get('config_template') . '/template/account/transaction_pay.tpl';
	        } else {
	            $this->template = 'default/template/account/transaction_pay.tpl';
	        }
	        
	        $json['output'] = $this->render();
	    }
	    
	    
	    $this->load->library('json');
	    
	    $this->response->setOutput(Json::encode($json));
	}
	
	/**
	 * 充值支付页面
	 */
	public function paysubmit(){   
        $json = array();
        $this->load_language('checkout/checkout');
        
        if (!$json && !$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/transaction', '', 'SSL');
            $json['redirect'] =$this->url->link('account/login', '', 'SSL');
        }
        
        if (! isset($this->session->data['charge'] ) ||  !$this->session->data['charge']) {
            $json['error']['warning']='没有选择充值商品!';
            $json['redirect'] = $this->url->link('account/transaction/charge', '', 'SSL');
        }
    
  
    
        /*
         * 结账时令牌错误，则认为订单被修改了
         * */
    /*    if(!$json&&!isset($this->session->data['checkout_token']) || $this->request->get['token'] != $this->session->data['checkout_token']){
            $json['error']['warning']='订单检查错误，请刷新页面后重新提交订单!';
    
            $this->log_sys->warn($json['error']['warning']);
        }
     */   	
    
    
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->request->post['payment_method'] || !isset($this->request->post['payment_method']) ) {

            $json['error']['warning'] = '没有选择支付方式!';
        }
        
        if(!isset($json['error'])){
            $this->load->model('checkout/order');
    
            //cheout提交订单唯一入口
            $order_id=$this->addOrder($this->request->post['payment_method']);
            	
            if($order_id){//创建订单
                $order_info=$this->model_checkout_order->getOrder($order_id);
    
                //$this->log_sys->info($order_info);
    
                if($order_info['payment_code']&&!in_array($order_info['payment_code'],$this->direct_payments)){
                    //	if(){
                    //$this->log_sys->debug('IlexDebug:: Checkout Update updateOrderStatus() : order '.$order_id .' payment_method '.$this->session->data['payment_method']['code']);
                    	
                    //修改订单状态为未支付状态
                    $this->model_checkout_order->updateOrderStatus($order_id,$this->config->get('config_order_nopay_status_id'));
    
                    //	}
                }
                
                // 清空储值订单
                unset($this->session->data['charge']);
                
                $json['order_id']=$order_id;
                $ret = $this->getPayment($this->request->post['payment_method'], $order_id);
                
                if(isset($ret['payment']))
                    $json['payment']= $ret['payment'];
                if(isset($ret['redirect']))
                    $json['redirect']=$ret['redirect'];
              //  $json['redirect'] = $json['payment'];
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
     * 追加订单
     * @return boolean|unknown
     */
    private function addOrder($payment_method) {
       // $this->load_language('checkout/checkout');
    
        $this->load->model ('account/address' );
        $data['shipping_required'] = 0;
        $total_data = array();
        $total=array();
        $total['promotion'] = 0;
        $total['general'] = 0;
        $total['fee']=0;
        $total['discount']=0;
        $total['total']= $this->session->data['charge']['price'];
        $taxes = $this->cart->getTaxes();
        
        
        //支付方式检测
        
        $pay_code       = $this->customer->getPaymentMethod();
        if(empty($pay_code))
        {
            $json ['error'] ['warning'] = $this->language->get ( 'error_payment' );
            return false;
        }
        
        /*
         * 构造订单数据*/            
        $payments = array();
         
        if($total['total'] < EPSILON) // 金额为零
        {
            $payments[] = array(
                'code'    =>  'free_checkout',
                'value'   =>  $total['total']
            );
        }
        else{        
            $payments[] = array(
                    'code'    =>  $pay_code,
                    'value'   =>  $total['total']
            );
        }
         
        $data['payments'] = $payments;
        
        /*构造订单数据*/
        $this->load->model('setting/extension');
        $sort_order = array();
        // 获取配置的跟结算相关的接口
        
        $this->load->model('total/sub_total');
        $this->load_language('total/sub_total');
        $total_data[] = array(
            'code'       => 'sub_total',
            'title'      => $this->language->get('text_sub_total'),
            'text'       => $this->currency->format($total['total']),
            'value'      => $total['total'],
            'sort_order' => $this->config->get('sub_total_sort_order')
        );
        $this->load->model('total/total');
        $this->load_language('total/total');
        $total_data[] = array(
            'code'       => 'total',
            'title'      => $this->language->get('text_total'),
            'text'       => $this->currency->format($total['total']),
            'value'      => $total['total'],
            'sort_order' => $this->config->get('total_sort_order')
        );       
                	
        $data['totals'] = $total_data;
        $data['total'] = $total['total'];

        $this->language->load('checkout/checkout');

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
        //$data['telephone'] = $this->customer->getTelephone();
        $data['telephone'] = $this->customer->getMobile();
        $data['fax'] = $this->customer->getFax();
        	        	
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
        
        $this->load->model('payment/' . $payment_method);
        
        $method = $this->{'model_payment_' . $payment_method}->getMethod();
        
        $data['payment_method'] = $method['title'];
        $data['payment_code']   = $method['code'];
        	
        	
        /* 获取购物车内商品*/
        $product_data = array();
        if ($this->session->data['charge']) {
            $charge = $this->session->data['charge'];
           
            $product_data[] = array(
                'product_id' => $charge['product_id'],
                'name'       => $charge['name'],
                'model'      => $charge['sku'],
                'prod_type'      => $charge['prod_type'],
                'shipping'      => $charge['shipping'],
                'promotion'  => '',
                'additional'  => '',
                'option'     => null,
                'download'   => null,
                'quantity'   => 1,
                'subtract'   => 0,
                'price'      => $charge['price'],
                'total'      => $charge['price'],
                'rule_code'  => 0,
                'combine'    => 0,     //套餐
                'packing_type'=> 0,     //包装
                'tax'        => 0
            );
        }
    
    
        $data['products'] = $product_data;
        $data['comment'] = '';
          
        $data['affiliate_id'] = 0;
        $data['commission'] = 0;
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

        $data['source_from']=$source_from;
        $data['user_agent']=$detect->getUserBrowser();
        $data['min_pre_times']=3600*24;
    
        //获取用户来源
        $data['partner_code']='';
    
        $this->load->model('checkout/order');

        $this->log_sys->info('account->transaction->addorder:serialize(data):'.serialize($data));
        
        // 生成订单
        $order_id= $this->model_checkout_order->create($data);
             
    
        if($order_id){
            return $order_id;
        }
        else
        {
            return false;
        }
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