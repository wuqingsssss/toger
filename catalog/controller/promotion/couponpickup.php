<?php
class ControllerPromotionCouponpickup extends Controller {
	private $error = array();
	protected function init(){
		$this->load_language('promotion/coupon');	
		
		$this->load->model('account/coupon');
		$this->load->model('account/customer');
	}
	/*
	 * 
	 * 显示分享页面*/
	public function index() {
		
		
		if(isset($this->request->get['code'])&&$this->request->get['code'])
		{
			$code=$this->request->get['code'];
		}
		else
		if(isset($this->session->data['coupon_code'])&&$this->session->data['coupon_code']){
			$code=$this->session->data['coupon_code'];
		}
		else {
			$this->redirect($this->url->link('common/home', '', 'SSL'));
		}
		
		$this->init();
	    //暂时关闭
	  //  $this->redirect($this->url->link('common/home', '', 'SSL'));
	  	
		$partner =$this->request->get['partner'];
		$point =$this->request->get['point'];
		
		//$code='CA0413DISC';		
		
		$this->session->data['coupon_code']=$code;
		$this->data['couponcode']=$code;
		$this->data['partner']=$partner;
		$this->data['point']=$point;
		
		$coupon_info=$this->model_account_coupon->getCouponByCode($code);
	
		$this->data['coupon_info']= $coupon_info;
		
		if ($this->customer->isLogged()) {
			/* 记录访问路径到session*/

			$res=$this->model_account_coupon->addCoupon($code,$this->customer->getId(),$pid,$partner);
			if($res==1){
			$this->data['success']='1';
			unset($this->session->data['coupon_code']);
			$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
			}
			elseif($res==-1)
			{	
				$this->data['success']='-2';
			}
			elseif($res==-2)
			{
				$this->data['success']='0';
			}
		}
		else
		{
			
			$this->data['success']='0';
		}
		
		$this->document->setTitle($coupon_info['share_title']?$coupon_info['share_title'].$coupon_info['share_desc']:$this->language->get('heading_title'));
		
		
		
		/* 判断是否由微信内置浏览器浏览*/


			$source_url=$this->url->link('common/home');
			if($this->request->get['code']){
			$source_url=$this->url->link('promotion/couponpickup', 'code='.$this->request->get['code']);
			$this->session->data['source_url']=$source_url;
			}
			$share_link=$coupon_info['share_link']?$coupon_info['share_link']:$source_url;
			
			$sharedata['pointid']=$this->request->get['point'];
			$sharedata['partner']=$this->request->get['partner'];
			$sharedata['linkparent']=$share_link;
			$sharedata['share_image']=$coupon_info['share_image']?HTTPS_IMAGE.$coupon_info['share_image']:'';			
			$sharedata['share_title']=$coupon_info['share_title'];
			$sharedata['share_desc']=$coupon_info['share_desc'];
				
			$this->data['sharedata']=$sharedata;
			

		
		$this->data['template']=$this->config->get('config_template');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/couponpickup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/promotion/couponpickup.tpl';
		} else {
			$this->template = 'default/template/promotion/couponpickup.tpl';
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
	
	function pickup(){
		$this->init();
		$json=array();
		$mobile = $this->request->post['userphone'];
		$partner=$this->request->post['partner'];//合作商户id
		$pid=$this->request->post['pid'];//取菜点id
		if(isset($this->session->data['coupon_code'])&&$this->session->data['coupon_code'])
		{
			$code=$this->session->data['coupon_code'];
		}
		else 
		{
			$json['success']=0;
			$json['msg'] = '优惠分享无效';
		}
			
		if(!$json)
		{		
			if($this->request->is_phone($this->request->post['userphone']))
			{
				$mobile=$this->request->post['userphone'];
			}
			else 
			{
		        $json['success']=0;
		        $json['msg']    = '手机号码不正确，请重新填写';	
			}
		}
		
		if(!$json)
		{		
			//根据新老用户绑定指定优惠码
			if(($customer_id=$this->model_account_customer->getCustomerByMobile($mobile))>0){
	
				$coupon_info=$this->model_account_coupon->getCouponByCode($code);
				if($coupon_info&&$coupon_info['free_get']){
	       
				$res=$this->model_account_coupon->addCoupon($code,$customer_id,$pid,$partner);
		
				if($res==1){
					$newuser=false;
					unset($this->session->data['coupon_code']);
					$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
					$json['success']=1;
					$json['newuser']=0;
					$json['coupon_info'] = $coupon_info;
				}
				elseif($res==-1) 
				{$json['success']=-2;
					$json['msg'] = '抱歉，您可能已经领取过了';
					$json['coupon_info'] = $coupon_info;
					unset($this->session->data['coupon_code']);
					$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
				}
				elseif($res==-2) 
				{$json['success']=-3;
					$json['msg'] = '抱歉，红包应被抢完了';
					unset($this->session->data['coupon_code']);
					$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
				}
		
				}
				else
				{
					$json['success']=-1;
					$json['msg'] = '优惠分享无效';
					unset($this->session->data['coupon_code']);
					$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
				}
			}
			else	
			{ 
				$this->session->data['redirect'] = $this->url->link('promotion/couponpickup', '', 'SSL');
				$json['success']=-4;
				$json['newuser']=1;
			}
		}
				
		$this->response->setOutput(json_encode($json));
	}
	
	
	
	
	function getrecode(){
		
		if(!isset($this->session->data['enter_route'])||$this->session->data['enter_route']=='promotion/couponpickup/getrecode')
		{
			return false;
		}
			
		if(isset($this->session->data['mobile_validate_time'])&&(time()-intval($this->session->data['mobile_validate_time']))< (58)){
		
			return false;
		}
		
		$this->init();
		if(isset($this->request->post['userphone']) && $this->request->post['userphone']){
			
			$this->load->model('account/customer');
			
			$mobile = $this->request->post['userphone'];
            $mobileError = $this->validateMobile($mobile);

        $json = array();

        if (!is_null($mobileError)) {
            $json['success'] = false;
            $json['msg'] =  $this->language->get('err_mobile');
        } else {
            $mobile_validate_code = $this->model_account_customer->sendMobileValidateSms($mobile);
            $this->session->data['mobile_validate_code'] = md5($mobile.$mobile_validate_code);
            // 增加时间认证
            $this->session->data['mobile_validate_time'] = time();
            $json['success'] = true;

            $this->log_payment->debug('controller->promotion->coupon::mobile:'.$mobile.';mobile_validate_code:' . $mobile_validate_code);
        }
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json));
			

		}
	}
	
	private function validateMobile($mobile) {
		$errorMsg = null;
		if ((strlen(utf8_decode($mobile)) < 1)) {
			$errorMsg = $this->language->get('error_mobile');
		} else if (!preg_match('/^[0-9]{11}$/', $mobile)) {
			$errorMsg = $this->language->get('error_mobile_format');
		} 
		return $errorMsg;
	}
	/**
	 * 分享动作处理
	 */
	public function share()
	{
		$this->init();

		$buyQuantity = getParamValue('buyQuantity', true,'int',0);
		$product_id = getParamValue("product_id", true,'int',0);
	
		if($updateResult)
		{
			$result = array(
				'success' => 'true',
			);
		}else{
			$result = array(
				'success' => 'false',
			);
		}
		
		$this->load->library('json');
		$this->response->setOutput(Json::encode($result));
	} 
	
}
?>