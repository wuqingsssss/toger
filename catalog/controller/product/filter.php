<?php 
class ControllerProductFilter extends Controller { 	
	public function results(){
		$json = array();
  		
  		$filter=array();
  		
  		if (isset($this->request->post['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->post['path']);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
		
  		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		if (isset($this->request->post['filter_manufacturer_id']) && $this->request->post['filter_manufacturer_id']) {
			$filter_manufacturer_id = $this->request->post['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = NULL;
		}
		
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
		
		$filter['filter_category_id']=$category_id;
		$filter['filter_manufacturer_id']=$filter_manufacturer_id;
		$filter['filter_sub_category']=TRUE;
		$filter['sort']=$sort;
		$filter['order']=$order;
		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;
		
		$fields=array('name','sku','model','cas','mdl');
		
		foreach($fields as $field){
			if(isset($this->request->post['filter_search_type']) && isset($this->request->post['filter_keyword']) && $this->request->post['filter_search_type']==$field){
				${'filter_'.$field}= trim($this->request->post['filter_keyword']);
			}else{
				${'filter_'.$field}= NULL;
			}
		}
		
		$filter['filter_name']= $filter_name; 
		$filter['filter_model']= $filter_model; 
		$filter['filter_sku']= $filter_sku; 
		$filter['filter_cas']= $filter_cas;
		$filter['filter_mdl']= $filter_mdl;
		
		
		
		//获取目前显示的两个模板
		$periods=$this->cart->getPeriods();
		 $period=$this->cart->getPeriod();

	
		/* 根据session获取当前菜品周期 */
$sequence=$this->cart->sequence;
		
		$filter['filter_start_date']=$period['start_date'];
		$filter['filter_end_date']  =$period['end_date'];
		$filter['filter_supply_period_id']  =$period['id'];
		$this->data['current_period']    =$period;
		
			
		$this->data['products'] = array();
				
		$product_total = $this->model_catalog_product->getTotalSupplyProducts($filter); 
		
		$results = $this->model_catalog_product->getSupplyProducts($filter);
		
		$this->data['products']=changeProductResults($results,$this);
				
		$url = '';
		
		if (isset($this->request->post['path'])) {
			$url .= '&path=' . $this->request->post['path'];
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
		$pagination->url = $this->url->link('product/category', $url . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();

						
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search-filter-lists.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/search-filter-lists.tpl';
		} else {
			$this->template = 'default/template/product/search-filter-lists.tpl';
		}
		
		$json['success']=true;
		
		$json['output'] = $this->render();
		
    	$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}
	
	
	public function index() { 
		$this->data['limits'] = array();
			
		$this->data['limits'][] = array(
			'text'  => $this->config->get('config_catalog_limit'),
			'value' => $this->config->get('config_catalog_limit'),
			'href'  => $this->url->link('product/category', '&limit=' . $this->config->get('config_catalog_limit'))
		);
					
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('product/category', '&limit=25')
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
		
		if(isset($this->request->get['filter_category_id'])){
			$this->data['filter_category_id']=$this->request->get['filter_category_id'];
		}else{
			$this->data['filter_category_id']='';
		}
		
		if(isset($this->request->get['filter_name'])){
			$this->data['filter_name']=$this->request->get['filter_name'];
		}else{
			$this->data['filter_name']='';
		}
		
		if(isset($this->request->get['manufacturer_id'])){
			$this->data['filter_manufacturer_id']=$this->request->get['manufacturer_id'];
		}else if(isset($this->request->get['filter_manufacturer_id'])){
			$this->data['filter_manufacturer_id']=$this->request->get['filter_manufacturer_id'];
		}else{
			$this->data['filter_manufacturer_id']='';
		}
		
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

		if(isset($this->session->data['yiyuangou'])){
		    $this->data['yiyuangou'] = 'true';
		}

    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product-filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product-filter.tpl';
		} else {
			$this->template = 'default/template/product/product-filter.tpl';
		}
		
    	$this->render();
  	}
}
?>