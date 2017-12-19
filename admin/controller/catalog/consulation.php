<?php
class ControllerCatalogConsulation extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/consulation');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/consulation');
	}
	
	protected function recirectToList(){
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
					
		$this->redirect($this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
 
	public function index() {
		$this->init();
		
		$this->getList();
	} 

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_consulation->addConsulation($this->request->post);
			
			$this->recirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_consulation->editConsulation($this->request->get['consulation_id'], $this->request->post);
			
			$this->recirectToList();
		}

		$this->getForm();
	}

	public function delete() { 
		$this->init();
		
		if($this->validateDelete()){
			if(isset($this->request->get['consulation_id'])){
				$this->model_catalog_consulation->deleteConsulation($this->request->get['consulation_id']);
			}
			
			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $select_id) {
					$this->model_catalog_consulation->deleteConsulation($select_id);
				}
			}
			
			$this->recirectToList();
		}

		$this->getList();
	}
	
	private function getFilterParams(){
		$params=array('filter_customer_name','filter_type','filter_content','filter_name','filter_product_id','filter_status','filter_date_added');
		
		return $params;
	}
	
	private function getCommonUrlParameters(){
		$url = '';
		
		foreach($this->getFilterParams() as $param){
			if (isset($this->request->get[$param])) {
				$url .= "&$param=" . $this->request->get[$param];
			}
		}
			
		return $url;
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
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
		
		
		foreach($this->getFilterParams() as $param){
			if(isset($this->request->get[$param])){
				${$param}=trim($this->request->get[$param]);
			}else{
				${$param}=NULL;
			}
		}
		
		
		$url = $this->getCommonUrlParameters();
			
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
			'href'      => $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('catalog/consulation/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/consulation/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['consulations'] = array();

		$limit=$this->config->get('config_admin_limit');
		
		$data = array(
			'filter_customer_name'  => $filter_customer_name,
			'filter_type'  => $filter_type,
			'filter_product_id'  => $filter_product_id,
			'filter_content'  => $filter_content,
			'filter_status'  => $filter_status,
			'filter_date_added'  => $filter_date_added,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$total = $this->model_catalog_consulation->getTotalConsulations($data);
		
		$results = $this->model_catalog_consulation->getConsulations($data);
		
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/consulation/update', 'token=' . $this->session->data['token'] . '&consulation_id=' . $result['consulation_id'] . $url, 'SSL')
			);
			
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->link('catalog/consulation/delete', 'token=' . $this->session->data['token'] . '&consulation_id=' . $result['consulation_id'] . $url, 'SSL')
			);
						
			$this->data['consulations'][] = array(
				'consulation_id'  => $result['consulation_id'],
				'name'       => $result['name'],
				'content'       => $result['content'], //TODO:增加长度限制
				'customer_name'     => $result['customer_name'],
				'type'     => EnumConsulationTypes::getConsulationType($result['type']),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['consulation_id'], $this->request->post['selected']),
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

		$url = $this->getCommonUrlParameters();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_product'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_author'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . '&sort=r.author' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . '&sort=r.status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		
		$url = $this->getCommonUrlParameters();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['types']=EnumConsulationTypes::getConsulationTypes();
		
		foreach($this->getFilterParams() as $param){
			if(isset($this->request->get[$param])){				
				$this->data[$param]=$this->request->get[$param];
			}else{
				$this->data[$param]='';
			}
		}
		
		$this->template = 'catalog/consulation_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function getForm() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$requires=array('customer_name','product','content','reply','type');
		
		foreach($requires as $field){
			if (isset($this->error[$field])) {
				$this->data['error_'.$field] = $this->error[$field];
			} else {
				$this->data['error_'.$field] = '';
			}
			
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

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
										
		if (!isset($this->request->get['consulation_id'])) { 
			$this->data['action'] = $this->url->link('catalog/consulation/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/consulation/update', 'token=' . $this->session->data['token'] . '&consulation_id=' . $this->request->get['consulation_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/consulation', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['consulation_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$result_info = $this->model_catalog_consulation->getConsulation($this->request->get['consulation_id']);
		}
			
		$this->load->model('catalog/product');
		
		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (isset($result_info)) {
			$this->data['product_id'] = $result_info['product_id'];
		} else {
			$this->data['product_id'] = '';
		}

		$fields=array('customer_name','product','content','reply','status','type');
		
		foreach($fields as $field){
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (isset($result_info)) {
				$this->data[$field] = $result_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}
		$this->data['types']=EnumConsulationTypes::getConsulationTypes();
		
		$this->template = 'catalog/consulation_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/consulation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((strlen(utf8_decode($this->request->post['customer_name'])) < 1)) {
			$this->error['customer_name'] = $this->language->get('error_customer_name');
		}
		
		if (! isset($this->request->post['type'])) {
			$this->error['type'] = $this->language->get('error_type');
		}
		
		if (! $this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}
		
		if (strlen(utf8_decode($this->request->post['content'])) < 1) {
			$this->error['content'] = $this->language->get('error_content');
		}
				
		if (strlen(utf8_decode($this->request->post['reply'])) < 1) {
			$this->error['reply'] = $this->language->get('error_reply');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/consulation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
}
?>