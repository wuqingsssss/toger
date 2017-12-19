<?php 
class ControllerCatalogDownloadCategory extends Controller { 
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/download_category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/download');
	}
	
	protected function recirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('catalog/downloadcategory', 'token=' . $this->session->data['token'], 'SSL'));
	}
 
	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_download->addCategory($this->request->post);

			$this->recirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_download->editCategory($this->request->get['category_id'], $this->request->post);
			
			$this->recirectToList();
		}

		$this->getForm();
	}

	public function delete() {
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_download->deleteCategory($category_id);
			}

			$this->recirectToList();
		}

		$this->getList();
	}
	
	public function changeStatus() {
		$this->init();
	
		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_download->editCategoryStatus($category_id,$this->request->get['status']);
			}
	
			$this->recirectToList();
		}
	
		$this->getList();
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
			'href'      => $this->url->link('catalog/downloadcategory', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		$this->data['insert'] = $this->url->link('catalog/downloadcategory/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/downloadcategory/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['enabled'] = $this->url->link('catalog/downloadcategory/changeStatus', 'status=1&token=' . $this->session->data['token'], 'SSL');
		$this->data['disabled'] = $this->url->link('catalog/downloadcategory/changeStatus', 'status=0&token=' . $this->session->data['token'], 'SSL');
		$this->data['categories'] = array();
		
		$results = $this->model_catalog_download->getCategories(0);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/downloadcategory/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'], 'SSL')
			);
			
			$action[] = array(
				'text' => $this->language->get('text_manage_product'),
				'href' => $this->url->link('catalog/download','&token=' . $this->session->data['token'] . '&filter_category_id=' . $result['category_id'], 'SSL')
			);
					
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'sort_order'  => $result['sort_order'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
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
		
		$this->template = 'catalog/download_category_list.tpl';
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
			'href'      => $this->url->link('catalog/downloadcategory', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('catalog/downloadcategory/insert', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_add'),
				'href'      => $this->data['action'],
				'separator' => $this->language->get('text_breadcrumb_separator')
			);
   		} else {
			$this->data['action'] = $this->url->link('catalog/downloadcategory/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'], 'SSL');
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_edit'),
				'href'      => $this->data['action'],
				'separator' => $this->language->get('text_breadcrumb_separator')
			);
		}
		
		$this->data['cancel'] = $this->url->link('catalog/downloadcategory', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_catalog_download->getCategory($this->request->get['category_id']);
    	}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($category_info)) {
			$this->data['category_description'] = $this->model_catalog_download->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

		$categories = $this->model_catalog_download->getCategories(0);

		// Remove own id from list
		if (isset($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['download_category_id']) {
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
				
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($category_info)) {
			$this->data['category_store'] = $this->model_catalog_download->getCategoryStores($this->request->get['category_id']);
		} else {
			$this->data['category_store'] = array(0);
		}			
		
		$this->load->model('tool/image');

		if (isset($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
	
		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($category_info)) {
			$this->data['category_layout'] = $this->model_catalog_download->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'catalog/download_category_form.tpl';
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
			if ((strlen(utf8_decode($value['name'])) < 2) || (strlen(utf8_decode($value['name'])) > 255)) {
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