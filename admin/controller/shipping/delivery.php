<?php
class ControllerShippingDelivery extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('shipping/delivery');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('delivery', $this->request->post);		
					
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
			'href'      => $this->url->link('shipping/delivery', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('shipping/delivery', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['delivery_express'] = array();
		
		if (isset($this->request->post['delivery_express'])) {
			$this->data['delivery_express'] = $this->request->post['delivery_express'];
		} elseif ($this->config->get('delivery_express')) {
			$this->data['delivery_express'] = $this->config->get('delivery_express');
		}
		
		if (isset($this->request->post['delivery_geo_zone_id'])) {
			$this->data['delivery_geo_zone_id'] = $this->request->post['delivery_geo_zone_id'];
		} else {
			$this->data['delivery_geo_zone_id'] = $this->config->get('delivery_geo_zone_id');
		}
		
		if (isset($this->request->post['delivery_status'])) {
			$this->data['delivery_status'] = $this->request->post['delivery_status'];
		} else {
			$this->data['delivery_status'] = $this->config->get('delivery_status');
		}
		
		if (isset($this->request->post['delivery_description'])) {
			$this->data['delivery_description'] = $this->request->post['delivery_description'];
		} else {
			$this->data['delivery_description'] = $this->config->get('delivery_description');
		}
		
		if (isset($this->request->post['delivery_sort_order'])) {
			$this->data['delivery_sort_order'] = $this->request->post['delivery_sort_order'];
		} else {
			$this->data['delivery_sort_order'] = $this->config->get('delivery_sort_order');
		}				
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
						
		$this->template = 'shipping/delivery.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/delivery')) {
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