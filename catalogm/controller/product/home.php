<?php 
class ControllerProductHome extends Controller {  
	public function index() { 
		$this->load_language('product/home');
	
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
		
		//获取周期 begin


		if(isset($this->request->get['sequence'])){
			$sequence = (int)$this->request->get['sequence'];

			if($sequence!=$this->cart->sequence)
			{
				$this->cart->clear();
				$this->cart->setPeriod($sequence);
			}
		}
		$sequence=$this->cart->sequence;


		
		
		$this->data['sequence'] = $sequence;
		
		$periods=$this->cart->getPeriods();
		 $period=$this->cart->getPeriod();
	//print_r($this->cart->getGoods());
		$this->data['supply_periods'] = $periods;

		//end
		//print_r($this->data['supply_periods']);
		$this->data['products'] = array();
			
		$filter_data = array(
//			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
		
		$fields=array('filter_manufacturer_id','filter_price_range','filter_attr_1','filter_attr_2','filter_attr_3','filter_attr_4','filter_attr_5','filter_category_id','filter_keyword');
		
		foreach($fields as $field){
			if(isset($this->request->get[$field]) && $this->request->get[$field]){
	  			$filter_data[$field]=$this->request->get[$field];
	  		}else{
	  			$filter_data[$field]= null;
	  		}
		}
		
		if(!$filter_data['filter_category_id']){
		    $filter_data['filter_category_id'] = $this->session->data['filter_category_id'];
		}
		    
		$this->data['filter_category_id']          =$filter_data['filter_category_id'];
		$this->session->data['filter_category_id'] =$filter_data['filter_category_id'];		
	
		/* 根据session获取当前菜品周期 */

		$filter_data['filter_start_date']=$period['start_date'];
		$filter_data['filter_end_date']  =$period['end_date'];
		$filter_data['filter_supply_period_id']  =$period['id'];
		$this->data['current_period']    =$period;
		
		
		
		if($filter_data['filter_keyword']){
			$filter_data['filter_name'] = $filter_data['filter_keyword'];
		}
	
		
		$product_total = $this->model_catalog_product->getTotalSupplyProducts($filter_data); 
		
		$results = $this->model_catalog_product->getSupplyProducts($filter_data);
 
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
	
	//	$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);


		$template='product/home_section.tpl';

		$this->renderSection($template);
		

  	}
  	
  	
  	
  	public function follow(){
  		$this->load->model('catalog/product');
  		$this->load->library('json');

  		
  		if(isset($this->request->get['product_id'])){
  			$product_id = (int)$this->request->get['product_id'];
  		}else{
  			$product_id = 0;
  		}
  		
		$article_data = array(
			'status'=>false,
			'info'=>""
		);
  		if (!$this->customer->isLogged()) {
  			$article_data = array(
				'status'=>"1",
			);
			$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
	  		$this->response->setOutput(Json::encode($article_data));
	  		return ;
    	} 
    	//如果已经存在了关注  
    	//$res=$this->model_catalog_product->is_followed($product_id,$this->customer->getId());判断是否点赞
        //点赞逻辑，同一个session会话一个菜品只能点赞一次，点赞数只加不减
    	if(isset($this->session->data['follow_customer_id'])&&isset($this->session->data['follow_customer_id'][$product_id])){
    		$article_data['follow']=$follow;
    		$article_data['status']=false;
    		$article_data['info']="请勿重复点赞!";
    	}else{   
    		
  			$follow=$this->model_catalog_product->follow($product_id,$this->customer->getId());
  		
  			$this->session->data['follow_customer_id']= array();
  			$this->session->data['follow_customer_id'][$product_id] = "true";
  			$article_data['status']='2';
  			$article_data['follow']=$follow;
    		$article_data['info']="点赞成功!";
    	}
  		$this->response->setOutput(Json::encode($article_data));
  	}
}
?>