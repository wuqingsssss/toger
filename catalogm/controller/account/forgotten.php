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

			$link=$this->url->link('account/forgotten/reset','u='.$this->encryption->encrypt($this->request->post['mobile']));
			$this->redirect($link);

////            $this->log_sys->debug($link);
//
//            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
//
//			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
//
//			$message .= '<br />';
//
//			$message .= '<a href="'.$link.'" target="_parent">'.$link.'</a>';
//
//			$mail = new Mail();
//			$mail->protocol = $this->config->get('config_mail_protocol');
//			$mail->parameter = $this->config->get('config_mail_parameter');
//			$mail->hostname = $this->config->get('config_smtp_host');
//			$mail->username = $this->config->get('config_smtp_username');
//			$mail->password = $this->config->get('config_smtp_password');
//			$mail->port = $this->config->get('config_smtp_port');
//			$mail->timeout = $this->config->get('config_smtp_timeout');
//			$mail->setTo($this->request->post['mobile']);
//			$mail->setFrom($this->config->get('config_mobile'));
//			$mail->setSender($this->config->get('config_name'));
//			$mail->setSubject($subject);
//			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
//			$mail->send();
//
//			$this->session->data['success'] = $this->language->get('text_success_step1');
//
//			$this->redirect($this->url->link('account/forgotten', '', 'SSL'));
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
		
		if (isset($this->error['mobile'])) {
			$this->data['error_mobile'] = $this->error['mobile'];
		} else {
			$this->data['error_mobile'] = '';
		}
		
		if (isset($this->error['mobile_vcode'])) {
			$this->data['error_mobile_vcode'] = $this->error['mobile_vcode'];
		} else {
			$this->data['error_mobile_vcode'] = '';
		}
		
		if(isset($this->request->post['mobile'])){
			$this->data['mobile']=$this->request->post['mobile'];
		}else{
			$this->data['mobile']='';
		}
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
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
			'common/footer35',
			'common/header35'	
		);
								
		$this->response->setOutput($this->render());		
	}

	private function validateMobileFmt($mobile) {
		$this->load_language('account/forgotten');
		
		$errorMsg = null;
		if ((strlen(utf8_decode($mobile)) < 1)) {
			$errorMsg = '请填写手机号';
		} else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
			$errorMsg = '手机号格式错误';
		} else if (!$this->model_account_customer->getCustomerByMobile($mobile)) {
			$errorMsg = '未注册的手机号';
		}
		return $errorMsg;
	}

	public function validate_mobile() {
		
		if(!isset($this->session->data['enter_route'])||$this->session->data['enter_route']=='account/forgotten/validate_mobile')
		{
			return false;
		}
		
		if(isset($this->session->data['mobile_validate_time'])&&(time()-intval($this->session->data['mobile_validate_time']))< (58)){
		
			return false;
		}
		
		$this->load_language('account/forgotten');
		$this->load->model('account/customer');

		
		$sysvcode = $this->request->get['sysvcode'];
		$sysvcodeError=null;
		if($this->config->get('config_customer_lr_captcha')&&!(!empty($this->session->data['captcha']) && $sysvcode==$this->session->data['captcha']))
		{
			$sysvcodeError=$this->language->get('error_sys_vcode_format');
			 
		}
		
		$mobile = $this->request->get['mobile'];
		$mobileError = $this->validateMobileFmt($mobile);

		$json = array();

		  if (!is_null($sysvcodeError)) {
        	$json['success'] = false;
        	$json['msg']['vcode-error'] = $sysvcodeError;
        }elseif (!is_null($mobileError)) {
            $json['success'] = false;
            $json['msg']['vcode-error'] = $mobileError;
        } else {
			$mobile_validate_code = $this->model_account_customer->sendForgetPwdSms($mobile);
			$this->session->data['forget_pwd_code'] = md5($mobile.$mobile_validate_code);
			
			// 记录时间戳
			$this->session->data['mobile_validate_time'] = time();
			
			$json['success'] = true;
             //$json['vcode']=$mobile_validate_code;
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}

	public function reset(){
		$this->load_language('account/forgotten');
		
//		if(!(isset($this->request->get['u']) && $this->request->get['u'])){
//			$this->redirect('error/not_found');
//		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_reset()) {
			$this->load->model('account/customer');
			
			$password=$this->request->post['password'];
			
			if(isset($this->session->data['resetmobile'])&&$this->session->data['resetmobile'])
			{
			$this->model_account_customer->editPasswordByMobile($this->session->data['resetmobile'], $password);
			$this->redirect($this->url->link('account/forgotten/success', '', 'SSL'));
			
			}
			else
			{
			$this->redirect($this->url->link('account/forgotten', '', 'SSL'));
			}
			return;
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

		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		$this->data['mobile']=$this->encryption->decrypt($this->request->get['u']);
		
		
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
			'common/footer35',
			'common/header35'	
		);
								
		$this->response->setOutput($this->render());	
	}

	public function success(){
		$this->load_language('account/forgotten');
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
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
			'common/footer35',
			'common/header35'	
		);
								
		$this->response->setOutput($this->render());	
	}
	private function validate() {
		
		if (strlen(utf8_decode($this->request->post['mobile']))<1) {
			$this->error['mobile'] = $this->language->get('error_mobile_required');
		} elseif (!$this->model_account_customer->getCustomerByMobile($this->request->post['mobile'])) {
			$this->error['mobile'] = $this->language->get('error_mobile');
		}
		
//		if (!isset($this->session->data['mobile_vcode']) || ($this->session->data['mobile_vcode'] != $this->request->post['mobile_vcode'])) {
//      		$this->error['mobile_vcode'] = $this->language->get('error_mobile_vcode');
//    	}

		

		$mobile_validate_code = md5($this->request->post['mobile'].$this->request->post['mobile_vcode']);
		$mobile_validate_code_session=$this->session->data['forget_pwd_code'];
		if(empty($mobile_validate_code)){
			$this->error['mobile_vcode'] = '请填写手机验证码';
		}
		elseif(!isset($this->session->data['mobile_validate_time'])||(time()-intval($this->session->data['mobile_validate_time']))> (2*60)){
            $this->error['mobile_vcode'] = '验证码超时，请重新获取';
        }
		
		else if(empty($mobile_validate_code_session) || strtolower($mobile_validate_code)!=strtolower($mobile_validate_code_session)){
			$this->error['mobile_vcode'] = '手机验证码不匹配';
		}

		if (!$this->error) {
			$this->session->data['resetmobile']=$this->request->post['mobile'];
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