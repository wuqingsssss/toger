<?php
class ControllerSmsApp2e extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('sms/app2e');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=sms/app2e&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('app2e', $this->request->post);				
			
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

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=sms/app2e&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/sms&token=' . $this->session->data['token'];
		

		if (isset($this->request->post['app2e_appid'])) {
			$this->data['app2e_appid'] = $this->request->post['app2e_appid'];
		} else {
			$this->data['app2e_appid'] = $this->config->get('app2e_appid');
		}


		if (isset($this->request->post['app2e_appsecret'])) {
			$this->data['app2e_appsecret'] = $this->request->post['app2e_appsecret'];
		} else {
			$this->data['app2e_appsecret'] = $this->config->get('app2e_appsecret');
		}
		
		if (isset($this->request->post['app2e_timeout'])) {
			$this->data['app2e_timeout'] = $this->request->post['app2e_timeout'];
		} else {
			$this->data['app2e_timeout'] = $this->config->get('app2e_timeout');
		}
		
		if (isset($this->request->post['app2e_sign'])) {
			$this->data['app2e_timeout'] = $this->request->post['app2e_sign'];
		} else {
			$this->data['app2e_sign'] = $this->config->get('app2e_sign');
		}
	
		if (isset($this->request->post['app2e_sort_order'])) {
			$this->data['app2e_sort_order'] = $this->request->post['app2e_sort_order'];
		} else {
			$this->data['app2e_sort_order'] = $this->config->get('app2e_sort_order');
		}
		
		if (isset($this->request->post['app2e_status'])) {
			$this->data['app2e_status'] = $this->request->post['app2e_status'];
		} else {
			$this->data['app2e_status'] = $this->config->get('app2e_status');
		}
		
		if (isset($this->request->post['app2e_sort_order'])) {
			$this->data['app2e_sort_order'] = $this->request->post['app2e_sort_order'];
		} else {
			$this->data['app2e_sort_order'] = $this->config->get('app2e_sort_order');
		}
		
		$this->template = 'sms/app2e.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'sms/app2e')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['app2e_appid']) {
			$this->error['appid'] = $this->language->get('error_appid');
		}

	
		
		if (!$this->request->post['app2e_appsecret']) {
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