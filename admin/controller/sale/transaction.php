<?php  
class ControllerSaleTransaction extends Controller {
	private $error = array();
     
  	public function index() {
		$this->load_language('sale/transaction');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/transaction');
		
		$this->getList();
  	}
  
  	/**
  	 * 新增储值码
  	 */
  	public function insert() {
    	$this->load_language('sale/transaction');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/transaction');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$postData = array_merge(array(),$this->request->post);
			
			$postData['operator'] = $this->user->getUserName();
			$this->model_sale_transaction->batchAddTransCode($postData);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('sale/transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->data['operation'] = EnumOperation::INSERT;
    
    	$this->getForm();
  	}

  	/**
  	 * 修改储值码信息（只允许修改未使用储值码）
  	 */
  	public function update() {
    	$this->load_language('sale/transaction');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/transaction');
				
    	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$postData = $this->request->post;
			$this->model_sale_transaction->editTransCodeInfo($this->request->get['trans_id'], $postData);
      		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('sale/transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->data['operation'] = EnumOperation::EDIT;
    
    	$this->getForm();
  	}


  	/**
  	 * 获取列表信息
  	 */
  	private function getList() {
		$params = $this->request->get;
		foreach($params as $k=>$v){
			$this->data[$k]=$v;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'trans_id';
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

		$token = $this->session->data['token'];
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $token, 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/transaction', 'token=' . $token . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('sale/transaction/insert', 'token=' . $token . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/transaction/delete', 'token=' . $token . $url, 'SSL');
		$this->data['link-list'] = $this->url->link('sale/transaction', 'token=' . $token . $url, 'SSL');
		$this->data['token'] = $token;


		$this->data['transactions'] = array();

		$data = array(
			'sort'      => $sort,
			'order'     => $order,
			'start'     => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'     => $this->config->get('config_admin_limit'),
			'used'      =>$this->request->get['used'],
			'is_tpl'      =>$this->request->get['is_tpl'],
		    'date_start'=>$this->request->get['date_start'],
		    'date_end'  =>$this->request->get['date_end'],
		    'trans_code'=>$this->request->get['trans_code'],
		    'customer_id'=>$this->request->get['customer_id'],
		    'operator'  => $this->request->get['operator']
		);
		
		$this->log_admin->debug($data);

		$dbResults = $this->model_sale_transaction->getTransCodeAll($data);
		
		$this->log_admin->debug($dbResults);
		
		$transaction_total = $dbResults['total'];
		
		$this->log_admin->debug($transaction_total);
		
		$results=$dbResults['rows'];

		foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/transaction/update', 'token=' . $token . '&trans_id=' . $result['trans_id'] . $url, 'SSL')
			);
						
			$this->data['trans_codes'][] = array(
				'trans_id'   => $result['trans_id'],
				'trans_code' => $result['trans_code'],
				'value'      => $this->currency->format($result['value']),
				'date_start' => $result['date_start'],
				'date_end'   => $result['date_end'],
			    'date_added' => $result['date_added'],
			    'date_modified'=>$result['date_modified'],
			    'customer_id'=> $result['customer_id'],
			    'operator'   => $result['operator'],
			    'used'       => $result['used'],
				'is_tpl'       => $result['is_tpl'],
				'tpl_id'       => $result['tpl_id'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['trans_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_code'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=trans_code' . $url;
		$this->data['sort_value'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=value' . $url;
		$this->data['sort_date_start'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=date_start' . $url;
		$this->data['sort_date_end'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=date_end' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=date_added' . $url;
		$this->data['sort_date_modified'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=date_modified' . $url;
		$this->data['sort_used'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=used' . $url;
		$this->data['sort_is_tpl'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=is_tpl' . $url;
		$this->data['sort_operator'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=operator' . $url;
		$this->data['sort_customer'] = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . '&sort=customer_id' . $url;
				
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/transaction&token=' . $token . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/transaction.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}

  	
  	/**
  	 * 补全菜品信息
  	 */
  	public function autocomplete() {
  		 
  		$json = array();
  		 
  		if(isset($this->request->post['filter_name'])){
  			$this->request->get['filter_name']=trim($this->request->post['filter_name']);
  		}
  		 
  		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_code'])) {
  	
  			$this->load->model('sale/transaction');
  	
  			$requestes=array(
  					'filter_code' => '',
  					'limit' => 20
  			);
  	
  			foreach ($requestes as $key => $value) {
  				if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
  					$$key = trim($this->request->get[$key]);
  				} else {
  					$$key = $value;
  				}
  			}
  			 
  			$data = array(
  					'keyword'         => $filter_code,
  					'start'        => 0,
  					'limit'        => $limit
  					//,'filter_status'       => 1 菜品状态判断暂时关闭 20150404
  			);
  	
  			$results = $this->model_sale_transaction->getTransCodeAll($data);
  	
  			foreach ($results['rows'] as $result) {
  				$json[] = array(
  						'trans_id'    => $result['trans_id'],
  						'trans_code'  => $result['trans_code'].'|'.(Int)$result['value']
  				);
  			}
  		}
  		 
  		$this->response->setOutput(json_encode($json));
  	}
  	 
  	
  	private function getForm() {
    	$this->data['token'] = $this->session->data['token'];
	
		if (isset($this->request->get['transaction_id'])) {
			$this->data['transaction_id'] = $this->request->get['transaction_id'];
		} else {
			$this->data['transaction_id'] = 0;
		}
				
 		if (!empty($this->error)) {
			$this->data['error_warnings'] = $this->error;
		}
		

		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
									
		if (!isset($this->request->get['trans_id'])) {
			$this->data['action'] = $this->url->link('sale/transaction/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/transaction/update', 'token=' . $this->session->data['token'] . '&trans_id=' . $this->request->get['trans_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/transaction', 'token=' . $this->session->data['token'] . $url, 'SSL');
  		
		if (isset($this->request->get['trans_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$transaction_info = $this->model_sale_transaction->getTransCodeInfo($this->request->get['trans_id']);
    	}
		
    	if (isset($this->request->post['prefix'])) {
    	    $this->data['prefix'] = $this->request->post['prefix'];
    	} elseif (isset($transaction_info)) {
    	    $this->data['prefix'] = $transaction_info['prefix'];
    	} else {
    	    $this->data['prefix'] = '';
    	}
    	  	
    	if (isset($transaction_info)) {
			$this->data['trans_code'] = $transaction_info['trans_code'];
		} else {
      		$this->data['trans_code'] = '';
    	}
		
		
    	if (isset($this->request->post['value'])) {
      		$this->data['value'] = $this->request->post['value'];
    	} elseif (isset($transaction_info)) {
			$this->data['value'] = $transaction_info['value'];
		} else {
      		$this->data['value'] = '';
    	}
		
    	if (isset($this->request->post['is_tpl'])) {
    		$this->data['is_tpl'] = $this->request->post['is_tpl'];
    	} elseif (isset($transaction_info)) {
    		$this->data['is_tpl'] = $transaction_info['is_tpl'];
    	} else {
    		$this->data['is_tpl'] = '';
    	}
    	
	    if (isset($this->request->post['date_start'])) {
       		$this->data['date_start'] = $this->request->post['date_start'];
		} elseif (isset($transaction_info)) {
			$this->data['date_start'] = date('Y-m-d', strtotime($transaction_info['date_start']));
		} else {
			$this->data['date_start'] = date('Y-m-d', time());
		}

		if (isset($this->request->post['date_end'])) {
       		$this->data['date_end'] = $this->request->post['date_end'];
		} elseif (isset($transaction_info)) {
			$this->data['date_end'] = date('Y-m-d', strtotime($transaction_info['date_end']));
		} else {
			$this->data['date_end'] = date('Y-m-d', time());
		}
		
		$this->template = 'sale/transaction_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();		
  	}
	
  	/**
  	 * 校验
  	 * @return boolean
  	 */
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/transaction')) {
      		$this->error[] = $this->language->get('error_permission');
    	}
    	
    	if (strlen(utf8_decode($this->request->post['prefix']))>5) {
    	    $this->error[] = $this->language->get('error_prefix');
    	}
			
    	if ($this->request->post['length'] < 10 || $this->request->post['code'] > 20) {
      		$this->error[] = $this->language->get('error_length');
    	}
    	
		$batch = $this->request->post['batch'];
		if (!empty($batch) && !preg_match("/^\d*$/",$batch) ){
      		$this->error[] = $this->language->get('error_batch');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

	
}
?>