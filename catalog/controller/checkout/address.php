<?php
class ControllerCheckoutAddress extends Controller {
	public function shipping() {
		$this->load_language('checkout/checkout');
		$this->load_language('misc/address');
		
		$this->load->model('account/address');

		$json = array();

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
			
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				
//				if ($this->request->post['shipping_address'] == 'existing') {
				if ($this->request->post['address_id'] > 0) {
					if (!isset($this->request->post['address_id'])) {
						$json['error']['warning'] = $this->language->get('error_address');
					}
						
					if ($this->request->post['address_id']) {
						$this->customer->setAddress($this->request->post['address_id']);
					}
						
					$this->session->data['shipping_address_id'] = $this->request->post['address_id'];

					$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
							
					if ($address_info) {
						$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
					}
					
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['shipping_method']);
				
				}

//				if ($this->request->post['shipping_address'] == 'new') {
				if ($this->request->post['address_id'] == '0') {
					if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
					}
						
					if ((strlen(utf8_decode($this->request->post['address_1'])) < 1) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
						$json['error']['address_1'] = $this->language->get('error_address_1');
					}
						
//					if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
//						$json['error']['city'] = $this->language->get('error_city');
//					}
						
					if ((strlen(utf8_decode($this->request->post['mobile'])) < 1)) {
						$json['error']['mobile'] = $this->language->get('error_mobile');
					}else if(strlen(utf8_decode($this->request->post['mobile'])) != 11){
						$json['error']['mobile'] = $this->language->get('error_mobile_length');
					}
						
					$this->load->model('localisation/country');
						
					$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
						
//					if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
//						$json['error']['postcode'] = $this->language->get('error_postcode');
//					}

					if ($this->request->post['zone_id'] == '') {
						$json['error']['zone'] = $this->language->get('error_zone');
					}
					
					if ($this->request->post['city_id'] == '') {
						$json['error']['city'] = $this->language->get('error_city');
					}
						
					if (!$json) {
						$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);

						$this->customer->setAddress($this->session->data['shipping_address_id']);

						if ($this->cart->hasShipping()) {
							$this->tax->setZone($this->config->get('config_country_id'), $this->request->post['zone_id']);
						}

						unset($this->session->data['shipping_methods']);
						unset($this->session->data['shipping_method']);
					}
						
				}
			}
		}
		$this->data['type'] = 'shipping';

		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		if(isset($this->session->data['order_id'])){
			$this->load->model('checkout/order');
			$this->load->model('account/address');
			$shipping_address = $this->model_account_address->getAddress($this->data['address_id']);
			$this->model_checkout_order->updateOrderAddrress($this->session->data['order_id'],$shipping_address);
			
			
		}
		
		$this->data['addresses'] = $this->model_account_address->getAddresses();
			
		$this->data['country_id'] = $this->config->get('config_country_id');
			
		$this->load->model('localisation/country');
			
		$this->data['countries'] = $this->model_localisation_country->getCountries();
			
		if(isset($this->request->get['action'])&&$this->request->get['action']=='modifyad'){
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['payment_method']);
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address_modified.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/address_modified.tpl';
			} else {
				$this->template = 'default/template/checkout/address_modified.tpl';
			}
			$json['output'] = $this->render();
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/address.tpl';
			} else {
				$this->template = 'default/template/checkout/address.tpl';
			}
			$json['address'] = $this->render();
		}
		
		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}
	
	public function update(){
		$this->load_language('account/address');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$json = array();
			
			if ((strlen(utf8_decode(trim($this->request->post['firstname']))) < 1)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
			
			if ((strlen(utf8_decode(trim($this->request->post['lastname']))) < 1)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
			
			if ((strlen(utf8_decode(trim($this->request->post['mobile']))) < 1)) {
				$json['error']['mobile'] = $this->language->get('error_mobile');
			}else if(strlen(utf8_decode($this->request->post['mobile'])) != 11){
				$json['error']['mobile'] = $this->language->get('error_mobile_length');
			}
			
			if ((strlen(utf8_decode(trim($this->request->post['address_1']))) < 1)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
			
			if ($this->request->post['city_id']=='') {
				$json['error']['city_id'] = $this->language->get('error_city');
			}
			
			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone_id'] = $this->language->get('error_zone');
			}
					
			if (!$json) {
				$this->load->model('account/address');
				$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);
				
				$json['success']= true;
			}
			
			$this->load->library('json');
		
			$this->response->setOutput(Json::encode($json));
			
			return;
		}
		
		
		
		if (!isset($this->request->get['address_id'])) {
    		$this->data['action'] = $this->url->link('checkout/address/insert', '', 'SSL');
		} else {
    		$this->data['action'] = $this->url->link('checkout/address/update', 'address_id=' . $this->request->get['address_id'], 'SSL');
		}
		
		$this->load->model('account/address');
		
    	if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$address_info = $this->model_account_address->getAddress($this->request->get['address_id']);
		}
		
		$values=array(
			'firstname' => '',
			'lastname' => '',
			'company' => '',
			'address_1' => '',
			'address_2' => '',
			'postcode' => '',
			'city_id' => '',
			'mobile' => '',
			'phone' => '',
			'country_id' => $this->config->get('config_country_id'),
			'zone_id' => '',
		);
		

		foreach ($values as $key => $value) {
			if (isset($this->request->post[$key])) {
	      		$this->data[$key] = $this->request->post[$key];
	    	} elseif (isset($address_info)) {
	      		$this->data[$key] = $address_info[$key];
	    	} else {
				$this->data[$key] = $value;
			}
		}
				
    	$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
    	
    	$this->data['country_id']=$this->config->get('config_country_id');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/address_form.tpl';
		} else {
			$this->template = 'default/template/checkout/address_form.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
	
	public function lists(){
		$this->load_language('checkout/checkout');
		
		$this->load->model('account/address');
		
		$this->data['addresses'] = $this->model_account_address->getAddresses();
		
		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/address_list.tpl';
		} else {
			$this->template = 'default/template/checkout/address_list.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}

	
}
?>