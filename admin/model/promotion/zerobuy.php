<?php
class ModelPromotionZerobuy extends Model {
	
	/**
	 * 保存促销规则，产品关系
	 * Enter description here ...
	 * @param unknown_type $product_id
	 * @param unknown_type $pr_id
	 */
	public function addProductToRule($product_id,$pr_id,$data)
	{
		if(isset($product_id)&&isset($pr_id)&&!is_null($product_id)&&!is_null($pr_id))
		{
			$sql = "INSERT INTO ".DB_PREFIX."pr_to_product SET product_id='".(int)$product_id."' , pr_id ='".(int)$pr_id."'";
			
			if(isset($data['start_date']))
			{
				$sql .= " ,start_date ='".$data['start_date']."'";
			}
			
			if(isset($data['end_date']))
			{
				$sql .= " ,end_date ='".$data['end_date']."'";
			}
			if(isset($data['group']))
			{
				$sql .= " ,group ='".$data['group']."'";
			}
			
			if(isset($data['sort_order']))
			{
				$sql .= " ,`sort_order` ='".$data['sort_order']."'";
			}
			if(isset($data['use_quantity']))
			{
				$sql .= " ,use_quantity ='".$data['use_quantity']."'";
			}
			if(isset($data['buy_quantity']))
			{
				$sql .= " ,buy_quantity ='".$data['buy_quantity']."'";
			}
			$this->db->query($sql);
		}
	}
	
	/**
	 * 获取促销产品关系信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */	
	public function getPromotionProductInfo($data)
	{
		if(isset($data['product_id'])&&isset($data['pr_id'])&&!is_null($data['product_id'])&&!is_null($data['pr_id']))
		{
			$sql = "SELECT ptp.*,pr.pr_code FROM ".DB_PREFIX."pr_to_product ptp left join ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id)  WHERE ptp.product_id='".(int)$data['product_id']."' AND ptp.pr_id ='".(int)$data['pr_id']."'";
			$query  = $this->db->query($sql);
			return $query->row;
		}
		return false;
		
	}
	
	
	public function updatePrProductInfo($data)
	{
		if(isset($data['product_id'])&&isset($data['pr_id'])&&!is_null($data['product_id'])&&!is_null($data['pr_id']))
		{
			$sql ="UPDATE ".DB_PREFIX."pr_to_product SET product_id='".$data['product_id']."'";
			
			if(isset($data['start_date']))
			{
				$sql .=" , start_date ='".$data['start_date']."'";
			}
			if(isset($data['end_date']))
			{
				$sql .=" , end_date ='".$data['end_date']."'";
			}
			if(isset($data['use_quantity']))
			{
				$sql .=" , use_quantity ='".$data['use_quantity']."'";
			}
			if(isset($data['group']))
			{
				$sql .= " ,`group` ='".$data['group']."'";
			}
			if(isset($data['sort_order']))
			{
				$sql .= " ,`sort_order` ='".$data['sort_order']."'";
			}
			$sql .=" WHERE product_id='".(int)$data['product_id']."' AND pr_id ='".(int)$data['pr_id']."'";
			
			$this->db->query($sql);

		}
		return false;
	}
	
private function editProductHasOption($product_id,$has_option){
		$this->db->query("UPDATE " . DB_PREFIX . "product SET has_option=".(int)$has_option." WHERE product_id=".(int)$product_id);
	}
	
