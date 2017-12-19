<?php  
class ControllerModuleProductList extends Controller {
	protected function index($setting) {

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_list.tpl';
		} else {
			print_r($this->config->get('config_template') . '/template/module/product_list.tpl');
			return;
		}
		
		$this->render();
	}
}
?>