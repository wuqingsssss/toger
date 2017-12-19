<?php
class ControllerSmsMont extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('sms/mont');

		$this->document->settitle($this->language->get('heading_title'));

		if (isset($this->error['appid'])) {
			$this->data['error_appid'] = $this->error['appid'];
		} else {
			$this->data['error_appid'] = '';
		}
		

		
		if (isset($this->error['appsecret'])) {
			$this->data['error_appsecret'] = $this->error['error_appsecret'];
		} else {
			$this->data['error_appsecret'] = '';
		}
		
   		$this->data['breadcrumbs']  = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/sms&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_smspath'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sms/mont&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('mont', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( HTTPS_SERVER . 'index.php?route=extension/sms&token=' . $this->session->data['token']);
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



		if (isset($this->error['appsecret'])) {
			$this->data['error_appsecret'] = $this->error['appsecret'];
		} else {
			$this->data['error_appsecret'] = '';
		}

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=sms/mont&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/sms&token=' . $this->session->data['token'];
		

		if (isset($this->request->post['mont_appid'])) {
			$this->data['mont_appid'] = $this->request->post['mont_appid'];
		} else {
			$this->data['mont_appid'] = $this->config->get('mont_appid');
		}


		if (isset($this->request->post['mont_appsecret'])) {
			$this->data['mont_appsecret'] = $this->request->post['mont_appsecret'];
		} else {
			$this->data['mont_appsecret'] = $this->config->get('mont_appsecret');
		}
		
		if (isset($this->request->post['mont_timeout'])) {
			$this->data['mont_timeout'] = $this->request->post['mont_timeout'];
		} else {
			$this->data['mont_timeout'] = $this->config->get('mont_timeout');
		}
		
		if (isset($this->request->post['mont_sign'])) {
			$this->data['mont_timeout'] = $this->request->post['mont_sign'];
		} else {
			$this->data['mont_sign'] = $this->config->get('mont_sign');
		}
		
		if (isset($this->request->post['mont_status'])) {
			$this->data['mont_status'] = $this->request->post['mont_status'];
		} else {
			$this->data['mont_status'] = $this->config->get('mont_status');
		}
		
		if (isset($this->request->post['mont_sort_order'])) {
			$this->data['mont_sort_order'] = $this->request->post['mont_sort_order'];
		} else {
			$this->data['mont_sort_order'] = $this->config->get('mont_sort_order');
		}
		
		$this->template = 'sms/mont.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'sms/mont')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mont_appid']) {
			$this->error['appid'] = $this->language->get('error_appid');
		}

	
		
		if (!$this->request->post['mont_appsecret']) {
			$this->error['appsecret'] = $this->language->get('error_appsecret');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>