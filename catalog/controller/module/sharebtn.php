<?php
class ControllerModuleShareBtn extends Controller {
	protected function index($data=array()) {
		$this->load_language ( 'module/chat' );
		if ($this->detect->is_weixin_browser () || defined ( 'DEBUG' ) && DEBUG) {
			$this->load->service ( 'weixin/interface' );
			$ticket = $this->service_weixin_interface->get_jsapi_ticket ();
			$wx_appid = $this->config->get ( 'wxpay_appid' );
			$this->data ['wx_appid'] = $wx_appid;
			
			$signPackage ['jsapi_ticket'] = $ticket['ticket'] ;
			$signPackage ['noncestr'] = str_pad ( mt_rand ( 1, 99999 ), 5, '0', STR_PAD_LEFT );
			$signPackage ['timestamp'] = time ();
			$signPackage ['curl'] = htmlspecialchars_decode ( 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] ); //
			$string1 = "jsapi_ticket=$signPackage[jsapi_ticket]&noncestr=$signPackage[noncestr]&timestamp=$signPackage[timestamp]&url=$signPackage[curl]";
			$signPackage ['signature'] = sha1 ( $string1 );
			$this->data ['assign'] = $signPackage;
			$this->data ['is_weixin_browser'] = 1;
			}
			if(!isset($this->session->data['source_url']))
				$this->session->data['source_url']=htmlspecialchars_decode('http://'.$this->request->server['HTTP_HOST'].$this->request->server['REQUEST_URI']);
			if (empty ( $this->data ['sharedata'] ['linkparent'] ))
				$this->data ['sharedata'] ['linkparent'] = $this->config->get ( 'config_share_linkurl' );
			if (empty ( $this->data ['sharedata'] ['share_image'] ))
				$this->data ['sharedata'] ['share_image'] = HTTPS_IMAGE . $this->config->get ( 'config_share_image' );
			if (empty ( $this->data ['sharedata'] ['share_title'] ))
				$this->data ['sharedata'] ['share_title'] = $this->config->get ( 'config_share_title' );
			if (empty ( $this->data ['sharedata'] ['share_desc'] ))
				$this->data ['sharedata'] ['share_desc'] = $this->config->get ( 'config_share_desc' );
				
			
			if(stripos($this->data ['sharedata'] ['linkparent'], 'dwz.cn')===false){//如果不是短连接则自动转成短连接
			$this->load->service ('baidu/dwz');
			
			$linkurl=$this->data ['sharedata'] ['linkparent'];
			if($this->customer->isLogged())$linkurl.="&icode=".$this->customer->getInviteCode();
			if($this->data ['sharedata'] ['pointid'])$linkurl.="&pid=".$this->data ['sharedata'] ['pointid'];
			if($this->data ['sharedata'] ['partner'])$linkurl.="&partner=".$this->data ['sharedata'] ['partner'];
			
			$dwz=$this->service_baidu_dwz->hcreate(htmlspecialchars_decode($linkurl));		
			$this->data ['sharedata'] ['linkparent']=$dwz['tinyurl'];
			}
			

		
		$this->data=array_merge($this->data,$data);
		
		
		$this->data ['template'] = $this->config->get ( 'config_template' );
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/sharebtn.tpl' )) {
			$this->template = $this->config->get ( 'config_template' ) . '/template/module/sharebtn.tpl';
		} else {
			return;
		}
		
		$this->render ();
	}
}
?>