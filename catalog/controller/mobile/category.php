<?php 
class ControllerMobileCategory extends Controller {  
	public function index() { 
		$this->load_language('product/category');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image'); 
		
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
					
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
			
		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
									
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
		
		if(!$category_info){
			$this->redirect($this->url->link('error/not_found'));
		}
	
		if(isset($category_info['meta_title'])&&$category_info['meta_title']!='')
  			$this->document->setTitle($category_info['meta_title']);
		else
			$this->document->setTitle($category_info['name']);
		
		$this->document->setDescription($category_info['meta_description']);
		$this->document->setKeywords($category_info['meta_keyword']);
		
		$this->data['heading_title'] = $category_info['name'];
		
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		if ($category_info['image']) {
			$this->data['thumb'] =resizeThumbImage($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
		} else {
			$this->data['thumb'] = '';
		}
								
		$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
		$this->data['compare'] = $this->url->link('product/compare');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}	
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
							
		$this->data['categories'] = array();
		
		$results = $this->model_catalog_category->getCategories($category_id);
		
		
		foreach ($results as $result) {
			
			$product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $result['category_id']));
			
			$this->data['categories'][] = array(
				'name'  => $result['name'] . ' (' . $product_total . ')',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
			);
		}
		
		$this->data['products'] = array();
		
		$data = array(
			'filter_category_id' => $category_id, 
			'filter_sub_category' => TRUE, 
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
				
		$product_total = $this->model_catalog_product->getTotalProducts($data); 
		
		$results = $this->model_catalog_product->getProducts($data);
		
		$this->data['products'] =changeProductResults($results,$this);

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
		$this->data['sorts'] = array();
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
		); 
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 'rating-DESC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
		); 
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 'rating-ASC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
		);
		
		$url = '';

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
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $this->config->get('config_catalog_limit'))
		);
					
		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=25')
		);
		
		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=75')
		);
		
		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=100')
		);
					
		$url = '';

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
		$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();
	
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->data['filter_category_id'] = 1;
	
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
		} else {
			$this->template = 'default/template/product/category.tpl';
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
  	
  	public function allsort(){
  		$this->load_language('product/allsort');
  		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/category/allsort'),
       		'separator' => $this->language->get('text_separator')
   		);	
   		
  		$this->load->model('catalog/category');
  		
  		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getChildCategories(0);
		
		$this->data['categories']=$categories;
		
  		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/allsort.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/allsort.tpl';
		} else {
			$this->template = 'default/template/product/allsort.tpl';
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
  	
	public function filter(){
  		$json = array();
  		
  		$filter=array();
  		
  		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
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
		$filter['filter_sub_category']=TRUE;
		$filter['sort']=$sort;
		$filter['order']=$order;
		$filter['start']=($page - 1) * $limit;
		$filter['limit']=$limit;
		
		$this->data['products'] = array();
				
		$product_total = $this->model_catalog_product->getTotalProducts($filter); 
		
		$results = $this->model_catalog_product->getProducts($filter);
		
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
  	
  	public function list_aside(){
  		$this->load_language('product/category');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
  		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				// 1为免费券活动特定分类 #TBD
				if($path_id == 1){
				    $category_info = array( 'name' => '菜票专栏');
				}
				else{
				    $category_info = $this->model_catalog_category->getCategory($path_id);
				}
				
				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
		
		$category_id = 0;
						
		$this->data['categories'] = array();
		
		$url='';
		
		$results = $this->model_catalog_category->getCategories($category_id);
	
		if( isset($this->session->data['freepromotion'])) {
		    $tmp =  $results[0];
		    $tmp['name']          = '菜票专栏';
		    $tmp['category_id']   = 1;
		    $results[] = $tmp;
		}
		
		

		$data_period = array(
			'filter_sub_category' => TRUE, 
			'start'              => 0,
			'limit'              => 100
		);

		
		$periods=$this->cart->getPeriods();
		$period=$this->cart->getPeriod();
		
		
		/* 根据session获取当前菜品周期 */
		
		$sequence = $this->cart->sequence;;
		

		$data_period['filter_start_date']=$period['start_date'];
		$data_period['filter_end_date']  =$period['end_date'];
		$data_period['filter_supply_period_id']  =$period['id'];
		$this->data['current_period']    =$period;
		
		
		
		foreach ($results as $result) {
			
			$data_period['filter_category_id'] = $result['category_id'];
			
			$product_total = $this->model_catalog_product->getTotalSupplyProducts($data_period);
			
			$this->data['categories'][] = array(
				'name'  => $result['name'] . ' (' . $product_total . ')',
				'href'  => $this->url->link('product/category', 'path=' .$result['category_id'] . $url)
			);
		}
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mobile/category_lists.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/mobile/category_lists.tpl';
		} else {
			$this->template = 'default/template/mobile/category_lists.tpl';
		}
		
		$this->render();				
  	}
}
?>