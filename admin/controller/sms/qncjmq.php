<?php
class ControllerSmsQncjmq extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('sms/qncjmq');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=sms/qncjmq&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('qncjmq', $this->request->post);				
			
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

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=sms/qncjmq&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/sms&token=' . $this->session->data['token'];
		

		if (isset($this->request->post['qncjmq_appid'])) {
			$this->data['qncjmq_appid'] = $this->request->post['qncjmq_appid'];
		} else {
			$this->data['qncjmq_appid'] = $this->config->get('qncjmq_appid');
		}


		if (isset($this->request->post['qncjmq_appsecret'])) {
			$this->data['qncjmq_appsecret'] = $this->request->post['qncjmq_appsecret'];
		} else {
			$this->data['qncjmq_appsecret'] = $this->config->get('qncjmq_appsecret');
		}
		
		if (isset($this->request->post['qncjmq_timeout'])) {
			$this->data['qncjmq_timeout'] = $this->request->post['qncjmq_timeout'];
		} else {
			$this->data['qncjmq_timeout'] = $this->config->get('qncjmq_timeout');
		}
		
		if (isset($this->request->post['qncjmq_sign'])) {
			$this->data['qncjmq_timeout'] = $this->request->post['qncjmq_sign'];
		} else {
			$this->data['qncjmq_sign'] = $this->config->get('qncjmq_sign');
		}
		
		if (isset($this->request->post['qncjmq_status'])) {
			$this->data['qncjmq_status'] = $this->request->post['qncjmq_status'];
		} else {
			$this->data['qncjmq_status'] = $this->config->get('qncjmq_status');
		}
		
		if (isset($this->request->post['qncjmq_sort_order'])) {
			$this->data['qncjmq_sort_order'] = $this->request->post['qncjmq_sort_order'];
		} else {
			$this->data['qncjmq_sort_order'] = $this->config->get('qncjmq_sort_order');
		}
		
		$this->template = 'sms/qncjmq.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'sms/qncjmq')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>