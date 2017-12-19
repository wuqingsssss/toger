<?php 
class ControllerTotalTotalDiscount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load_language('total/total_discount');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('total_discount', $this->request->post);
		
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
			'href'      => $this->url->link('total/total_discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('total/total_discount', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['total_discount_status'])) {
			$this->data['total_discount_status'] = $this->request->post['total_discount_status'];
		} else {
			$this->data['total_discount_status'] = $this->config->get('total_discount_status');
		}

		if (isset($this->request->post['total_discount_sort_order'])) {
			$this->data['total_discount_sort_order'] = $this->request->post['total_discount_sort_order'];
		} else {
			$this->data['total_discount_sort_order'] = $this->config->get('total_discount_sort_order');
		}
		
		if (isset($this->request->post['total_discount_step1'])) {
		    $this->data['total_discount_step1'] = $this->request->post['total_discount_step1'];
		} else {
		    $this->data['total_discount_step1'] = $this->config->get('total_discount_step1');
		}
		
		if (isset($this->request->post['total_discount_discount1'])) {
		    $this->data['total_discount_discount1'] = $this->request->post['total_discount_discount1'];
		} else {
		    $this->data['total_discount_discount1'] = $this->config->get('total_discount_discount1');
		}
		
		if (isset($this->request->post['total_discount_step2'])) {
		    $this->data['total_discount_step2'] = $this->request->post['total_discount_step2'];
		} else {
		    $this->data['total_discount_step2'] = $this->config->get('total_discount_step2');
		}
		
		if (isset($this->request->post['total_discount_discount2'])) {
		    $this->data['total_discount_discount2'] = $this->request->post['total_discount_discount2'];
		} else {
		    $this->data['total_discount_discount2'] = $this->config->get('total_discount_discount2');
		}
		
		if (isset($this->request->post['total_discount_step3'])) {
		    $this->data['total_discount_step3'] = $this->request->post['total_discount_step3'];
		} else {
		    $this->data['total_discount_step3'] = $this->config->get('total_discount_step3');
		}
		
		if (isset($this->request->post['total_discount_discount3'])) {
		    $this->data['total_discount_discount3'] = $this->request->post['total_discount_discount3'];
		} else {
		    $this->data['total_discount_discount3'] = $this->config->get('total_discount_discount3');
		}
		
		if (isset($this->request->post['total_discount_start_date'])) {
		    $this->data['total_discount_start_date'] = $this->request->post['total_discount_start_date'];
		} else {
		    $this->data['total_discount_start_date'] = $this->config->get('total_discount_start_date');
		}
		
		if (isset($this->request->post['total_discount_end_date'])) {
		    $this->data['total_discount_end_date'] = $this->request->post['total_discount_end_date'];
		} else {
		    $this->data['total_discount_end_date'] = $this->config->get('total_discount_end_date');
		}

		$this->template = 'total/total_discount.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/total_discount')) {
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