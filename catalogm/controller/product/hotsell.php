<?php 
class ControllerProductHotSell extends Controller {  
	public function index() { 
		$this->load_language('product/hotsell');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('product/home'),
				'separator' => $this->language->get('text_separator')
			);	
				
		
		$this->load->model('catalog/product');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
		
		$this->data['products'] = array();
			
		$filter_data = array(
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
		
		$fields=array('filter_manufacturer_id','filter_price_range','filter_attr_1','filter_attr_2','filter_attr_3','filter_attr_4','filter_attr_5');
		
		foreach($fields as $field){
			if(isset($this->request->get[$field]) && $this->request->get[$field]){
	  			$filter_data[$field]=$this->request->get[$field];
	  		}else{
	  			$filter_data[$field]= null;
	  		}
		}
				
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data); 
		
		$results = $this->model_catalog_product->getProducts($filter_data);
		
		$this->data['products']=changeProductResults($results,$this);
			
		$url = '';
		
		foreach($fields as $field){
			if(isset($this->request->get[$field]) && $this->request->get[$field]){
	  			$url .= "&$field=" . $this->request->get[$field];
	  		}
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->data['limits'] = array();
		
		$this->data['limits'][] = array(
			'text'  => $this->config->get('config_catalog_limit'),
			'value' => $this->config->get('config_catalog_limit'),
			'href'  => $this->url->link('product/category', $url . '&limit=' . $this->config->get('config_catalog_limit'))
		);
					
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('product/category', $url . '&limit=25')
		);
		
		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => $this->url->link('product/category', $url . '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => $this->url->link('product/category', $url . '&limit=75')
		);
		
		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => $this->url->link('product/category', $url . '&limit=100')
		);
					
		$url = '';
		
		$fields=array('filter_manufacturer_id','filter_price_range','filter_attr_1','filter_attr_2','filter_attr_3','filter_attr_4','filter_attr_5');
		
		foreach($fields as $field){
			if(isset($this->request->get[$field]) && $this->request->get[$field]){
	  			$url .= "&$field=" . $this->request->get[$field];
	  		}
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/home', $url . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();
	
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/hotsell.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/hotsell.tpl';
		} else {
			$this->template = 'default/template/product/hotsell.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
			
		$this->response->setOutput($this->render());										

  	}
}
?>