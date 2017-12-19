<?php
class ModelLocalisationCbd extends Model {		
	public function getCbdsByCityId($city_id) {
		$data = $this->cache->get('cbd.' . $city_id);
	
		if (!$data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cbd WHERE city_id = '" . (int)$city_id 
			. "' AND status = '1' ORDER BY  convert(name using gb2312) ");
	
			$query = $query->rows;
			
			$this->cache->set('cbd.' . $city_id, $data);
		}
	
		return $query;
	}
}
?>