<?php
class ModelCatalogProduct extends Model {

	private function editProductHasOption($product_id,$has_option){
		$this->db->query("UPDATE " . DB_PREFIX . "product SET has_option=".(int)$has_option." WHERE product_id=".(int)$product_id);
		$this->log_admin->info($has_option);
	}
	
	/**
	 * 追加新菜品
	 * @param unknown $data
	 */
	public function addProduct($data) {
		$name='';
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
		                 model = '" . $this->db->escape($data['model']) . "', 
		                 sku = '" . $this->db->escape($data['sku']) . "', 
		                 upc = '" . $this->db->escape($data['upc']) . "', 
				         link_url = '" . $this->db->escape($data['link_url']) . "', 
  			             share_link = '" . $this->db->escape($data['share_link']) . "',
				         share_image = '" . $this->db->escape($data['share_image']) . "',
		                 location = '" . $this->db->escape($data['location']) . "', 
		                 quantity = '" . (int)$data['quantity'] . "', 
		                 minimum = '" . (int)$data['minimum'] . "', 
		                 subtract = '" . (int)$data['subtract'] . "', 
		                 stock_status_id = '" . (int)$data['stock_status_id'] . "', 
		                 date_available = '" . $this->db->escape($data['date_available']) . "', 
				         date_unavailable = '" . $this->db->escape($data['date_unavailable']) . "', 
		                 manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
		                 shipping = '" . (int)$data['shipping'] . "', 
		                 price = '" . (float)$data['price'] . "', 
		                 points = '" . (int)$data['points'] . "', 
		                 weight = '" . (float)$data['weight'] . "', 
		                 weight_class_id = '" . (int)$data['weight_class_id'] . "', 
		                 length = '" . (float)$data['length'] . "', 
		                 width = '" . (float)$data['width'] . "', 
		                 height = '" . (float)$data['height'] . "', 
		                 length_class_id = '" . (int)$data['length_class_id'] . "', 
		                 status = '" . (int)$data['status'] . "', 
		                 tax_class_id = '" . (int)$data['tax_class_id'] . "', 
		                 sort_order = '" . (int)$data['sort_order'] . "', 
		                 date_added = NOW(),
		                 garnish='".$data['garnish']."',
		                 cooking_time='".$data['cooking_time']."',
		                 calorie='".$data['calorie']."',
		                 follow='".(int)$data['follow']."', 
		                 combine='".(int)$data['combine']."',
		                 packing_type = '".(int)$data['packing_type']."',
		                 prod_type = '".(int)$data['prod_type']."' ");

		$product_id = $this->db->getLastId();
		
		$this->editProductParameters($product_id,$data);
		
		if (isset($data['featured'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET  featured='" . (int)$data['featured'] . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET  image = '" . $this->db->escape($data['image']) . "'  WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id){
				$name=$this->makeSlugs($this->db->escape($value['name']));
			}
					
			$this->editProductDescription($product_id,$language_id,$value);	
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

					$product_option_id = $this->db->getLastId();

					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else if($product_option['type'] == 'virtual_product' ){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
					$product_option_id = $this->db->getLastId();
					
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  product_value= '" . $this->db->escape($product_option_value['product_value']) . "', product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "',  points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "'");
						}
					}
				}else if($product_option['type'] == 'color' ){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
					$product_option_id = $this->db->getLastId();
					
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							if(!isset($product_option_value['product_value'])||(isset($product_option_value['product_value'])&&$product_option_value['product_value']==''))
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  color_product_id= '" . (int)$product_id . "', product_value= '" . $this->db->escape($product_option_value['product_value']) . "',  product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "'");
							else
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  color_product_id= '" . (int)$product_option_value['color_product_id'] . "', product_value= '" . $this->db->escape($product_option_value['product_value']) . "',  product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "'");
						}
					}
				}else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', quantity = '" . (int)$value['quantity'] . "',limited = '" . (int)$value['limited'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}
		
		//设置套餐
		if(!empty($data['combine'])){
		if(isset($data['product_combine'])){
			foreach ($data['product_combine'] as $combine_id) {
				$this->addProductCombine($product_id,$combine_id);
			}
		}
		}
		//设置优惠劵

			if(isset($data['product_coupon'])){
				foreach ($data['product_coupon'] as $coupon) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_coupon SET product_id = '" . (int)$product_id . "', coupon_id = '" . (int)$coupon['coupon_id'] . "', coupon_num = '" . (int)$coupon['coupon_num'] . "'");
					}
			}

			//储值卡
			if(isset($data['product_trans_code'])){
				foreach ($data['product_trans_code'] as $trans_code) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_trans_code SET product_id = '" . (int)$product_id . "', 
					                  trans_code_id = '" . (int)$trans_code['trans_code_id'] . "', 
					                  num = '" . (int)$trans_code['num'] . "',
					                  is_tpl = '" . (int)$trans_code['is_tpl'] . "'");
				}
			}
			
		if (isset($data['product_special'])) {
			$sql='';
			foreach ($data['product_special'] as $value) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "';";
				if($value['tags']){
					$sql.="UPDATE " . DB_PREFIX . "product_special SET limited = '" . (int)$value['limited'] . "',date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE tags='" . $value['tags'] . "' AND customer_group_id = '" . (int)$value['customer_group_id'] . "';";
				}
			}
				
			$this->db->multi_query($sql);
		}
		
		if (isset($data['product_special0'])) {
			$sql='';
			foreach ($data['product_special0'] as $value) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_special0 SET product_id = '" . (int)$product_id . "',delivery_id = '" . (int)$value['delivery_id'] . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "';";
				if($value['tags']){
					$sql.="UPDATE " . DB_PREFIX . "product_special0 SET limited = '" . (int)$value['limited'] . "',date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE tags='" . $value['tags'] . "' AND customer_group_id = '" . (int)$value['customer_group_id'] . "';";
				}
			}
		
			$this->db->multi_query($sql);
		}
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image) . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_icon'])) {
			foreach ($data['product_icon'] as $tag_id) {
				$this->addProductToTag($product_id,$tag_id);
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if(isset($data['product_tag'])){
			foreach ($data['product_tag'] as $language_id => $value) {
				if ($value) {
					$tags = explode(',', $value);
	
					foreach ($tags as $tag) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_tag SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
					}
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $name . "'");
		}
		
		if(isset($data['donation'])){
			$this->editProductDonationSetting($product_id,$data['donation']);
		}
	    $this->log_admin->info($data);
		$this->cache->delete('product');
		//重置缓存空间
		$this->mem->reset_namespace('products');
	}
	
	private function deleteProductToTags($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_tag WHERE product_id = '" . (int)$product_id . "'");
		$this->log_admin->info($data);
	}
	
	private function addProductToTag($product_id,$tag_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_tag SET product_id = '" . (int)$product_id . "', tag_id = '" . (int)$tag_id . "'");
		$this->log_admin->info($data);
	}
	
	private function editProductParameters($product_id,$data){
		
	}

	/**
	 * 更新菜品信息
	 * @param unknown $product_id
	 * @param unknown $data
	 */
	public function editProduct($product_id, $data) {

		$name='';
		$this->db->query("UPDATE " . DB_PREFIX . "product SET 
		                 model = '" . $this->db->escape($data['model']) . "', 
		                 sku = '" .   $this->db->escape($data['sku']) . "', 
		                 upc = '" .   $this->db->escape($data['upc']) . "', 
				         link_url = '" . $this->db->escape($data['link_url']) . "',
				         share_link = '" . $this->db->escape($data['share_link']) . "',
				         share_image = '" . $this->db->escape($data['share_image']) . "',
		                 location = '" . $this->db->escape($data['location']) . "', 
		                 quantity = '" . (int)$data['quantity'] . "', 
		                 minimum = '" . (int)$data['minimum'] . "', 
		                 subtract = '" . (int)$data['subtract'] . "', 
		                 stock_status_id = '" . (int)$data['stock_status_id'] . "', 
		                 date_available = '" . $this->db->escape($data['date_available']) . "', 
				         date_unavailable = '" . $this->db->escape($data['date_unavailable']) . "', 
		                 manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
		                 shipping = '" . (int)$data['shipping'] . "', 
		                 price = '" . (float)$data['price'] . "', 
		                 points = '" . (int)$data['points'] . "', 
		                 weight = '" . (float)$data['weight'] . "', 
		                 weight_class_id = '" . (int)$data['weight_class_id'] . "', 
		                 length = '" . (float)$data['length'] . "', 
		                 width = '" . (float)$data['width'] . "', 
		                 height = '" . (float)$data['height'] . "', 
		                 length_class_id = '" . (int)$data['length_class_id'] . "', 
		                 status = '" . (int)$data['status'] . "', 
		                 tax_class_id = '" . (int)$data['tax_class_id'] . "', 
		                 sort_order = '" . (int)$data['sort_order'] . "', 
		                 date_modified = NOW(),
		                 garnish='".$data['garnish']."',
		                 cooking_time='".$data['cooking_time']."',
		                 calorie='".$data['calorie']."',
		                 follow='".(int)$data['follow']."',
		                 combine='".(int)$data['combine']."',
		                 packing_type = '".(int)$data['packing_type']."',
		                 prod_type = '".(int)$data['prod_type']."' 
		                 WHERE product_id = '" . (int)$product_id . "'");

//		if(isset($data['has_option'])){
//			$this->editProductHasOption($product_id,$data['has_option']);
//		}
		
		$this->editProductParameters($product_id,$data);
		
		if (isset($data['featured'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET  featured='" . (int)$data['featured'] . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			if($this->config->get('config_language_id')==$language_id){
				$name=$this->makeSlugs($this->db->escape($value['name']));
			}
				
			$this->editProductDescription($product_id,$language_id,$value);	
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

					$product_option_id = $this->db->getLastId();

					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET   product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else if($product_option['type'] == 'autocomplete' ){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
					$product_option_id = $this->db->getLastId();
					
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							//添加option value 的值 
							if(!$product_option_value['option_value_id'] && $product_option_value['option_value']){
								$option_id=$product_option['option_id'];
								
								$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', sort_order = '0'");
				
								$option_value_id = $this->db->getLastId();
								
								$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '1', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($product_option_value['option_value']) . "'");
								
								$product_option_value['option_value_id']=$option_value_id;
							}
							
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] 
							. "', product_option_id = '" . (int)$product_option_id 
							. "', product_id = '" . (int)$product_id 
							. "', option_id = '" . (int)$product_option['option_id'] 
							. "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) 
							. "', quantity = '" . (int)$product_option_value['quantity'] 
							. "', subtract = '" . (int)$product_option_value['subtract'] 
							. "', price = '" . (float)$product_option_value['price'] 
							. "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) 
							. "', points = '" . (int)$product_option_value['points'] 
							. "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) 
							. "', weight = '" . (float)$product_option_value['weight'] 
							. "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else if($product_option['type'] == 'virtual_product' ){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
					$product_option_id = $this->db->getLastId();
					
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  product_value= '" . $this->db->escape($product_option_value['product_value']) . "', product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "',  points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "'");
						}
					}
				}else if($product_option['type'] == 'color' ){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET  product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
					
					$product_option_id = $this->db->getLastId();
					
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							if(!isset($product_option_value['product_value'])||(isset($product_option_value['product_value'])&&$product_option_value['product_value']==''))
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  color_product_id= '" . (int)$product_id . "', product_value= '" . $this->db->escape($product_option_value['product_value']) . "',  product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "'");
							else
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET  color_product_id= '" . (int)$product_option_value['color_product_id'] . "', product_value= '" . $this->db->escape($product_option_value['product_value']) . "',  product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "'");
						 }
					}
				}else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		/* 批量处理开始*/
		$sql="";
		
		if (isset($data['product_discount'])) {
			
			$oldid=array();
			
			foreach ($data['product_discount'] as $value) {
				if($value['product_discount_id']){
					$oldid[]=$value['product_discount_id'];
					$sql.="UPDATE " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "', quantity = '" . (int)$value['quantity'] . "',limited = '" . (int)$value['limited'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE product_discount_id='".$value['product_discount_id']."';";	
				}
				else 
				{
					$sql.="INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "', quantity = '" . (int)$value['quantity'] . "',limited = '" . (int)$value['limited'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "';";
	
				}
			}
			
			if($oldid){
				$sql="DELETE FROM " . DB_PREFIX . "product_discount WHERE product_discount_id not in(" . implode(',', $oldid) . ") AND product_id='".(int)$product_id."';".$sql;
			}
			
			//$this->db->multi_query($sql);
		}
		else 
		{
			$sql.="DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id='".(int)$product_id."';";
			
			
		}

		if (isset($data['product_special'])) {
	
			$oldid=array();
			foreach ($data['product_special'] as $value) {
				if($value['product_special_id']){
					$oldid[]=$value['product_special_id'];
				$sql.="UPDATE " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE product_special_id='".$value['product_special_id']."';";	
				}else{
				$sql.="INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "';";
				}
				if($value['tags']){
					$sql.="UPDATE " . DB_PREFIX . "product_special SET limited = '" . (int)$value['limited'] . "',date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE tags='" . $value['tags'] . "' AND customer_group_id = '" . (int)$value['customer_group_id'] . "';";
				}
			}
			if($oldid){
				$sql="DELETE FROM " . DB_PREFIX . "product_special WHERE product_special_id not in(" . implode(',', $oldid) . ") AND product_id='".(int)$product_id."';".$sql;
			}

			//$this->db->multi_query($sql);
			
		}
		else 
		{
			$sql.="DELETE FROM " . DB_PREFIX . "product_special WHERE product_id='".(int)$product_id."';";
			
		}
		if (isset($data['product_special0'])) {
		
			$oldid=array();
			foreach ($data['product_special0'] as $value) {
				if($value['product_special_id']){
					$oldid[]=$value['product_special_id'];
					$sql.="UPDATE " . DB_PREFIX . "product_special0 SET product_id = '" . (int)$product_id . "',delivery_id = '" . (int)$value['delivery_id'] . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE product_special_id='".$value['product_special_id']."';";
				}else{
					$sql.="INSERT INTO " . DB_PREFIX . "product_special0 SET product_id = '" . (int)$product_id . "',delivery_id = '" . (int)$value['delivery_id'] . "', customer_group_id = '" . (int)$value['customer_group_id'] . "',subordinate_type= '" . (int)$value['subordinate_type'] . "', priority = '" . (int)$value['priority'] . "', quantity = '" . (int)$value['quantity'] . "', code = '" . $this->db->escape($value['code']). "',limited = '" . (int)$value['limited'] . "',tags = '" . $value['tags'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "';";
				}
				if($value['tags']){
					$sql.="UPDATE " . DB_PREFIX . "product_special0 SET limited = '" . (int)$value['limited'] . "',date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "' WHERE tags='" . $value['tags'] . "' AND customer_group_id = '" . (int)$value['customer_group_id'] . "';";
				}
			}
			if($oldid){
				$sql="DELETE FROM " . DB_PREFIX . "product_special0 WHERE product_special_id not in(" . implode(',', $oldid) . ") AND product_id='".(int)$product_id."';".$sql;
			}
		
			//$this->db->multi_query($sql);
				
		}
		else
		{
			$sql.="DELETE FROM " . DB_PREFIX . "product_special0 WHERE product_id='".(int)$product_id."';";
				
		}
	
		$sql.="DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "';";
		if (isset($data['product_image'])) {
			
			foreach ($data['product_image'] as $image) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image) . "';";
			}
			
		}
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "';";

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "';";
			}
		}

		$sql.="DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "';";

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "';";
			}
		}
		
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "';";
		$sql.="DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "';";

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$sql.="DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "';";
				$sql.="INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "';";
				$sql.="DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "';";
				$sql.="INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "';";
			}
		}
		
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_coupon WHERE product_id = '" . (int)$product_id . "';";	
		if(isset($data['product_coupon'])){
			foreach ($data['product_coupon'] as $coupon) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_coupon SET product_id = '" . (int)$product_id . "', coupon_id = '" . (int)$coupon['coupon_id'] . "', coupon_num = '" . (int)$coupon['coupon_num'] . "';";
			}
		}
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_trans_code WHERE product_id = '" . (int)$product_id . "';";
		//储值卡
		if(isset($data['product_trans_code'])){
			foreach ($data['product_trans_code'] as $trans_code) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_trans_code SET product_id = '" . (int)$product_id . "', 
				       trans_code_id = '" . (int)$trans_code['trans_code_id'] . "', 
				       num = '" . (int)$trans_code['num'] . "',
				       is_tpl = '" . (int)$trans_code['is_tpl'] . "';";
			}
		}
		
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "';";
		
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$sql.="INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "';";
			}
		}
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "';";
		
		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$sql.="INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "';";
				}
			}
		}
		
		$sql.="DELETE FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id. "';";
		
		if(isset($data['product_tag'])){
			foreach ($data['product_tag'] as $language_id => $value) {
				if ($value) {
					$tags = explode(',', $value);
		
					foreach ($tags as $tag) {
						$sql.="INSERT INTO " . DB_PREFIX . "product_tag SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape(trim($tag)) . "';";
					}
				}
			}
		}
		
		$sql.="DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "';";
		
		if ($data['keyword']) {
			$sql.="INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "';";
		}else{
			$sql.="INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $name . "';";
		}

		$this->db->multi_query($sql);
		
		/*批量处理结束*/
		
		
		$this->deleteProductToTags($product_id);
		
		if (isset($data['product_icon'])) {
			foreach ($data['product_icon'] as $tag_id) {
				$this->addProductToTag($product_id,$tag_id);
			}
		}
		
		
		// 更新套餐信息
		$this->deleteProductCombine($product_id);
		//如果是套餐，更新套餐信息
		if( !empty($data['combine'])) {
		if(isset($data['product_combine'])){
			foreach ($data['product_combine'] as $combine_id) {
				$this->addProductCombine($product_id,$combine_id);
			}
		}
		}

		if(isset($data['donation'])){
			$this->editProductDonationSetting($product_id,$data['donation']);
		}
		$this->log_admin->info($data);
		$this->cache->delete('product');
		//重置缓存空间
		if($this->mem){
    		$this->mem->reset_namespace('products');
    		$this->mem->reset_namespace('product.'.$product_id);
		}
		
	}
	
	private function editProductDonationSetting($product_id,$donation){
		$this->db->query("UPDATE " . DB_PREFIX . "product SET donation=".(int)$donation." WHERE product_id=".(int)$product_id);
		$this->log_admin->info($donation);
	}
	

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;

			$data['keyword'] = '';

			//$data['status'] = '0';
			
			// FIXME just for test
			$data['status'] = '1';

			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));

			$data['product_image'] = array();

			$results = $this->getProductImages($product_id);

			foreach ($results as $result) {
				$data['product_image'][] = $result['image'];
			}

			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_tag' => $this->getProductTags($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));
			
			//$index=20000;
			//for ($i = 0; $i < $index; $i++) {
			$this->addProduct($data);
			//}
			
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_tag WHERE product_id='" . (int)$product_id. "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		$this->deleteProductCombine($product_id);
		$this->log_admin->info("deleteProduct($product_id)");
		$this->cache->delete('product');
		//重置缓存空间
		$this->mem->reset_namespace('products');
		$this->mem->reset_namespace('product.'.$product_id);
	}

	public function getProduct($product_id,$language_id=0) {
		if(!$language_id)$language_id=$this->config->get('config_language_id');
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$language_id . "'");

		return $query->row;
	}

	public function updateProductStatus($product_id,$status) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '" . (int)$status . "' WHERE product_id = '" . (int)$product_id . "'");
		$this->log_admin->info("updateProductStatus($product_id,$status)");
	}
	
	
	public function getProductsTemplets($data = array()) {
			$sql = "SELECT DISTINCT p.product_id AS upid, p.*,pd.*,pt.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) left join ".DB_PREFIX."product_templet pt on(pt.product_id=p.product_id)";

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			
			$sql.=" WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
				$sql .= " AND prod_type='".$this->request->get['type']."'";
			}
			
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE BINARY '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}

			if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
				$sql .= " AND LCASE(p.model) LIKE LCASE('" . $this->db->escape($data['filter_model']) . "%')";
			}
			
			if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
				$sql .= " AND LCASE(p.sku) LIKE LCASE('" . $this->db->escape($data['filter_sku']) . "%')";
			}

			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}

			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])&&$data['filter_category_id']!='') {
				$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
			}
			
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.sku',
				'p.size',
				'p.price',
				'p.quantity',
				'p.status',
				'p.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.product_id";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
	}
	
	public function getProducts($data = array()) {
		
		if($data) {
			$sql = "SELECT DISTINCT p.product_id AS upid, p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			if (isset($data['filter_period_id'])&& !is_null($data['filter_period_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_supply_period p2p ON (p.product_id = p2p.product_id)";
			}
			
			$sql.=" WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
				$sql .= " AND prod_type='".$this->request->get['type']."'";
			}
			
		
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND concat(LCASE(pd.name),LCASE(p.sku),LCASE(p.product_id)) LIKE BINARY '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}

			if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
				$sql .= " AND LCASE(p.model) LIKE LCASE('" . $this->db->escape($data['filter_model']) . "%')";
			}
			
			if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
				$sql .= " AND concat(LCASE(p.sku),LCASE(p.product_id)) LIKE LCASE('" . $this->db->escape($data['filter_sku']) . "%')";
			}

			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}

			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])&&$data['filter_category_id']!='') {
				$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
			}
			if (isset($data['filter_period_id'])&& !is_null($data['filter_period_id'])&&$data['filter_period_id']!='') {
				$sql .= " AND p2p.period_id = '" . (int)$data['filter_period_id'] . "'";
				}
				

			$sort_data = array(
				'pd.name',
				'p.model',
				'p.sku',
				'p.size',
				'p.price',
				'p.quantity',
				'p.status',
				'p.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.product_id";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$product_data = $this->cache->get('product.' . $this->config->get('config_language_id'));

			if (!$product_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");

				$product_data = $query->rows;

				$this->cache->set('product.' . $this->config->get('config_language_id'), $product_data);
			}

			return $product_data;
		}
	}
	
	/**
	 * 获取促销信息
	 * @param unknown $product_id
	 * @return multitype:string NULL
	 */
	public function getProductPromotionInfo($product_id){
	    $sql=" SELECT price FROM " . DB_PREFIX . "product_discount  
	           WHERE product_id = '{$product_id}'  AND quantity = '1' AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority ASC, price ASC LIMIT 1";
	    
	    $sql2 = "SELECT price FROM " . DB_PREFIX . "product_special 
	           WHERE product_id = '{$product_id}'  AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority ASC, price ASC LIMIT 1";
	    $query  = $this->db->query($sql);
	    $query2 = $this->db->query($sql2);
	           
	    //促销信息
	    $promotion = array();
	    if($query->row){
	        $promotion['promotion_price'] = $query->row['price'];
	        $promotion['promotion_code']  = EnumPromotionTypes::PROMOTION_NORMAL;
	    }
	    if($query2->row){
	        $promotion['promotion_price'] = $query2->row['price'];
	        $promotion['promotion_code']  = EnumPromotionTypes::PROMOTION_SPECIAL;
	    }
	    
	    return  $promotion;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {

			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'subtitle'             => $result['subtitle'],
				'unit'             => $result['unit'],
				'origin'             => $result['origin'],
				'storage'             => $result['storage'],
				'package'             => $result['package'],
				'delivery'             => $result['delivery'],
				'description'      => $result['description'],
				'des_img'      => $result['des_img'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_title'     => $result['meta_title'],
				'meta_description' => $result['meta_description'],
					'share_title'     => $result['share_title'],
					'share_desc'     => $result['share_desc'],
			    'cooking'          => $result['cooking']
			);
		}

		return $product_description_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pa.attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'name'                          => $product_attribute['name'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY product_option_id ASC");
	
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox'|| $product_option['type'] == 'virtual_product'|| $product_option['type'] == 'color' || $product_option['type'] == 'autocomplete') {
				$product_option_value_data = array();
				
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY product_option_value_id ASC");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'color_product_id'         => $product_option_value['color_product_id'],
						'product_value'           => $product_option_value['product_value'],
						'name'                    => $product_option_value['name'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);
			}
		}
		return $product_option_data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}
	public function getProductSpecial0s($product_id) {
		$query = $this->db->query("SELECT ps.*,pd.code,pd.region_name FROM " . DB_PREFIX . "product_special0 as ps LEFT JOIN ". DB_PREFIX."point_delivery as pd ON ps.delivery_id=pd.delivery_id WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
	
		return $query->rows;
	}
	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	public function getProductperiods($product_id) {
		$product_category_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_supply_period WHERE product_id = '" . (int)$product_id . "'");
	
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['period_id'];
		}
	
		return $product_category_data;
	}
	public function getProductToTags($product_id) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_tag WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$data[] = $result['tag_id'];
		}

		return $data;
	}
	public function getProductCoupons($product_id) {
		$query = $this->db->query("SELECT p.*,c.name FROM " . DB_PREFIX . "product_coupon as p 
LEFT JOIN " . DB_PREFIX . "coupon as c on p.coupon_id =c.coupon_id
				WHERE p.product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
	
	/**
	 * 获取虚拟商品绑定储值信息
	 * @param 商品ID $product_id
	 */
	public function getProductTrans($product_id) {
		$query = $this->db->query("SELECT p.* , t.trans_code, t.operator, t.date_start, t.date_end, t.`value`  FROM " . DB_PREFIX . "product_trans_code as p
                                    LEFT JOIN " . DB_PREFIX . "trans_code as t on p.trans_code_id =t.trans_id
				                    WHERE p.product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
	
	
	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getProductTags($product_id) {
		$product_tag_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "'");

		$tag_data = array();

		foreach ($query->rows as $result) {
			$tag_data[$result['language_id']][] = $result['tag'];
		}

		foreach ($tag_data as $language => $tags) {
			$product_tag_data[$language] = implode(',', $tags);
		}

		return $product_tag_data;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";

		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		if (isset($data['filter_period_id'])&& !is_null($data['filter_period_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_supply_period p2p ON (p.product_id = p2p.product_id)";
		}
		$sql.=" WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE LCASE('%" . $this->db->escape($data['filter_name']) . "%')";
		}

		if (isset($this->request->get['type']) && !is_null($this->request->get['type'])) {
				$sql .= " AND p.prod_type='".$this->request->get['type']."'";
		}
			
		
		if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND LCASE(p.model) LIKE LCASE('%" . $this->db->escape($data['filter_model']) . "%')";
		}
		
		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND LCASE(p.sku) LIKE LCASE('%" . $this->db->escape($data['filter_sku']) . "%')";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
		}
		if (isset($data['filter_period_id'])&& !is_null($data['filter_period_id'])) {
			$sql .= " AND p2p.period_id = '" . (int)$data['filter_period_id'] . "'";
		}
		
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getProductOptionStocks($pId)
	{
		$SQL = "
                SELECT `" . DB_PREFIX . "product_option_value`.`product_option_value_id`, `" . DB_PREFIX . "option_value_description`.`name`
                FROM
                    `" . DB_PREFIX . "product_option_value`,
                    `" . DB_PREFIX . "option_value_description`,
                    `" . DB_PREFIX . "option_value`,
                    `" . DB_PREFIX . "option`
                WHERE
                    `" . DB_PREFIX . "product_option_value`.`product_id` = '".$pId."'
                AND
                    `" . DB_PREFIX . "option_value_description`.`language_id` = '".(int)$this->config->get('config_language_id') ."'
                AND
                    `" . DB_PREFIX . "product_option_value`.`option_value_id` = `" . DB_PREFIX . "option_value_description`.`option_value_id`
                AND
                    `" . DB_PREFIX . "option_value`.`option_value_id` = `" . DB_PREFIX . "product_option_value`.`option_value_id`
                AND
                    `" . DB_PREFIX . "option`.`option_id` = `" . DB_PREFIX . "option_value`.`option_id`
                ORDER BY `" . DB_PREFIX . "product_option_value`.`product_option_value_id`,`" . DB_PREFIX . "option`.`sort_order`, `" . DB_PREFIX . "option_value`.`sort_order` ASC";


		$options_qry = $this->db->query($SQL);

		$optionValues = array();
		foreach($options_qry->rows as $row)
		{
			$optionValues[$row['product_option_value_id']] = $row['name'];
		}

		$SQL = "SELECT * FROM `" . DB_PREFIX . "product_option_relation` WHERE `product_id` = '".$pId."' ORDER BY `id` ASC";
		$mix_qry = $this->db->query($SQL);

		$optionsStockArray = array();

		foreach($mix_qry->rows as $row)
		{
			$options = explode(':', $row['var']);
			$combi = '';
			foreach($options as $k=>$v)
			{
				$combi .= $optionValues[$v] .' > ';
			}

			$optionsStockArray[$row['id']] = array(
                    'id'            => $row['id'],
                    'sku'           => $row['sku'],
                    'product_id'    => $row['product_id'],
                    'combi'         => $combi,
                    'stock'         => $row['stock'],
                    'active'        => $row['active'],
                    'var'           => $row['var'],
                    'price'           => $row['price'],
                    'weight'           => $row['weight'],
                    'subtract'      => $row['subtract']
			);
		}

		return $optionsStockArray;
	}

	public function calcOptions($pId)
	{
		$SQL = "SELECT `" . DB_PREFIX . "product_option_value`.`product_option_value_id`, `" . DB_PREFIX . "product_option_value`.`option_id`
                FROM
                    `" . DB_PREFIX . "product_option_value`,
                    `" . DB_PREFIX . "option_value_description`,
                    `" . DB_PREFIX . "option_value`,
                    `" . DB_PREFIX . "option`
                WHERE
                    `" . DB_PREFIX . "product_option_value`.`product_id` = '".$pId."'
                AND
                    ((`" . DB_PREFIX . "option`.`type` = 'radio') OR (`" . DB_PREFIX . "option`.`type` = 'select')  OR (`" . DB_PREFIX . "option`.`type` = 'autocomplete'))
                AND
                    `" . DB_PREFIX . "product_option_value`.`option_value_id` = `" . DB_PREFIX . "option_value_description`.`option_value_id`
                AND
                    `" . DB_PREFIX . "option_value`.`option_value_id` = `" . DB_PREFIX . "product_option_value`.`option_value_id`
                AND
                    `" . DB_PREFIX . "option`.`option_id` = `" . DB_PREFIX . "option_value`.`option_id`
                ORDER BY `" . DB_PREFIX . "product_option_value`.`product_option_value_id`,`" . DB_PREFIX . "option`.`sort_order`, `" . DB_PREFIX . "option_value`.`sort_order` ASC";
		
		$options_qry = $this->db->query($SQL);

		$unique_grp = array();
		foreach($options_qry->rows as $row)
		{
			if(!array_key_exists($row['option_id'], $unique_grp))
			{
				$unique_grp[$row['option_id']] = array();
			}

			$unique_grp[$row['option_id']][] = $row['product_option_value_id'];
		}

		$newArray = array();
		$i = 0;
		foreach($unique_grp as $key => $grp)
		{
			$newArray[$i] = $grp;
			$i++;
		}

		$final = array();

		if(!empty($newArray))
		{
			foreach($newArray[0] as $k1 => $v1)
			{
				if(!empty($newArray[1]))
				{
					foreach($newArray[1] as $k2 => $v2)
					{
						if(!empty($newArray[2]))
						{
							foreach($newArray[2] as $k3 => $v3)
							{
								if(!empty($newArray[3]))
								{
									foreach($newArray[3] as $k4 => $v4)
									{
										if(!empty($newArray[4]))
										{
											foreach($newArray[4] as $k5 => $v5)
											{
												if(!in_array($v1.':'.$v2.':'.$v3.':'.$v4.':'.$v5, $final))
												{
													$final[] = $v1.':'.$v2.':'.$v3.':'.$v4.':'.$v5;
												}
											}
										}else{
											if(!in_array($v1.':'.$v2.':'.$v3.':'.$v4, $final))
											{
												$final[] = $v1.':'.$v2.':'.$v3.':'.$v4;
											}
										}
									}
								}else{
									if(!in_array($v1.':'.$v2.':'.$v3, $final))
									{
										$final[] = $v1.':'.$v2.':'.$v3;
									}
								}
							}
						}else{
							$t_vars=array();
							$t_vars[]=$v1;
							$t_vars[]=$v2;
							
							sort($t_vars);
							$var=implode(":",$t_vars);
							
							if(!in_array($var, $final))
							{
								$final[] = $var;
							}
							
							/*if(!in_array($v1.':'.$v2, $final))
							{
								$final[] = $v1.':'.$v2;
							}*/
						}
					}
				}else{
					if(!in_array($v1, $final))
					{
						$final[] = $v1;
					}
				}
			}
		}

		return $final;
	}

	public function addProductOptionRelation($product_id,$product_option_stock){
		//recalculate
		$optionsStockCalcs = $this->calcOptions((int)$product_id);

		foreach ($product_option_stock as $key => $option_stock)
		{
			if(in_array($option_stock['var'], $optionsStockCalcs))
			{
				unset($optionsStockCalcs[array_search($option_stock['var'], $optionsStockCalcs)]);

				$sql="
                                    INSERT INTO " . DB_PREFIX . "product_option_relation
                                    SET
                                        `product_id` = '" . (int)$product_id . "',
                                        `var` = '".$option_stock['var']."',
                                        `sku` = '".$option_stock['sku']."',
                                        `stock` = '".$option_stock['stock']."',
                                        `active` = '".$option_stock['active']."',
                                        `subtract` = '".$option_stock['subtract']."',
                                        `price` = '".(float)$option_stock['price']."',
                                        `weight` = '".(float)$option_stock['weight']."'
                                        ";

				$this->db->query($sql);
			}
		}
		//补全默认选项
		foreach($optionsStockCalcs as $newOption)
		{
			$this->db->query("
                            INSERT INTO " . DB_PREFIX . "product_option_relation
                            SET
                                `product_id` = '" . (int)$product_id . "',
                                `var` = '".$newOption."',
                                `sku` = '',
                                `stock` = '0',
                                `price` = 0,
                                `weight` = 0,
                                `active` = '0',
                                `subtract` = '1'
                                ");
		}
		$this->log_admin->info("addProductOptionRelation($product_id,".serialize($product_option_stock).")");
	}
	
	private function editProductDescription($product_id,$language_id,$value){
		$sql="INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id 
		. "', language_id = '" . (int)$language_id 
		. "', name = '" . $this->db->escape($value['name']) 
		. "', meta_title = '" . $this->db->escape($value['meta_title']) 
		. "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) 
		. "', meta_description = '" . $this->db->escape($value['meta_description']) 
		. "', des_img = '" . $this->db->escape($value['des_img']) 
		. "',share_title = '" . $this->db->escape($value['share_title'])
		. "', share_desc = '" . $this->db->escape($value['share_desc'])
		. "', description = '" . $this->db->escape($value['description']) . "'";
		
		if(isset($value['subtitle'])){
			$sql.=",subtitle='".$this->db->escape($value['subtitle']) ."'";
		}
		
		if(isset($value['origin'])){
			$sql.=",origin='".$this->db->escape($value['origin']) ."'";
		}
		
		if(isset($value['unit'])){
			$sql.=",unit='".$this->db->escape($value['unit']) ."'";
		}
		
		if(isset($value['storage'])){
			$sql.=",storage='".$this->db->escape($value['storage']) ."'";
		}
		
		if(isset($value['package'])){
			$sql.=",package='".$this->db->escape($value['package']) ."'";
		}
		
		if(isset($value['delivery'])){
			$sql.=",delivery='".$this->db->escape($value['delivery']) ."'";
		}
		
	    if(isset($value['cooking'])){
			$sql.=",cooking='".$this->db->escape($value['cooking']) ."'";
		}
		
		$this->db->query($sql);
		$this->log_admin->info("editProductDescription($product_id,$language_id,$value)");
	}
	
	public function getProductCombine($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_combine WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['combine_id'];
		}

		return $product_related_data;
	}
	
	private function deleteProductCombine($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_combine WHERE product_id = '" . (int)$product_id . "'");
		$this->log_admin->info("deleteProductCombine($product_id)");
	}
	
	
	
	private function addProductCombine($product_id,$combine_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_combine SET product_id = '" . (int)$product_id . "', combine_id = '" . (int)$combine_id . "'");
		$this->log_admin->info("addProductCombine($product_id,$combine_id)");
	}	
	
	
	public function getAllProductTemplets($product_id){
		$sql = "select * from ".DB_PREFIX."product_templet pt left join ".DB_PREFIX."product p on(p.product_id=pt.product_id) left join ".DB_PREFIX."product_description pd on(pd.product_id=p.product_id)  where pt.product_id='".(int)$product_id."' and pd.language_id=1";
		$results =  $this->db->query($sql)->rows;
		return $results;
	}
	
	public function delTemplet($product_templet_id){
		$sql = "delete from ".DB_PREFIX."product_templet where product_templet_id='".(int)$product_templet_id."'";
		$this->db->query($sql);
		$this->log_admin->info("delTemplet($product_templet_id)");
	}
	
	public function addProductTemplet($product_id,$templet_info){
		$sql = "insert into ".DB_PREFIX."product_templet set product_id='".(int)$product_id."',templet_info='".$templet_info."'";
		$this->db->query($sql);
		$this->log_admin->info("addProductTemplet($product_id,$templet_info)");
	}

	public function isProductInPeriod($productId,$periodId,$pick_times){
		// 根据session标记获取当前菜品所在周期id
		$sql="select * from ts_product_supply_period p";
		
		if($pick_times) $sql.=" LEFT JOIN ts_supply_period as sp ON sp.id=p.period_id";
		
		$sql.=" WHERE p.product_id='".(int)$productId."'";
	if((int)$periodId>0)
		$sql.=" AND p.period_id='".(int)$periodId."'";
	if($pick_times&&$periodId!=0)
		$sql.=" AND sp.p_end_date>=DATE('".$pick_times."') and sp.p_start_date<=DATE('".$pick_times."')";
		$row=$this->db->query($sql)->row;
		return !empty($row);
	}
}
?>