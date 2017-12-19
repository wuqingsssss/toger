<?php
class ControllerModuleIlexDonation extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/ilex_donation');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ilex_donation', $this->request->post);		
						
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
			'href'      => $this->url->link('module/ilex_donation', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/ilex_donation', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['ilex_donation_module'])) {
			$this->data['modules'] = $this->request->post['ilex_donation_module'];
		} elseif ($this->config->get('ilex_donation_module')) { 
			$this->data['modules'] = $this->config->get('ilex_donation_module');
		}		

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('promotion/promotion');
		
		$filter=array(
			'filter_code' => EnumPromotionTypes::TOTAL_DONATION,
			'filter_datetime' => true
		);
		
		$promotions=$this->model_promotion_promotion->getPromotions($filter);
		
		$this->data['pbs']=array();
		
		foreach($promotions as $result){
			$this->data['pbs'][]=array(
				'name' => $result['pb_name'],
				'value' => $result['pb_id']
			);
		}
		
		
		
		$this->template = 'module/ilex_donation.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ilex_donation')) {
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