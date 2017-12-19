<?php
class ControllerPromotionZerobuy extends Controller {
	private $error = array();

	/**
	 * 加载必要语言，model
	 */
	protected function init(){
		$this->load_language('promotion/promotion');
		$this->load_language('promotion/zerobuy');
		$this->document->setTitle($this->language->get('heading_title_zero_buy'));

		$this->load->model('promotion/zerobuy');
	}

	public function update()
	{
		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->validateForm() ) {
			$this->model_promotion_zerobuy->updatePrProductInfo($this->request->post);
			$this->redirectToList();
		}
		$this->getform();
	}
	
	public function getform()
	{
		$this->data['token'] = $this->session->data['token'];
		$this->load->model('promotion/promotion');
		$pruleinfo=$this->model_promotion_promotion->getPromotionRule($this->request->get['pr_id']);
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
		 
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($pruleinfo['pb_name'], $this->url->link('promotion/promotion/update', 'token=' . $this->session->data['token'].'&pb_id='.$pruleinfo['pb_id'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

		
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title_rule'), $this->url->link('promotion/zerobuy', 'token=' . $this->session->data['token'].'&pr_id=' . $this->request->get['pr_id'], 'SSL'), $this->language->get('text_breadcrumb_separator'));

		$this->initOperStatus();

	
		$this->data['action'] = $this->url->link('promotion/zerobuy/update', 'token=' . $this->session->data['token'] . '&pr_id=' . $this->request->get['pr_id']."&product_id=".$this->request->get['product_id'], 'SSL');
		$this->data['cancel'] = $this->url->link('promotion/zerobuy', 'token=' . $this->session->data['token']."&pr_id=".$this->request->get['pr_id'], 'SSL');
		if (isset($this->request->get['pr_id'])&&isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$promotion_product_info = $this->model_promotion_zerobuy->getPromotionProductInfo($this->request->get);

			$this->data['zerobuyInfo']= $promotion_product_info;
			$this->load->model('catalog/product');
			$this->data['product'] = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}
		$this->data['product_id'] = $this->request->get['product_id'];
		$this->data['pr_id'] = $this->request->get['pr_id'];
		
		$this->template = 'promotion/zerobuy_product_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
/**
	 * 面包屑
	 */
	private function createBreadcrumbs($text,$href,$separator)
	{
		return array(
       		'text'      =>$text,
			'href'      => $href,
      		'separator' => $separator
		);
	}

	private function createActions($text,$href)
	{
		return  array(
				'text' => $text,
				'href' => $href
		);
	}
	
	
	private function initOperStatus()
	{
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	}
	
	
	public function index()
	{
		$this->init();
		$this->getList();
	}
	
	
	public function delete()
	{
		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validateForm()) {
			if(isset($this->request->get['pr_id'])&&isset($this->request->get['product_id']))
			{
				$this->model_promotion_zerobuy->deletePrToProduct($this->request->get['product_id'],$this->request->get['pr_id']);
			}
			$this->redirectToList();
		}
		$this->getList();
	}
	
	public function deleteall() {
    	$this->init();
		if (isset($this->request->post['selected']) && $this->validateForm()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_promotion_zerobuy->deletePrToProduct($product_id,$this->request->post['pr_id']);
	  		}
			$this->redirectToList();
		}
    	$this->getList();
  	}
	
	
	public function getList()
	{
		$this->load_language('promotion/zerobuy');
		$this->load->model('promotion/zerobuy');
		$this->load->model('promotion/promotion');
		
  		$requestes=array(
  		  	'filter_name' => null,
  		  	'filter_model' => null,
  			'filter_sku' => null,
  		  	'filter_price' => null,
  		  	'filter_quantity' => null,
  			'filter_category_id' => null,
  		  	'filter_status' => null,
  			'filter_group' => null,
  		  	'sort' => 'pd.name',
  		  	'order' => 'DESC',
  		  	'page' => 1
  		);
  			
  		foreach ($requestes as $key => $value) {
  			if (isset($this->request->get[$key])) {
  				$$key = $this->request->get[$key];
  			} else {
  				$$key = $value;
  			}
  		}

  
  		$pruleinfo=$this->model_promotion_promotion->getPromotionRule($this->request->get['pr_id']);

		$url = $this->filter();

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		
   		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
   		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($pruleinfo['pb_name'], $this->url->link('promotion/promotion/update', 'token=' . $this->session->data['token'].'&pb_id='.$pruleinfo['pb_id'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
   		 

		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
   			$this->data['breadcrumbs'][] = array(
   				       		'text'      => $pruleinfo['pr_group'].$this->language->get('heading_title_rule'),
   							'href'      => $this->url->link('promotion/zerobuy', 'token=' . $this->session->data['token'] . $url, 'SSL'),
   				      		'separator' => $this->language->get('text_breadcrumb_separator')
   			);
   			$this->data['breadcrumbs'][] = array(
   				       		'text'      => $pruleinfo['pr_group'].$this->language->get('heading_title_product_select'),
   							'href'      => $this->url->link('promotion/zerobuy', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL'),
   				      		'separator' => $this->language->get('text_breadcrumb_separator')
   			);
   		}else{
   			$this->data['breadcrumbs'][] = array(
       		'text'      => $pruleinfo['pr_group'].$this->language->get('heading_title_zero_buy'),	
			'href'      => $this->url->link('promotion/zerobuy', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   			
   		}
   		
   		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
   			$this->data['insert'] = $this->url->link('promotion/zerobuy/saveproduct', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL');
   		}else {
   			$this->data['insert'] = $this->url->link('promotion/zerobuy', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL');
   		}   		
   		

		// check if installed linkpost wordpress
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled('linkpost');
		foreach ($extensions as $key => $value) {
			if ($value == 'wordpress') {
				if ($extensions[$key]['installed']) {
					$this->data['linkpost_wordpress'] = $this->url->link('linkpost/wordpress/newpost', 'token=' . $this->session->data['token'], 'SSL');
				}
			}
		}
		
		$limit=$this->config->get('config_admin_limit');
				
		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_sku'	  => $filter_sku,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_category_id' =>$filter_category_id,
			'filter_group' =>$filter_group,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
			
		);
		
		if(isset($filter_category_id))
		{
			$this->data['filter_category_id'] = $filter_category_id;
		}
		$this->load->model('tool/image');
		$this->load->model('catalog/manufacturer');
		
		$type = null;
		$pr_id = 0;
		if(isset($this->request->get['pr_id'])){
			$pr_id = $this->request->get['pr_id'];
		}
		
		if(isset($this->request->get['type']))
		{
			$type = $this->request->get['type'];
		}
		$this->data['type'] = $type;
		$this->data['pr_id'] = $pr_id;
		$product_total = $this->model_promotion_zerobuy->getTotalProducts($data,$pr_id,$type);
		$results = $this->model_promotion_zerobuy->getProducts($data,$pr_id,$type);
		
		foreach ($results as $result) {
			$action = array();

			
			if(isset($this->request->get['type']))
			{
				
				$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('promotion/zerobuy/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				);
				
			}else {
				$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('promotion/zerobuy/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				);
				$action[] = array(
						'text' => $this->language->get('text_delete'),
						'href' => $this->url->link('promotion/zerobuy/delete', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].$url, 'SSL')
				);
			}
			

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			if($this->isInString1($result['image'],'http')){
			   $image=$result['image'];
			}
			
			$product_specials = $this->model_promotion_zerobuy->getProductSpecials($result['product_id']);

			if ($product_specials) {
				$special = reset($product_specials);
				if(($special['date_start'] != '0000-00-00' && $special['date_start'] > date('Y-m-d')) || ($special['date_end'] != '0000-00-00' && $special['date_end'] < date('Y-m-d'))) {
					$special = FALSE;
				}
			} else {
				$special = FALSE;
			}
			
			$manufacturer='';
			
			if($result['manufacturer_id']){
				$manufacturer_info=$this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
				
				if($manufacturer_info){
					$manufacturer=$manufacturer_info['name'];
				}
			}
			


			$this->data['products'][$result['product_id']] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'sku'      => $result['sku'],
				'manufacturer'      => $manufacturer,
				'price'      => $this->currency->format($result['price']),
				'special'    => $special['price'],
				'image'      => $image,
				'useQuantity' => isset($result['use_quantity'])?$result['use_quantity']:0,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action,
					'pr_group'     => $result['pr_group'],
					'sort_order'     => $result['sort_order'],
					'p_sort_order'     => $result['p_sort_order'],
				'has_option'      => 0,
			);
			
		}
		
		$this->load->model('catalog/category');
		$categories_2 = $this->model_catalog_category->getCategories(0);
		
		$this->data['categories'] = $categories_2;
    	$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = $this->getUrlSortParameters();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
			$this->data['sort_name'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
			$this->data['sort_model'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
			$this->data['sort_sku'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.sku' . $url, 'SSL');
			$this->data['sort_price'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
			$this->data['sort_quantity'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
			$this->data['sort_order'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
			$this->data['sort_category_id'] = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . '&sort=p.category_id' . $url, 'SSL');
		}else{
			
			$this->data['delete'] = $this->url->link('promotion/zerobuy/deleteall', 'token=' . $this->session->data['token'] , 'SSL');
			
			$this->data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
			$this->data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
			$this->data['sort_sku'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sku' . $url, 'SSL');
			$this->data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
			$this->data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
			$this->data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
			$this->data['sort_category_id'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.category_id' . $url, 'SSL');
			$this->data['sort_size'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.size' . $url, 'SSL');
		}
		
		$url = '';

		$url = $this->filter();

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
			$pagination->url = $this->url->link('promotion/zerobuy', 'type='.$this->request->get['type'].'&token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}else{
			$pagination->url = $this->url->link('promotion/zerobuy', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}
		
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_sku'] = $filter_sku;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
			$this->template = 'promotion/zerobuy_product_list2.tpl';
		}else{
			$this->template = 'promotion/zerobuy_product_list.tpl';
		}
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	} 
	
	
	public function saveproduct()
	{
		$this->index();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if (isset($this->request->post['selected'])&&isset($this->request->post['pr_id'])) {
				foreach ($this->request->post['selected'] as $product_id) {
					//插入操作,不存在
					if(!$this->model_promotion_zerobuy->getPromotionProductInfo(array('product_id'=>$product_id,'pr_id'=>$this->request->post['pr_id'])))
					{
						$this->model_promotion_zerobuy->addProductToRule($product_id,$this->request->post['pr_id'],$this->request->post);
					}
				}
			}
			$this->redirectToList();
		}
		$this->getList();
		
		
	}

	
	private function redirectToList(){
		$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));

		$url=$this->getUrlParameters();

		$this->redirect(HTTPS_SERVER . 'index.php?route=promotion/zerobuy&token=' . $this->session->data['token'] . $url);
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'promotion/zerobuy')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		return true;
			
		if (!$this->error) {
			return TRUE;
		} else {
			$this->error['warning'] = $this->language->get('error_required_data');
			return FALSE;
		}
	}
	
private function getCommonUrlParameters(){
		$url = '';
		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}else if(isset($this->request->post['type'])) {
			$url .= '&type=' . $this->request->post['type'];
		}
		if (isset($this->request->get['pr_id'])) {
			$url .= '&pr_id=' . $this->request->get['pr_id'];
		}else if(isset($this->request->post['pr_id'])) {
			$url .= '&pr_id=' . $this->request->post['pr_id'];
		}

		return $url;
	}

	public function getUrlParameters(){
		$url = '';

		$url=$this->getCommonUrlParameters();

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		return $url;
	}
	
	private function filter($url='') {
  		$requestes=array(
  			'filter_name' => 'filter_name',
  			'filter_model' => 'filter_model',
  			'filter_sku' =>   'filter_sku',
  			'filter_price' => 'filter_price',
  			'filter_quantity' => 'filter_quantity',
  			'filter_status' => 'filter_status',
  			'sort' => 'sort',
  			'order' => 'order',
  			'page' => 'page',
  			'filter_category_id' => 'filter_category_id',
  			'pr_id' => 'pr_id',
  		);
  		foreach ($requestes as $key => $value) {
  			if (isset($this->request->get[$key])) {
  				$url .= '&'.$key.'=' . $this->request->get[$value];
  			}
  		}
  		return $url;
  	}
  	
	private function isInString1($haystack, $needle) {
  		//防止$needle 位于开始的位置
  		$haystack = '-_-!' . $haystack;
  		return (bool)strpos($haystack, $needle);
  	}
  	
	private function getUrlSortParameters(){
  		$url='';
  		
  		$requestes=array(
  			'filter_name' => 'filter_name',
  			'filter_model' => 'filter_model',
  			'type' => 'type',
  			'filter_sku' =>   'filter_sku',
  			'filter_price' => 'filter_price',
  			'filter_quantity' => 'filter_quantity',
  			'filter_status' => 'filter_status',
  			'page' => 'page',
  			'filter_category_id' => 'filter_category_id'
  		);
  			
  		foreach ($requestes as $key => $value) {
  			if (isset($this->request->get[$key])) {
  				$url .= '&'.$key.'=' . $this->request->get[$value];
  			}
  		}
  		
  		return $url;
  	}

}
?>