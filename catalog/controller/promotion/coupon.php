<?php
class ControllerPromotionCoupon extends Controller {
	private $error = array();
	protected function init(){
		$this->load_language('promotion/coupon');
	
		$this->load->model('account/coupon');

		$this->load->model('account/customer');
		
		initOperStatus();
	}
	/*
	 * 
	 * 显示分享页面*/
	public function index() {
	    //暂时关闭
	  //  $this->redirect($this->url->link('common/home', '', 'SSL'));
		if (!$this->customer->isLogged()) {
			/* 记录访问路径到session*/
			if(isset($this->request->get['route']) && $this->request->get['route']){
				$this->session->data['redirect'] = $this->url->link($this->request->get['route'], '', 'SSL');
			}else{
				$this->session->data['redirect'] = $this->url->link('promotion/coupon', '', 'SSL');
			}
			/*如果未登录，则跳转到登录页面*/
			//$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		else
		{
			$this->data['isLogged']=$this->customer->isLogged();
		
		}
		
		$this->init();
		
		$this->session->data['link_url']    ='http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$this->session->data['source_url']  =$_SERVER['HTTP_REFERER'];
		
		//$this->document->setTitle($this->language->get('heading_coupon'));
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		
		
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
//			//$userlist=$wx->hget_all_weixin_users($access_token);
//
//	       //  print_r($wx->hget_weixin_userinfo($userlist['data']['openid'][0],$access_token));
//	         
//
//			$ticket      =$wx->hget_jsapi_ticket($access_token);
			
			
			$signPackage['jsapi_ticket']    =$ticket['ticket'];
			$signPackage['noncestr']        =str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			$signPackage['timestamp']       =time();
			$signPackage['curl']=htmlspecialchars_decode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//
			$string1="jsapi_ticket=$signPackage[jsapi_ticket]&noncestr=$signPackage[noncestr]&timestamp=$signPackage[timestamp]&url=$signPackage[curl]";
			$signPackage['signature']=sha1($string1);
			$this->data['assign']=$signPackage;
			//print_r($_SERVER);
			//print_r($signPackage);
		}
		
		//$sharelink = $this->model_promotion_coupon->getShareLink($this->customer->getId());
		
		$this->data['sharelink'] = $sharelink;
		$this->data['template']=$this->config->get('config_template');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/promotion_coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/promotion/promotion_coupon.tpl';
		} else {
			$this->template = 'default/template/module/promotion/promotion_coupon.tpl';
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
	
	 //领取优惠劵接口，ajax
	function getcoupon(){
		$this->init();
		//加载优惠劵操作类

		$mobile = $this->request->post['userphone'];
		$mobileError = $this->validateMobile($mobile);
		
		$userpwd = $this->request->post['userpwd'];
			
		$recode=md5($mobile.$this->request->post['recode']);//用户手机验证码
		$partner=$this->request->post['partner'];//合作商户id
		$pid=$this->request->post['pid'];//取菜点id
		$sid=$this->request->post['sid'];//用户点选的分享类型编号
		
		
		/* 生成返回结果数据集*/
		$json = array();
		if (!is_null($mobileError)&&!empty($recode)) {
			$json['success'] = false;
			$json['msg'] = $mobileError;
		} else {
			/* 增加领取优惠劵逻辑*/
			
			if(isset($this->session->data['mobile_validate_code'])&&!empty($this->session->data['mobile_validate_code']) && $recode==$this->session->data['mobile_validate_code'])
			{ 
				
			    // 加超时2分钟验证
			    if((time()-intval($this->session->data['mobile_validate_time']))> (2*60)){
			        $json['success']  = false;
				    $json['msg']      = $this->language->get('err_recode');
			    }
				elseif($this->session->data['promotioncouponpickcount']>0 && !DEBUG)
				{
					$json['success'] = true;
					$json['msg']     = $this->language->get('err_count_not');
				}
				else 
				{
				
				//根据新老用户绑定指定优惠码
				if(($customer_id=$this->model_account_customer->getCustomerByMobile($mobile))>0){	
					
					$code='CA0413DISC';
					$coupon_info=$this->model_account_coupon->getCouponByCode($code);
					$res=$this->model_account_coupon->addCouponToCustomer($coupon_info['coupon_id'],$customer_id,$pid,$partner);
					
					
					$newuser=false;
				}
				else{
					//新用户处理流程
					$newuser=true;
					if(!empty($userpwd)){
					
					$code='CA0413PIAO';
					$coupon_info=$this->model_account_coupon->getCouponByCode($code);
					
					$customer_id=$this->model_account_customer->addCustomer(array('mobile'=>$mobile,'password'=>$userpwd, 'pid'=>$pid, 'partner'=>$partner));
					// 已修改为注册用户时发优惠券
					
					$res = true;
					}
					else 
					{
					$res=false;	
					}
				}
				if($res){
					//如果领卷成功
					unset($this->session->data['mobile_validate_code']);
					        $this->load->model('account/sharelink');				
							$data['coupon_id']   =$coupon_info['coupon_id'];
							$data['customer_id'] =$customer_id;
							$data['point_id']    =$pid;
							$data['partner_code']=$partner;
							$data['remark']      =$sid;
							$data['link_url']    =$this->session->data['link_url'];
							$data['source_url']  =$this->session->data['source_url'];
							
							$this->model_account_sharelink->addShareLink($data);
							
			      $this->log_payment->debug('model_account_sharelink->addShareLink::serialize(data):' . serialize($data));
							
				
					//一个session只能领卷一次限制
					$this->session->data['promotioncouponpickcount']+=1;
					
					$json['success'] = true;
					if($newuser)
					$json['msg']     = sprintf($this->language->get('success_getcoupon'), $coupon_info['name']);
					else 
					$json['msg']     = sprintf($this->language->get('success2_getcoupon'), $coupon_info['name']);
				}
				else{
					$json['success'] = false;
					$json['newuser'] =$newuser;
					$json['msg']     = $this->language->get('err_nonewuserpwd');
				}		
				}		
			}
			else {
				    $json['success']  = false;
				    $json['msg']      = $this->language->get('err_recode');
			}			
		}
		/* json返回操作结果数据*/
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	function getrecode(){
		
		if(!isset($this->session->data['enter_route'])||$this->session->data['enter_route']=='promotion/coupon/getrecode')
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