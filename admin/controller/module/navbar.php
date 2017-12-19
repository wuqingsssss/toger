<?php
class ControllerModuleNavbar extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/navbar');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('navbar', array());		

			$this->model_design_layout->updateModules('navbar',  $this->request->post['navbar_module']);
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/navbar', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['dimension'])) {
			$this->data['error_dimension'] = $this->error['dimension'];
		} else {
			$this->data['error_dimension'] = array();
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
			'href'      => $this->url->link('module/navbar', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('module/navbar', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
						
		if (isset($this->request->post['navbar_module'])) {
			$this->data['modules'] = $this->request->post['navbar_module'];
		}else {
			//兼容老版本写法，可以自动升级到新版
			$this->data['modules'] =array_merge($this->config->get('navbar_module')?$this->config->get('navbar_module'):array(),$this->model_design_layout->getLayoutModules(array('code'=>'navbar')));
		}

		/* 获取可用的促销专题列表*/
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
		
		$this->template = 'module/navbar.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/navbar')) {
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