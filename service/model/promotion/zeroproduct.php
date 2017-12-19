<?php
class ModelPromotionZeroproduct extends Model {
	
	/**
	 * 获取促销产品关系信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */	
	public function getPromotionProductInfo($data)
	{
		if(isset($data['product_id'])&&isset($data['pr_id'])&&!is_null($data['product_id'])&&!is_null($data['pr_id']))
		{
			$sql = "SELECT * FROM ".DB_PREFIX."pr_to_product  WHERE product_id='".(int)$data['product_id']."' AND pr_id ='".(int)$data['pr_id']."'";
			$query  = $this->db->query($sql);
			return $query->row;
		}
		return false;
		
	}

	public function getProduct($product_id,$pr_id) {
	if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$sql="SELECT DISTINCT *,  pd.name AS name,pd.subtitle AS subtitle,pd.storage AS storage,pd.unit AS unit,pd.origin AS origin,pd.delivery AS delivery,p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)    WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$query = $this->db->query($sql);
		$icons=$this->getProductToTags($product_id);
		
		if ($query->num_rows) {

			$product_data= array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'icons'             => $icons,
				'description'      => $query->row['description'],
				'meta_title'	   => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'subtitle'     => $query->row['subtitle'],
				'origin'     => $query->row['origin'],
				'unit'     => $query->row['unit'],
				'storage'     => $query->row['storage'],
				'delivery'     => $query->row['delivery'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => (int)$query->row['rating'],
				'reviews'          => $query->row['reviews'],
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'prod_type'           => $query->row['prod_type'],
				'donation'           => $query->row['donation'],
				'size'           => $query->row['size'],
				'delivery_time'           => $query->row['delivery_time'],
			);
			
			$data = array(
				'pr_id' =>$pr_id,
				'product_id'=>$product_id,
			);
			$promotionProductInfo = $this->getPromotionProductInfo($data);
			if($promotionProductInfo)
			{
				$info = array(
				'end_date'     =>$promotionProductInfo['end_date'],
			    'start_date'     =>$promotionProductInfo['start_date'],
				'use_quantity'     =>$promotionProductInfo['use_quantity'],
				'buy_quantity'     =>$promotionProductInfo['buy_quantity'],
				);
				$product_data = array_merge($info,$product_data);
			}
			return $product_data;
		} else {
			return false;
		}
	}
	public function getProducts($data = array(),$pr_id,$type) {
		if ($data) {
			$sql = "SELECT DISTINCT p.product_id AS upid, p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  left join ".DB_PREFIX."pr_to_product ptp on ( ptp.product_id=p.product_id) ";

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

			if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])&&$data['filter_category_id']!='') {
				$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
			}
			
//			$sql .= " AND ptp.start_date <=now() AND ptp.end_date >=now()";
			if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
			{
				$sql .="  AND p.product_id in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' and pb.start_time<NOW() and pb.end_time>=now() )";
			}
			else if(isset($pr_id)&& !is_null($pr_id)&&(!is_null($type)))
			{
				$sql .="  AND p.product_id not in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' and pb.start_time<NOW() and pb.end_time>=now() )";
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
	 * 获取满足条件的产品信息
	 * @param unknown_type $data
	 */
	public function getTotalProducts($data = array(),$pr_id,$type) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) left join ".DB_PREFIX."pr_to_product ptp  on ( ptp.product_id=p.product_id) ";

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

		if (isset($data['filter_category_id'])&& !is_null($data['filter_category_id'])) {
			$sql .= " AND ( p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			$sql .= " OR  p2c.category_id IN ( SELECT category_id FROM " . DB_PREFIX . "category  WHERE parent_id='" . (int)$data['filter_category_id'] . "' ))";
		}
//		$sql .= " AND ptp.start_date <=now() AND ptp.end_date >=now()";
		if(isset($pr_id)&& !is_null($pr_id)&&(is_null($type)))
		{
			$sql .="  AND p.product_id in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' and pb.start_time<NOW() and pb.end_time>=now() )";
		}
		else if(isset($pr_id)&& !is_null($pr_id)&&(!is_null($type)))
		{
			$sql .="  AND p.product_id not in( SELECT ptp.product_id FROM  ".DB_PREFIX."pr_to_product ptp LEFT JOIN ".DB_PREFIX."p_rule pr on (pr.pr_id=ptp.pr_id) LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_id='".(int)$pr_id."' and pb.start_time<NOW() and pb.end_time>=now() )";
		}	
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getProductToTags($product_id) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_tag WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$data[] = $result['tag_id'];
		}

		return $data;
	}
	
	
	public function getRuleProductByCode($pr_code)
	{
		if(isset($pr_code))
		{
			$sql = "SELECT pr.* FROM  ".DB_PREFIX."p_rule pr  LEFT JOIN ".DB_PREFIX."p_basic pb ON (pb.pb_id=pr.pb_id) WHERE pb.status='0' AND pr.pr_code='".$this->db->escape(mb_strtolower($pr_code, 'UTF-8'))."'";
			$query = $this->db->query($sql);
			return $query->row;
		}
		return false;
	}
	
	
	public function updateRuleProductBuy($data)
	{
		if(isset($data['pr_id'])&&isset($data['product_id'])&&isset($data['buyQuantity']))
		{
			$sql = "UPDATE ".DB_PREFIX."pr_to_product ptp SET ptp.buy_quantity ='".(int)$data['buyQuantity']."' WHERE ptp.pr_id ='".(int)$data['pr_id']."' AND ptp.product_id ='".(int)$data['product_id']."'";
			$this->db->query($sql);
			return true;
		}
		return false;
		
	}
	
	public function havePayZeroBuy($rule_code,$customer_id)
	{
		$sql = "select count(*) as total from ( SELECT o.*,op.product_id FROM ".DB_PREFIX."order_product op left join ".DB_PREFIX."order o on (op.order_id=o.order_id)) oo  right join (select distinct ptp.product_id from ".DB_PREFIX."p_rule pr left join ".DB_PREFIX."pr_to_product ptp on (ptp.pr_id=pr.pr_id) left join ".DB_PREFIX."p_basic pb on (pb.pb_id=pr.pb_id) where pr.pr_code ='".$rule_code."' and pb.end_time >NOW() and pb.start_time <=NOW() ) ppt  on (oo.product_id in (ppt.product_id)) where oo.order_status_id='2' and oo.customer_id = '".(int)$customer_id."'";
		
		$query = $this->db->query($sql);
		
		$counts = $query->row['total'];
		if(isset($counts)&&((int)$counts)>0)
		{
			return true;
		}
		return false;
	}
	
}
?>