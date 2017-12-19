<?php
class ControllerModuleProductListCat extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/product_list_cat');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
						$this->model_setting_setting->editSetting('product_list_cat', array());
			
			$this->model_design_layout->updateModules('product_list_cat',  $this->request->post['product_list_cat_module']);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('module/product_list_cat', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}
				
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/product_list_cat', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('module/product_list_cat', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$this->data['categories'] = array();
		
		$this->load->model('catalog/category');
		$results = $this->model_catalog_category->getCategories(0);
		foreach ($results as $result) {
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name']
			);
		}
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['product_list_cat_module'])) {
			$this->data['modules'] = $this->request->post['product_list_cat_module'];
		} else{ 
			//兼容老版本写法，可以自动升级到新版
			$this->data['modules'] =array_merge($this->config->get('product_list_cat_module')?$this->config->get('product_list_cat_module'):array(),$this->model_design_layout->getLayoutModules(array('code'=>'product_list_cat')));
			
		}		
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		
		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
		
		$this->template = 'module/product_list_cat.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/product_list_cat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['product_list_cat_module'])) {
			foreach ($this->request->post['product_list_cat_module'] as $key => $value) {
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
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