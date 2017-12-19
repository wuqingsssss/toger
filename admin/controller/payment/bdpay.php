<?php
class ControllerPaymentBdpay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('payment/bdpay');

		$this->document->settitle($this->language->get('heading_title'));
		echo($this->language);
		if (isset($this->error['appid'])) {
			$this->data['error_appid'] = $this->error['appid'];
		} else {
			$this->data['error_appid'] = '';
		}
		

		
		if (isset($this->error['apikey'])) {
			$this->data['error_apikey'] = $this->error['error_apikey'];
		} else {
			$this->data['error_apikey'] = '';
		}
		
   		$this->data['breadcrumbs']  = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/bdpay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('bdpay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['appid'])) {
			$this->data['error_appid'] = $this->error['appid'];
		} else {
			$this->data['error_appid'] = '';
		}



		if (isset($this->error['apikey'])) {
			$this->data['error_apikey'] = $this->error['apikey'];
		} else {
			$this->data['error_apikey'] = '';
		}

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/bdpay&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		

		if (isset($this->request->post['bdpay_appid'])) {
			$this->data['bdpay_appid'] = $this->request->post['bdpay_appid'];
		} else {
			$this->data['bdpay_appid'] = $this->config->get('bdpay_appid');
		}


		if (isset($this->request->post['bdpay_apikey'])) {
			$this->data['bdpay_apikey'] = $this->request->post['bdpay_apikey'];
		} else {
			$this->data['bdpay_apikey'] = $this->config->get('bdpay_apikey');
		}
		
		if (isset($this->request->post['bdpay_order_status_id'])) {
			$this->data['bdpay_order_status_id'] = $this->request->post['bdpay_order_status_id'];
		} else {
			$this->data['bdpay_order_status_id'] = $this->config->get('bdpay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['bdpay_status'])) {
			$this->data['bdpay_status'] = $this->request->post['bdpay_status'];
		} else {
			$this->data['bdpay_status'] = $this->config->get('bdpay_status');
		}
		
		if (isset($this->request->post['bdpay_sort_order'])) {
			$this->data['bdpay_sort_order'] = $this->request->post['bdpay_sort_order'];
		} else {
			$this->data['bdpay_sort_order'] = $this->config->get('bdpay_sort_order');
		}
		
		$this->template = 'payment/bdpay.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bdpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['bdpay_appid']) {
			$this->error['appid'] = $this->language->get('error_appid');
		}

	
		
		if (!$this->request->post['bdpay_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>