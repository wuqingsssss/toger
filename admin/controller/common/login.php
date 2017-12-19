<?php  
class ControllerCommonLogin extends Controller { 
	private $error = array();
	          
	public function index() { 
    	$this->load_language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			$this->session->data['token'] = md5(mt_rand());
			
			if($this->config->get('config_admin_language_status')){
				//Add language version
				if(isset($this->request->post['language_code'])){
					$this->session->data['language'] = $this->request->post['language_code'];
				}
			}
			
			$this->session->data['loginUser'] = $this->user->getId();
						
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
    	$this->data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = '';
		}
		
		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}


		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
			
			unset($this->request->get['route']);
			
			if (isset($this->request->get['token'])) {
				unset($this->request->get['token']);
			}
			
			$url = '';
						
			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}
			
			$this->data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$this->data['redirect'] = '';	
		}
	
		$this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		
		if($this->config->get('config_admin_language_status')){
			$this->data['language_status']=TRUE;
			
			$this->load->model('localisation/language');
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->get['language'])) {
				$code = $this->request->get['language'];
			} elseif (isset($this->session->data['language'])) {
				$code = $this->session->data['language'];
			} elseif (isset($this->request->cookie['language'])) {
				$code = $this->request->cookie['language'];
			} else {
				$code = $this->config->get('config_admin_language');
			}
			
			$this->data['code']=$code;
		}else{
			$this->data['language_status']=FALSE;
		}
		
		$this->template = 'common/login.tpl';
		
		$this->id = 'content';
		$this->layout = 'layout/default';
				
		$this->render();
  	}
		
	private function validate() {
		if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		
		if (!$this->error) {
		    $this->log_admin->info($this->user);
			return true;
		} else {
		    $this->log_admin->info("username=".$this->request->post['username'].";".$this->error['warning']);
			return false;
		}
	}
}  
?>
