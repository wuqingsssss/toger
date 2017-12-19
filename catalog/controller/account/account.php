<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {

	    
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 

		$this->load_language('account/account');

		$this->document->setTitle($this->language->get('heading_title'));

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
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('account/order');
		
	

		$this->data['orders'] = array();
		
		$order_total = $this->model_account_order->getTotalOrders();
		

		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$limit=5;
		$filter = array(
				'start' => ($page - 1) * $limit,
				'limit' => $limit
		);
		$results = $this->model_account_order->getOrders($filter);

		
		$common = new Common($this->registry);
	
		foreach ($results as $result) {		
			
			$sub_results = $this->model_account_order->getSubOrders($result['order_id']);


			$count = count($sub_results);
			
			if($count>0){
				$this->data['porders'][$result['order_id']]=$count;
			}
				
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'p_order_id'   => $result['p_order_id'],
				'count'   => $count,
				'pdate'   => $result['pdate'],
				'shipping_point_id'   => $result['shipping_point_id'],
				'shipping_time'   => $result['shipping_time'],

				'customer'       => $result['firstname'],
//				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'products'   => $this->getOrderProducts($result['order_id']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL')
			);

		}
		
		$this->data['display_name'] = sprintf($this->language->get('text_welcome'),$this->customer->getDisplayName());
		
		$this->data['total'] = $this->currency->format($this->customer->getBalance());
		$this->data['points'] = (int)$this->customer->getRewardPoints();
		
		
		$this->data['user_group_id']=$this->customer->getCustomerGroupId();
		$this->data['user_group_name']=$this->getCustomerGroupName($this->customer->getCustomerGroupId());
		
		
		$this->data['tel'] =$this->customer->getTelephone();
		$this->data['email'] =$this->customer->getEmail();
		
		$this->data['register'] = $this->url->link('account/register', '', 'SSL');
		$this->data['login'] = $this->url->link('account/login', '', 'SSL');
		$this->data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$this->data['password'] = $this->url->link('account/password', '', 'SSL');
		$this->data['wishlist'] = $this->url->link('account/wishlist');
		$this->data['order'] = $this->url->link('account/order', '', 'SSL');
		$this->data['download'] = $this->url->link('account/download', '', 'SSL');
		$this->data['return'] = $this->url->link('account/return', '', 'SSL');
		
		$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['address'] = $this->url->link('account/address', '', 'SSL');
		$this->data['invite'] = $this->url->link('account/invite', '', 'SSL');
		$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
		
		$this->data['coupon'] = $this->url->link('account/coupon', '', 'SSL');
		$this->data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$this->data['password'] = $this->url->link('account/password', '', 'SSL');
		
		$this->data['consulation'] = $this->url->link('account/consulation', '', 'SSL');

		$this->load->service('payment/balance');
		// 储值信息根据用户判断
		if( $this->service_payment_balance->checkUser()) {
		    $this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
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
  	
  	private function getCustomerGroupName($customer_group_id){
  		$sql="SELECT  * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id=".(int)$customer_group_id;
  		
  		$query=$this->db->query($sql);
  		
  		if($query->rows){
  			return $query->row['name'];
  		}else{
  			return '';
  		}
  	}
  	
	protected function getTotalOrderCount($order_status_id=null){
  		$filter = array(
    			'filter_order_status' => $order_status_id
    	);
    
    	return $this->model_account_order->getTotalOrders($filter);
  	}
  	
private function getOrderProducts($order_id){

		$this->load->model('account/order');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$order_info = $this->model_account_order->getOrder($order_id);
		$results=array();

		$products = $this->model_account_order->getOrderProducts($order_id);

      	foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

         	foreach ($options as $option) {
          		if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']),
					);
				} else {
					$filename = substr($option['value'], 0, strrpos($option['value'], '.'));
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
					);						
				}
        	}

        	$product_info=$this->model_catalog_product->getProduct($product['product_id']);

        	$thumb=resizeThumbImage($product_info['image'],60,60,TRUE);

        	$results[] = array(
				'order_product_id' => $product['order_product_id'],
          		'product_id'             => $product['product_id'],
          		'name'             => $product['name'],
          		'model'            => $product['model'],
          		'thumb'            => $thumb,
        		'href'    	 	   => $this->url->link('product/product', 'product_id=' . $product['product_id']),
          		'option'           => $option_data,
          		'quantity'         => $product['quantity'],
          		'price'            => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
				'total'            => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
				'selected'         => isset($this->request->post['selected']) && in_array($result['order_product_id'], $this->request->post['selected'])
        	);
      	}

      	return $results;
	}
}
?>