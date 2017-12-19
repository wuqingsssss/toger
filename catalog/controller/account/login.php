<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();
	
	public function index() {
		
 $infomation= new Common($this->registry);
        $infomation->get_openid();
		if ($this->customer->isLogged()) {  

		    //如果SESSION有redirect记录，则跳转，否则去主页
		    if (isset($this->session->data['redirect']) && (strpos($this->session->data['redirect'], HTTP_SERVER) !== false || strpos($this->session->data['redirect'], HTTPS_SERVER) !== false)) {
		        $url = $this->session->data['redirect'];
		        unset($this->session->data['redirect']);
		        $this->redirect(str_replace('&amp;', '&', $url));
		    } else {
		        $this->redirect($this->url->link('common/home', '', 'SSL'));
		    }
      		//$this->redirect($this->url->link('account/account', '', 'SSL'));
    	}
    	

       // var_dump($this->session->data['openid']);
    	
    	$this->load_language('account/login');

    	$this->document->setTitle($this->language->get('heading_title'));
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $customer_id = $this->customer->getId();
           //绑定openid,已封装到 model
          

			unset($this->session->data['guest']);
			
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

			if ($address_info) {
				$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
			}	

			
			
			$this->load->model('account/coupon');
			//查询是否存在免费券
			$results=$this->model_account_coupon->getCouponsByType($this->customer->getId(), 'R');
			if($results) {
			    $this->session->data['freepromotion'] = $results[0]['coupon_customer_id'];
			}
			
			if (isset($this->session->data['redirect']) && (strpos($this->session->data['redirect'], HTTP_SERVER) !== false || strpos($this->session->data['redirect'], HTTPS_SERVER) !== false)) {
			    $url = $this->session->data['redirect'];
		        unset($this->session->data['redirect']);
		        $this->redirect(str_replace('&amp;', '&', $url));
			} else {
				$this->redirect($this->url->link('common/home', '', 'SSL')); 
			}
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
        	'text'      => $this->language->get('text_login'),
			'href'      => $this->url->link('account/login', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['text_no_account'] =sprintf($this->language->get('text_no_account'), $this->url->link('account/register'));
		
		$this->data['action'] = $this->url->link('account/login', '', 'SSL');
		$this->data['register'] = $this->url->link('account/register', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

    	
/*		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) !== false || strpos($this->request->post['redirect'], HTTPS_SERVER) !== false)) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];
	  		
			unset($this->session->data['redirect']);		  	
    	} else {
			$this->data['redirect'] = '';
		}*/
		
		if (isset($this->request->post['email'])){
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

		//$this->register();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/login.tpl';
		} else {
			$this->template = 'default/template/account/login.tpl';
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
  
  	private function register(){
  		if (isset($this->error['warning'])) {
  			$this->data['error_warning'] = $this->error['warning'];
  		} else {
  			$this->data['error_warning'] = '';
  		}
  		
  		if (isset($this->error['email'])) {
  			$this->data['error_email'] = $this->error['email'];
  		} else {
  			$this->data['error_email'] = '';
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
  		
  		if (isset($this->error['captcha'])) {
  			$this->data['error_captcha'] = $this->error['captcha'];
  		} else {
  			$this->data['error_captcha'] = '';
  		}
  		
  		$this->data['register'] = $this->url->link('account/register', '', 'SSL');
  		
  		if (!empty($this->request->get['invitecode'])) {
  			$this->data['invitecode'] = $this->request->get['invitecode'];
  		}else{
  			$this->data['invitecode'] =0;
  		}
  		
  		if (isset($this->request->post['email'])) {
  			$this->data['email'] = $this->request->post['email'];
  		} else {
  			$this->data['email'] = '';
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
  		
  		if (isset($this->request->post['newsletter'])) {
  			$this->data['newsletter'] = $this->request->post['newsletter'];
  		} else {
  			$this->data['newsletter'] = '';
  		}
  		
  		if (isset($this->request->post['captcha'])) {
  			$this->data['captcha'] = $this->request->post['captcha'];
  		} else {
  			$this->data['captcha'] = '';
  		}
  		
  		if ($this->config->get('config_account_id')) {
  			$this->load->model('catalog/information');
  				
  			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
  				
  			if ($information_info) {
  				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
  			} else {
  				$this->data['text_agree'] = '';
  			}
  		} else {
  			$this->data['text_agree'] = '';
  		}
  		
  		if (isset($this->request->post['agree'])) {
  			$this->data['agree'] = $this->request->post['agree'];
  		} else {
  			$this->data['agree'] = true;
  		}
  		
  		
  	}
  	
  	private function validate() {
  		if((strlen(utf8_decode($this->request->post['email'])) < 1)){
  			$this->error['warning'] = $this->language->get('error_email_empty');
  			
  			return false;
  		}
  		
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
    		if($this->customer->getLoginCode()==EnumLoginCode::ERROR_ACCOUNT){
    			$this->error['warning'] = $this->language->get('error_login');
    		}
    		
    		if($this->customer->getLoginCode()==EnumLoginCode::ERROR_ACTIVE){
    			$this->error['warning'] = $this->language->get('error_active');
    		}
    		
    		if($this->customer->getLoginCode()==EnumLoginCode::ERROR_Approved){
    			$this->error['warning'] = $this->language->get('error_approved');
    		}
      		
    	}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}  	
  	}
}
?>