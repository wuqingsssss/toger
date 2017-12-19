<?php
class ControllerPromotionP extends Controller {
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
	   $pid= $this->request->get['pid'];
	   $productid= $this->request->get['productid'];
	   $this->data['pid']=$pid;
	   $this->data['productid']=$productid;
	   
	   

	   $period=$this->cart->getPeriod();
	   
	   if(!$period){
	   	$this->redirect($this->url->link('error/not_found'));
	   }
	   
	   $this->load->model('catalog/product');
	   $product_info = $this->model_catalog_product->getProduct($productid,$period['id']);
	   
	   $this->load->model('tool/image');
	   if ($product_info['image']) {
	   	$product_info['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
	   } else {
	   	$product_info['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));;
	   }
	   
	   $this->data['product_info']=$product_info;
	   
	   
	   if(isset($product_info['meta_title']))
	   	$this->document->setTitle($product_info['meta_title']!=''?$product_info['meta_title']:$product_info['name']);
	   else
	   	$this->document->setTitle($heading_title);
	   
	   $this->document->setDescription($product_info['meta_description']);
	   $this->document->setKeywords($product_info['meta_keyword']);

	   
	   $productsrelated = $this->model_catalog_product->getProductRelated($productid);
	   	
	   $this->data['productsrelated'] =changeProductResults($productsrelated,$this);

	   
	   
	   /* 判断是否由微信内置浏览器浏览*/
	   $detect = new Mobile_Detect();
	   
	   if($detect->is_weixin_browser()||1){
		$this->load->service('weixin/interface');
		$ticket      =$this->service_weixin_interface->get_jsapi_ticket();
		$wx_appid=$this->config->get('wxpay_appid');
		$this->data['wx_appid']=$wx_appid;
		   
		   
//	   	require_once(DIR_SYSTEM . 'helper/WeixinHelp.php');//加载微信接口文件
//	   	$wx = new WeixinHelp($this->registry);
//	   	$wx_appid=$this->config->get('wxpay_appid');
//	   	$wx_appsecret=$this->config->get('wxpay_appsecret');
//	   	$this->data['wx_appid']=$wx_appid;
//	   
//	   	$access_token=$wx->get_weixin_access_token($wx_appid,$wx_appsecret,true);
//	   
//	   	$ticket      =$wx->hget_jsapi_ticket($access_token);
	   
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
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/promotion/'.$pid.'/'.$pid.'.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/promotion/'.$pid.'/'.$pid.'.tpl';
		} else {
			$this->template = 'default/template/module/promotion/p'.$pid.'/p'.$pid.'.tpl';
		}

		$this->children = array(
				'common/footer',
				'common/header'
			);
		$this->response->setOutput($this->render());
	}
}
?>