	public function addProduct($data) {
		$name='';
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

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
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "', quantity = '" . (int)$value['quantity'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}
		
		if(isset($data['product_combine'])){
			foreach ($data['product_combine'] as $combine_id) {
				$this->addProductCombine($product_id,$combine_id);
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
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
	
		$this->cache->delete('product');
	}
	
	private function deleteProductToTags($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_tag WHERE product_id = '" . (int)$product_id . "'");
	}
	
	private function addProductToTag($product_id,$tag_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_tag SET product_id = '" . (int)$product_id . "', tag_id = '" . (int)$tag_id . "'");
	}
	


	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getProducts($data = array(),$pr_id,$type) {
		if ($data) {
			$sql = "";
			if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
			{
				$sql .= "SELECT DISTINCT p.product_id AS upid,ptp.use_quantity as use_quantity,pr.pr_group,ptp.sort_order as p_sort_order, p.*,pd.* FROM " . DB_PREFIX . "product p
						 LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
						  LEFT JOIN ".DB_PREFIX."pr_to_product ptp on (ptp.product_id=p.product_id)
						  LEFT JOIN ".DB_PREFIX."p_rule pr on (ptp.pr_id=pr.pr_id)";
			}else
			{
				$sql .= "SELECT DISTINCT p.product_id AS upid, p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";
			}
			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			
			$sql.=" WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
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

			if (isset($data['filter_group']) && !is_null($data['filter_group'])) {
				$sql .= " AND ptp.group LIKE LCASE('%" . $this->db->escape($data['filter_group']) . "%')";
			}
			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])&&$data['filter_category_id']!='') {
				$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
			}
			
			if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
			{
				$sql .="  AND p.product_id in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' ) and ptp.pr_id='".(int)$pr_id."' ";
			}
//			else if(isset($pr_id)&& !is_null($pr_id)&&(!is_null($type)))
//			{
//				$sql .="  AND p.product_id not in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' )";
//			}
			
			
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
				$sql .= " ORDER BY ptp.sort_order";
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
				'meta_keyword'     => $result['meta_keyword'],
				'meta_title'     => $result['meta_title'],
				'meta_description' => $result['meta_description']
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
	
	public function getProductToTags($product_id) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_tag WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$data[] = $result['tag_id'];
		}

		return $data;
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

	/**
	 * 获取满足条件的产品信息
	 * @param unknown_type $data
	 */
	public function getTotalProducts($data = array(),$pr_id,$type) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";
		
		if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
		{
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  left join ".DB_PREFIX."pr_to_product ptp on (ptp.product_id=p.product_id)";
		}
		
		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		
		$sql.=" WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE LCASE('%" . $this->db->escape($data['filter_name']) . "%')";
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
		if (isset($data['filter_group']) && !is_null($data['filter_group'])) {
			$sql .= " AND ptp.group =LIKE LCASE('%" . $this->db->escape($data['filter_group']) . "%')";
		}
		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
		}

		if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
		{
			$sql .="  AND p.product_id in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' ) and ptp.pr_id='".(int)$pr_id."'";
		}
//		else if(isset($pr_id)&& !is_null($pr_id)&&(!is_null($type)))
//		{
//			$sql .="  AND p.product_id not in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' )";
//		}	
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
	}
	
	private function editProductDescription($product_id,$language_id,$value){
		$sql="INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id 
		. "', language_id = '" . (int)$language_id 
		. "', name = '" . $this->db->escape($value['name']) 
		. "', meta_title = '" . $this->db->escape($value['meta_title']) 
		. "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) 
		. "', meta_description = '" . $this->db->escape($value['meta_description']) 
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
		
		
		$this->db->query($sql);
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
	}
	
	
	
	private function addProductCombine($product_id,$combine_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_combine SET product_id = '" . (int)$product_id . "', combine_id = '" . (int)$combine_id . "'");
	}	
	
	public function deletePrToProduct($product_id,$pr_id)
	{
		if(isset($product_id)&&isset($pr_id))
		{
			$this->db->query("DELETE FROM " . DB_PREFIX . "pr_to_product WHERE product_id = '" . (int)$product_id . "' and pr_id ='".(int)$pr_id."'");
		}
	} 
	
	
//	public function getRuleProduct($pr_id)
//	{
//		if(isset($pr_id))
//		{
//			$sql = "SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb.id=pr.pb.id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."'";
//			$query = $this->db->query($sql);
//			return $query->rows;
//		}
//		return false;
//	}

}
?>