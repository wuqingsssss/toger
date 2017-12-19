<?php
class ModelAccountProduct extends Model {
	public function addProduct($data) {
		$name='';
		$this->db->query("INSERT INTO " . DB_PREFIX . "project_product SET number = '" . $this->db->escape($data['number']) ."', trade_type = '". $this->db->escape($data['trade_type'])."', industry_type = '".$this->db->escape($data['industry_type'])."', name = '".$this->db->escape($data['name'])."', period = '".$this->db->escape($data['period'])."' , supply_demand = '".$this->db->escape($data['supply_demand'])."', createtor = '".$this->db->escape($this->session->data['loginUser'])."', price = '".$this->db->escape($data['price'])."', project_status = '".$this->db->escape($data['project_status'])."', status = '".$this->db->escape($data['status'])."', conditions = '".$this->db->escape($data['conditions'])."', description = '".$this->db->escape($data['description'])."', local_addr_zone = '".$this->db->escape($data['local_addr_zone'])."', date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if(isset($data['local_addr_city']))
		{
			$this->db->query("update ". DB_PREFIX . "project_product SET  local_addr_city = '".$this->db->escape($data['local_addr_city'])."' where product_id = '" . (int)$product_id . "'");
		}
		
		if(isset($data['unit'])&&!is_null($data['unit']))
		{
			$this->db->query("update ". DB_PREFIX . "project_product SET  unit = '".$this->db->escape($data['unit'])."' where product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("update ". DB_PREFIX . "project_product SET  customer_id = '".$this->db->escape($this->customer->getId())."' where product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "project_product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
//
//		if ($data['keyword']) {
//			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
//		}else{
//			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $name . "'");
//		}

		$this->cache->delete('product');
	}

	public function editProduct($product_id, $data) {
		$name='';
		
		
		$this->db->query("UPDATE " . DB_PREFIX . "project_product SET number = '" . $this->db->escape($data['number']) ."', trade_type = '". $this->db->escape($data['trade_type'])."', industry_type = '".$this->db->escape($data['industry_type'])."', name = '".$this->db->escape($data['name'])."', period = '".$this->db->escape($data['period'])."' , supply_demand = '".$this->db->escape($data['supply_demand'])."', createtor = '".$this->db->escape($this->session->data['loginUser'])."', price = '".$this->db->escape($data['price'])."', project_status = '".$this->db->escape($data['project_status'])."', conditions = '".$this->db->escape($data['conditions'])."', description = '".$this->db->escape($data['description'])."', status = '".$this->db->escape($data['status'])."'  WHERE product_id = '" . (int)$product_id . "'");

		if(isset($data['local_addr_zone']))
		{
			$this->db->query("update ". DB_PREFIX . "project_product SET  local_addr_zone = '".$this->db->escape($data['local_addr_zone'])."' where product_id = '" . (int)$product_id . "'");
		}
		
		if(isset($data['local_addr_city']))
		{
			$this->db->query("update ". DB_PREFIX . "project_product SET  local_addr_city = '".$this->db->escape($data['local_addr_city'])."' where product_id = '" . (int)$product_id . "'");
		}
		
		if(isset($data['unit']))
		{
			$this->db->query("update ". DB_PREFIX . "project_product SET  unit = '".$this->db->escape($data['unit'])."' where product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "project_product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "project_product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");

//		if ($data['keyword']) {
//			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->makeSlugs($this->db->escape($data['keyword'])) . "'");
//		}else{
//			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $name . "'");
//		}
		
		$this->cache->delete('product');
	}
	
	public function editVerifyStatus($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "project_product SET verified = '" . $this->db->escape($data['verified']) ."'  WHERE product_id = '" . (int)$product_id . "'");
		
	}
	

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "project_product p  WHERE p.product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;

			$data['keyword'] = '';

			//$data['status'] = '0';
			
			// FIXME just for test
			$data['status'] = '1';

			//$index=20000;
			//for ($i = 0; $i < $index; $i++) {
			$this->addProduct($data);
			//}
			
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "project_product WHERE product_id = '" . (int)$product_id . "' ");
		$this->db->query("DELETE FROM " . DB_PREFIX . "project_product_to_category WHERE product_id = '" . (int)$product_id . "'");
//		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
	}

	public function getProduct($product_id) {
		
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "project_product p  WHERE p.product_id = '" . (int)$product_id . "'");

		return $query->row;
	}

