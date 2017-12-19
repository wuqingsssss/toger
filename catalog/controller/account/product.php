<?php
class ControllerAccountProduct extends Controller {
	private $error = array();

	protected function init(){
		if (!$this->customer->isLogged()) {
			if(isset($this->request->get['route']) && $this->request->get['route']){
				$this->session->data['redirect'] = $this->url->link($this->request->get['route'], '', 'SSL');
			}else{
				$this->session->data['redirect'] = $this->url->link('account/product', '', 'SSL');
			}
	  		
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
    	
		$this->load_language('account/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/product');
	}

	public function index() {
		
		$this->init();

		$this->getList();
	}
	

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			

			$this->model_account_product->addProduct($this->request->post);


			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->filter();

			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->filter();

			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}

		$this->getForm();
	}

	public function verify() {

		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_account_product->editVerifyStatus($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->filter();

			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}

		$this->getInfo();
	}

	public function getInfo()
	{
		$addr = '';
		$url = $this->filter();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/product',  $url, 'SSL'),
      		'separator' => false
		);

			
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('account/product/insert',  $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_add'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		} else {
			$this->data['action'] = $this->url->link('account/product/verify',  '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_edit'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		}

		$this->data['cancel'] = $this->url->link('account/product',  $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_account_product->getProduct($this->request->get['product_id']);
		}

		$results = array(
			'number' =>'number',
			'trade_type' =>'trade_type',
			'industry_type' =>'industry_type',
			'keyword' =>'keyword',
			'sort_order' =>'sort_order',
			'price' =>'price',
			'project_status' =>'project_status',
			'status' =>'status',
			'type' =>'type',
			'description' =>'description',
			'conditions' =>'conditions',
			'name' =>'name',
			'conditions' =>'conditions',
			'local_addr_zone' =>'local_addr_zone',
			'local_addr_city' =>'local_addr_city',
			'period' =>'period',
			'supply_demand' =>'supply_demand',
		);

		foreach ($results as $key => $value) {
			$this->product_setter(isset($product_info)?$product_info : NULL, $value);
		}

		$this->load->model('account/category');


		$this->template = 'account/product_info.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}


	public function delete() {
		$this->init();

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_account_product->deleteProduct($product_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->filter();

			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}
		//TODO:Add customer_id For Project
		if(isset($this->request->get['product_id'])){
			$product_id=$this->request->get['product_id'];
			
			$this->model_account_product->deleteProduct($product_id);
		}

		$this->getList();
	}

	public function changeStatus() {
		$this->init();

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_account_product->updateProductStatus($product_id,$this->request->get['status']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->filter();

			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}

		$this->getList();
	}


	public function copy() {
		$this->init();

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_account_product->copyProduct($product_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filter();
			$this->redirect($this->url->link('account/product',  $url, 'SSL'));
		}

		$this->getList();
	}

	private function filter($url='') {
		$requestes=array(
  			'filter_name' => 'filter_name',
  			'filter_supply_demand' => 'filter_supply_demand',
  			'filter_number' => 'filter_number',
  			'filter_trade_type' => 'filter_trade_type',
  			'filter_price' => 'filter_price',
  			'filter_verified' => 'filter_verified',
  			'filter_status' => 'filter_status',
  			'sort' => 'sort',
  			'order' => 'order',
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
  		  	'filter_number' => null,
  			'filter_project' => null,
  			'filter_supply_demand' => null,
  			'filter_trade_type' => null,
			'filter_verified' => null,
  		  	'filter_price' => null,
  		  	'filter_quantity' => null,
  			'filter_category_id' => null,
  		  	'filter_status' => null,
  		  	'sort' => 'pd.name',
  		  	'order' => 'ASC',
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
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/product',  $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
		);

		$this->data['insert'] = $this->url->link('account/product/insert',  $url, 'SSL');
		$this->data['copy'] = $this->url->link('account/product/copy',  $url, 'SSL');
		$this->data['delete'] = $this->url->link('account/product/delete',  $url, 'SSL');
		$this->data['enabled'] = $this->url->link('account/product/changeStatus', 'status=1' , 'SSL');
		$this->data['disabled'] = $this->url->link('account/product/changeStatus', 'status=0', 'SSL');

		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_trade_type' => $filter_trade_type,
			'filter_supply_demand'	  => $filter_supply_demand,
			'filter_number'	  => $filter_number,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_category_id' =>$filter_category_id,
			'filter_verified' =>$filter_verified,
			'filter_customer' => $this->customer->getId(),
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');
		

		$product_total = $this->model_account_product->getTotalProducts($data);

		$results = $this->model_account_product->getProducts($data);


		foreach ($results as $result) {
			$action = array();

			$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('account/product/update',  '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			
			$action[] = array(
					'text' => $this->language->get('text_delete'),
					'href' => $this->url->link('account/product/delete',  '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
				


			$this->data['products'][$result['product_id']] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'number'      => $result['number'],
				'trade_type'        => $result['trade_type'],
				'price'      => $this->currency->format($result['price']),
				'project_status' => $result['project_status'],
				'supply_demand'       => ($result['supply_demand']==1?$this->language->get('text_supply') : $this->language->get('text_demand')),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action,
				'verified'  =>($result['verified']=='0'?$this->language->get('text_pass') : $this->language->get('text_no_pass')),
			);

		}


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

		$url = $this->filter();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		//  sort  logic
		$this->data['sort_name'] = $this->url->link('account/product',  '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_number'] = $this->url->link('account/product',  '&sort=p.model' . $url, 'SSL');
		$this->data['sort_trade_type'] = $this->url->link('account/product',  '&sort=p.sku' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('account/product',  '&sort=p.price' . $url, 'SSL');
		$this->data['sort_supply_demand'] = $this->url->link('account/product',  '&sort=p.quantity' . $url, 'SSL');
		//		$this->data['sort_status'] = $this->url->link('project/product',  '&sort=p.status' . $url, 'SSL');
		//		$this->data['sort_order'] = $this->url->link('project/product',  '&sort=p.sort_order' . $url, 'SSL');
		//		$this->data['sort_category_id'] = $this->url->link('project/product',  '&sort=p.category_id' . $url, 'SSL');
		$url = '';

		$url = $this->filter();

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/product',  $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_project'] = $filter_project;
		$this->data['filter_supply_demand'] = $filter_supply_demand;
		$this->data['filter_number'] = $filter_number;
		$this->data['filter_trade_type'] = $filter_trade_type;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_verified'] = $filter_verified;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/product_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/product_list.tpl';
		} else {
			$this->template = 'default/template/account/product_list.tpl';
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
		$addr = '';
		$errores=array(
			'warning'=>'',
  			'name' => '',
  			'number' =>'',
  			'trade_type'=>'', 
  			'industry_type'=>'',
  			'local_addr'=>'',
  			'period'=>'',
  			'project_status'=>'',
  			'price'=>'',
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
			'href'      => $this->url->link('account/product',  $url, 'SSL'),
      		'separator' => false
		);

			
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('account/product/insert',  $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_add'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		} else {
			$this->data['action'] = $this->url->link('account/product/update',  '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
			$this->data['breadcrumbs'][] = array(
			    'text'      => $this->language->get('text_edit'),
				'href'      => $this->data['action'],
			    'separator' => $this->language->get('text_breadcrumb_separator')
			);
		}

		$this->data['cancel'] = $this->url->link('account/product',  $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_account_product->getProduct($this->request->get['product_id']);
		}

		$dataZone =array();

		$this->load->model('localisation/language');

		$this->load->model('localisation/city');

		$this->load->model('localisation/zone');
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		

		$zoneResults = $this->model_localisation_zone->getZones($dataZone);

		if(isset($product_info))
		{
			$addr = $product_info['local_addr_zone'];
		}
		else{
			if(isset($zoneResults)&&!is_null($zoneResults))
			{
				$addr = $zoneResults[0]['name'];
			}
		}
		$dataCity=array(
			'filter_zone' =>$addr
		);
		$cityResults = $this->model_localisation_city->getCities($dataCity);

		$this->data['zones'] = $zoneResults;

		$this->data['citys'] = $cityResults;

		$this->data['trade_types'] = getTradeType();

		$this->data['industry_types'] = getBelongIndustry();

		$this->data['project_statuss'] = getProjectStatus();

		$this->data['project_conditions'] = getCondition();

		$results = array(
			'number' =>'number',
			'trade_type' =>'trade_type',
			'industry_type' =>'industry_type',
			'keyword' =>'keyword',
			'sort_order' =>'sort_order',
			'price' =>'price',
			'project_status' =>'project_status',
			'status' =>'status',
			'type' =>'type',
			'description' =>'description',
			'conditions' =>'conditions',
			'name' =>'name',
			'conditions' =>'conditions',
			'local_addr_zone' =>'local_addr_zone',
			'local_addr_city' =>'local_addr_city',
			'period' =>'period',
			'supply_demand' =>'supply_demand',
			'unit'=>'unit'
		);

		foreach ($results as $key => $value) {
			$this->product_setter(isset($product_info)?$product_info : NULL, $value);
		}

		echo $this->data['description'];

		$this->load->model('account/category');

		$this->data['categories'] = $this->model_account_category->getCategories(0);

		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = $this->request->post['product_category'];
		} elseif (isset($product_info)) {
			$this->data['product_category'] = $this->model_account_product->getProductCategories($this->request->get['product_id']);
		} else {
			$this->data['product_category'] = array();
		}

		
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/product_form.tpl';
		} else {
			$this->template = 'default/template/account/product_form.tpl';
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


	public function getCitys()
	{
		$cityResults = array();
		$data = array();
		$data['cityZone'] = $this->request->post['cityZone'] ;

		if(isset($data['cityZone']))
		{
			$this->load->model('localisation/city');
			$data['filter_zone'] = $data['cityZone'];
			$cityResults = $this->model_localisation_city->getCities($data);
		}

		$this->response->setOutput(json_encode($cityResults));
	}

	private function validateForm() {
		$rules=$this->load->rule();
		$this->load_language('error_msg');
			
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
			
		//		if(is_null($this->request->post['number']) || utf8_strlen(utf8_decode($this->request->post['number']) ==0))
		//		{
		//			$this->error['number'] = $this->language->get('error_number');
		//		}
			
		//		if(is_null($this->request->post['name']) || utf8_strlen(utf8_decode($this->request->post['name']) ==0))
		//		{
		//			$this->error['name'] = $this->language->get('error_name');
		//		}
		//
		//		if(is_null($this->request->post['period']) || utf8_strlen(utf8_decode($this->request->post['period']) ==0))
		//		{
		//			$this->error['period'] = $this->language->get('error_period');
		//		}
		//
		//		if(is_null($this->request->post['price']) || utf8_strlen(utf8_decode($this->request->post['price']) ==0))
		//		{
		//			$this->error['price'] = $this->language->get('error_price');
		//		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}

		return true;
	}

	private function validateDelete() {
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateCopy() {
		if (!$this->customer->isLogged()) {
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

	public function autocomplete() {

		$json = array();

		if(isset($this->request->post['filter_name']))
		$this->request->get['filter_name']=$this->request->post['filter_name'];
			
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_number']) || isset($this->request->get['filter_sku'])  || isset($this->request->get['filter_category_id'])) {
			$this->load->model('account/product');

			$requestes=array(
			    'filter_name' => '',
			    'filter_number' => '',
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
				'filter_number'        => $filter_number,
				'filter_sku'       		=> $filter_sku,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_account_product->getProducts($data);


			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_account_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' ||$product_option['type'] == 'virtual_product' ||$product_option['type'] == 'color' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
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

				$json[] = array(
					'product_id' => $result['upid'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'model'      => $result['model'],
					'sku'      => $result['sku'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}
		$this->response->setOutput(json_encode($json));
	}


	/**
	 * $lvSeId:父分类ID
	 * 有空可以用递归,目前简单只到4级
	 * 数据格式：  
	 * Array(
	*	    [jishu] => Array
	*	        (
	*	            [14] => Array
	*	                (
	*	                )
	*	            [13] => Array
	*	                (
	*	                    [category_id] => 13
	*	                    [category_name] => 绿色技术分类
	*	                    [key] => Array
	*	                        (
	*	                            [15] => Array
	*	                                (
	*	                                    [category_id] => 15
	*	                                    [category_name] => 贵金属
	*	                                    [key] => Array
	*	                                        (
	*	                                            [17] => Array
	*	                                                (
	*	                                                    [category_id] => 17
	*	                                                    [category_name] => 废钢铁
	*	                                                )
	*	                                        )
	*	                                )
	*	                        )
	*	                )
	*	        )
	*		)
	*/
	function getLvSeMenu($lvSeId)
	{
		$result = array();
		$this->load->model('account/category');
		//获取绿色技术的二类信息
		$lvSonCategories = $this->model_account_category->getParentCategories($lvSeId);
		if(isset($lvSonCategories)&& !is_null($lvSonCategories))
		{
			foreach ($lvSonCategories as $category)
			{
				$result['jishu'][''.$category['category_id'].'']=array(
					'category_id' => $category['category_id'],
					'category_name'=>$category['category_name']
				);

				$lvGrandSonCategories = $this->model_account_category->getParentCategories($category['category_id']);

				if(isset($lvGrandSonCategories)&& !is_null($lvGrandSonCategories))
				{
					foreach ($lvGrandSonCategories as $lvGrandSonCategory){
						$result['jishu'][''.$category['category_id'].'']['key'][''.$lvGrandSonCategory['category_id'].'']=array(
						'category_id' => $lvGrandSonCategory['category_id'],
						'category_name'=>$lvGrandSonCategory['category_name']
						);
						
						$lvGrandGrandSonCategories = $this->model_account_category->getParentCategories($lvGrandSonCategory['category_id']);
						
						if(isset($lvGrandGrandSonCategories)&&!is_null($lvGrandGrandSonCategories))
						{
							foreach ($lvGrandGrandSonCategories as $lvGrandGrandSonCategory){
								$result['jishu'][''.$category['category_id'].'']['key'][''.$lvGrandSonCategory['category_id'].'']['key'][''.$lvGrandGrandSonCategory['category_id']]=array(
									'category_id' => $lvGrandGrandSonCategory['category_id'],
									'category_name'=>$lvGrandGrandSonCategory['category_name']
								);
							}
						}
					}
				}
			}
		}
		return $result;
	}


}
?>