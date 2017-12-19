<?php 
class ControllerDesignLayout extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('design/layout');
	}
	
	protected function redirectToList(){
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
		
		$this->redirect($this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
 
	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_layout->addLayout($this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_layout->editLayout($this->request->get['layout_id'], $this->request->post);

			$this->redirectToList();
		}

		$this->getForm();
	}
 

	
	public function delete() {
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $layout_id) {
				$this->model_design_layout->deleteLayout($layout_id);
			}
			
			$this->redirectToList();
		}

		$this->getList();
	}
	public function deletemodule() {
		$this->init();
	
		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $layout_module_id) {
				$this->model_design_layout->deleteLayoutModule($layout_module_id);
			}
				
			$this->redirect($this->url->link('design/modulelist', 'token=' . $this->session->data['token'] . '&layout_id=' . $this->request->get['layout_id'], 'SSL'));
		}
		elseif (isset($this->request->get['lm_id'])&&$this->request->get['lm_id'])
		{
			$this->model_design_layout->deleteLayoutModule((int)$this->request->get['lm_id']);
			
		}
	
		$this->modulelist();
	}
	public function modulelist(){
		$this->init();

		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		
		
		$layout_info = $this->model_design_layout->getLayout($this->request->get['layout_id']);
		
		$this->data['breadcrumbs'][] = array(
				'text'      =>  $layout_info['name'],
				'href'      => $this->url->link('design/layout/modulelist', 'token=' . $this->session->data['token'] . '&layout_id=' . $layout_info['layout_id'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		
		$layout_id=$this->request->get['layout_id'];
		$results = $this->model_design_layout->getLayoutModules(array('layout_id'=>$layout_id));
		
		$this->data['insert'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/layout/deletemodule', 'token=' . $this->session->data['token'] . $url, 'SSL');
		

		foreach ($results as $key=> $result) {
			$action = array();
				
			if($result['code']){
				
			$code=explode('.', $result['code']);
			
			$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('module/'.$code[0], 'token=' . $this->session->data['token'] . '&layout_id=' . $result['layout_id'] , 'SSL')
			);
			$action[] = array(
					'text' => $this->language->get('button_remove'),
					'href' => $this->url->link('design/layout/deletemodule/', 'token=' . $this->session->data['token'] . '&layout_id=' . $result['layout_id']. '&lm_id=' . $result['layout_module_id'] , 'SSL')
			);
			}
			$results[$key]['module_name']= $code[0];
			$results[$key]['module_id']= $code[1];
			$results[$key]['action']= $action;
			$results[$key]['selected']=  isset($this->request->post['selected']) && in_array($result['layout_id'], $this->request->post['selected']);
		}
		
		
		
		//print_r($results);
		
		$this->data['layoutmodules']=$results;
		
		$this->template = 'design/layout_module_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	
	}
	
	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['insert'] = $this->url->link('design/layout/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/layout/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		 
		$this->data['layouts'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$layout_total = $this->model_design_layout->getTotalLayouts();
		
		$results = $this->model_design_layout->getLayouts($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/layout/update', 'token=' . $this->session->data['token'] . '&layout_id=' . $result['layout_id'] . $url, 'SSL')
			);
			$action[] = array(
					'text' => $this->language->get('text_edit_moudle'),
					'href' => $this->url->link('design/layout/modulelist', 'token=' . $this->session->data['token'] . '&layout_id=' . $result['layout_id'] . $url, 'SSL')
			);

			$this->data['layouts'][] = array(
				'layout_id' => $result['layout_id'],
				'name'      => $result['name'],
				'selected'  => isset($this->request->post['selected']) && in_array($result['layout_id'], $this->request->post['selected']),				
				'action'    => $action
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
		
		$this->data['sort_name'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $layout_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'design/layout_list.tpl';
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

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
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
			'href'      => $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		if (!isset($this->request->get['layout_id'])) { 
			$this->data['action'] = $this->url->link('design/layout/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('design/layout/update', 'token=' . $this->session->data['token'] . '&layout_id=' . $this->request->get['layout_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['layout_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$layout_info = $this->model_design_layout->getLayout($this->request->get['layout_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($layout_info)) {
			$this->data['name'] = $layout_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['priority'])) {
			$this->data['priority'] = $this->request->post['priority'];
		} elseif (isset($layout_info)) {
			$this->data['priority'] = $layout_info['priority'];
		} else {
			$this->data['priority'] = '';
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['layout_route'])) {
			$this->data['layout_routes'] = $this->request->post['layout_route'];
		} elseif (isset($layout_info)) {
			$this->data['layout_routes'] = $this->model_design_layout->getLayoutRoutes($this->request->get['layout_id']);
		} else {
			$this->data['layout_routes'] = array();
		}	
				
		$this->template = 'design/layout_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('setting/store');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		
		foreach ($this->request->post['selected'] as $layout_id) {
			if ($this->config->get('config_layout_id') == $layout_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
			
			$product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);
	
			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);
	
			if ($category_total) {
				$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
			}
							
			$information_total = $this->model_catalog_information->getTotalInformationsByLayoutId($layout_id);
		
			if ($information_total) {
				$this->error['warning'] = sprintf($this->language->get('error_information'), $information_total);
			}
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>