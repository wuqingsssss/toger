<?php 
class ControllerCheckoutShipping extends Controller {
  	public function index() {
		$this->load_language('checkout/checkout');
		
		$json = array();
		
		$this->load->model('account/address');
		
		if ($this->customer->isLogged()) {
			if(isset($this->session->data['shipping_address_id']))
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
			else
				$shipping_address = $this->model_account_address->getAddress($this->customer->getAddressId());
		}else{
			$json['redirect']=$this->url->link('account/login', '', 'SSL');
		}

		if (!isset($shipping_address)) {								
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
				
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				if (!isset($this->request->post['shipping_method'])) {
					$json['error']['warning'] = $this->language->get('error_shipping');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);
					
					if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
						$json['error']['warning'] = $this->language->get('error_shipping');
					}
				}			
			}
		
			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);
			
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$pickup_address=explode('_', $shipping[1]);
				$point_id=$pickup_address[1];
				
				$this->load->model('catalog/point');
				$point_info=$this->model_catalog_point->getPoint($point_id);
				
				setcookie("select_point_id",$point_info['point_code_new'],time()+3600*24*7);
				
			 }			
		} else {
			if (isset($shipping_address)) {
				//地区税点设置
				$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
				
				if (!isset($this->session->data['shipping_methods'])) {
					$quote_data = array();
					
					$this->load->model('setting/extension');
					
					$results = $this->model_setting_extension->getExtensions('shipping');
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('shipping/' . $result['code']);
									
							$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);
							if ($quote) {
								$quote_data[$result['code']] = array(
									'title'      => $quote['title'],
									'description'      => $quote['description'],
									'quote'      => $quote['quote'], 
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
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
				}
			}
		
			if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
				$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
			} else {
				$this->data['error_warning'] = '';
			}	
						
			if (isset($this->session->data['shipping_methods'])) {
				$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
			} else {
 				$this->data['shipping_methods'] = array();
			}
			
			if (isset($this->session->data['shipping_method']['code'])) {
				$this->data['shipping_code'] = $this->session->data['shipping_method']['code'];
				$this->customer->setShippingMethod($this->session->data['shipping_method']['code']);
			} else {
				$this->data['shipping_code'] = '';
			}
		
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
			} else {
				$this->template = 'default/template/checkout/shipping.tpl';
			}
			
			$json['output'] = $this->render();	
		}
			
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
  	}
  	
	public function detail(){
  			if (isset($this->session->data['shipping_method']['code'])) {
				$shipping_code = $this->session->data['shipping_method']['code'];
			} else {
				$shipping_code = $this->customer->getShippingMethod();
			}
			
			if($shipping_code=='flat.flat'){
				echo  $this->getChild('total/shipping_time');
			}else if($shipping_code=='free.free'){
				echo $this->getChild('total/point');
			}
  	}
  	
  	private function getDeviceCode($point_id){
  		$this->load->model('catalog/point');
  		
  		$point_info=$this->model_catalog_point->getPoint($point_id);
  		
  		if($point_info){
  			//TODO:设备号如何获取还需要修改
  			return $point_info['device_code'];
  		}
  		
  		return '';
  	}
  	
  	private function generatePickupCode($device_code){	
  		return $device_code. str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
  		
  	}
}
?>