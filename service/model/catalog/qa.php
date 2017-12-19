<?php
class ModelCatalogQa extends Model {
	public function addQ($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "qa SET customer = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', question = '" . $this->db->escape(strip_tags($data['text'])) . "', date_added = NOW()");
	}

	public function getQByProductId($product_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT *, DATE_FORMAT(date_added, '%Y/%m/%d') as d, DATE_FORMAT(date_modified, '%Y/%m/%d') as d2 FROM " . DB_PREFIX . "qa WHERE product_id = '" . (int)$product_id . "' AND status = '1' ORDER BY qa_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getArchieve($product_id) {
		$query = $this->db->query("SELECT *, DATE_FORMAT(date_added, '%Y/%m/%d') as d, DATE_FORMAT(date_modified, '%Y/%m/%d') as d2 FROM " . DB_PREFIX . "qa WHERE product_id = '" . (int)$product_id . "' and status=1 order by qa_id desc ");

		return $query->rows;
	}

	public function getTotalQsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa WHERE product_id = '" . (int)$product_id . "' AND status = '1' ");

		return $query->row['total'];
	}
}
?>
