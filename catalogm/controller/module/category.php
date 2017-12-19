<?php  
class ControllerModuleCategory extends Controller {
	protected function index() {
		$this->load_language('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			
			$category_id = array_pop($parts);
		} else {
			$parts = array();
			
			$category_id=0;
		}
		
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
			$this->data['category_id'] = 0;
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		if(isset($this->request->get['path'])){
			$path=$this->request->get['path'].'_';
		}else{
			$path='';
		}
		
		$path='';
		
		$this->data['categories'] = array();

		$category_id=0; //设定默认显示全部分类
		
		$categories = $this->model_catalog_category->getCategories($category_id);
		
		//Show siblings if child not existed
		if(COUNT($categories)==0){
			$path=implode('_',$parts).'_'; //修正path的值
			
			$category_id = array_pop($parts);
			
			$categories = $this->model_catalog_category->getCategories($category_id);
		}
		
		foreach ($categories as $category) {
			$children_data = array();
			
			$children = $this->model_catalog_category->getCategories($category['category_id']);
			
			foreach ($children as $child) {
				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name'] ,
					'href'        => $this->url->link('product/category', 'path=' .$path. $category['category_id'] . '_' . $child['category_id'])	
				);				
			}
						
			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] ,
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' .$path. $category['category_id'])
			);
		}
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
  	}
  	
  	public function nav(){
  		$this->load_language('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
			$this->data['category_id'] = 0;
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getChildCategories(0);
		
		$this->data['categories']=$categories;

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_nav.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category_nav.tpl';
		} else {
			$this->template = 'default/template/module/category_nav.tpl';
		}
		
		$this->render();
  	}
  	
  	public function latest_product($setting=array()){
  		
  		$this->data['category_id']=$setting['category_id'];
  		$this->data['limit']=$setting['limit'];
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_latest_product.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category_latest_product.tpl';
		} else {
			$this->template = 'default/template/module/category_latest_product.tpl';
		}
		
		$this->render();
  	}
}
?>