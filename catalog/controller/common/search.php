<?php   
class ControllerCommonSearch extends Controller {
	protected function index() {
		$this->load_language('product/search');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/likesearch');
		
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
			$category = $this->model_catalog_category->getCategory((int)$filter_category_id);
			if(isset($category)&&isset($category['name'])&&isset($category['category_id']))
			{
				$this->data['category_id'] = $category['category_id'];
				$this->data['category_name'] = $category['name'];
				$this->data['searchFlag'] = '1';
			}
			else{
				$this->data['category_id'] = '';
				$this->data['category_name'] = '';
				$this->data['searchFlag'] = '0';
			}
		} else {
			$filter_category_id = 0;
			$this->data['category_id'] = '';
			$this->data['category_name'] = '';
			$this->data['searchFlag'] = '0';
		} 
		
		if (isset($this->request->get['filter_sub_category'])) {
			$filter_sub_category = $this->request->get['filter_sub_category'];
		} else {
			$filter_sub_category = 'true';
		} 
								
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
		
		if (isset($this->request->get['keyword'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['keyword']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
		$url = '';
				
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_category'])) {
			$url .= '&filter_sub_category=' . $this->request->get['filter_sub_category'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('common/search', $url),
      		'separator' => $this->language->get('text_separator')
   		);

		$this->data['products'] = array();
		
		$fields=array('model','subtitle','description');
		$filter_model = null;
		$filter_subtitle = null;
		$filter_description = null;
		if(isset($this->request->get['filter_keyword']))
		{
			$filter_model = trim($this->request->get['filter_keyword']);
			$filter_subtitle = trim($this->request->get['filter_keyword']);
			$filter_description = trim($this->request->get['filter_keyword']);
			$this->data['filter_keyword'] =  trim($this->request->get['filter_keyword']);
		}
		else{
			$this->data['filter_keyword'] =  '';
		}
		
		$categories = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->request->get['filter_name']) ||isset($this->request->get['filter_keyword']) || isset($this->request->get['filter_search_type'])) {
			$data = array(

				'filter_model'        => $filter_model, 
				'filter_subtitle'     => $filter_subtitle, 
				'filter_description'  => $filter_description,
				'filter_category_id'  => $filter_category_id, 
				'filter_sub_category' => $filter_sub_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
			
			$product_total = $this->model_catalog_likesearch->getTotalLikeProducts($data);
			$results = $this->model_catalog_likesearch->getLikeProducts($data);
			
			if ( isset($this->request->get['filter_keyword'])) {
				$keyword = $this->request->get['filter_keyword'];
			}else{
				$keyword = '';
			}
		
            $search_history_data = '';
            
            foreach($results as $result) {
                $search_history_data .= $result['product_id'] . ';'; 
            }
            
//            if (trim($keyword) != '') {
//				$this->model_catalog_likesearch->ReportKeyword($keyword,$search_history_data);
//			}
			// End add
			$this->data['products'] =changeProductResults($results,$this,$url);

	
			$url = $this->getUrlBasicParameters();
		
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
			$pagination->url = $this->url->link('common/search/dosearch', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
		}	
		
		
		if (isset($this->request->get['filter_search_type'])) {
			$filter_search_type=  $this->request->get['filter_search_type'];
		}else{
			$filter_search_type = false;
			
		}
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_sub_category'] = $filter_sub_category;
		$this->data['filter_search_type'] = $filter_search_type;
		$this->data['categories'] = $categories;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->children[] = 'module/hotword';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/search.tpl';
		} else {
			$this->template = 'default/template/common/search.tpl';
		}
    	$this->render();
	} 
	
	
	public function dosearch() { 
    	$this->load_language('product/search');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/likesearch');
		
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
			
			$category = $this->model_catalog_category->getCategory((int)$filter_category_id);
			if(isset($category)&&isset($category['name'])&&isset($category['category_id']))
			{
				$this->data['category_id'] = $category['category_id'];
				$this->data['category_name'] = $category['name'];
				$this->data['searchFlag'] = '1';
			}
			else
			{
				$this->data['category_id'] = '';
				$this->data['category_name'] = '';
				$this->data['searchFlag'] = '0';
			}
		} else {
			$filter_category_id = 0;
			$this->data['category_id'] = '';
			$this->data['category_name'] = '';
			$this->data['searchFlag'] = '0';
		} 
		
		if (isset($this->request->get['filter_sub_category'])) {
			$filter_sub_category = $this->request->get['filter_sub_category'];
		} else {
			$filter_sub_category = 'true';
		} 
								
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
		
		if (isset($this->request->get['keyword'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['keyword']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
		
		$url = $this->getUrlBasicParameters();
		
				
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_category'])) {
			$url .= '&filter_sub_category=' . $this->request->get['filter_sub_category'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('common/search/dosearch', $url),
      		'separator' => $this->language->get('text_separator')
   		);
		
		$this->data['products'] = array();
		
		$fields=array('model','subtitle','description');
		$filter_model = null;
		$filter_subtitle = null;
		$filter_description = null;
		if(isset($this->request->get['filter_keyword']))
		{
			$filter_model = trim($this->request->get['filter_keyword']);
			$filter_subtitle = trim($this->request->get['filter_keyword']);
			$filter_description = trim($this->request->get['filter_keyword']);
			$this->data['filter_keyword'] = trim($this->request->get['filter_keyword']);
		}else{
			
			$this->data['filter_keyword'] = '';
		}
		
		$categories = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->request->get['filter_name']) ||isset($this->request->get['filter_keyword']) || isset($this->request->get['filter_search_type'])) {
			$data = array(

				'filter_model'        => $filter_model, 
				'filter_subtitle'     => $filter_subtitle, 
				'filter_description'  => $filter_description,
				'filter_category_id'  => $filter_category_id, 
				'filter_sub_category' => $filter_sub_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
			$product_total = $this->model_catalog_likesearch->getTotalLikeProducts($data);
			$results = $this->model_catalog_likesearch->getLikeProducts($data);
			
			if ( isset($this->request->get['filter_keyword'])) {
				$keyword = $this->request->get['filter_keyword'];
			}else{
				$keyword = '';
			}
		
            $search_history_data = '';
            
            foreach($results as $result) {
                $search_history_data .= $result['product_id'] . ';'; 
            }
            
//            if (trim($keyword) != '') {
//				$this->model_catalog_likesearch->ReportKeyword($keyword,$search_history_data);
//			}
			// End add
			$this->data['products'] =changeProductResults($results,$this,$url);

	
			$url = $this->getUrlBasicParameters();
		
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
			$pagination->url = $this->url->link('common/search/dosearch', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
		}	
		
		
		if (isset($this->request->get['filter_search_type'])) {
			$filter_search_type=  $this->request->get['filter_search_type'];
		}else{
			$filter_search_type = "";
			
		}
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_sub_category'] = $filter_sub_category;
		$this->data['filter_search_type'] = $filter_search_type;
		$this->data['categories'] = $categories;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search_result.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/search_result.tpl';
		} else {
			$this->template = 'default/template/common/search_result.tpl';
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
	

	private function getUrlBasicParameters(){
  		$url = '';
	

				
		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}
		
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_category'])) {
			$url .= '&filter_sub_category=' . $this->request->get['filter_sub_category'];
		}
		
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
		}
		
		return $url;
  	}
  	
  	
}
?>