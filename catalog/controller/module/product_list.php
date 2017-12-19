<?php  
class ControllerModuleProductList extends Controller {
	protected function index($setting) {

						print_r('products');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_List.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_List.tpl';
		} else {
			return;
		}
		
		$this->render();
	}
}
?>