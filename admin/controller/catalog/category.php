<?php 
class ControllerCatalogCategory extends Controller { 
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/category');
	}
	
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url=$this->getUrlCommonParameters();
			
		$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'].$url, 'SSL')); 
	}
 
	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->addCategory($this->request->post);

			$this->redirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function delete() {
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$this->redirectToList();
		}

		$this->getList();
	}
	
	public function changeStatus() {
		$this->init();
	
		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->editCategoryStatus($category_id,$this->request->get['status']);
			}
	
			$this->redirectToList();
		}
	
		$this->getList();
	}
	
	private function getUrlCommonParameters(){
		$url='';
		
		if(isset($this->request->get['filter_category_id'])){
			$url.="&filter_category_id=".$this->request->get['filter_category_id'];
		}
		
		return $url;
	}

	private function getList() {
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		$url=$this->getUrlCommonParameters();
   		
   		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['enabled'] = $this->url->link('catalog/category/changeStatus', 'status=1&token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['disabled'] = $this->url->link('catalog/category/changeStatus', 'status=0&token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['categories'] = array();
		
		if(isset($this->request->get['page'])){
			$page=(int)$this->request->get['page'];
		}else{
			$page=1;
		}
		
		if(isset($this->request->get['sort'])){
			$sort=(int)$this->request->get['sort'];
		}else{
			$sort='c.parent_id,c.category_id';
		}
		
		if(isset($this->request->get['order'])){
			$order=(int)$this->request->get['order'];
		}else{
			$order='ASC';
		}
		
		if(isset($this->request->get['filter_category_id'])){
			$filter_category_id=$this->request->get['filter_category_id'];
		}else{
			$filter_category_id=NULL;
		}
		
		
		$limit=$this->config->get('config_admin_limit');
		
		$data = array(
			'filter_category_id'            => $filter_category_id,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);
		
		$total=$this->model_catalog_category->getTotalCategories($data);
		
		$results = $this->model_catalog_category->getFilterCategories($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'].$url, 'SSL')
			);
			
			if($this->model_catalog_category->hasSubCategories($result['category_id'])){
				$action[] = array(
					'text' => $this->language->get('text_manage_sub'),
					'href' => $this->url->link('catalog/category','&token=' . $this->session->data['token'] . '&filter_category_id=' . $result['category_id'], 'SSL')
				);
			}
			
			$action[] = array(
				'text' => $this->language->get('text_manage_product'),
				'href' => $this->url->link('catalog/product','&token=' . $this->session->data['token'] . '&filter_category_id=' . $result['category_id'], 'SSL')
			);
					
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'products'        => $this->model_catalog_category->getNumProductsInCategory($result['category_id']),
				'sort_order'  => $result['sort_order'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		
		$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] .$url. '&page={page}', 'SSL');
		

		$this->data['pagination'] = $pagination->render();
		
	
 
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
		
		$this->template = 'catalog/category_list.tpl';
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
			$this->data['error_name'] = array();
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		$url=$this->getUrlCommonParameters();
   		
   		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
			
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_add'),
				'href'      => $this->data['action'],
				'separator' => $this->language->get('text_breadcrumb_separator')
			);
   		} else {
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'].$url, 'SSL');
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_edit'),
				'href'      => $this->data['action'],
				'separator' => $this->language->get('text_breadcrumb_separator')
			);
		}
		
		$this->data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
    	}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($category_info)) {
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

		$categories = $this->model_catalog_category->getCategories(0);

		// Remove own id from list
		if (isset($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}
		}

		$this->data['categories'] = $categories;

		$values=array(
			'parent_id' =>0,
			'keyword' =>'',
			'image' =>'',
			'code' =>'',
			'top' =>0,
			'column' =>1,
			'sort_order' =>0,
			'status' =>1
		);
		
		foreach ($values as $key => $value) {
			if (isset($this->request->post[$key])) {
				$this->data[$key] = $this->request->post[$key];
			} elseif (isset($category_info)) {
				$this->data[$key] = $category_info[$key];
			} else {
				$this->data[$key] = $value;
			}
		}
		
		if(!isset($category_info) && isset($this->request->get['filter_category_id'])){
			$this->data['parent_id']=$this->request->get['filter_category_id'];
		}
				
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($category_info)) {
			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$this->data['category_store'] = array(0);
		}			
		
		$this->load->model('tool/image');

		if (isset($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	
		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($category_info)) {
			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'catalog/category_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validateForm() {
		$rules=$this->load->rule();
		$this->load_language('error_msg');
		 
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['name'])) < 1)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] =  $this->language->get('error_name');
		}
					
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
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