<?php
class ControllerShippingPickupAddr extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('shipping/pickupaddr');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('addr', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
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
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/pickupaddr', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('shipping/pickupaddr', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['addrs'])) {
			$this->data['modules'] = $this->request->post['addrs'];
		} elseif ($this->config->get('addrs')) {
			$this->data['modules'] = $this->config->get('addrs');
		}
		
		if (isset($this->request->post['pickupaddr_geo_zone_id'])) {
			$this->data['pickupaddr_geo_zone_id'] = $this->request->post['pickupaddr_geo_zone_id'];
		} else {
			$this->data['pickupaddr_geo_zone_id'] = $this->config->get('pickupaddr_geo_zone_id');
		}
		
		if (isset($this->request->post['pickupaddr_status'])) {
			$this->data['pickupaddr_status'] = $this->request->post['pickupaddr_status'];
		} else {
			$this->data['pickupaddr_status'] = $this->config->get('pickupaddr_status');
		}
		
		if (isset($this->request->post['pickupaddr_description'])) {
			$this->data['pickupaddr_description'] = $this->request->post['pickupaddr_description'];
		} else {
			$this->data['pickupaddr_description'] = $this->config->get('pickupaddr_description');
		}
		
		if (isset($this->request->post['pickupaddr_sort_order'])) {
			$this->data['pickupaddr_sort_order'] = $this->request->post['pickupaddr_sort_order'];
		} else {
			$this->data['pickupaddr_sort_order'] = $this->config->get('pickupaddr_sort_order');
		}				
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
						
		$this->template = 'shipping/pickupaddr.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/pickupaddr')) {
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