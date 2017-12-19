<?php 
class ControllerTotalShippingCharge extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load_language('total/shipping_charge');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('shipping_charge', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
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
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/shipping_charge', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('total/shipping_charge', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['shipping_charge_status'])) {
			$this->data['shipping_charge_status'] = $this->request->post['shipping_charge_status'];
		} else {
			$this->data['shipping_charge_status'] = $this->config->get('shipping_charge_status');
		}

		if (isset($this->request->post['shipping_charge_sort_order'])) {
			$this->data['shipping_charge_sort_order'] = $this->request->post['shipping_charge_sort_order'];
		} else {
			$this->data['shipping_charge_sort_order'] = $this->config->get('shipping_charge_sort_order');
		}
		
		if (isset($this->request->post['shipping_charge_step'])) {
		    $this->data['shipping_charge_step'] = $this->request->post['shipping_charge_step'];
		} else {
		    $this->data['shipping_charge_step'] = $this->config->get('shipping_charge_step');
		}
		//新用户免配送费最低金额
		if (isset($this->request->post['shipping_new_step'])) {
		    $this->data['shipping_new_step'] = $this->request->post['shipping_new_step'];
		} else {
		    $this->data['shipping_new_step'] = $this->config->get('shipping_new_step');
		}
		
		//新用户配送费
		if (isset($this->request->post['shipping_new_value'])) {
		    $this->data['shipping_new_value'] = $this->request->post['shipping_new_value'];
		} else {
		    $this->data['shipping_new_value'] = $this->config->get('shipping_new_value');
		}
		
		if (isset($this->request->post['shipping_charge_value'])) {
		    $this->data['shipping_charge_value'] = $this->request->post['shipping_charge_value'];
		} else {
		    $this->data['shipping_charge_value'] = $this->config->get('shipping_charge_value');
		}

		$this->template = 'total/shipping_charge.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/shipping_charge')) {
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