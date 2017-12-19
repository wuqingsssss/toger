<?php
class ModelCatalogLinkGroup extends Model {
	public function addLinkGroup($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX 
      	. "link_group SET name = '" . $this->db->escape($data['name']) 
      	."',status='".(int)$data['status']."'");

      	$link_group_id = $this->db->getLastId();
      	
      	return $link_group_id;
	}
	
	public function editLinkGroup($link_group_id, $data) {
        $query=$this->db->query("UPDATE " . DB_PREFIX . "link_group SET name = '" . $this->db->escape($data['name']) 
      	."',status='".(int)$data['status']."' where link_group_id=".(int)$link_group_id);	
	}
	
	public function deleteLinkGroup($link_group_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "link_group WHERE link_group_id = '" . (int)$link_group_id . "'");
	}	

	public function getLinkGroup($link_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "link_group WHERE link_group_id = '" . (int)$link_group_id . "'");
		
		return $query->row;
	}

	public function getLinkGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "link_group";
	
		$sort_data = array(
			'name',
			'status'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ld.name";	
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
	
	public function getTotalLinkGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "link_group");
		
		return $query->row['total'];
	}	
	
	public function getLinkGroupOptions(){
		$sql="SELECT * FROM " . DB_PREFIX . "link_group";
		
		$query=$this->db->query($sql);
		
		$options=array();
		
		foreach($query->rows as $result){
			$options[]=array(
				'value' => $result['link_group_id'],
				'name' => $result['name']
			);
		}
		
		return $options;
	}
}
?>