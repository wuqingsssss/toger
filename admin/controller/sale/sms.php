<?php 
class ControllerSaleSms extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load_language('sale/sms');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/customer');
		
		$this->load->model('sale/customer_group');
		
		$this->load->model('sale/affiliate');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/store');
		
			$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);			
			
			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
			
			$emails = array();
			
			switch ($this->request->post['to']) {
				case 'newsletter':
					$results = $this->model_sale_customer->getCustomersByNewsletter();
				
					foreach ($results as $result) {
						if (isset($result['mobile'])&&$result['mobile']!='') {
							$emails[] = $result['mobile'];
						}
					}
					break;
				case 'customer_all':
					$results = $this->model_sale_customer->getCustomers();
			
					foreach ($results as $result) {
						if (isset($result['mobile'])&&$result['mobile']!='') {
							$emails[] = $result['mobile'];
						}
					}						
					break;
				case 'customer_group':
					$results = $this->model_sale_customer->getCustomersByCustomerGroupId($this->request->post['customer_group_id']);
			
					foreach ($results as $result) {
						if (isset($result['mobile'])&&$result['mobile']!='') {
							$emails[$result['customer_id']] = $result['mobile']; 
						}
						
					}						
					break;
				case 'customer':
					if (isset($this->request->post['customer'])) {					
						foreach ($this->request->post['customer'] as $customer_id) {
							$customer_info = $this->model_sale_customer->getCustomer($customer_id);
							
							if ($customer_info&&isset($customer_info['mobile'])&&$customer_info['mobile']!='') {
								$emails[] = $customer_info['mobile'];
							}
						}
					}
					break;	
														
				case 'product':
					if (isset($this->request->post['product'])) {
						foreach ($this->request->post['product'] as $product_id) {
							$results = $this->model_sale_customer->getCustomersMobileByProduct($product_id);
							
							foreach ($results as $result) {
								$emails[] = $result['mobile'];
							}
						}
					}
					break;												
			}
			
			$emails = array_unique($emails);
			
			if ($emails) {
				$message  = $this->request->post['message'];

				foreach ($emails as $number) {
					$sms=new Sms();
				    $message = $message;
					$sms->send($number,$message);
				}
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		
	 	
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = '';
		}	

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/sms', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
				
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		$this->data['action'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token'], 'SSL');
    	$this->data['cancel'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} else {
			$this->data['store_id'] = '';
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['to'])) {
			$this->data['to'] = $this->request->post['to'];
		} else {
			$this->data['to'] = '';
		}
				
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
				
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);
				
		$this->data['customers'] = array();
		
		if (isset($this->request->post['customer'])) {					
			foreach ($this->request->post['customer'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
				if ($customer_info) {
					$this->data['customers'][] = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);
				}
			}
		}

		
		
		$this->load->model('catalog/product');

		$this->data['products'] = array();
		
		if (isset($this->request->post['product'])) {					
			foreach ($this->request->post['product'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
					
				if ($product_info) {
					$this->data['products'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}
				
		if (isset($this->request->post['subject'])) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		if (isset($this->request->post['message'])) {
			$this->data['message'] = $this->request->post['message'];
		} else {
			$this->data['message'] = '';
		}

		$this->template = 'sale/sms.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'sale/sms')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>