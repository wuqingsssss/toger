<?php 
class ControllerTotalDiscount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load_language('total/discount');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('discount', $this->request->post);
		
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
			'href'      => $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['discount_status'])) {
			$this->data['discount_status'] = $this->request->post['discount_status'];
		} else {
			$this->data['discount_status'] = $this->config->get('discount_status');
		}

		if (isset($this->request->post['discount_sort_order'])) {
			$this->data['discount_sort_order'] = $this->request->post['discount_sort_order'];
		} else {
			$this->data['discount_sort_order'] = $this->config->get('discount_sort_order');
		}
		
		if (isset($this->request->post['discount_type'])) {
		    $this->data['discount_type'] = $this->request->post['discount_type'];
		} else {
		    $this->data['discount_type'] = $this->config->get('discount_type');
		}
		
		if (isset($this->request->post['discount_value'])) {
		    $this->data['discount_value'] = $this->request->post['discount_value'];
		} else {
		    $this->data['discount_value'] = $this->config->get('discount_value');
		}
		
		if (isset($this->request->post['discount_condition'])) {
		    $this->data['discount_condition'] = $this->request->post['discount_condition'];
		} else {
		    $this->data['discount_condition'] = $this->config->get('discount_condition');
		}
		
		if (isset($this->request->post['discount_name'])) {
		    $this->data['discount_name'] = $this->request->post['discount_name'];
		} else {
		    $this->data['discount_name'] = $this->config->get('discount_name');
		}
		

		$this->template = 'total/discount.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/discount')) {
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