<?php
class ModelCatalogLink extends Model {
	
	public function getLinks($limit=20){
		$sql="SELECT * FROM " . DB_PREFIX . "link l LEFT JOIN " . DB_PREFIX 
		. "link_description ld ON (l.link_id = ld.link_id) WHERE  ld.language_id = '" . (int)$this->config->get('config_language_id') 
		. "' AND l.status = '1' AND l.sort_order <> '-1' ORDER BY l.sort_order DESC, LCASE(ld.name)";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
}
?>