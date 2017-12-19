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
		
		if(!isset($this->request->get['code'])||!$this->request->get['code'])
		{$this->redirect($this->url->link('common/home', '', 'SSL'));}
		$this->init();
	    //暂时关闭
	  //  $this->redirect($this->url->link('common/home', '', 'SSL'));
	  
		$code=$this->request->get['code'];
		$partner =$this->request->get['partner'];
		$pid =$this->request->get['pid'];
		
		//$code='CA0413DISC';		
		
		$this->session->data['packet_code']=$code;
		$this->data['packet_code']=$code;
		$this->data['partner']=$partner;
		$this->data['pid']=$pid;
		//$coupon_info=$this->model_account_coupon->getCouponByCode($code);
		$packet_info=$this->model_account_coupon->getPacketByCondition('campaign',$code);
		$this->data['packet_info']=$packet_info;
		if ($this->customer->isLogged()) {
			/* 记录访问路径到session*/

			$res=$this->model_account_coupon->addPacket2Customer($packet_info['packet_id'], $packet_info['batch'], $this->customer->getId(),$packet_info['pick_tyle'],$pid,$partner);
			
			
			if($res==1){
	
			$this->data['success']='1';	
			}
			elseif($res==-1) {//已经另取过
		
				$this->data['success']='-2';	
			}
			else{
				$this->data['success']='0';
			}
		}
		else
		{  
			$this->data['success']='0';
		}
		
		
		$this->document->setTitle($packet_info['share_title']?$packet_info['share_title']:$this->language->get('heading_title'));
		
		
		
		/* 判断是否由微信内置浏览器浏览*/
		$detect = new Mobile_Detect();
		
		if($detect->is_weixin_browser()||1){
			$this->load->service('weixin/interface');
			$ticket      =$this->service_weixin_interface->get_jsapi_ticket();
			$wx_appid=$this->config->get('wxpay_appid');
			$this->data['wx_appid']=$wx_appid;
			
			
//			require_once(DIR_SYSTEM . 'helper/WeixinHelp.php');//加载微信接口文件
//			$wx = new WeixinHelp($this->registry);
//	        $wx_appid=$this->config->get('wxpay_appid');
//	        $wx_appsecret=$this->config->get('wxpay_appsecret');
//	        $this->data['wx_appid']=$wx_appid;
//	 
//			$access_token=$wx->get_weixin_access_token($wx_appid,$wx_appsecret,true);
//	         
//			$ticket      =$wx->hget_jsapi_ticket($access_token);
						
			$signPackage['jsapi_ticket']    =$ticket['ticket'];
			$signPackage['noncestr']        =str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			$signPackage['timestamp']       =time();
			$signPackage['curl']=htmlspecialchars_decode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//
			$string1="jsapi_ticket=$signPackage[jsapi_ticket]&noncestr=$signPackage[noncestr]&timestamp=$signPackage[timestamp]&url=$signPackage[curl]";
			$signPackage['signature']=sha1($string1);
			$this->data['assign']=$signPackage;
			
			
			$this->data['is_weixin_browser'] =1;

		}
		
		//$sharelink = $this->model_promotion_coupon->getShareLink($this->customer->getId());
		
		$this->data['sharelink'] = $sharelink;
		$this->data['template']=$this->config->get('config_template');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/packetickup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/promotion/packetpickup.tpl';
		} else {
			$this->template = 'default/template/promotion/packetpickup.tpl';
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
		
		
		if(isset($this->session->data['packet_code'])&&$this->session->data['packet_code'])
		{
			$code=$this->session->data['packet_code'];
		}
		else 
		{
			$json['success']=0;
			$json['msg'] = '红包分享无效';
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
	
			$packet_info=$this->model_account_coupon->getPacketByCondition('campaign',$code);
				if($packet_info){
	
			$res=$this->model_account_coupon->addPacket2Customer($packet_info['packet_id'], $packet_info['batch'], $this->customer->getId(),$packet_info['pick_tyle'],$pid,$partner);
			
		
				if($res==1){
					$newuser=false;
					$json['success']=1;
					$json['newuser']=0;
					$json['packet_info'] = $packet_info;
				}
				elseif($res==-1) 
				{   $json['success']=-2;
					$json['msg'] = '抱歉，您可能已经领取过了';
			
					$json['packet_info'] = $packet_info;
				}
				else{
					
					$json['success']=-1;
					$json['msg'] = '红包已经过期或不存在';
				}
				
				
				}
				else
				{
					$json['success']=-3;
					$json['msg'] = '红包被领完了';
				}
			}
			else	
			{
				$json['success']=-4;
				$json['newuser']=1;
			}
		}
				
		$this->response->setOutput(json_encode($json));
	}
	
	
	/* *手机验证码方法**/
	function getrecode(){
		if(!isset($this->session->data['enter_route'])||$this->session->data['enter_route']=='promotion/packetpickup/getrecode')
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