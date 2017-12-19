<?php 
class ControllerPaymentCash extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load_language('payment/cash');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cash', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
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
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/cash', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('payment/cash', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['cash_total'])) {
			$this->data['cash_total'] = $this->request->post['cash_total'];
		} else {
			$this->data['cash_total'] = $this->config->get('cash_total'); 
		}
				
		if (isset($this->request->post['cash_order_status_id'])) {
			$this->data['cash_order_status_id'] = $this->request->post['cash_order_status_id'];
		} else {
			$this->data['cash_order_status_id'] = $this->config->get('cash_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['cash_user_group_id'])) {
			$this->data['cash_user_group_id'] = $this->request->post['cash_user_group_id'];
		} else {
			$this->data['cash_user_group_id'] = $this->config->get('cash_user_group_id'); 
		} 
		
		$this->load->model('user/user_group');						
		
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
		
		if (isset($this->request->post['cash_status'])) {
			$this->data['cash_status'] = $this->request->post['cash_status'];
		} else {
			$this->data['cash_status'] = $this->config->get('cash_status');
		}
		
		if (isset($this->request->post['cash_sort_order'])) {
			$this->data['cash_sort_order'] = $this->request->post['cash_sort_order'];
		} else {
			$this->data['cash_sort_order'] = $this->config->get('cash_sort_order');
		}

		$this->template = 'payment/cash.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cash')) {
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