	public function updateProductStatus($product_id,$status) {
		$this->db->query("UPDATE " . DB_PREFIX . "project_product SET status = '" . (int)$status . "' WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function getProducts($data = array()) {
		if ($data) {
			$sql = "SELECT DISTINCT p.product_id AS upid, p.* FROM " . DB_PREFIX . "project_product p  ";
			
			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "project_product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			
				$sql.=" WHERE 1=1 ";
			
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND LCASE(p.name) LIKE BINARY '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}
			
			if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
				$sql .= " AND p.customer_id = '" . $this->db->escape($data['filter_customer']) . "'";
			}

			if (isset($data['filter_number']) && !is_null($data['filter_number'])) {
				$sql .= " AND LCASE(p.number) LIKE LCASE('" . $this->db->escape($data['filter_number']) . "%')";
			}
		
			if (isset($data['filter_trade_type']) && !is_null($data['filter_trade_type'])) {
				$sql .= " AND LCASE(p.trade_type) LIKE LCASE('" . $this->db->escape($data['filter_trade_type']) . "%')";
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
			
			if (isset($data['filter_verified']) && !is_null($data['filter_verified'])) {
				$sql .= " AND p.verified = '" . (int)$data['filter_verified'] . "'";
			}
			
			if (isset($data['filter_supply_demand']) && !is_null($data['filter_supply_demand'])) {
				$sql .= " AND p.supply_demand = '" . (int)$data['filter_supply_demand'] . "'";
			}

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])&&$data['filter_category_id']!='') {
				$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "project_category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
			}
			
			$sort_data = array(
				'p.name',
				'p.number',
				'p.trade_type',
				'p.price',
				'p.supply_demand',
				'p.status',
				'p.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.name";
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
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");

				$product_data = $query->rows;

				$this->cache->set('product.' . $this->config->get('config_language_id'), $product_data);
			}

			return $product_data;
		}
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "project_product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

//	public function getProductDescriptions($product_id) {
//		$product_description_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_description_data[$result['language_id']] = array(
//				'name'             => $result['name'],
//				'description'      => $result['description'],
//				'meta_keyword'     => $result['meta_keyword'],
//				'meta_title'     => $result['meta_title'],
//				'meta_description' => $result['meta_description']
//			);
//		}
//
//		return $product_description_data;
//	}

//	public function getProductAttributes($product_id) {
//		$product_attribute_data = array();
//
//		$product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pa.attribute_id");
//
//		foreach ($product_attribute_query->rows as $product_attribute) {
//			$product_attribute_description_data = array();
//
//			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
//
//			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
//				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
//			}
//
//			$product_attribute_data[] = array(
//				'attribute_id'                  => $product_attribute['attribute_id'],
//				'name'                          => $product_attribute['name'],
//				'product_attribute_description' => $product_attribute_description_data
//			);
//		}
//
//		return $product_attribute_data;
//	}
//
//	public function getProductOptions($product_id) {
//		$product_option_data = array();
//
//		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
//	
//		foreach ($product_option_query->rows as $product_option) {
//			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox'|| $product_option['type'] == 'virtual_product'|| $product_option['type'] == 'color') {
//				$product_option_value_data = array();
//				
//				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
//				
//				foreach ($product_option_value_query->rows as $product_option_value) {
//					$product_option_value_data[] = array(
//						'product_option_value_id' => $product_option_value['product_option_value_id'],
//						'option_value_id'         => $product_option_value['option_value_id'],
//						'color_product_id'         => $product_option_value['color_product_id'],
//						'product_value'           => $product_option_value['product_value'],
//						'name'                    => $product_option_value['name'],
//						'quantity'                => $product_option_value['quantity'],
//						'subtract'                => $product_option_value['subtract'],
//						'price'                   => $product_option_value['price'],
//						'price_prefix'            => $product_option_value['price_prefix'],
//						'points'                  => $product_option_value['points'],
//						'points_prefix'           => $product_option_value['points_prefix'],
//						'weight'                  => $product_option_value['weight'],
//						'weight_prefix'           => $product_option_value['weight_prefix']
//					);
//				}
//
//				$product_option_data[] = array(
//					'product_option_id'    => $product_option['product_option_id'],
//					'option_id'            => $product_option['option_id'],
//					'name'                 => $product_option['name'],
//					'type'                 => $product_option['type'],
//					'product_option_value' => $product_option_value_data,
//					'required'             => $product_option['required']
//				);
//			} else {
//				$product_option_data[] = array(
//					'product_option_id' => $product_option['product_option_id'],
//					'option_id'         => $product_option['option_id'],
//					'name'              => $product_option['name'],
//					'type'              => $product_option['type'],
//					'option_value'      => $product_option['option_value'],
//					'required'          => $product_option['required']
//				);
//			}
//		}
//		return $product_option_data;
//	}

