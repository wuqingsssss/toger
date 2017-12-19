<?php
class ControllerModuleWelcome extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load_language('module/welcome');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('welcome', array());
			//print_r($this->request->post['welcome_module']);
			$this->model_design_layout->updateModules('welcome',  $this->request->post['welcome_module']);
			
			$this->session->data['success'] = $this->language->get('text_success');		
			//$this->redirect($this->url->link('module/welcome', 'token=' . $this->session->data['token'], 'SSL'));
			$this->goback();
		}
				
		$this->setbackparent();
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
			'href'      => $this->url->link('module/welcome', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('module/welcome', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		$this->data['modules'] = array();
		
		if (isset($this->request->post['welcome_module'])) {
			$this->data['modules'] = $this->request->post['welcome_module'];
		} else { 
			//兼容老版本写法，可以自动升级到新版
			$this->data['modules'] =array_merge($this->config->get('welcome_module')?$this->config->get('welcome_module'):array(),$this->model_design_layout->getLayoutModules(array('code'=>'welcome')));	
		}	
		$this->load->model('design/layout');
		
		$layouts=$this->model_design_layout->getLayouts();
		
		foreach($layouts as $row)
			$this->data['layouts'][$row['layout_id']]=$row;
		
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
		
		$this->template = 'module/welcome.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/welcome')) {
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