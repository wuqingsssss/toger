<?php  
class ControllerModuleIlexCategoryNav extends Controller {
	protected function index($setting) {
		static $module = 0;

		$this->data['heading_title'] = $setting['title'][$this->config->get('config_language_id')];
		
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
					
		$categories = $this->getChildCategories(0);
		
		$this->data['categories']=$categories;
		
		$this->data['module'] = $module++; 
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ilex_category_nav.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ilex_category_nav.tpl';
		} else {
			$this->template = 'default/template/module/ilex_category_nav.tpl';
		}
		
		$this->render();
  	}
  	
	private function getChildCategories($parent_id=0,$recursion=TRUE){
		$data=array(
			'parent_id' => $parent_id,
			'language_id' => $this->config->get('config_language_id'),
			'recursion' => $recursion
		);
		
		$cache = md5(http_build_query($data));
		
		$cache_data = $this->cache->get('category.' . $cache);
		
		if(!$cache_data){
			$sql="SELECT c.category_id AS category_id,cd.name AS name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') 
			. "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order ASC,c.category_id ASC";
			
			$query=$this->db->query($sql);
			
			$categorys=array();
			
			foreach($query->rows as $result){
				$children_data = array();
					
				if($recursion){
					
				
				$children = $this->getChildCategories($result['category_id']);
					foreach ($children as $child) {
						$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'] ,
							'children'        => $child['children'] ,
							'href'        => $this->url->link('product/category', 'path='.$result['category_id'].'_'. $child['category_id'])	
						);				
					}
				}
				
				$categorys[]=array(
					'category_id' => $result['category_id'],
					'name'        => $result['name'] ,
					'children'    => $children_data,
					'href'        => $this->url->link('product/category', 'path=' . $result['category_id'])
				);
			}
			
			if($categorys){
				$this->cache->set('category.' . $cache, $categorys);
			}
			
			return $categorys;
		}
		
		return $cache_data;
	}
}
?>