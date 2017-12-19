<?php    
class ControllerSaleCustomer extends Controller { 
	private $error = array();
	
	protected function init(){
		$this->load_language('sale/customer');
		 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/customer');
	}
	
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
							
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
  
  	public function index() {
  		$this->init();
		
    	$this->getList();
  	}
  
  	public function insert() {
		$this->init();
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$customer_id = $this->model_sale_customer->addCustomer($this->request->post);
      	  	if($customer_id){
      	  	    $flag = $this->request->post['payment_transaction'];
			    $this->model_sale_customer->setTransactionPayment($customer_id, $flag);
      	  	}
      	  	$this->redirectToList();
		}
    	
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->init();
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);
	  		
			$customer_id = $this->request->get['customer_id'];
			if($customer_id){
			    $flag = $this->request->post['payment_transaction'];
			    $this->model_sale_customer->setTransactionPayment($customer_id, $flag);
			}
			//$this->redirectToList();
			$this->goback();
		}
            $this->setbackparent();
    	$this->getForm();
  	}   

  	public function delete() {
		$this->init();
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_sale_customer->deleteCustomer($customer_id);
			}
			
			$this->redirectToList();
    	}
    
    	$this->getList();
  	}  
	
	public function approve() {
		$this->init();
		
		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif (isset($this->request->post['selected'])) {
			$approved = 0;
			
			foreach ($this->request->post['selected'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
				
				if ($customer_info && !$customer_info['approved']) {
					$this->model_sale_customer->approve($customer_id);
					
					$approved++;
				}
			} 
			
			$this->redirectToList();		
		}
		
		$this->getList();
	} 
    
  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		
		if (isset($this->request->get['filter_mobile'])) {
		    $filter_mobile = $this->request->get['filter_mobile'];
		} else {
		    $filter_mobile = null;
		}
		

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = null;
		}
		
		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}
				
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}	

		if (isset($this->request->get['filter_date_latest'])) {
		    $filter_date_latest = $this->request->get['filter_date_latest'];
		} else {
		    $filter_date_latest = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.customer_id'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_mobile'])) {
		    $url .= '&filter_mobile=' . $this->request->get['filter_mobile'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_latest'])) {
		    $url .= '&filter_date_latest=' . $this->request->get['filter_date_latest'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['approve'] = $this->url->link('sale/customer/approve', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['customers'] = array();

		$data = array(
			'filter_name'              => $filter_name, 
		    'filter_mobile'            => $filter_mobile,
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
			'filter_date_added'        => $filter_date_added,
		    'filter_date_latest'       => $filter_date_latest,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$customer_total = $this->model_sale_customer->getTotalCustomers($data);
	
		$results = $this->model_sale_customer->getCustomers($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
			);
			
			$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['firstname'].' '.$result['lastname'],
				'username'       => $result['username'],
			    'mobile'         => $result['mobile'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'customer_group_id' => $result['customer_group_id'],
				'role'   => EnumCustomerRole::getCustomerRole($result['customer_right']),
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => $result['date_added'],
				'date_latest_login'     => $result['date_latest_login'],
				'login'          => $this->url->link('sale/customer/login', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL'),
				'selected'       => isset($this->request->post['selected']) && in_array($result['customer_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
		
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_mobile'])) {
		    $url .= '&filter_mobile=' . $this->request->get['filter_mobile'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
				
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_latest'])) {
		    $url .= '&filter_date_latest=' . $this->request->get['filter_date_latest'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_mobile'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=mobile' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
		$this->data['sort_customer_group'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
		$this->data['sort_approved'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.approved' . $url, 'SSL');
		$this->data['sort_ip'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');
		$this->data['sort_date_latest'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_latest_login' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_mobile'])) {
		    $url .= '&filter_mobile=' . $this->request->get['filter_mobile'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
				
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_latest'])) {
		    $url .= '&filter_date_latest=' . $this->request->get['filter_date_latest'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_mobile'] = $filter_mobile;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_ip'] = $filter_ip;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_latest'] = $filter_date_latest;
		
		$this->load->model('sale/customer_group');
		
    	$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/customer_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}
  
  	private function getForm() {
   		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$this->data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$this->data['customer_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}

 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
		if (isset($this->error['address_firstname'])) {
			$this->data['error_address_firstname'] = $this->error['address_firstname'];
		} else {
			$this->data['error_address_firstname'] = '';
		}

 		if (isset($this->error['address_lastname'])) {
			$this->data['error_address_lastname'] = $this->error['address_lastname'];
		} else {
			$this->data['error_address_lastname'] = '';
		}
		
		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}
		
		if (isset($this->error['shipping'])) {
			$this->data['error_shipping'] = $this->error['shipping1'];
		} else {
			$this->data['error_shipping'] = '';
		}
		
		if (isset($this->error['shipping_code'])) {
			$this->data['error_shipping_code'] = $this->error['shipping_code'];
		} else {
			$this->data['error_shipping_code'] = '';
		}
		if (isset($this->error['shipping_data'])) {
			$this->data['error_shipping_data'] = $this->error['shipping_data'];
		} else {
			$this->data['error_shipping_data'] = '';
		}
		

		
		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}
		
		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}
		
		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}
		
		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

		if (!isset($this->request->get['customer_id'])) {
			$this->data['action'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
      		
      		$payment_transaction = $this->model_sale_customer->getTransactionPayment($this->request->get['customer_id']);
    	}
    	
    	$fields=array('mobile');
    	
    	foreach($fields as $field){
	    	if (isset($this->request->post[$field])) {
	      		$this->data[$field] = $this->request->post[$field];
			} elseif (isset($customer_info)) { 
				$this->data[$field] = $customer_info[$field];
			} else {
	      		$this->data[$field] = '';
	    	}
    	}
			
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($customer_info)) { 
			$this->data['firstname'] = $customer_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (isset($customer_info)) { 
			$this->data['email'] = $customer_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
    	/*
    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (isset($customer_info)) { 
			$this->data['lastname'] = $customer_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}*/

		if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (isset($customer_info)) { 
			$this->data['telephone'] = $customer_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (isset($customer_info)) { 
			$this->data['fax'] = $customer_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}
    	
    	if (isset($this->request->post['payment_transaction'])) {
    	    $this->data['payment_transaction'] = $this->request->post['payment_transaction'];
    	} elseif (isset($payment_transaction)) {
    	    $this->data['payment_transaction'] = $payment_transaction;
    	} else {
    	    $this->data['payment_transaction'] = 0;
    	}

    	if (isset($this->request->post['newsletter'])) {
      		$this->data['newsletter'] = $this->request->post['newsletter'];
    	} elseif (isset($customer_info)) { 
			$this->data['newsletter'] = $customer_info['newsletter'];
		} else {
      		$this->data['newsletter'] = '';
    	}
		
		$this->load->model('sale/customer_group');
			
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

    	if (isset($this->request->post['customer_group_id'])) {
      		$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
    	} elseif (isset($customer_info)) { 
			$this->data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
      		$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (isset($customer_info)) { 
			$this->data['status'] = $customer_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
    	
    	if (isset($this->request->post['username'])) {
      		$this->data['username'] = $this->request->post['username'];
    	} elseif (isset($customer_info)) { 
			$this->data['username'] = $customer_info['username'];
		} else {
      		$this->data['username'] = '';
    	}

    	if (isset($this->request->post['password'])) { 
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) { 
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
		if (isset($this->request->post['address'])) { 
      		$this->data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$this->data['addresses'] = array();
    	}


		
		$this->data['ips'] = array();
    	
		$this->data['country_id'] = $this->config->get('config_country_id');
		
		if (isset($customer_info)) {
			$results = $this->model_sale_customer->getIpsByCustomerId($this->request->get['customer_id']);
		
			foreach ($results as $result) {
				$this->data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
					'date_added' => $result['date_added'],
					'filter_ip'  => HTTPS_SERVER . 'index.php?route=sale/customer&token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip']
				);
			}
		}		

		$this->data['coupons'] = array();
			
		$results = $this->model_sale_customer->getCustomerCoupons($this->request->get['customer_id']);
      		
		foreach ($results as $result) {
        	$this->data['coupons'][] = array(
        		'name' => $result['name'],
				'date_add'  => date("y-m-d", strtotime($result['date_add'])),
				'used' => $result['used'],
        		'mobile' => $result['mobile'],
        		'date_limit' => date("y-m-d",strtotime($result['date_limit']))
        	);
      	}					
		
		$this->template = 'sale/customer_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
			 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['email'])) > 96) || 
    	    (!isset($this->request->post['email']) && !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
    	
    	
    	if (($this->request->post['password']) || (!isset($this->request->get['customer_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}

    	
		if (isset($this->request->post['address'])) {
			
			//print_r($this->request->post['address']);
			//die();
			foreach ($this->request->post['address'] as $key => $value) {
				if ((strlen(utf8_decode($value['firstname'])) < 1) || (strlen(utf8_decode($value['firstname'])) > 32)) {
					$this->error['address_firstname'][$key] = $this->language->get('error_firstname');
				}
				if (empty($value['shipping_code'])||empty($value['shipping_data'])||empty($value['address_1_poi'])) {
					$this->error['address_address_1'][$key] = $this->language->get('error_shipping');
				}
				
				/* 中国人只要一个名字就够了
				if ((strlen(utf8_decode($value['lastname'])) < 1) || (strlen(utf8_decode($value['lastname'])) > 32)) {
					$this->error['address_lastname'][$key] = $this->language->get('error_lastname');
				}
				
				if ((strlen(utf8_decode($value['address_1'])) < 1) || (strlen(utf8_decode($value['address_1'])) > 128)) {
					$this->error['address_address_1'][$key] = $this->language->get('error_address_1');
				}
			
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
						
				if ($value['city_id'] == '') {
					$this->error['address_city'][$key] = $this->language->get('error_city');
				}
			
				if ($value['zone_id'] == '') {
					$this->error['address_zone'][$key] = $this->language->get('error_zone');
				}	*/
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	} 
	
	public function login() {
		$json = array();

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		$this->load->model('sale/customer');
				
		$customer_info = $this->model_sale_customer->getCustomer($customer_id);
				
		if ($customer_info) {
			$this->session->data['customer_id'] = $customer_id;
				
			$this->redirect(SERVER_CATALOG);
		} else {
			$this->load_language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
			);
		
			$this->template = 'error/not_found.tpl';
			$this->children = array(
				'common/header',
				'common/footer',
			);
		
			$this->response->setOutput($this->render());
		}
	}
	
	public function transaction() {
    	$this->language->load('sale/customer');
		
		$this->load->model('sale/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}		
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_balance'] = $this->language->get('text_balance');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_amount'] = $this->language->get('column_amount');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['transactions'] = array();
			
		$results = $this->model_sale_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->get['customer_id']), $this->config->get('config_currency'));
		
		$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->get['customer_id']);
			
		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/customer/transaction', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_transaction.tpl';		
		
		$this->response->setOutput($this->render());
	}
			
	public function reward() {
    	$this->language->load('sale/customer');
		
		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}	
				
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_balance'] = $this->language->get('text_balance');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_points'] = $this->language->get('column_points');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['rewards'] = array();
			
		$results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['rewards'][] = array(
				'points'      => $result['points'],
				'description' => $result['description'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$this->data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->get['customer_id']);
		
		$reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);
			
		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_reward.tpl';		
		
		$this->response->setOutput($this->render());
	}

	public function coupon() {
    	$this->language->load('sale/customer');
		
		$this->load->model('sale/customer');		
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_date_add'] = $this->language->get('column_date_add');
		$this->data['column_used'] = $this->language->get('column_used');
		$this->data['column_date_limit'] = $this->language->get('column_date_limit');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['coupons'] = array();
			
		$results = $this->model_sale_customer->getCustomerCoupons($this->request->get['customer_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['coupons'][] = array(
        		'name' => $result['name'],
				'date_add'  => date($this->language->get('date_format_short'), strtotime($result['date_add'])),
				'used' => $result['used'],
        		'date_limit' => date($this->language->get('date_format_short'),strtotime($result['date_limit']))
        	);
      	}			
		
	
		$coupon_total = $this->model_sale_customer->getTotalCustomerCoupons($this->request->get['customer_id']);
			
		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/customer/coupon', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_coupon.tpl';		
		
		$this->response->setOutput($this->render());
	}

	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->post['filter_name'])) {
			$this->load->model('sale/customer');
			
			$data = array(
				'filter_name' => $this->request->post['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);
		
			$results = $this->model_sale_customer->getCustomers($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'    => $result['customer_id'], 
					'name'           => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
					'customer_group' => $result['customer_group'],
					'firstname'      => $result['firstname'],
					'lastname'       => $result['lastname'],
					'email'          => $result['email'],
					'telephone'      => $result['telephone'],
					'mobile'      => $result['mobile'],
					'fax'            => $result['fax'],
					'address'        => $this->model_sale_customer->getAddresses($result['customer_id'])
				);					
			}
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);
				
		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}		
	
	public function address() {
		$json = array();
		
		if (isset($this->request->post['address_id']) && $this->request->post['address_id']) {
			$this->load->model('sale/customer');
			
			$json = $this->model_sale_customer->getAddress($this->request->post['address_id']);
		}
		
		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));		
	}
}

final class EnumCustomerRole{
	const ADMIN =0;
	
	const TEACHER =1;
	
	const SAFER =2;
	
	const STUDENT =3;
	
	public static function getCustomerRole($role){
		switch($role){
			case EnumCustomerRole::ADMIN : return "管理员" ; break;
			case EnumCustomerRole::TEACHER : return "老师" ; break;
			case EnumCustomerRole::SAFER : return "安全员" ; break;
			case EnumCustomerRole::STUDENT : return "学生" ; break;
		}
	}
}

?>