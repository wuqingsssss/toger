<?php  
class ControllerModuleCategoryManufacturer extends Controller {
	protected function index($category_id) {
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		
		$results=$this->model_catalog_category->getCategoryManufacturers($category_id);

		$manufacturers=array();
		
		foreach($results as $result){
			$result_info=$this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
			
			if($result_info){
				$manufacturers[]=array(
					'name' => $result_info['name'],
					'thumb' => resizeThumbImage($result_info['image'],0,0,TRUE),
					'href' => $this->url->link('product/manufacturer/product&manufacturer_id='.(int)$result_info['manufacturer_id'])
				);
			}
		}
		
		$this->data['manufacturers']=$manufacturers;
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_manufacturer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category_manufacturer.tpl';
		} else {
			$this->template = 'default/template/module/category_manufacturer.tpl';
		}
		
		$this->render();
	}
}
?>