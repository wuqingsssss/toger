<?php
class ControllerModulePartners extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/partners');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
		   
			$this->model_setting_setting->editSetting('partners', $this->request->post);		
					
			$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
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
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/partners', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
  		
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/partners&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['partners_module'])) {
			$this->data['modules'] = $this->request->post['partners_module'];
		} elseif ($this->config->get('partners_module')) { 
			$this->data['modules'] = $this->config->get('partners_module');
		}	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('design/banner');
		
		$this->data['banners'] = $this->model_design_banner->getBanners();
		
		$this->template = 'module/partners.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	
}
?>