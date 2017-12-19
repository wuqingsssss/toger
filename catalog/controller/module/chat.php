<?php  
class ControllerModuleChat extends Controller {
	protected function index() {
		if(!$this->config->get('config_online_status')){ //Add config for chat module
			return;
		}
		
		$this->load_language('module/chat');

		$this->id = 'chat';
		//TODO: Change setting
		$this->data['qqs']=explode(',',"2458342738,2369622690,2695684625");
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/chat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/chat.tpl';
		} else {
			$this->template = 'default/template/module/chat.tpl';
		}
		
		$this->render();
	}
}
?>