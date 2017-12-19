<?php
class ModelCatalogConsulation extends Model {
	public function addConsulation($data) {
		$sql="INSERT INTO " . DB_PREFIX . "product_consulation SET customer_name = '" . $this->db->escape($data['customer_name']) 
		. "', customer_id = '" . $this->db->escape($data['customer_id']) 
		. "', product_id = '" . $this->db->escape($data['product_id']) 
		. "', type = '" . (int)($data['type']) 
		. "', content = '" . $this->db->escape(strip_tags($data['content'])) 
		. "', status = '" . (int)$data['status'] . "', email_alert = '" . (int)$data['email_alert'] . "', date_added = NOW(),date_modified = NOW()";
		
		$this->db->query($sql);
	}
	
	public function editConsulation($consulation_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "product_consulation SET customer_name = '" . $this->db->escape($data['customer_name']) 
		. "', product_id = '" . $this->db->escape($data['product_id']) 
		. "', type = '" . (int)($data['type']) 
		. "', content = '" . $this->db->escape(strip_tags($data['content'])) 
		. "', reply = '" . $this->db->escape(strip_tags($data['reply'])) 
		. "', status = '" . (int)$data['status'] . "' WHERE consulation_id = '" . (int)$consulation_id . "'";
		
		$this->db->query($sql);
	}
	
	public function deleteConsulation($consulation_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_consulation WHERE consulation_id = '" . (int)$consulation_id . "'");
	}
	
	public function getConsulation($consulation_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "product_consulation r WHERE r.consulation_id = '" . (int)$consulation_id . "'");
		
		return $query->row;
	}

	public function getConsulations($data = array()) {
		$sql = "SELECT r.*, pd.name FROM " . DB_PREFIX . "product_consulation r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";																																					  
		
		$sql=$this->getFilterSql($sql,$data);
			
		$sort_data = array(
			'pd.name',
			'r.customer_name',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
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
	
	public function getTotalConsulations($data = array()) {
		$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_consulation r WHERE 1=1";
		
		$sql=$this->getFilterSql($sql,$data);
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	private function getFilterSql($sql,$data=array()){
		if (isset($data['filter_customer_id']) && !is_null($data['filter_customer_id'])) {
			$sql .= " AND r.customer_id = ".(int)$data['filter_customer_id'];
		}
		
		if (isset($data['filter_product_id']) && !is_null($data['filter_product_id'])) {
			$sql .= " AND r.product_id = ".(int)$data['filter_product_id'];
		}
		
		if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
			$sql .= " AND r.type= ".(int)$data['filter_type'];
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status']) && $data['filter_status']) {
			$sql .= " AND r.status= 1";
		}
		
		return $sql;
	}
	
	public function getTotalConsulationsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_consulation WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>