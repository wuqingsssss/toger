<?php
class ModelCatalogLink extends Model {
	public function addLink($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX 
      	. "link SET uri = '" . $this->db->escape($data['uri']) 
      	."',image='".$this->db->escape($data['image'])
      	."',sort_order='".(int)$data['sort_order']
      	."',status='".(int)$data['status']."', date_added = NOW(),date_modified=NOW()");

      	$link_id = $this->db->getLastId(); 

      	foreach ($data['link_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "link_description SET link_id = '" . (int)$link_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "',description = '" . $this->db->escape($value['description']) . "'");
      	}	
      	
      	if(isset($data['link_group'])){
      		foreach($data['link_group'] as $link_group_id){
      			$this->addLinkToGroup($link_id,$link_group_id);
      		}
      	}
      	
      	$this->cache->delete('link');
	}
	
	
	
	public function editLink($link_id, $data) {
        $query=$this->db->query("update " . DB_PREFIX . "link set uri = '" . $this->db->escape($data['uri']) 
      	."',image='".$this->db->escape($data['image'])
      	."',sort_order='".(int)$data['sort_order']
      	."',status='".(int)$data['status']."',date_modified=NOW() where link_id=".(int)$link_id);

      	$this->db->query("DELETE FROM " . DB_PREFIX . "link_description WHERE link_id = '" . (int)$link_id . "'");

      	foreach ($data['link_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "link_description SET link_id = '" . (int)$link_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "',description = '" . $this->db->escape($value['description']) . "'");
      	}
      	
      	$this->deleteLinkGroups($link_id);
      	
		if(isset($data['link_group'])){
      		foreach($data['link_group'] as $link_group_id){
      			$this->addLinkToGroup($link_id,$link_group_id);
      		}
      	}
      	
      	$this->cache->delete('link');	
	}
	
	public function deleteLink($link_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "link WHERE link_id = '" . (int)$link_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "link_description WHERE link_id = '" . (int)$link_id . "'");	
	  	
	  	$this->deleteLinkGroups($link_id);
	  	
	  	$this->cache->delete('link');
	}	

	public function getLink($link_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "link WHERE link_id = '" . (int)$link_id . "'");
		
		return $query->row;
	}

	public function getLinks($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "link l LEFT JOIN " . 
		DB_PREFIX . "link_description ld ON (l.link_id = ld.link_id) WHERE ld.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		$sort_data = array(
			'ld.name',
			'l.sort_order',
			'l.status'
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
	
	public function getLinkDescriptions($link_id) {
		$link_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "link_description WHERE link_id = '" . (int)$link_id . "'");
		
		foreach ($query->rows as $result) {
			$link_description_data[$result['language_id']] = array('name' => $result['name'],'description'=>$result['description']);
		}
		
		return $link_description_data;
	}
	
	public function getTotalLinks() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "link");
		
		return $query->row['total'];
	}	
	
	public function getLinkGroups($link_id) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "link_to_group WHERE link_id = '" . (int)$link_id . "'");

		foreach ($query->rows as $result) {
			$data[] = $result['link_group_id'];
		}

		return $data;
	}
	
	private function addLinkToGroup($link_id,$link_group_id){
		$sql="INSERT INTO " . DB_PREFIX . "link_to_group SET link_id=".(int)$link_id.",link_group_id=".(int)$link_group_id;
		
		$this->db->query($sql);
	}
	
	private function deleteLinkGroups($link_id){
		$sql="DELETE FROM " . DB_PREFIX . "link_to_group WHERE link_id=".(int)$link_id;
		
		$this->db->query($sql);
	}
}
?>