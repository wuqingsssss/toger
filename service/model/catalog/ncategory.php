<?php
class ModelCatalogncategory extends Model {
	public function getncategory($ncategory_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE c.ncategory_id = '" . (int)$ncategory_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getncategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		
		return $query->rows;
	}
	// added by Alex
	public function getncategoriesByStore($ncategory_id = 0,$store_id) {
		if($ncategory_id==0)
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE c.parent_id = '0'  AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$store_id. "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		else
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE  c.parent_id = '" . (int)$ncategory_id . "'  AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$store_id. "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}
	
	
	public function getncategoryByStore($ncategory_id,$store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE c.ncategory_id = '" . (int)$ncategory_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$store_id . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getncategoriesByParentId($ncategory_id) {
		$ncategory_data = array();
		
		$ncategory_query = $this->db->query("SELECT ncategory_id FROM " . DB_PREFIX . "ncategory WHERE parent_id = '" . (int)$ncategory_id . "'");
		
		foreach ($ncategory_query->rows as $ncategory) {
			$ncategory_data[] = $ncategory['ncategory_id'];
			
			$children = $this->getncategoriesByParentId($ncategory['ncategory_id']);
			
			if ($children) {
				$ncategory_data = array_merge($children, $ncategory_data);
			}			
		}
		
		return $ncategory_data;
	}
		
	public function getncategoryLayoutId($ncategory_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_ncategory');
		}
	}
					
	public function getTotalncategoriesByncategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function getArticles($ncategory_id){
		$sql="SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id)";
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "news_to_ncategory n2n ON (n.news_id = n2n.news_id)";
			
		$sql .= " WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
		
		$sql .= " AND n2n.ncategory_id = '" . (int)$ncategory_id . "'";
		
		$query = $this->db->query($sql);
		
		

		return $query->rows;
	}
}
?>