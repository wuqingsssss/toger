<?php
class ControllerModuleLink extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->load_language('module/link');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('link', $this->request->post);		
			
			$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
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
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/link', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		
		
		
		$this->data['action'] =$this->url->link('module/link', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] =$this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['link_module'])) {
			$this->data['modules'] = $this->request->post['link_module'];
		} elseif ($this->config->get('link_module')) { 
			$this->data['modules'] = $this->config->get('link_module');
		}	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/link.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/link')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>