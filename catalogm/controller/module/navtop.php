<?php  
class ControllerModuleNavtop extends Controller {
	protected function index($data) {

		$this->data=array_merge($this->data,$data);
		$this->data['header_type'] = 'normal';
		
		if($this->detect->is_weixin_browser()){
		    $this->data['header_type'] = 'weixin';
		}
		else if(isset($this->session->data ['platform']['platform_code'])&&$this->session->data ['platform']['platform_code']=='app'){
		    $this->data['header_type'] = 'app';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/navtop.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/navtop.tpl';
		} else {
		return;
		}
		
		$this->render();
	}
}
?>