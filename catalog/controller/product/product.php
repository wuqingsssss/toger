<?php  
class ControllerProductProduct extends Controller {
	private $error = array();

	public function filter(){
		$url='';
		
		if(isset($this->request->get['path'])){
			$this->data['path']=$this->request->get['path'];
		}else{
			$this->data['path']='';
		}
		
		$this->data['filter']=$this->url->link('product/filter/results');
		
		if(isset($this->request->get['manufacturer_id'])){
			$this->data['manufacturer_id']=$this->request->get['manufacturer_id'];
		}else{
			$this->data['manufacturer_id']='';
		}
		
		if(isset($this->request->get['filter_keyword'])){
			$this->data['filter_keyword']=$this->request->get['filter_keyword'];
		}else{
			$this->data['filter_keyword']='';
		}
		
		if(isset($this->request->get['filter_search_type'])){
			$this->data['filter_search_type']=$this->request->get['filter_search_type'];
		}else{
			$this->data['filter_search_type']='';
		}
		
		if (isset($this->request->post['sort'])) {
			$this->data['sort']=$this->request->post['sort'];
		} else {
			$this->data['sort']='p.price';
		}
		
		if (isset($this->request->post['order'])) {
			$this->data['order']=$this->request->post['order'];
		} else {
			$this->data['order']= 'ASC';
		}
		
		if(isset($this->session->data['yiyuangou'])){
		    $this->data['yiyuangou'] = 'true';
		}

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getChildCategories(0);

		$this->data['categories']=$categories;
		
		$this->data['sequence'] = $this->cart->sequence;
		$this->data['filter_category_id'] = isset($this->session->data['filter_category_id'])?$this->session->data['filter_category_id']:0;
		// 免费菜活动 #TBD
		if(isset($this->session->data['freepromotion'])){
		    $this->data['freepromotion'] =  $this->session->data['freepromotion'];
		}
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product-filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product-filter.tpl';
		} else {
			$this->template = 'default/template/product/product-filter.tpl';
		}
		
