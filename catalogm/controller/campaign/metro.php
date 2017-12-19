<?php

class ControllerCampaignMetro extends Controller {
    private $error = array();

    public function index() {
        if ($this->customer->isLogged()) {
             $this->redirect('http://wx.zidongbox.com/wap/order/activityPackOrder.do?id='.$this->session->data['id'].'&mid='.$this->session->data['mid']);
        }

        if(isset($this->request->get['cond'])){       	
        	$this->session->data['cond']=$this->request->get['cond'];
        }
        else if(!isset($this->session->data['cond'])){
            $this->session->data['cond']=2;
        }
        
        if(isset($this->request->get['id'])){       	
        	$this->session->data['id']=$this->request->get['id'];
        }
        else if(!isset($this->session->data['id'])){
            $this->session->data['id']=0;
        }
        
        if(isset($this->request->get['mid'])){
            $this->session->data['mid']=$this->request->get['mid'];
        }
        else if(!isset($this->session->data['mid'])){
            $this->session->data['mid']=0;
        }

        $this->load_language('campaign/metro');

        $this->document->setTitle('菜君携手农夫山泉');

        $this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login'));

        $this->load->model('account/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->validateBasic();
            //$this->log_sys->info('controller->account->register->index::mobile:'.$this->request->post['mobile'].';post_mobile_vcode'.$this->request->post['mobile_vcode'].';mobile_validate_code:' . $this->session->data['mobile_validate_code']);
            $this->log_sys->trace('POST');
            
            if ($this->validate()) {
                unset($this->session->data['guest']);
                unset($this->session->data['mobile_validate_code']);
                unset($this->session->data['mobile_validate_time']);
                unset($this->session->data['captcha']);
                       
                $data = $this->request->post;
                $data['password'] = (string)rand(100000, 999999);
                $customer_id = $this->model_account_customer->getCustomerByMobile($data['mobile']);
                
                if($customer_id){
                    $this->log_sys->info('地铁活动：老用户'.$data['mobile']);
                }
                else {
                    $customer_id = $this->model_account_customer->addCustomer($data);
                    $this->log_sys->info('地铁活动：新用户'.$data['mobile']);
                }

                if($this->session->data['cond'] == 1){
                    $this->redirect('http://wx.zidongbox.com/wap/order/activityPackOrder.do?id='.$this->session->data['id'].'&mid='.$this->session->data['mid']);
                }
                else{
                    $this->customer->login($this->request->post['mobile'], $data['password']);                  
                    $this->redirect($this->url->link('promotion/promotion&pkey=&pid=4'));
                }       
            }
        }


        $error_fields = array('warning', 'email', 'mobile', 'password', 'confirm', 'mobile_vcode', 'agree', 'name','reference');

        foreach ($error_fields as $field) {
            if (isset($this->error[$field])) {
                $this->data['error_' . $field] = $this->error[$field];
            } else {
                $this->data['error_' . $field] = '';
            }
        }

        $this->data['action'] = $this->url->link('campaign/metro', '', 'SSL');

        $fields = array('email', 'mobile', 'password', 'confirm', 'mobile_vcode', 'name','reference');

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } else {
                $this->data[$field] = '';
            }
        }

        if (isset($this->request->post['salution'])) {
            $this->data['salution'] = $this->request->post['salution'];
        } else {
            $this->data['salution'] = 'M';
        }

        $this->id = "register";

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/campaign/exercise.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/campaign/exercise.tpl';
        } else {
            $this->template = 'default/template/account/register.tpl';
        }
				$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
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


    protected function getForm() {
     
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

        if (isset($this->error['mobile_vcode'])) {
            $this->data['error_mobile_vcode'] = $this->error['mobile_vcode'];
        } else {
            $this->data['error_mobile_vcode'] = '';
        }

        if (isset($this->error['agree'])) {
            $this->data['error_agree'] = $this->error['agree'];
        } else {
            $this->data['error_agree'] = '';
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

        if (isset($this->request->post['mobile_vcode'])) {
            $this->data['mobile_vcode'] = $this->request->post['mobile_vcode'];
        } else {
            $this->data['mobile_vcode'] = '';
        }

        $fields = array('firstname', 'department', 'telephone', 'mobile', 'company', 'company_address', 'website');

        foreach ($fields as $field) {
            if (isset($this->error[$field])) {
                $this->data['error_' . $field] = $this->error[$field];
            } else {
                $this->data['error_' . $field] = '';
            }

            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } else {
                $this->data[$field] = '';
            }
        }

        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information'. 'information_id=' . $this->config->get('config_account_id'), '', 'SSL'), $information_info['title'], $information_info['title']);
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


    public function validate_mobile() {   	
        $this->load->model('account/customer');
        $this->load_language('account/register');
    	if(!isset($this->session->data['enter_route'])||$this->session->data['enter_route']=='campaign/metro/validate_mobile')
    	{
    		return false;
    	}
    	
    	if(isset($this->session->data['mobile_validate_time'])&&(time()-intval($this->session->data['mobile_validate_time']))< (58)){

    		return false;
    	}

        $sysvcode = $this->request->get['sysvcode'];
        $sysvcodeError=null;
        
        if($this->config->get('config_customer_lr_captcha')&&!(!empty($this->session->data['captcha']) && $sysvcode==$this->session->data['captcha']))
        {
        	$sysvcodeError=$this->language->get('error_sys_vcode_format');
        	
        }
        
        $mobile = $this->request->get['mobile'];
        $mobileError = $this->validateNewMobile($mobile);
        
        
        $json = array();
       // $json['session']=$this->session->data;
        if (!is_null($sysvcodeError)) {
        	$json['success'] = false;
        	$json['msg']['vcode-error'] = $sysvcodeError;
        }elseif (!is_null($mobileError)) {
            $json['success'] = false;
            $json['msg']['vcode-error'] = $mobileError;
        } else {
        	
        	
            $mobile_validate_code = $this->model_account_customer->sendMobileValidateSms($mobile);
            $this->session->data['mobile_validate_code'] = md5($mobile.$mobile_validate_code);
            $this->log_sys->info('controller->account->register->validate_mobile::mobile:'.$mobile.';mobile_validate_code:' . $mobile_validate_code);
            
            // 记录时间戳
            $this->session->data['mobile_validate_time'] = time();
            $json['success'] = true;
//            $json['vcode']=$mobile_validate_code;
        }
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
    }

    private function validateNewMobile($mobile) {
    	$this->load_language('campaign/metro');
        $errorMsg = null;
        if ((strlen(utf8_decode($mobile)) < 1)) {
            $errorMsg = $this->language->get('error_mobile');
        } else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
            $errorMsg = $this->language->get('error_mobile_format');
        } //else if ($this->model_account_customer->getCustomerByMobile($mobile,'',1)) {
          //  $errorMsg = $this->language->get('error_mobile_hava_register');
        //}
        return $errorMsg;
    }

    /**
     * 校验注册信息
     */
    private function validateBasic() {

        // 如果有，校验邮箱
        $email = $this->request->post['email'];
        if (!empty($email)) {
            if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                $this->error['email'] = $this->language->get('error_email');
            } else if ($this->model_account_customer->getTotalCustomersByEmail($email)) {
                $this->error['email'] = $this->language->get('error_exists');
            }
        }

        // 校验手机号码
        $mobileError = $this->validateNewMobile($this->request->post['mobile']);
        if (!is_null($mobileError)) {
            $this->error['mobile'] = $mobileError;
        }
        $mobile_validate_code = md5($this->request->post['mobile'].$this->request->post['mobile_vcode']);
        $mobile_validate_code_session=$this->session->data['mobile_validate_code'];
        
        //验证码时间限定（2分钟）
        if(empty($mobile_validate_code)){
            $this->error['mobile_vcode'] = $this->language->get('error_mobile_empty_vcode');
        }
        else if(!isset($this->session->data['mobile_validate_time'])||(time()-intval($this->session->data['mobile_validate_time']))> (2*60)){
            $this->error['mobile_vcode'] = $this->language->get('error_timeout');
        }
        else if(empty($mobile_validate_code_session) || strtolower($mobile_validate_code)!=strtolower($mobile_validate_code_session)){
            $this->error['mobile_vcode'] = $this->language->get('error_mobile_vcode');
        }

        // 如果有，名字长度
        if(!empty($this->request->post['name'])){
            if ((strlen(utf8_decode($this->request->post['name'])) < 1)) {
                $this->error['name'] = $this->language->get('error_name');
            }
        }

        // 校验推荐码
        $reference = $this->request->post['reference']='zidongbox';
        if(!empty($reference)){
            $this->load->model('account/customer');
            $query=$this->model_account_customer->getReference($reference);
            if(!$query){
                $this->error['reference'] = $this->language->get('error_reference');

            }
        }
    }

    private function validate() {

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function active() {
        if($this->session->data['cond'] == 1){
            $this->redirect('http://wx.zidongbox.com/wap/order/activityPackOrder.do?id='.$this->session->data['id'].'&mid='.$this->session->data['mid']); 
        }
        else{
            $this->redirect($this->url->link('promotion/promotion&pkey=&pid=4'));
        }
    }
}

?>