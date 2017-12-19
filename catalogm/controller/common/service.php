<?php   
class ControllerCommonService extends Controller {
	protected function index() {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/service.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/service.tpl';
		} else {
			$this->template = 'default/template/common/service.tpl';
		}
		
    	$this->render();
	}
}
?>