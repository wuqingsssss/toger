<?php 
class ControllerCommonSearchFilter extends Controller { 	
	public function index() { 
		$this->data['manufacturers']=$this->getManufacturerOptions();
		$this->data['prices']=$this->getAttributeValues2(83);
		
		$this->data['tedian']=$this->getAttributeValues(37);
		$this->data['os']=$this->getAttributeValues(38);
		$this->data['network']=$this->getAttributeValues(39);
		$this->data['waiguan']=$this->getAttributeValues(40);
		$this->data['photo']=$this->getAttributeValues(41);
		
		if(isset($this->request->get['limit'])){
			$this->data['limit']=$this->request->get['limit'];
		}else{
			$this->data['limit']=$this->config->get('config_catalog_limit');
		}
		
		if(isset($this->request->get['sort'])){
			$this->data['sort']=$this->request->get['sort'];
		}else{
			$this->data['sort']='';
		}
		
		if(isset($this->request->get['order'])){
			$this->data['order']=$this->request->get['order'];
		}else{
			$this->data['order']='';
		}
		
		$fields=array('filter_manufacturer_id','filter_price_range','filter_attr_1','filter_attr_2','filter_attr_3','filter_attr_4','filter_attr_5');
		
		foreach($fields as $field){
			if(isset($this->request->get[$field]) && $this->request->get[$field]){
	  			$this->data[$field]=$this->request->get[$field];
	  		}else{
	  			$this->data[$field]=0;
	  		}
		}
		
//		echo $this->data['filter_price_range'];
		
		$this->data['limits'] = array();
			
		$this->data['limits'][] = array(
			'text'  => 5,
			'value' => 5,
			'href'  => $this->url->link('product/category', '&limit=25')
		);
		
		$this->data['limits'][] = array(
			'text'  => $this->config->get('config_catalog_limit'),
			'value' => $this->config->get('config_catalog_limit'),
			'href'  => $this->url->link('product/category', '&limit=' . $this->config->get('config_catalog_limit'))
		);
		
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('product/category', '&limit=50')
		);
		
		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => $this->url->link('product/category', '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => $this->url->link('product/category', '&limit=75')
		);
		
		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => $this->url->link('product/category', '&limit=100')
		);
		

    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search-filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/search-filter.tpl';
		} else {
			$this->template = 'default/template/common/search-filter.tpl';
		}
		
    	$this->render();
  	}
  	
  	public function lists(){
  		$json = array();
  		
  		$filter=array();
  		
  		if(isset($this->request->post['filter_manufacturer_id']) && $this->request->post['filter_manufacturer_id']){
  			$filter['filter_manufacturer_id']=$this->request->post['filter_manufacturer_id'];
  		}else{
  			$filter['filter_manufacturer_id']= null;
  		}
  		
  		
  		if(!isset($this->request->post['filter_category_id'])){
  			$filter['filter_category_id']=107;
  		}else{
  			$filter['filter_category_id']=$this->request->post['filter_category_id'];
  		}
  		
  		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		if (isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->post['limit'])) {
			$limit = $this->request->post['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
		
		
		$filter['sort']=$sort;
		$filter['order']=$order;
		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;
		
		$this->data['products'] = array();
				
		$product_total = $this->model_catalog_product->getTotalProducts($filter); 
		
		$results = $this->model_catalog_product->getProducts($filter);
		
		$this->data['products']=changeProductResults($results,$this);
					
		$url = '';
		
  		$fields=array('filter_manufacturer_id','filter_price_range','filter_attr_1','filter_attr_2','filter_attr_3','filter_attr_4','filter_attr_5');
		
		foreach($fields as $field){
			if(isset($this->request->post[$field]) && $this->request->post[$field]){
	  			$url .= "&$field=" . $this->request->post[$field];
	  		}
		}
		
		if (isset($this->request->post['sort'])) {
			$url .= '&sort=' . $this->request->post['sort'];
		}	

		if (isset($this->request->post['order'])) {
			$url .= '&order=' . $this->request->post['order'];
		}

		if (isset($this->request->post['limit'])) {
			$url .= '&limit=' . $this->request->post['limit'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('common/home', $url . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();
  		  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search-filter-lists.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/search-filter-lists.tpl';
		} else {
			$this->template = 'default/template/common/search-filter-lists.tpl';
		}
		
		$json['success']=true;
		
		$json['output'] = $this->render();
		
    	$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}
  	
  	private function getAttributeValues($attribute_id){
  		$this->load->model('catalog/attribute');
  		
  		$options=array();
  		
  		$options[]=array(
  			'name' => $this->language->get('text_all'),
  			'value' => 0
  		);
  		
  		$results=$this->model_catalog_attribute->getAttributeValues($attribute_id);
  		
  		foreach($results as $result){
  			if($result['description']){
  				$description=$result['description'];
  			}else{
  				$description=$result['name'];
  			}
  			
  			$options[]=array(
  				'name' => $result['name'],
  				'value' => $result['attribute_value_id']
  			);
  		}
  		return $options;
  	}
  	
  	private function getAttributeValues2($attribute_id){
  		$this->load->model('catalog/attribute');
  		
  		$options=array();
  		
  		$options[]=array(
  			'name' => $this->language->get('text_all'),
  			'value' => 0
  		);
  		
  		$results=$this->model_catalog_attribute->getAttributeValues($attribute_id);
  		
  		foreach($results as $result){
  			if($result['description']){
  				$description=$result['description'];
  			}else{
  				$description=$result['name'];
  			}
  			
  			$options[]=array(
  				'name' => $description,
  				'value' => $result['name']
  			);
  		}
  		return $options;
  	}
  	
  	private function getPriceOptions(){
  		$options=array();
  		
  		$options[]=array(
  			'name' => $this->language->get('text_all'),
  			'value' => 0
  		);
  		
  		$options[]=array(
  			'name' => '0-499',
  			'value' => '0-499'
  		);
  		
  		$options[]=array(
  			'name' => '500-999',
  			'value' => '500-999'
  		);
  		
  		$options[]=array(
  			'name' => '1000-1999',
  			'value' => '1000-1999'
  		);
  		
  		$options[]=array(
  			'name' => '2000-2999',
  			'value' => '2000-2999'
  		);
  		
  		$options[]=array(
  			'name' => '3000-3999',
  			'value' => '3000-3999'
  		);
  		
  		$options[]=array(
  			'name' => '4000-4999',
  			'value' => '4000-4999'
  		);
  		
  		$options[]=array(
  			'name' => '5000以上',
  			'value' => '5000'
  		);
  		
  		return $options;
  	}
  	
  	private function getManufacturerOptions(){
  		$manufacturers=array();
  		
  		$this->load->model('catalog/manufacturer');
		
		$results=$this->model_catalog_manufacturer->getManufacturers();
		
		$manufacturers[]=array(
			'manufacturer_id' => 0,
			'name' => $this->language->get('text_all'),
			'href' => $this->url->link('product/manufacturer')
		);
		
		foreach($results as $result){
			$manufacturers[]=array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name' => $result['name'],
				'href' => $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $result['manufacturer_id'])
			);
		}
		
		return $manufacturers;
  	}
}
?>