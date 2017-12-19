<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();
	
	public function index() {
        $infomation= new Common($this->registry);
        $infomation->get_openid();
		if ($this->customer->isLogged()) { 
		
			$this->goback();
			
/*
		    //如果SESSION有redirect记录，则跳转，否则去主页
		    if (isset($this->session->data['redirect']) && (strpos($this->session->data['redirect'], HTTP_SERVER) !== false || strpos($this->session->data['redirect'], HTTPS_SERVER) !== false)) {
		        $url = $this->session->data['redirect'];
		        unset($this->session->data['redirect']);
		        $this->redirect(str_replace('&amp;', '&', $url));
		    } else {
		        $this->redirect($this->url->link('common/home', '', 'SSL'));
		    }
      		//$this->redirect($this->url->link('account/account', '', 'SSL'));
      		 * */
      	
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
			/*
			if (isset($this->session->data['redirect']) && (strpos($this->session->data['redirect'], HTTP_SERVER) !== false || strpos($this->session->data['redirect'], HTTPS_SERVER) !== false)) {
			    $url = $this->session->data['redirect'];
		        unset($this->session->data['redirect']);
		        $this->redirect(str_replace('&amp;', '&', $url));
			} else {
				$this->redirect($this->url->link('common/home', '', 'SSL')); 
			}
			*/
			$this->goback();
    	} 
    	 else 
    	{
    	//	$this->setback(true,'',array('account/login','account/logout'));
    		//print_r($this->session->data['REFERER']);
    		//print_r($this->request->server['HTTP_REFERER']);
    		//print_r($this->request->server);
    	}
    	
    	$this->session->data['redirect']= $this->request->server['HTTP_REFERER'];
    	
    	
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
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

		if($this->detect->is_weixin_browser()){
		    $this->data['header_type'] = 'weixin';
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
			'common/footer35',
			'common/header35'	
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
	/**简洁登录
	 * 短信验证码登录
	 */
	public function msg_login(){
		
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$data['mobile'] = trim($this->request->post['mobile']);
			$code = $this->request->post['code'];
			if(($return = $this->validateMobile($data['mobile'], $code)) == ''){
				//去检查该手机用户 是否存在 不存在 则新增
				$this->load->model('account/customer');
				$customer_id = $this->model_account_customer->getCustomerByMobile($data['mobile']);
				if(!$customer_id){
					$customer_id = $this->model_account_customer->addCustomer($data);
				}
				if($customer_id){
					//跳转页面
					$this->customer->setCustomerById($customer_id);
					$this->redirect($this->url->link('common/home', '', 'SSL'));
				}else{
					$this->data['error'] = '生成帐号失败';
				}
			}else{
				$this->data['error'] = $return;
			}
//			var_dump($return);exit;
		}
		$this->data['mobile'] = $this->request->post['mobile'];
		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/msg_login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/msg_login.tpl';
		} else {
			$this->template = 'default/template/account/login.tpl';
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
	 * 验证手机号和验证码
	 * @param type $mobile
	 * @return type
	 */
	private function validateMobile($mobile,$code) {
		$errorMsg = '';
		//验证手机号
		if ((strlen(utf8_decode($mobile)) < 1)) {
            $errorMsg = '手机号不能为空';
        } else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
            $errorMsg = '无效手机号';
        }
		
		//验证手机验证码
		$mobile_validate_code = md5($mobile.$code);
        $mobile_validate_code_session=$this->session->data['mobile_validate_code'];
		if (empty($code)) {
			$errorMsg = '请输入短信验证码';
		} else if (!isset($this->session->data['mobile_validate_time']) || (time() - intval($this->session->data['mobile_validate_time'])) > (2 * 60)) {
			$errorMsg = '验证码错误或已失效';
		} else if (empty($mobile_validate_code_session) || strtolower($mobile_validate_code) != strtolower($mobile_validate_code_session)) {
			$errorMsg = '验证码错误';
		}
		
		return $errorMsg;
	}
	
	/**
	 * 发送手机验证码接口 
	 */
	public function get_mobile_code() {
		$this->load->model('account/customer');
		$mobile = trim($this->request->get['mobile']);
		
		if ((strlen(utf8_decode($mobile)) < 1)) {
			$json['success'] = false;
			$json['msg'] = '手机号不能为空';
        } else if (!preg_match("/1[3458]{1}\d{9}$/", $mobile)) {
			$json['success'] = false;
			$json['msg'] = '无效手机号';
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