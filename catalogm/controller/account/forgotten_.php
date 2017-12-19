<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load_language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('account/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->language->load('mail/forgotten');

			$link=$this->url->link('account/forgotten/reset','u='.$this->encryption->encrypt($this->request->post['email']));

//            $this->log_sys->debug($link);

            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";

			$message .= '<br />';
			
			$message .= '<a href="'.$link.'" target="_parent">'.$link.'</a>';

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			$this->session->data['success'] = $this->language->get('text_success_step1');

			$this->redirect($this->url->link('account/forgotten', '', 'SSL'));
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),     	
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_forgotten'),
			'href'      => $this->url->link('account/forgotten', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}
		
		if(isset($this->request->post['email'])){
			$this->data['email']=$this->request->post['email'];
		}else{
			$this->data['email']='';
		}
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['action'] = $this->url->link('account/forgotten', '', 'SSL');
 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten.tpl';
		} else {
			$this->template = 'default/template/account/forgotten.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
								
		$this->response->setOutput($this->render());		
	}
	
	public function reset(){
		$this->load_language('account/forgotten');
		
		if(!(isset($this->request->get['u']) && $this->request->get['u'])){
			$this->redirect('error/not_found');
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_reset()) {
			$this->load->model('account/customer');
			
			$password=$this->request->post['password'];
			
			$this->model_account_customer->editPassword($this->request->post['email'], $password);
			
			$this->redirect($this->url->link('account/forgotten/success', '', 'SSL'));
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
 		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
 		if (isset($this->request->post['confirm'])) {
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
		
		$this->data['email']=$this->encryption->decrypt($this->request->get['u']);
		
		
		$this->data['action'] = $this->url->link('account/forgotten/reset', 'u='.$this->request->get['u'], 'SSL');
 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten_step2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten_step2.tpl';
		} else {
			$this->template = 'default/template/account/forgotten_step2.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
								
		$this->response->setOutput($this->render());	
	}

	public function success(){
		$this->load_language('account/forgotten');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten_step3.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten_step3.tpl';
		} else {
			$this->template = 'default/template/account/forgotten_step3.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
								
		$this->response->setOutput($this->render());	
	}
	private function validate() {
		
		if (strlen(utf8_decode($this->request->post['email']))<1) {
			$this->error['email'] = $this->language->get('error_email_required');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	}
    	

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validate_reset() {
		
		if ((strlen(utf8_decode($this->request->post['password'])) < 4)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function step($index){
		$this->load_language('account/forgotten');
		
		$this->data['index']=$index;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten_step.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten_step.tpl';
		} else {
			$this->template = 'default/template/account/forgotten_step.tpl';
		}

		$this->render();
	}
	
	public function service(){
		$this->load_language('account/forgotten');
		
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten_service.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/forgotten_service.tpl';
		} else {
			$this->template = 'default/template/account/forgotten_service.tpl';
		}

		$this->render();
	}
}
?>