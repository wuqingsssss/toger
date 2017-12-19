<?php 
class ModelSettingSetting extends Model {
	public function getSetting($group, $store_id = 0) {
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
		
		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}

		return $data;
	}
	
	public function editSetting($group, $data, $store_id = 0) {
		$sql.="DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "';";
		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$sql.="INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "';";
			} else {
				if(isset($value[0]['sort_order'])){
				  foreach ($value as $key2 => $item) {
					$sort_order[$key2] = $item['sort_order'];
				   }
				   array_multisort($sort_order, SORT_ASC, $value);
				}
				
				
				
				$sql.="INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1';";
			}
		}
		$this->db->multi_query($sql);
	}
	
	public function updateSetting($group, $data, $store_id = 0) {
		
		$sql='';
		
		$key_array = array_keys($data);
		$sql.="DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "' AND `key` in ('" .implode("','", $key_array) . "');";
		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$sql.="INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "';";
			} else {
				if(isset($value[0]['sort_order'])){
					foreach ($value as $key2 => $item) {
						$sort_order[$key2] = $item['sort_order'];
					}
					array_multisort($sort_order, SORT_ASC, $value);
				}
				
				$sql.="INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1';";
			}
		}
	
		$this->db->multi_query($sql);
	}
	
	public function deleteSetting($group, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	}
}
?>