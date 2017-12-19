<?php
class ControllerShareLj extends Controller {
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
		
		$this->document->setTitle('饥饿测试站');
		$this->document->setDescription('你饥到要要要，我饿到不要不要，来此测试，告别饥饿！');
		/* 判断是否由微信内置浏览器浏览*/
		$detect = new Mobile_Detect();
		
		if($detect->is_weixin_browser()||1){
			$this->load->service('weixin/interface');
			$ticket      =$this->service_weixin_interface->get_jsapi_ticket();
			$wx_appid=$this->config->get('wxpay_appid');
			$this->data['wx_appid']=$wx_appid;
			
//			require_once(DIR_SYSTEM . 'helper/WeixinHelp.php');//加载微信接口文件
//			$wx = new WeixinHelp($this->registry);
//			$wx_appid=$this->config->get('wxpay_appid');
//			$wx_appsecret=$this->config->get('wxpay_appsecret');
//			$this->data['wx_appid']=$wx_appid;
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

		$this->data['template']=$this->config->get('config_template');
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/share/lj.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/share/lj.tpl';
		} else {
			$this->template = 'default/template/share/lj.tpl';
		}

		$this->response->setOutput($this->render());
	}
}
?>