<?php
class ControllerModuleFeatured extends Controller {
	protected function index($setting) {
		$this->load_language('module/featured'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product'); 
		
		$this->load->model('tool/image');

		$this->data['products'] = array();
		
		if(isset($setting['limit'])){
			$filter_data['limit']=(int)$setting['limit'];
		}else{
			$filter_data['limit']=6;

		}

		//获取周期 begin
		$this->load->model('catalog/supply_period');

		if(isset($this->request->get['sequence'])){
			$sequence = (int)$this->request->get['sequence'];
		
			if($sequence!=$this->cart->sequence)
			{
				$this->cart->clear();
				$this->cart->setPeriod($sequence);
			}
		}
		$sequence=$this->cart->sequence;
		
		
	 $periods=$this->cart->getPeriods();
		$period=$this->cart->getPeriod();
		
		$filter_data['filter_supply_period_id']  =$period['id'];
		
		
		
		if($this->config->get('featured_product')){
			$products = explode(',', $this->config->get('featured_product'));		
		}else{
			$products = $this->model_catalog_product->getFeaturedProductIds($filter_data);		
		}
		

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $product_info['special'] : $product_info['price']);
				} else {
					$tax = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'icons' => $product_info['icons'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'subtitle'        => $product_info['subtitle'],
					'unit'        => $product_info['unit'],
					'origin'        => $product_info['origin'],
					'description' => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
					'price'   	 => $price,
					'special' 	 => $special,
					'tax'         => $tax,
					'rating'     => $rating,
					'garnish'        => $product_info['garnish'],
					'cooking_time'        => $product_info['cooking_time'],
					'calorie'        => $product_info['calorie'],
					'follow'        => $product_info['follow'],
					'donation'      => $product_info['donation'],
					'reviews'    => (int)$product_info['reviews'],
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
		}

		if($setting['position']=='content_top' || $setting['position']=='content_bottom' ){
			$tpl='module/featured.tpl';
		}else{
			$tpl='module/featured_list.tpl';
		}

		$this->renderSection($tpl);
	}
}
?>