//	public function getProductImages($product_id) {
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
//
//		return $query->rows;
//	}
//
//	public function getProductDiscounts($product_id) {
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");
//
//		return $query->rows;
//	}
//
//	public function getProductSpecials($product_id) {
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
//
//		return $query->rows;
//	}
//
//	public function getProductRewards($product_id) {
//		$product_reward_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
//		}
//
//		return $product_reward_data;
//	}
//
//	public function getProductDownloads($product_id) {
//		$product_download_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_download_data[] = $result['download_id'];
//		}
//
//		return $product_download_data;
//	}
//
//	public function getProductStores($product_id) {
//		$product_store_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_store_data[] = $result['store_id'];
//		}
//
//		return $product_store_data;
//	}
//
//	public function getProductLayouts($product_id) {
//		$product_layout_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_layout_data[$result['store_id']] = $result['layout_id'];
//		}
//
//		return $product_layout_data;
//	}
//

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
//
//	public function getProductRelated($product_id) {
//		$product_related_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
//
//		foreach ($query->rows as $result) {
//			$product_related_data[] = $result['related_id'];
//		}
//
//		return $product_related_data;
//	}
//
//	public function getProductTags($product_id) {
//		$product_tag_data = array();
//
//		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "'");
//
//		$tag_data = array();
//
//		foreach ($query->rows as $result) {
//			$tag_data[$result['language_id']][] = $result['tag'];
//		}
//
//		foreach ($tag_data as $language => $tags) {
//			$product_tag_data[$language] = implode(',', $tags);
//		}
//
//		return $product_tag_data;
//	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "project_product p  ";
		
		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "project_product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		
		$sql.=" WHERE 1=1 ";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(p.name) LIKE LCASE('%" . $this->db->escape($data['filter_name']) . "%')";
		}
		
		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$sql .= " AND p.customer_id = '" . $this->db->escape($data['filter_customer']) . "'";
		}

		if (isset($data['filter_number']) && !is_null($data['filter_number'])) {
			$sql .= " AND LCASE(p.number) LIKE LCASE('%" . $this->db->escape($data['filter_number']) . "%')";
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
		
		if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
			$sql .= " AND p.type = '" . (int)$data['filter_type'] . "'";
		}

		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "project_category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

//	public function getTotalProductsByStockStatusId($stock_status_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByTaxClassId($tax_class_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByWeightClassId($weight_class_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByLengthClassId($length_class_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByDownloadId($download_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByManufacturerId($manufacturer_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByAttributeId($attribute_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByOptionId($option_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");
//
//		return $query->row['total'];
//	}
//
//	public function getTotalProductsByLayoutId($layout_id) {
//		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
//
//		return $query->row['total'];
//	}

/**
	 * 查询分类下的数据
	 * @param $data      	   ： 查询条件
	 * @param $category      : 分类类型
	 * @param $supplyType    ： 供求类型
	 */
	public function getSpecifiedCategoryProjectInfo($data = array(),$supplyType)
	{

		$sql = "select tpp.*,tpc.* from ".DB_PREFIX."project_product tpp right join ".DB_PREFIX."project_product_to_category tpptc on tpp.product_id = tpptc.product_id left join ".DB_PREFIX."project_category tpc on tpc.category_id = tpptc.category_id where 1=1" ;

		if(isset($supplyType)&& !is_null($supplyType))
		{
			$sql .= " AND tpp.supply_demand = ".$this->db->escape($supplyType);
		}
			
		if(isset($data['category_id']) && !is_null($data['category_id']))
		{
			$sql .= " AND tpc.category_id = ".(int)$this->db->escape($data['category_id']);
		}
			
		if(isset($data['verified']) && !is_null($data['verified']))
		{
			$sql .= " AND tpp.verified = ".(int)$this->db->escape($data['verified']);
		}
		
		if(isset($data['except_id']) && !is_null($data['except_id']))
		{
			$sql .= " AND tpp.product_id not in ( ".(int)$this->db->escape($data['except_id']).")";
		}
		
		
		$sort_data = array(
				'tpp.name',
				'tpp.number',
				'tpp.trade_type',
				'tpp.price',
				'tpp.supply_demand',
				'tpp.status',
				'tpp.sort_order'
				);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY tpp.name";
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
	
}
?>