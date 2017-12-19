<?php
class ControllerCatalogVote extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/vote');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vote');
	}
	
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');

		$url = $this->filter();
		
		$this->redirect($this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

  	public function index() {
  		$this->init();
		
		$this->getList();
  	}

  	private function filter($url='') {
  		$requestes=array(
  			'filter_date_start' => 'filter_date_start',
  			'filter_date_end' => 'filter_date_end',
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

	public function delete() {
    	$this->init();
		if($this->validateDelete())
		{
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $product_id) {
					$this->model_catalog_vote->clearVoteInfo($product_id);
		  		}
	
				$this->redirectToList();
			}
		}

    	$this->getList();
  	}
  	
  	public function deleteAllVoteInfo()
  	{
  		$this->init();
		$this->model_catalog_vote->deleteAllVoteInfo($product_id);
    	$this->getList();
  	}
  	
  	private function getList() {
  		$requestes=array(
  		  	'filter_date_end' => null,
  		  	'filter_date_start' => null,
  		  	'sort' => 'p.product_id',
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
   							'href'      => $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . $url, 'SSL'),
   				      		'separator' => $this->language->get('text_breadcrumb_separator')
   			);
   		}else{
   			$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . $url, 'SSL'),
	      		'separator' => $this->language->get('text_breadcrumb_separator')
	   		);
   		}
   		

   		
   	
		$this->data['clear'] = $this->url->link('catalog/vote/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['clearAll'] = $this->url->link('catalog/vote/deleteAllVoteInfo', 'token=' . $this->session->data['token'], 'SSL');
		
		
		// check if installed linkpost wordpress
//		$this->load->model('setting/extension');
//		$extensions = $this->model_setting_extension->getInstalled('linkpost');
//		foreach ($extensions as $key => $value) {
//			if ($value == 'wordpress') {
//				if ($extensions[$key]['installed']) {
//					$this->data['linkpost_wordpress'] = $this->url->link('linkpost/wordpress/newpost', 'token=' . $this->session->data['token'], 'SSL');
//				}
//			}
//		}
//		
		$limit=$this->config->get('config_admin_limit');
				
		$this->data['products'] = array();

		$data = array(
			'filter_date_start'	  => $filter_date_start,
			'filter_date_end'	  => $filter_date_end,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$this->load->model('tool/image');
//		$this->load->model('catalog/manufacturer');

		//获取未上架产品
		$total = $this->model_catalog_vote->getTotalVoteProduct($data);
		$results = $this->model_catalog_vote->getVoteProductIds($data);
		
		foreach ($results as $result) {
//			$action = array();
//
//			$action[0] = array(
//					'text' => $this->language->get('text_edit'),
//					'href' => $this->url->link('catalog/vote/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
//			);
//			
//			$action[1] = array(
//					'text' => $this->language->get('text_edit'),
//					'href' => $this->url->link('catalog/vote/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
//			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 60, 60);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 60, 60);
			}

			if($this->isInString1($result['image'],'http')){
			   $image=$result['image'];
			}
			
//			$product_specials = $this->model_catalog_vote->getProductSpecials($result['product_id']);

//			if ($product_specials) {
//				$special = reset($product_specials);
//				if(($special['date_start'] != '0000-00-00' && $special['date_start'] > date('Y-m-d')) || ($special['date_end'] != '0000-00-00' && $special['date_end'] < date('Y-m-d'))) {
//					$special = FALSE;
//				}
//			} else {
//				$special = FALSE;
//			}
			
//			$manufacturer='';
//			
//			if($result['manufacturer_id']){
//				$manufacturer_info=$this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
//				
//				if($manufacturer_info){
//					$manufacturer=$manufacturer_info['name'];
//				}
//			}
			$this->data['products'][$result['product_id']] = array(
				'voted_good_num' => $result['voted_good_num'],
				'voted_bad_num' => $result['voted_bad_num'],
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'sku'      => $result['sku'],
//				'manufacturer'      => $manufacturer,
				'price'      => $this->currency->format($result['price']),
//				'special'    => $special['price'],
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_grouding_on') : $this->language->get('text_grouding_down')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action,
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

//		
//
//		if ($order == 'ASC') {
//			$url .= '&order=DESC';
//		} else {
//			$url .= '&order=ASC';
//		}
		
//		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
//			$this->data['sort_name'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
//			$this->data['sort_model'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
//			$this->data['sort_sku'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.sku' . $url, 'SSL');
//			$this->data['sort_price'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
//			$this->data['sort_quantity'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
//			$this->data['sort_status'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
//			$this->data['sort_order'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
//			$this->data['sort_category_id'] = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . '&sort=p.category_id' . $url, 'SSL');
//		}else{
//			
//			$this->data['sort_model'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
//			
//			$this->data['sort_price'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
//			$this->data['sort_quantity'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
//			$this->data['sort_status'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
//			$this->data['sort_order'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
//			$this->data['sort_category_id'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.category_id' . $url, 'SSL');
//			
//		}

		$url = $this->getUrlSortParameters();
		
  		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$this->data['sort_name'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_sku'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=p.sku' . $url, 'SSL');
		$this->data['sort_voted_num'] = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . '&sort=voted_good_num' . $url, 'SSL');
		
		
		$url = '';

		$url = $this->filter();

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		
		//TODO:type=1是什么意思
		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
			$pagination->url = $this->url->link('catalog/vote', 'type=1&token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}else{
			$pagination->url = $this->url->link('catalog/vote', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		}

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/vote_list.tpl';
		
		
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}
  	
  	private function getUrlSortParameters(){
  		$url='';
  		
  		$requestes=array(
  			'filter_date_start' => 'filter_date_start',
  			'filter_date_end' => 'filter_date_end',
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

  	

  	private function isInString1($haystack, $needle) {
  		//防止$needle 位于开始的位置
  		$haystack = '-_-!' . $haystack;
  		return (bool)strpos($haystack, $needle);
  	}
  	

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/vote')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

}
?>