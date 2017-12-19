<?php
class ModelCatalogInformationGroup extends Model {
	public function getInformationGroup($information_group_id) {
		$sql="SELECT DISTINCT * FROM " . DB_PREFIX . "information_group ig LEFT JOIN " . DB_PREFIX 
		. "information_group_description igd ON (ig.information_group_id = igd.information_group_id) WHERE ig.information_group_id = '" . (int)$information_group_id 
		. "' AND igd.language_id = '" . (int)$this->config->get('config_language_id') 
		. "' AND ig.status = '1'";
		
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	
	public function getInformationLayoutId($information_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . (int)$information_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_information');
		}
	}	
}
?>