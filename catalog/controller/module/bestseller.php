<?php
class ControllerModuleBestSeller extends Controller {
	protected function index($setting) {
		$this->load_language('module/bestseller');
 
		$this->load->model('catalog/product');
		
		$this->data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		
		$this->data['products'] =changeProductResults($results,$this,'',$setting['image_width'],$setting['image_height']);
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/bestseller.tpl';
		} else {
			$this->template = 'default/template/module/bestseller.tpl';
		}

		$this->render();
	}
}
?>