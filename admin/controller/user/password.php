<?php
class ControllerUserPassword extends Controller {
	private $error = array();
	     
  	public function index() {
  		$this->load_language('user/password');
  		
    	$this->document->setTitle($this->language->get('heading_title'));
			  
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('user/user');
			
			$this->model_user_user->editPassword($this->user->getId(), $this->request->post['password']);
 
      		$this->session->data['success'] =$this->language->get('text_success');
    	}
    	
    	$this->getForm();
  	}
  	
  	private function getForm(){
  		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

		
      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('user/password', 'token=' . $this->session->data['token'], 'SSL'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

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
	
    	$this->data['action'] = $this->url->link('user/password', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
    	$this->template = 'user/password.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}
  
  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['old_password'])) < 1)) {
      		$this->error['old_password'] = $this->language->get('error_old_password_required');
    	}else{
    		if(!$this->user->login($this->user->getUserName(),$this->request->post['old_password'])){
    			$this->error['old_password'] = $this->language->get('error_old_password');
    		}
    	}
    	
    	if ((strlen(utf8_decode($this->request->post['password'])) < 1)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
