<?php
class ControllerModuleBossCarousel extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/boss_carousel');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boss_carousel', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
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
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/boss_carousel', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/boss_carousel', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['boss_carousel_module'])) {
			$this->data['modules'] = $this->request->post['boss_carousel_module'];
		} elseif ($this->config->get('boss_carousel_module')) { 
			$this->data['modules'] = $this->config->get('boss_carousel_module');
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('design/banner');
		
		$this->data['banners'] = $this->model_design_banner->getBanners();

		$this->template = 'module/boss_carousel.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/boss_carousel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['boss_carousel_module'])) {
			foreach ($this->request->post['boss_carousel_module'] as $key => $value) {				
				if (!$value['width'] || !$value['height']) {
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
	
	public function install() 
	{
		$this->load->model('setting/setting');
		
		$boss_carousel = array('boss_carousel_module' => array ( 
		0 => array ( 'width' => 142, 'height' => 33, 'layout_id' => 0, 'position' => 'footer_top', 'status' => 1, 'sort_order' => 2 )
		));
		
		$this->model_setting_setting->editSetting('boss_carousel', $boss_carousel);		
	}
}
?>