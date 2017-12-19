<?php  
class ControllerModuleNavmain extends Controller {
	protected function index() {


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/navmain.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/navmain.tpl';
		} else {
			return;
		}
		
		$this->render();
	}
}
?>