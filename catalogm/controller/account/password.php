<?php
class ControllerAccountPassword extends Controller {
	private $error = array();
	     
  	public function index() {	
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('account/password', '', 'SSL');

      		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}

		$this->load_language('account/password');

    	$this->document->setTitle($this->language->get('heading_title'));
			  
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('account/customer');
			
			$this->model_account_customer->editPassword($this->customer->getId(), $this->request->post['password']);
 
      		$this->session->data['success'] = $this->language->get('text_success');
	  
	  		$this->redirect($this->url->link('account/logout', '', 'SSL'));
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
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/password', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
    	
		if (isset($this->error['old_password'])) { 
			$this->data['error_old_password'] = $this->error['old_password'];
		} else {
			$this->data['error_old_password'] = '';
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
	
    	$this->data['action'] = $this->url->link('account/password', '', 'SSL');
		
		if (isset($this->request->post['old_password'])) {
    		$this->data['old_password'] = $this->request->post['old_password'];
		} else {
			$this->data['old_password'] = '';
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

    	$this->data['back'] = $this->url->link('account/account', '', 'SSL');
    	
  		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/password.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/password.tpl';
		} else {
			$this->template = 'default/template/account/password.tpl';
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
  		$this->load->model('account/customer');
  		
  		$customer=$this->model_account_customer->getCustomer($this->customer->getId());
  		
  		if (md5($this->request->post['old_password']) != $customer['password']) {
      		$this->error['old_password'] = $this->language->get('error_old_password');
    	}
  		
    	
    	if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
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
	/**
	 * 根据手机验证码修改密码
	 */
	public function edit_pwd(){
		$mobile = $this->customer->getMobile();
		$id = $this->customer->getId();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$code = trim($this->request->post['code']);
			$pwd = htmlspecialchars(trim($this->request->post['password']));
			//验证手机验证码
			$mobile_validate_code = md5($mobile.$code);
			$mobile_validate_code_session=$this->session->data['mobile_validate_code'];
			if (empty($code)) {
				$errorMsg = '请输入短信验证码';
			} else if (!isset($this->session->data['mobile_validate_time']) || (time() - intval($this->session->data['mobile_validate_time'])) > (2 * 60)) {
				$errorMsg = '验证码错误或已失效';
			} else if (empty($mobile_validate_code_session) || strtolower($mobile_validate_code) != strtolower($mobile_validate_code_session)) {
				$errorMsg = '验证码错误';
			}else{//修改密码
				$this->load->model('account/customer');
				$this->model_account_customer->editPassword($id, $pwd);
				$this->redirect($this->url->link('account/logout', '', 'SSL'));
			}
			$this->data['error'] = $errorMsg;
		}
		
		//渲染模版
		$this->data['action'] = $this->url->link('account/password/edit_pwd', '', 'SSL');
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit_pwd.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/edit_pwd.tpl';
		} else {
			$this->template = 'default/template/account/password.tpl';
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
	
	/**
	 * 发送当前用户手机验证码
	 */
	public function send_code() {
		$this->load->model('account/customer');
		$mobile = $this->customer->getMobile();
		if(empty($mobile)){
			$json['success'] = false;
			$json['msg'] = '请先登录';
		}else{
			$mobile_validate_code = $this->model_account_customer->sendMobileValidateSms($mobile);
			$this->session->data['mobile_validate_code'] = md5($mobile . $mobile_validate_code);
			$this->log_sys->info('controller->account->register->validate_mobile::mobile:' . $mobile . ';mobile_validate_code:' . $mobile_validate_code);

			// 记录时间戳
			$this->session->data['mobile_validate_time'] = time();
			$json['success'] = true;

//			$json['code'] = $mobile_validate_code;
		}
		
		$this->load->library('json');
        $this->response->setOutput(Json::encode($json));
	}
}
?>
