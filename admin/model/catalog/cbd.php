<?php 
class ModelCatalogCbd extends Model {
	public function addCbd($data) {
		$sql="INSERT INTO " . DB_PREFIX . "cbd SET name = '" .$this->db->escape($data['name']) . "',country_id = '" .(int)$data['country_id'] 
		. "',zone_id = '" .(int)$data['zone_id']. "',city_id = '" .(int)$data['city_id'] 
		. "',status = '" .(int)$this->db->escape($data['status']) 
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "'";
		
		$this->db->query($sql);
		
		$point_id = $this->db->getLastId();
		

		return $cbd_id;
	}
	
	
	public function editCbd($cbd_id, $data) {
		$sql="UPDATE " . DB_PREFIX . "cbd SET name = '" .$this->db->escape($data['name']) . "',country_id = '" .(int)$data['country_id'] 
		. "',zone_id = '" .(int)$data['zone_id']. "',city_id = '" .(int)$data['city_id'] 
		. "',status = '" .(int)$this->db->escape($data['status']) 
		. "',sort_order = '" .(int)$this->db->escape($data['sort_order']) . "' WHERE id = '" . (int)$cbd_id . "'";
		
		$this->db->query($sql);

	}
	
	public function deleteCbd($cbd_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cbd WHERE id = '" . (int)$cbd_id . "'");
		
	
	}
		
	public function getCbd($cbd_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cbd WHERE id = '" . (int)$cbd_id . "'");
		
		return $query->row;
	}
	
	public function getCbds($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cbd";
			
		$sort_data = array(
			
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY id";	
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

	
	public function getTotalCbds() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cbd");
		
		return $query->row['total'];
	}	
}
?>