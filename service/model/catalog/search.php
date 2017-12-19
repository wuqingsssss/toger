<?php
class ModelCatalogSearch extends Model {
	
	public function getFeaturedKeyword($limit=10){
        $sql = "SELECT term,COUNT(term) AS total FROM " . DB_PREFIX . "report_search WHERE result !='' AND term !='' GROUP BY term ORDER BY total DESC LIMIT 0,".$limit;
        
        $query=$this->db->query($sql);
        
        return $query->rows;
    }
}
?>