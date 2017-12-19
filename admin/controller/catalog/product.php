<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->service('meituan/product','service');
		
				
	}
	
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');

		$url = $this->filter();
		
		$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

  	public function index() {
  	    $this->log_admin->trace("");
  	    
  		$this->init();
		
		$this->getList();
  	}

  	public function insert() {
    	$this->init();
    	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->redirectToList();
			
    	}

    	if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
    		$this->getForm2();
    	}else{
    		$this->getForm();
    	}
  	}

  	public function update() {
    	$this->init();
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_catalog_product->editProduct ( $this->request->get ['product_id'], $this->request->post );
			/*
			if ($this->session->data ['REFERER']) {
				$this->redirect ( $this->session->data ['REFERER'] );
			} else {
				$this->redirectToList ();
			}*/
			
			$this->goback();
		}
		    $this->setbackparent();

		//$this->session->data['REFERER']= $this->request->server['HTTP_REFERER'];
		

  		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
    		$this->getForm2();
    	}else{
    		$this->getForm();
    	}
  	}

  	public function delete() {
    	$this->init();

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}

			$this->redirectToList();
		}

    	$this->getList();
  	}
  	
  	public function changeStatus() {
    	$this->init();

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->updateProductStatus($product_id,$this->request->get['status']);
	  		}

			$this->redirectToList();
		}

    	$this->getList();
  	}


  	public function copy() {
    	$this->init();

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
	  		}

			$this->redirectToList();
		}

    	$this->getList();
  	}

  	public function hmtprocreate(){
  		$this->init();
  		
  		if (isset($this->request->post['selected']) ) {
  			foreach ($this->request->post['selected'] as $product_id) {

  				$product_info = $this->model_catalog_product->getProduct($product_id);
  				$datapro=array();
  				$datapro['sku']=$product_info['sku'];
  				$datapro['combine']=$product_info['combine'];
  				$datapro['name']=$product_info['name'];
  				$datapro['image']=basename($product_info['image']);
  				$datapro['price']=$product_info['price'];
  				$datapro['description']='description';//rawurlencode($product_info['description'])
  				
  				$promotion=$this->model_catalog_product->getProductPromotionInfo($product_id);
  				if($promotion&&isset($promotion['promotion_price']))
  				$datapro['special_price']=$promotion['promotion_price'];
  				//$data['special_price']=$promotion['promotion_code'];

  				$prodata[]=$datapro;
  				
  			}
  			$data['foodinfo']=json_encode($prodata);
  			
  			$res=$this->service_meituan_product->HMTProCreate($data);
  			$this->log_admin->debug($res);

  			if($res['status']=='0'){
  					
  			}
  			$this->redirectToList();
  		}
  		
  		$this->getList();
  		
  	}
  	public function hmtprodelete(){
  		$this->init();
  	
  		if (isset($this->request->post['selected']) ) {
  			foreach ($this->request->post['selected'] as $product_id) {
  	
  				$product_info = $this->model_catalog_product->getProduct($product_id);
  				$datapro=array();
  				$datapro['sku']=$product_info['sku'];
             	$prodata[]=$datapro;
  			}
	
  			$data['foodinfo']=json_encode($prodata);
  				
  			$res=$this->service_meituan_product->HMTProDelete($data);

  			if($res['status']=='0'){
  					
  			}
  			$this->redirectToList();
  		}
  	
  		$this->getList();
  	
  	}
  	
  	
  	private function isInString1($haystack, $needle) {
  		//防止$needle 位于开始的位置
  		$haystack = '-_-!' . $haystack;
  		return (bool)strpos($haystack, $needle);
  	}
  	
  	private function filter($url='') {
  		$requestes=array(
  			'filter_name' => 'filter_name',
  			'filter_model' => 'filter_model',
  			'type' => 'type',
  			'filter_sku' =>   'filter_sku',
  			'filter_price' => 'filter_price',
  			'filter_quantity' => 'filter_quantity',
  			'filter_status' => 'filter_status',
  			'sort' => 'sort',
  			'order' => 'order',
  			'page' => 'page',
  			'filter_category_id' => 'filter_category_id',
  			'filter_period_id' =>'filter_period_id'
  		);
  			
  		foreach ($requestes as $key => $value) {
  			if (isset($this->request->get[$key])) {
  				$url .= '&'.$key.'=' . $this->request->get[$value];
  			}
  		}
  		
  		return $url;
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
  	
  	private function getList() {
  		$requestes=array(
  		  	'filter_name' => null,
  		  	'filter_model' => null,
  			'filter_sku' => null,
  		  	'filter_price' => null,
  		  	'filter_quantity' => null,
  			'filter_category_id' => null,
  			'filter_period_id' => null,
  		  	'filter_status' => null,
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

		$url = $this->filter();

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
   			$this->data['breadcrumbs'][] = array(
   				       		'text'      => $this->language->get('heading_title'),
   							'href'      => $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL'),
   				      		'separator' => $this->language->get('text_breadcrumb_separator')
   			);
   		}else{
   			$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
	      		'separator' => $this->language->get('text_breadcrumb_separator')
	   		);
   		}
   		

   		
   		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
   			$this->data['insert'] = $this->url->link('catalog/product/insert', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['copy'] = $this->url->link('catalog/product/copy', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('catalog/product/delete', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['enabled'] = $this->url->link('catalog/product/changeStatus', 'type=1&status=1&token=' . $this->session->data['token'], 'SSL');
			$this->data['disabled'] = $this->url->link('catalog/product/changeStatus', 'type=1&status=0&token=' . $this->session->data['token'], 'SSL');
			//$this->data['hmtprocreate'] = $this->url->link('catalog/product/hmtprocreate', 'type=1&token=' . $this->session->data['token'], 'SSL');
			//$this->data['hmtprodelete'] = $this->url->link('catalog/product/hmtprodelete', 'type=1&token=' . $this->session->data['token'], 'SSL');
   		}else{
   			$this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['enabled'] = $this->url->link('catalog/product/changeStatus', 'status=1&token=' . $this->session->data['token'], 'SSL');
			$this->data['disabled'] = $this->url->link('catalog/product/changeStatus', 'status=0&token=' . $this->session->data['token'], 'SSL');
			//$this->data['hmtprocreate'] = $this->url->link('catalog/product/hmtprocreate', 'token=' . $this->session->data['token'], 'SSL');
			//$this->data['hmtprodelete'] = $this->url->link('catalog/product/hmtprodelete', 'token=' . $this->session->data['token'], 'SSL');
   		}
		
		
		// check if installed linkpost wordpress
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled('linkpost');
		
		$this->log_admin->debug($extensions);
		
		foreach ($extensions as $key => $value) {
			if ($value == 'wordpress') {
				if (!empty($extensions[$key]['installed'])) {
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
				'filter_period_id' =>$filter_period_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$this->load->model('tool/image');
		$this->load->model('catalog/manufacturer');

		$this->log_admin->debug($data);
		$product_total = $this->model_catalog_product->getTotalProducts($data);

		
		
		$mtskulist=$this->service_meituan_product->HMTProList();
		$this->log_admin->debug($mtskulist);

		$this->load->model('catalog/category');
		
		$categories=$this->model_catalog_category->getCategories(0);
		foreach($categories as $cat)
			$cats[$cat['category_id']]=$cat['name'];
		$this->data['categories'] = $categories;
		
		
		$this->load->model ( 'catalog/supply_period' ,'service');
		$searcher = array (
				'filter_show_date' => date ( 'Y-m-d H:i:s', time () ),
				'start' => 0,
				'limit' => getShowLimit ()
		);
			
		$supplyperiods = $this->model_catalog_supply_period->getSupplyPeriods ( $searcher ,1);
		
		$this->data['periods'] = $supplyperiods;
		foreach($supplyperiods as $p)
			$periods[$p['id']]=$p['name'];		
		
		$results = $this->model_catalog_product->getProducts($data);
	
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			
			$preview=array(
					'text' => $this->language->get('text_preview'),
					'href' =>$result['link_url']?$result['link_url']: HTTP_CATALOG.'index.php?route=product/product&product_id='. $result['product_id']
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			if($this->isInString1($result['image'],'http')){
			   $image=$result['image'];
			}
			
			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			if ($product_specials) {
				$special = reset($product_specials);
				if(($special['date_start'] != '0000-00-00' && $special['date_start'] > date('Y-m-d')) || ($special['date_end'] != '0000-00-00' && $special['date_end'] < date('Y-m-d'))) {
					$special = FALSE;
				}
			} else {
				$special = FALSE;
			}
			
			$datenow = strtotime ( date ( 'Y-m-d', time () ) );
			if (strtotime ( $product_data ['date_available'] ) > $datenow) {
				$available = -1; // 未开始
			}elseif (( int ) $product_data ['date_unavailable'] && strtotime ( $product_data ['date_unavailable'] ) < $datenow) {
				$available = 2; // 已过期
			}
			else {
				$available = 1;
			}
			
			if ($query->row ['quantity'] <= 0 && $query->row ['subtract']) {
				$available = 3; // 库存不足
			}

			if (! $this->model_catalog_product->isProductInPeriod ( $result['product_id'], -1, date('Y-m-d H:i:s',time()) ))
			{
					$available = 4;//菜品不在周期
			}
			
			
			
			$product_data['available']=$available;
			
			$manufacturer='';
			
			if($result['manufacturer_id']){
				$manufacturer_info=$this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
				
				if($manufacturer_info){
					$manufacturer=$manufacturer_info['name'];
				}
			}

			
			$cat_ids=$this->model_catalog_product->getProductCategories($result['product_id']);
			$cat_ids=array_flip($cat_ids);
			$cat_names=implode(',', array_intersect_key($cats,$cat_ids));
			
			$p_ids=$this->model_catalog_product->getProductperiods($result['product_id']);
			$p_ids=array_flip($p_ids);
			$period_names=implode(',', array_intersect_key($periods,$p_ids));
							
			
			$cat_names=str_replace($cats[$filter_category_id], '<font color="red">'.$cats[$filter_category_id].'</font>', $cat_names);
			$this->data['products'][$result['product_id']] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'cat_name'=>$cat_names,
					'period_name'=>$period_names,
						
				'model'      => $result['model'],
				'sku'        => $result['sku'],
				'prod_type'        => $result['prod_type'],
				'manufacturer'      => $manufacturer,
				'price'      => $this->currency->format($result['price']),
				'special'    => $special['price'],
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'combine'   => $result['combine'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'available'     => $available,
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'preview'     => $preview,
				'action'     => $action,
				'status_mt'     => in_array($result['sku'],$mtskulist),
				'has_option'      => 0,
			);
			
		}

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
			$pagination->url = $this->url->link('catalog/product', 'type=1&token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}else{
			$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}
		

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_sku'] = $filter_sku;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_period_id'] = $filter_period_id;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		
		
		
		//获取第三方平台列表
		$this->load->model('catalog/partnercode');
		$this->data['partners'] = $this->model_catalog_partnercode->getAllPartners();
		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
			$this->template = 'catalog/product_list2.tpl';
		}else{
			$this->template = 'catalog/product_list.tpl';
		}
		
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}

  	private function product_setter($product_info,$key,$default=''){
  		if (isset($this->request->post[$key])) {
  			$this->data[$key] = $this->request->post[$key];
  		} elseif (isset($product_info)) {
  			$this->data[$key] = $product_info[$key];
  		} else {
  			$this->data[$key] = $default;
  		}
  	}
  	
  	private function getForm() {
  		$errores=array(
  			'warning' => '',
  			'name' => array(),
  			'meta_description' =>array(),
	  		'description' =>array(),
	  		'model' => '',
  			'date_available' => ''
  		);
  		
  		$err_flag='error_';
  		foreach ($errores as $key => $value) {
	  		if (isset($this->error[$key])) {
				$this->data[$err_flag.$key] = $this->error[$key];
			} else {
				$this->data[$err_flag.$key] = $value;
			}
  		}
  		
		$url = $this->filter();

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => false
   		);

		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_add'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		} else {
			$this->data['action'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_edit'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		}

		$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
      		
      		if($product_info['share_link']){
      			$this->load->service ('baidu/dwz','service');
      			$dwz=$this->service_baidu_dwz->hcreate(htmlspecialchars_decode($product_info['share_link']));
      		     $product_info['share_short_link']=$dwz['tinyurl'];}
    	}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		//获取 当前语言code
		$lan_code = $this->language->get('code');
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($product_info)) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$this->data['product_description'] = array();
		}
		
		$this->load->model('tool/image');
		//商品信息 详细说明标签  图片地址格式化
		if ($this->data['product_description'][$this->data['languages'][$lan_code]['language_id']]['des_img'] && file_exists(DIR_IMAGE . $this->data['product_description'][$this->data['languages'][$lan_code]['language_id']]['des_img'])) {
			$this->data['product_description'][$this->data['languages'][$lan_code]['language_id']]['des_img'] = $this->model_tool_image->resize($this->data['product_description'][$this->data['languages'][$lan_code]['language_id']]['des_img'], 100, 100);
		} else {
			$this->data['product_description'][$this->data['languages'][$lan_code]['language_id']]['des_img'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->product_setter(isset($product_info)?$product_info : NULL, 'model');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'sku');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'upc');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'location');
		
		$this->product_setter(isset($product_info)?$product_info : NULL, 'garnish');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'cooking_time');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'calorie');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'follow');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'packing_type');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'prod_type');
		
		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['product_store'])) {
			$this->data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($product_info)) {
			$this->data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$this->data['product_store'] = array(0);
		}
		
		$this->product_setter(isset($product_info)?$product_info : NULL, 'keyword');
		$this->product_setter(isset($product_info)?$product_info : NULL, 'link_url');
		$this->product_setter(isset($product_info)?$product_info : '', 'share_image');
		$this->product_setter(isset($product_info)?$product_info : '', 'share_link');
		$this->product_setter(isset($product_info)?$product_info : '', 'share_short_link');
		if (isset($this->request->post['product_tag'])) {
			$this->data['product_tag'] = $this->request->post['product_tag'];
		} elseif (isset($product_info)) {
			$this->data['product_tag'] = $this->model_catalog_product->getProductTags($this->request->get['product_id']);
		} else {
			$this->data['product_tag'] = array();
		}

		$this->product_setter(isset($product_info)?$product_info : NULL, 'image');
		
		
		if (isset($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		if (isset($product_info) && $product_info['share_image'] && file_exists(DIR_IMAGE . $product_info['share_image'])) {
			$this->data['share_image_preview'] = $this->model_tool_image->resize($product_info['share_image'], 100, 100);
		} else {
			$this->data['share_image_preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if(isset($product_info)&&$this->isInString1($product_info['image'],'http')){
			$this->data['preview'] = $product_info['image'];
		}
		
		$this->load->model('catalog/manufacturer');

    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	$this->product_setter(isset($product_info)?$product_info : NULL, 'manufacturer_id',0);
    	$this->product_setter(isset($product_info)?$product_info : NULL, 'shipping',1);
    	
		if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (isset($product_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}
		if (isset($this->request->post['date_unavailable'])) {
			$this->data['date_unavailable'] = $this->request->post['date_unavailable'];
		} elseif (isset($product_info)) {
			$this->data['date_unavailable'] = $product_info['date_unavailable'];
		} else {
			$this->data['date_unavailable'] = date('Y-m-d', time() + 7*86400);
		}
		
		$this->product_setter(isset($product_info)?$product_info : NULL, 'quantity',1);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'minimum',1);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'subtract',1);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'sort_order',1);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'featured',0);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'donation',0);
		$this->product_setter(isset($product_info)?$product_info : NULL, 'combine',0);
		
		
		
		
		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($product_info)) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

		$this->product_setter(isset($product_info)?$product_info : NULL, 'price');
    	$this->product_setter(isset($product_info)?$product_info : NULL, 'status',1);
    	
		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->product_setter(isset($product_info)?$product_info : NULL, 'tax_class_id',0);
	
		$this->product_setter(isset($product_info)?$product_info : NULL, 'weight');
    	
		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
      		$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
    	} elseif (isset($product_info)) {
      		$this->data['weight_class_id'] = $product_info['weight_class_id'];
    	} elseif (isset($weight_info)) {
      		$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
		} else {
      		$this->data['weight_class_id'] = '';
    	}

    	$this->product_setter(isset($product_info)?$product_info : NULL, 'length');
    	$this->product_setter(isset($product_info)?$product_info : NULL, 'width');
    	$this->product_setter(isset($product_info)?$product_info : NULL, 'height');
    	
		$this->load->model('localisation/length_class');

		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
      		$this->data['length_class_id'] = $this->request->post['length_class_id'];
    	} elseif (isset($product_info)) {
      		$this->data['length_class_id'] = $product_info['length_class_id'];
    	} elseif (isset($length_info)) {
      		$this->data['length_class_id'] = $this->config->get('config_length_class_id');
    	} else {
    		$this->data['length_class_id'] = '';
		}

		if (isset($this->request->post['product_attribute'])) {
			$this->data['product_attributes'] = $this->request->post['product_attribute'];
		} elseif (isset($product_info)) {
			$this->data['product_attributes'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$this->data['product_attributes'] = array();
		}

		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif (isset($product_info)) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		} else {
			$product_options = array();
		}
		
		$this->data['product_options'] = array();

		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' ||$product_option['type'] == 'virtual_product' ||$product_option['type'] == 'color' || $product_option['type'] == 'autocomplete'  ) {
				$product_option_value_data = array();

				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] =$product_option_value;
				}
				
				$this->data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);
			} else {
				$this->data['product_options'][] = $product_option;
			}
		}
		
		
		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		
		/* 获取配送站 */
		$points_option = array ();
		$this->load->model('catalog/pointdelivery');
		$delivers = $this->model_catalog_pointdelivery->getDeliverys ();
		$status=array(1=>'',2=>'(测试)',0=>'(离线)');
		$statustitle=array();
		$points_option_name[0]='通用';
		foreach ( $delivers as $deliver ) {
			$points_option [EnumDelivery::getDeliveryInfo($deliver['code'])][] = array (
					'value' => $deliver ['delivery_id'],
					'name' => $deliver ['region_name'].$status[$deliver ['status']]
			);

			$points_option_name[$deliver ['delivery_id']]='宅配：'.$deliver ['region_name'].$status[$deliver ['status']];
		}
		
		$this->data['points_option'] = $points_option;
		$this->data['points_option_name'] = $points_option_name;
		
		
		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($product_info)) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$this->data['product_discounts'] = array();
		}

		if (isset($this->request->post['product_special'])) {
			$this->data['product_specials'] = $this->request->post['product_special'];
		} elseif (isset($product_info)) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$this->data['product_specials'] = array();
		}

		
		
		
		
		
		if (isset($this->request->post['product_special0'])) {
			$product_special0s = $this->request->post['product_special0'];
		} elseif (isset($product_info)) {
			$product_special0s = $this->model_catalog_product->getProductSpecial0s($this->request->get['product_id']);
		} else {
			$product_special0s = array();
		}
		
		$product_special0array=array();
		foreach ($product_special0s as $key=>$special)
		{
			if(empty(trim($special['code']))){
				$special['region_name']='all';
				$special['delivery_id']=0;
			}
			
			$special['delivery_name']=EnumDelivery::getDeliveryInfo($special['code']);
			
			$product_special0array[$special['region_name']][$special['delivery_id']][$special['product_special_id']]=$special;

		}
		
		//print_r($product_special0array);
		$this->data['product_special0s'] =$product_special0array;
		
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($product_info)) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$this->data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['product_images'][] = array(
				'image'   => $image,
				'preview' => $this->model_tool_image->resize($image, 100, 100)
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->load->model('catalog/download');

		$this->data['downloads'] = $this->model_catalog_download->getDownloads();

		if (isset($this->request->post['product_download'])) {
			$this->data['product_download'] = $this->request->post['product_download'];
		} elseif (isset($product_info)) {
			$this->data['product_download'] = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$this->data['product_download'] = array();
		}

		$this->load->model('catalog/category');

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = $this->request->post['product_category'];
		} elseif (isset($product_info)) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else if(isset($this->request->get['filter_category_id']) && $this->request->get['filter_category_id']){
			$this->data['product_category'] = array($this->request->get['filter_category_id']);
		} else {
			$this->data['product_category'] = array();
		}

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($product_info)) {
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = array();
		}

		$this->data['product_related'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
		
/* #BEGIN 增加组合商品相关处理逻辑  */
  		if (isset($this->request->post['product_combine'])) {
			$products = $this->request->post['product_combine'];
		} elseif (isset($product_info)) {
			$products = $this->model_catalog_product->getProductCombine($this->request->get['product_id']);
		} else {
			$products = array();
		}
		$this->data['product_combine'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$this->data['product_combine'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
/* #END 增加组合商品相关处理逻辑  */
		/* #BEGIN 增加优惠劵关联相关处理逻辑  */
		if (isset($this->request->post['product_coupon'])) {
			$coupons = $this->request->post['product_coupon'];
		} elseif (isset($product_info)) {

			$this->load->model('catalog/product');
			$coupons= $this->model_catalog_product->getProductCoupons($this->request->get['product_id']);
		} else {
			$coupons= array();
		}
	
		$this->data['product_coupon'] = $coupons;
		
		
		

		/* #END 增加优惠劵关联相关处理逻辑  */
		/* #BEGIN 增加储值卡关联相关处理逻辑  */
		if (isset($this->request->post['product_trans_code'])) {
			$trans_code = $this->request->post['product_trans_code'];
		} elseif (isset($product_info)) {
		
			$this->load->model('catalog/product');
			$trans_code= $this->model_catalog_product->getProductTrans($this->request->get['product_id']);
		} else {
			$trans_code= array();
		}
		
		$this->data['product_trans_code'] = $trans_code;
		
		/* #END 增加储值卡关联相关处理逻辑  */
		
		
   		$this->product_setter(isset($product_info)?$product_info : NULL, 'points');

		if (isset($this->request->post['product_reward'])) {
			$this->data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($product_info)) {
			$this->data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$this->data['product_reward'] = array();
		}

		if (isset($this->request->post['product_layout'])) {
			$this->data['product_layout'] = $this->request->post['product_layout'];
		} elseif (isset($product_info)) {
			$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
		} else {
			$this->data['product_layout'] = array();
		}
		
		$fields=array('delivery_time','size');
		
		foreach($fields as $field){
			$this->product_setter(isset($product_info)?$product_info : NULL, $field);
		}
		
		
  		if (isset($this->request->post['package'])) {
			$this->data['package'] = $this->request->post['package'];
		} elseif (isset($product_info)) {
			$this->data['package'] =$product_info['package'] ;
		} else {
			$this->data['package'] = '';
		}
		
		
		/* Option stock */
  		/*if (isset($this->request->post['has_option'])) {
      		$this->data['has_option'] = $this->request->post['has_option'];
      	} elseif (isset($product_info)) {
      		$this->data['has_option'] = $product_info['has_option'];
      	} else {
      		$this->data['has_option'] = '';
      	}
      		
  		if (isset($this->request->get['product_id'])) {
      		$this->data['product_option_stocks'] = $this->model_catalog_product->getProductOptionStocks($this->request->get['product_id']);
      	} else {
      		$this->data['product_option_stocks'] = array();
      	}
      	*/
		
      	if(isset($product_info)){
      		$this->data['product_id']=$product_info['product_id'];
      	}else{
      		$this->data['product_id']=0;
      	}
      	
      	//增加商品固定标签选择
      	$this->data['product_tags']=array();//$this->initProductTags();
      	
  		if (isset($this->request->post['product_tag'])) {
			$this->data['product_tag'] = $this->request->post['product_tag'];
		} elseif (isset($product_info)) {
			$this->data['product_tag'] = $this->model_catalog_product->getProductTags($this->request->get['product_id']);
		} else {
			$this->data['product_tag'] = array();
		}
		
		if(isset($this->request->get['product_id'])){
			$results = $this->model_catalog_product->getAllProductTemplets((int)$this->request->get['product_id']);
			$this->data['templets']= array();
			if($results){
			foreach ($results as $result) {
				$action = array();

				$action[] = array(
						'text' => $this->language->get('text_delete'),
						'href' => $this->url->link('catalog/product/del_templet', 'token=' . $this->session->data['token'] . '&product_templet_id=' . $result['product_templet_id'] , 'SSL')
				);
				
				$this->data['templets'][] = array(
					'templet_info' => unserialize($result['templet_info']),
					'product_id'=>$result['product_id'],
					'name'=>$result['name'],
					'product_templet_id'=>$result['product_templet_id'],
					'action'=>$action,
				);
			}
		}
		
		}else{
			$this->data['templets'] = array();
		}
		
		$this->data['save_to_templet'] = $this->url->link('catalog/product/save_to_templet&product_id='.(int)$product_info['product_id'].'&token=' . $this->session->data['token'],'','SSL');
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		
		$this->data['write_templet_name'] = $this->url->link('catalog/product/write_templet_name','','SSL');
		
		$this->template = 'catalog/product_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}
  	
  	
  	public function del_templet(){
  		
  		$this->init();
  		
  		if(isset($this->request->get['product_templet_id'])){
  			$product_templet_id = $this->request->get['product_templet_id'];
  		}else{
  			$product_templet_id=0;
  		}
  		
  		$this->model_catalog_product->delTemplet($product_templet_id);
  		
  		$this->redirectToList();
  	}
  	
  	public function save_to_templet(){
  		
  		$this->init();
  		
  		if(isset($this->request->get['product_id'])){
  			$product_id = $this->request->get['product_id'];
  		}else{
  			$product_id=0;
  		}
  		
  		if(isset($this->request->post['price'])){
  			$price = (float)$this->request->post['price'];
  		}else{
  			$price=0;
  		}
  		
  		if(isset($this->request->post['templet_name'])){
  			$templet_name = $this->request->post['templet_name'];
  		}else{
  			$templet_name='';
  		}
  		
  		
  		
  		
  		$templet_info = serialize(array('price'=>$price,'templet_name'=>$templet_name));
  		$this->model_catalog_product->addProductTemplet($product_id,$templet_info);
  		$this->redirectToList();
  	}
  	
  	private function validateForm() {
  		$rules=$this->load->rule();
    	
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->post['product_description'] as $language_id => $value) {
      		if ((utf8_strlen(utf8_decode($value['name'])) < 1) ) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function option() {
		$output = '';

		$this->load->model('catalog/option');

		$results = $this->model_catalog_option->getOptionValues($this->request->get['option_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['option_value_id'] . '"';

			if (isset($this->request->get['option_value_id']) && ($this->request->get['option_value_id'] == $result['option_value_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->response->setOutput($output);
	}

	/**
	 * 补全菜品信息
	 */
	public function autocomplete() {
		
		$json = array();
	
		if(isset($this->request->post['filter_name'])){
			$this->request->get['filter_name']=trim($this->request->post['filter_name']);
		}

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_sku'])  || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/product');
			
			$requestes=array(
			    'filter_name' => '',
			    'filter_model' => '',
				'filter_sku' => '',
			    'filter_category_id' => '',
			    'filter_sub_category' => '',
			    'limit' => 20
			  );
			  
			foreach ($requestes as $key => $value) {
			    if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
			      $$key = trim($this->request->get[$key]);
			    } else {
			      $$key = $value;
			    }
			 }

			$data = array(
				'filter_name'         => $filter_name,
				'filter_model'        => $filter_model,
				'filter_sku'       	  => $filter_sku,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
				,'filter_status'       => 1 //菜品状态判断暂时关闭 20150404 
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);	
							
				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' ||$product_option['type'] == 'virtual_product' ||$product_option['type'] == 'color' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'autocomplete') {
						$option_value_data = array();
					
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $product_option_value['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);	
						}
					
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $option_value_data,
							'required'          => $product_option['required']
						);	
					} else {
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $product_option['option_value'],
							'required'          => $product_option['required']
						);				
					}
				}
				/*增加促销逻辑 */
				
				/*增加促销逻辑 */
				$product_promotion = $this->model_catalog_product->getProductPromotionInfo($result['product_id']);
				
				$json[] = array(
					'product_id' => $result['upid'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'model'      => $result['model'],
					'sku'      => $result['sku'],
					'image'      => $result['image'],
					'option'     => $option_data,
					'price'      => $result['price'],
				    'promotion_code' => $product_promotion['promotion_code'],
				    'promotion_price'=> $product_promotion['promotion_price'],
				    'status'     => $result['status']
				);	
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function autocompletetemplet() {
		
		$json = array();
		
		if(isset($this->request->post['filter_name'])){
			$this->request->get['filter_name']=trim($this->request->post['filter_name']);
		}
			
			
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_sku'])  || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/product');
			
			$requestes=array(
			    'filter_name' => '',
			    'filter_model' => '',
				'filter_sku' => '',
			    'filter_category_id' => '',
			    'filter_sub_category' => '',
			    'limit' => 40
			  );
			  
			foreach ($requestes as $key => $value) {
			    if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
			      $$key = trim($this->request->get[$key]);
			    } else {
			      $$key = $value;
			    }
			 }

			$data = array(
				'filter_name'         => $filter_name,
				'filter_model'        => $filter_model,
				'filter_sku'       		=> $filter_sku,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_catalog_product->getProductsTemplets($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);	
				
				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' ||$product_option['type'] == 'virtual_product' ||$product_option['type'] == 'color' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'autocomplete') {
						$option_value_data = array();
					
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $product_option_value['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);	
						}
					
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $option_value_data,
							'required'          => $product_option['required']
						);	
					} else {
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $product_option['option_value'],
							'required'          => $product_option['required']
						);				
					}
				}
				
				
				$templetInfo = unserialize($result['templet_info']);
				
				$json[] = array(
					'product_id' => $result['upid'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')."------".$templetInfo['templet_name'],	
					'model'      => $result['model'],
					'sku'      => $result['sku'],
					'option'     => $option_data,
					'price'      => $templetInfo['price']?$templetInfo['price']: $result['price'],
					'templet_id' =>$result['product_templet_id'],
				    'sort_order' => 10
				);	
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function optionvalue() {
		
		$json = array();
		
		if(isset($this->request->post['filter_name']))
			$this->request->get['filter_name']=$this->request->post['filter_name'];
			
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_sku'])  || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/product');
			
			$requestes=array(
			    'filter_name' => '',
			    'filter_model' => '',
				'filter_sku' => '',
			    'filter_category_id' => '',
			    'filter_sub_category' => '',
			    'limit' => 20
			  );
			  
			foreach ($requestes as $key => $value) {
			    if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
			      $$key = $this->request->get[$key];
			    } else {
			      $$key = $value;
			    }
			 }

			$data = array(
				'filter_name'         => $filter_name,
			);
			
			$this->load->model('catalog/option');
			
			$results = $this->model_catalog_option->getFilterOptionValues($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'option_value_id' => $result['option_value_id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')
				);	
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function attachment() {
		$this->load_language('catalog/product');
	
		$json = array();
	
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
				
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}
				
// 			$allowed = array();
				
// 			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
				
// 			foreach ($filetypes as $filetype) {
// 				$allowed[] = trim($filetype);
// 			}
				
// 			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
// 				$json['error'] = $this->language->get('error_filetype');
// 			}
	
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
	
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(mt_rand());
	
				// Hide the uploaded file name so people can not link to it directly.
				$json['file'] = $this->encryption->encrypt($file);
	
				if(!is_dir (DIR_DOWNLOAD.'/TZH-attachment/')){
					mkdir(DIR_DOWNLOAD.'/TZH-attachment/');
				}
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD.'/TZH-attachment/' . $file);
			}
	
			$json['success'] = $this->language->get('text_upload');
		}
	
		$this->response->setOutput(json_encode($json));
	}
	
	public function certificate() {
		$this->load_language('catalog/product');
	
		$json = array();
	
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
	
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}
	
			// 			$allowed = array();
	
			// 			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
	
			// 			foreach ($filetypes as $filetype) {
			// 				$allowed[] = trim($filetype);
			// 			}
	
			// 			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
			// 				$json['error'] = $this->language->get('error_filetype');
			// 			}
	
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
	
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(mt_rand());
	
				// Hide the uploaded file name so people can not link to it directly.
				$json['file'] = $this->encryption->encrypt($file);
	
				if(!is_dir (DIR_DOWNLOAD.'/TZH-certificate/')){
					mkdir(DIR_DOWNLOAD.'/TZH-certificate/');
				}
	
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD.'/TZH-certificate/' . $file);
			}
	
			$json['success'] = $this->language->get('text_upload');
		}
	
		$this->response->setOutput(json_encode($json));
	}
	
	private function initProductTags(){
		return EnumProductTags::getProductTags();
	}
}
?>