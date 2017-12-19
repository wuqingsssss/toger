<?php  
class ControllerModuleProductFilter extends Controller {
	protected function index($setting) {

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_filter.tpl';
		} else {
			return;
		}
		
		$this->render();
	}
}
?>