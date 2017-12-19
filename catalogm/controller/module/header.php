<?php  
class ControllerModuleHeader extends Controller {
	protected function index($data) {
        $this->data['header_type'] = 'normal';
        
	    if($this->detect->is_weixin_browser()){
	        $this->data['header_type'] = 'weixin';
	    }
	    else if(isset($this->session->data ['platform']['platform_code'])&&$this->session->data ['platform']['platform_code']=='app'){
	        $this->data['header_type'] = 'app';
	    }
	    
		$this->data['setting'] = $data;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/header.tpl';
		} else {
		return;
		}
		
		$this->render();
	}
}
?>