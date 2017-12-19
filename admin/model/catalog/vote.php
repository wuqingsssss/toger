<?php
class ModelCatalogVote extends Model {
	//vote user info
	public function insertVoteUserInfo($data)
	{
		$sql = "INSERT INTO ".DB_PREFIX . "product_vote_info SET product_id = '".$this->db->escape($data['product_id'])."'"
		." , vote_user_id = '".$this->db->escape($data['vote_user_id'])."'"
		." , vote_user_ip = '".$this->db->escape($data['vote_user_ip'])."'"
		." , vote_user_mac = '".$this->db->escape($data['vote_user_mac'])."'"
		." , vote_num = '".$this->db->escape($data['vote_num'])."'"
		." , date_added = NOW()"
		;
		$this->db->query($sql);
		
		return $this->getTotalVoteCounts($data);
	}
	
	public function clearVoteInfo($product_id){
		$sql = "delete from ".DB_PREFIX . "product_vote_info where product_id='".$this->db->escape($product_id)."'";
		if(isset($product_id))
		{
			$this->db->query($sql);
		}
	}
	
	public function deleteAllVoteInfo(){
		$sql = "delete from ".DB_PREFIX . "product_vote_info";
		$this->db->query($sql);
	}
	
	public function getTotalVoteCounts($data)
	{
		$sql = "select count(*) as total from  ".DB_PREFIX . "product_vote_info where product_id = '".$this->db->escape($data['product_id'])."'";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function checkHasVoted($data)
	{
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX . "product_vote_info where  product_id= '".$this->db->escape($data['product_id'])."'";
		
		if(isset($data['vote_user_id']))
		{
			$sql .= " AND vote_user_id = '".$this->db->escape($data['vote_user_id'])."'";
		}
		else{
			if(isset($data['vote_user_ip']))
			{
				$sql .= " AND vote_user_ip = '".$this->db->escape($data['vote_user_ip'])."'";
			}
			if(isset($data['vote_user_mac']))
			{
				$sql .= " AND vote_user_mac = '".$this->db->escape($data['vote_user_mac'])."'";
			}
		}
		$query = $this->db->query($sql);
		if (isset($query->row['total'])) {
		   return true;
	    } else {
	  	   return false;
	    }	
	}
	
	
	private function getProductToTags($product_id){
		$sql="SELECT * FROM " . DB_PREFIX . "product_to_tag WHERE product_id=".(int)$product_id;
		
		$query=$this->db->query($sql);
		
		$tags=array();
		
		foreach($query->rows as $result){
			$tags[]=$result['tag_id'];
		}
		
		return $tags;
	}

	public function getProduct($product_id) {
		
		$sql="SELECT DISTINCT *, pd.name AS name,pd.subtitle AS subtitle,pd.storage AS storage,pd.unit AS unit,pd.origin AS origin,pd.delivery AS delivery,p.image, m.name AS manufacturer, (select count(*) from ".DB_PREFIX."product_vote_info pvi2 where pvi2.product_id=p.product_id) as voted_good_num , (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$query = $this->db->query($sql);
		
		$icons=$this->getProductToTags($product_id);
		
		if ($query->num_rows) {

			$product_data= array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'voted_good_num'             => $query->row['voted_good_num'],
				'grouding'             => $query->row['gounding'],
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
				'delivery_time'           => $query->row['delivery_time']
			);
			
			return $product_data;
		} else {
			return false;
		}
	}
	
	public function getVoteProductIds($data) {
		$sql="SELECT p.product_id, (SELECT COUNT(*) FROM ".DB_PREFIX."product_vote_info pvi2 WHERE pvi2.product_id=p.product_id) AS voted_good_num FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
		
		$sql= $this->getfileterSql2($sql, $data);

		$sort_data = array(
			'pd.name',
			'p.sku',
			'voted_good_num'
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
		
		foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}	
		
		return $product_data;	
	}
	
	
	
	public function getTotalVoteProduct($data) {
		$sql="SELECT count(p.product_id) as total, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"." AND p.featured='1' AND p.status = '0' AND p.date_available <= NOW()"; 
		
		$sql= $this->getfileterSql1($sql, $data);
		
		$sql.=" ORDER BY p.sort_order DESC";
		
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	
	private function getfileterSql1($sql,$data=array())
	{
	if((isset($data['filter_date_start']) && $data['filter_date_start'])||(isset($data['filter_date_end']) && $data['filter_date_end']))
	{
		$sql="SELECT count(p.product_id) as total, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) right join ts_product_vote_info pvi ON (pvi.product_id=p.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"." AND p.featured='1' AND p.status = '0' AND p.date_available <= NOW()"; 
	}	
		
		if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$sql .= " AND DATE(pvi.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "' ";
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$sql .= " AND DATE(pvi.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "' ";
		}
		return $sql;
	}
	
	
private function getfileterSql2($sql,$data=array())
	{
	if((isset($data['filter_date_start']) && $data['filter_date_start'])||(isset($data['filter_date_end']) && $data['filter_date_end']))
	{
		$sql="SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) right join ts_product_vote_info pvi ON (pvi.product_id=p.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"." AND p.featured='1' AND p.status = '0' AND p.date_available <= NOW()"; 
	}	
		
	if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$sql .= " AND DATE(pvi.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "' ";
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$sql .= " AND DATE(pvi.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "' ";
		}
		
		return $sql;
		
	}
	private function getFilterSql($sql,$data=array()){
		if (isset($data['start'])) {
			if (isset($data['limit']) && $data['limit']) {
				$sql.=" LIMIT " . (int)$data['start'] . ",". (int)$data['limit'];
			}
		}
		return $sql;
	}

}
?>