    	$this->render();
	}
	
	public function index() { 
		//保证所有使用code的商品逻辑都必须登录
		if(isset($this->request->get['p_code']) && $this->request->get['p_code']){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('product/product', 'p_code='.$this->request->get['p_code'], 'SSL');
				 
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
		}
		$this->load_language('product/product');
	
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);
		
		$this->load->model('catalog/category');	
		$this->load->model('catalog/product');
		
		if (isset($this->request->get['path'])) {
			$path = '';
				
			foreach (explode('_', $this->request->get['path']) as $path_id) {
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
		}
		
		$this->load->model('catalog/manufacturer');	
		
		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);	
				
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {	
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $this->request->get['manufacturer_id']),					
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
						
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}	
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			);	
		}
		
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		
		$period=$this->cart->getPeriod();

		if(!$period){
			$this->redirect($this->url->link('error/not_found'));
		}
		
		$product_info = $this->model_catalog_product->getProduct($product_id,$period['id']);
		
		$categorys = $this->model_catalog_product->getCategories($product_id);
		
		if($categorys){
			$category = $this->model_catalog_category->getCategory($categorys[0]['category_id']);
			if($category){
				$this->data['category_name'] = $category['name'];
			}else{
				$this->data['category_name'] = "";
			}
		}else{
			$this->data['category_name'] = "";
		}
		
		
		
		$this->data['product_info'] = $product_info;
		$this->data['share_link'] =  $this->url->link('common/home');
		
		if(!$product_info){
			$this->redirect($this->url->link('error/not_found'));
		}
		
		$url = '';
		
		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}
		
		if (isset($this->request->get['manufacturer_id'])) {
			$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
		}			

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
					
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . $this->request->get['filter_tag'];
		}
		
		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}	
					
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $product_info['name'],
			'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
			'separator' => $this->language->get('text_separator')
		);
				

			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
			
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}	
						
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}

			$heading_title=$product_info['name'];

			if(isset($product_info['meta_title']))
				$this->document->setTitle($product_info['meta_title']!=''?$product_info['meta_title']:$product_info['name']);
			else
				$this->document->setTitle($heading_title);
				
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			
			$this->data['share_link'] =$this->url->link('product/product', 'product_id=' . $this->request->get['product_id']);
			
			$this->data['heading_title'] = $heading_title;
			
			$this->data['subtitle'] = $product_info['subtitle'];
			$this->data['origin'] = $product_info['origin'];
			$this->data['unit'] = $product_info['unit'];
			$this->data['storage'] = $product_info['storage'];
			$this->data['delivery'] = $product_info['delivery'];
			
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			
			
			$this->load->model('catalog/review');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']));

			
			$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturer_link'] = $this->url->link('product/manufacturer/product', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];
			$this->data['upc'] = $product_info['upc'];
			
			//Add by kaian 2013-06-25
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['weight'] = $product_info['weight'];
			$this->data['sku'] = $product_info['sku'];
			$this->data['size'] = $product_info['size'];		
			
			$this->data['icons'] = $product_info['icons'];		
			$this->data['follow'] = $product_info['follow'];
			$this->data['review'] = (int)$product_info['reviews'];
			$this->data['cooking_time'] = $product_info['cooking_time'];

			
			$this->data['location'] = $product_info['location'];
			// end
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}
			
			$this->load->model('tool/image');

			$this->data['image_thumb_width'] = $this->config->get('config_image_thumb_width');
			$this->data['image_thumb_height'] = $this->config->get('config_image_thumb_height');
			
			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}
			
			if ($product_info['image']) {
				$this->data['big'] = $this->model_tool_image->resize($product_info['image'],768, 1024);
			} else {
				$this->data['big'] = '';
			}
			
			if ($product_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));;
			}
			
			if ($product_info['image']) {
				$this->data['small'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
			} else {
				$this->data['small'] = '';
			}
			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
			foreach ($results as $result) {
				$this->data['images'][] = array(
					'big' => $this->model_tool_image->resize($result['image'] , 768, 1024),
					'popup' => $this->model_tool_image->resize($result['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'middle' => $this->model_tool_image->resize($result['image'] ,$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}	
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}
						
			$promotion = array();
			if ($product_info['promotion']['promotion_price']) {
				 $promotion['promotion_price'] = $this->currency->format($this->tax->calculate($product_info['promotion']['promotion_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				 $promotion['promotion_code']  = $product_info['promotion']['promotion_code'];
				 $this->data['promotion']  = $promotion;
			}
			
			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
			
		/*	$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
			
			$this->data['discounts'] = array(); 
			
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}*/
			
			$this->data['options'] = array();
			
			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio'|| $option['type'] == 'color' || $option['type'] == 'checkbox' || $option['type'] == 'autocomplete') { 
					$option_value_data = array();
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'color_product_id'         => $option_value['color_product_id'],
								'href'    	 => $this->url->link('product/product', 'product_id=' . $option_value['color_product_id']),
								'name'                    => $option_value['name'],
								'price'                   => (float)$option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
					
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}
			
			if($this->data['options'] && $this->data['options'][0]['option_value']){
				$this->data['default_product_option_value_id']=$this->data['options'][0]['option_value'][0]['product_option_value_id'];
				
				if($this->data['price']){
					$option_price=$this->calcProductOptionPrice($this->data['default_product_option_value_id']);
					
					if($option_price){
						$this->data['price']=$this->currency->format($option_price);
					}
				}
				
			}else{
				$this->data['default_product_option_value_id']='';
			}
			
							
			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}
			
			$this->data['customer_name'] = $this->customer->getName();
			
			//常规设置选项
			$this->data['review_status'] = $this->config->get('config_review_status');
			
			if($this->model_catalog_product->checkIfPurchased($this->request->get['product_id'])&&$this->config->get('config_review_status'))
				$this->data['purchased_status'] = 1;
			else{
				$this->data['purchased_status']=0;
			}
			
			if(isset($product_info['promotion']['promotion_price'])) {
			    $product_info['promotion']['promotion_price'] = $this->currency->format( $product_info['promotion']['promotion_price']);
			}
			$this->data['promotion'] = $product_info['promotion'];
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['garnish'] = $product_info['garnish'];
			$this->data['calorie'] = $product_info['calorie'];
			$this->data['cooking_time'] = $product_info['cooking_time'];
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['cooking'] = html_entity_decode($product_info['cooking'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
			
			$this->data['products'] = array();
			
			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
			
			$this->data['products'] =changeProductResults($results,$this);
			
			$this->data['tags'] = array();
					
			$results = $this->model_catalog_product->getProductTags($this->request->get['product_id']);
			
			foreach ($results as $result) {
				$this->data['tags'][] = array(
					'tag'  => $result['tag'],
					'href' => $this->url->link('product/search', 'filter_tag=' . $result['tag'])
				);
			}
			
			//增加商品价格待定和限制购买
			$this->data['cart']=TRUE;
			
			if($product_info['price'] <= 0){
				$this->data['price']= $this->language->get('text_enquiry_price');
				$this->data['cart']=FALSE;
			}
			
			/* 未上架商品 */
			if(! $product_info['status']){ 
				$this->data['price']= 0;
				$this->data['cart']=FALSE;
			}
			
			//增加促销代码
			$this->data['p_code']='';
			
			$this->load->model('promotion/product');
			
			if(isset($this->request->get['p_code']) && $this->request->get['p_code']){
				$promotion_code=$this->request->get['p_code'];
				
				//检测是否存在使用满足促销规则的商品
				$existed=checkPromotionProduct($product_id,$promotion_code);
				
				if($existed){
					$this->data['p_code']=$promotion_code;
					
					//获取促销商品的价格
					$this->data['special'] = $this->currency->format(getPromotionProductPrice($product_id,$promotion_code));
				}
			}
			
			
			if($this->detect->is_weixin_browser()|| defined ( 'DEBUG' ) && DEBUG){
			
				$this->session->data['source_url']=$product_info['link_url'];
		
				$url=$product_info['link_url']?$product_info['link_url']:$this->url->link('product/product', $url.'&product_id=' . $product_info['product_id']);
				
				$share_link=$product_info['share_link']?$product_info['share_link']:$url;

				$sharedata['linkparent']=$share_link;
				$sharedata['share_image']=$product_info['share_image']?HTTPS_IMAGE.$product_info['share_image']:$this->data['thumb'];
				$sharedata['share_title']=$product_info['share_title']?$product_info['share_title']:$product_info['name'];
				$sharedata['share_desc']=$product_info['share_desc']?$product_info['share_desc']:$product_info['subtitle'];
			
				$this->data['sharedata']=$sharedata;
					
				$this->data['is_weixin_browser'] =1;
			
			}
			
			
			
			
			
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);
			
			$this->document->setBreadcrumbs($this->data['breadcrumbs']);
			
			$url='';
			
			if(isset($this->request->get['path'])){
				$this->data['back']=$this->url->link('product/category','path=' . $path) ;
			}else{
				$this->data['back']=$this->url->link('common/home');
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
			} else {
				$this->template = 'default/template/product/product.tpl';
			}
			
			$this->document->setBreadcrumbs($this->data['breadcrumbs']);
			
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
  	
	public function review() {
    	$this->load_language('product/product');
		
		$this->load->model('catalog/review');

		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
			
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author']==''?$this->language->get('text_no_name'):$result['author'],
				'text'       => strip_tags($result['text']),
				'rating'     => (int)$result['rating'],
        		'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function write() {
		$this->load_language('product/product');
		
		$this->load->model('catalog/review');
		
		$json = array();
		
		if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
			$json['error'] = $this->language->get('error_name');
		}
		
		if ((strlen(utf8_decode($this->request->post['text'])) < 1) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
			$json['error'] = $this->language->get('error_text');
		}

		if (!$this->request->post['rating']) {
			$json['error'] = $this->language->get('error_rating');
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$json['error'] = $this->language->get('error_captcha');
		}
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
	public function upload() {
		$this->load_language('product/product');
		
		$json = array();
		
		if (isset($this->request->files['file']['name']) && $this->request->files['file']['name']) {
			if ((strlen(utf8_decode($this->request->files['file']['name'])) < 1) || (strlen(utf8_decode($this->request->files['file']['name'])) > 128)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	
			
			$allowed = array();
			
			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($this->request->files['file']['name'], '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
						
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($this->request->files['file']['name']) . '.' . md5(rand());
				
				// Hide the uploaded file name sop people can not link to it directly.
				$this->load->library('encryption');
				
				$encryption = new Encryption($this->config->get('config_encryption'));
				
				$json['file'] = $encryption->encrypt($file);
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
	
	public function attribute($setting=array()){
		if(!isset($setting['product_id'])){
			return;
		}
		
		$product_id=$setting['product_id'];
		
		$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_attribute.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_attribute.tpl';
		} else {
			$this->template = 'default/template/product/product_attribute.tpl';
		}

		$this->render();
	}
	
	public function lists($products){
		
  		$this->data['products']=$products;
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_list.tpl';
		} else {
			$this->template = 'default/template/product/product_list.tpl';
		}
				
		$this->response->setOutput($this->render());
  	}
  	
	private function getProductId(){
  		if(isset($this->request->get['product_id'])){
  			return $this->request->get['product_id'];
  		}else{
  			return 0;
  		}
  		
  	}
  	
	public function combine(){
  		$this->load_language('product/product');
  		
  		$product_id=$this->getProductId();
  		
  		if($product_id){
  			$this->load->model('catalog/product');
  			$this->load->model('tool/image');
  			
  			$product_info=$this->model_catalog_product->getProduct($product_id);
  			
  			if($product_info){
  				$this->data['self']=$this->url->link('product/product', 'product_id=' . $this->request->get['product_id']);
  				$this->data['heading_title']=$product_info['name'];
  				$this->data['product_id']=$product_id;
  				
	  			if ($product_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				} else {
					$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));;
				}
  				
				$group_total=0;
		
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$group_total = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				} 
							
				if ((float)$product_info['special']) {
					$group_total = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				} 
				
				
			    $this->data['groups']=array();
				$groups=$this->model_catalog_product->getProductGroups($product_id);
		
				foreach($groups as $result){
					
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
					} else {
						$image = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}
					
					$amount=0;
					
					if($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))){
						$amount=$this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
						//$group_total+=$amount;
					}
					
					$this->data['groups'][] = array(
						'product_id' => $result['product_id'],
						'thumb'   	 => $image,
						'name'    	 => $result['name'],
						'price_amount'   	 => $amount,
						'price'   	 => $price,
						'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					);
				}
				
				$this->data['group_total_number']=$group_total;
				$this->data['group_total']=$this->currency->format($group_total);
				$this->data['group_action']=$this->url->link('checkout/cart');
				
	  			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_combine.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/product/product_combine.tpl';
				} else {
					$this->template = 'default/template/product/product_combine.tpl';
				}
				
				$this->render();
				
  			}
  		}
  	}
  	
	public function currency(){
  		$num=0;
  		
  		if(isset($this->request->get['num'])){
  			$num=$this->request->get['num'];
  		}
  		
  		$this->response->setOutput($this->currency->format($num));
  	}
}
?>