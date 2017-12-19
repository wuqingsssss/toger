<?php
class ModelSaleEnquiry extends Model {
	public function deleteEnquiry($enquiry_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry WHERE enquiry_id = '" . (int)$enquiry_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry_product WHERE enquiry_id = '" . (int)$enquiry_id . "'");
	}
	
	
	public function getEnquiry($enquiry_id){
		$sql="SELECT * FROM " . DB_PREFIX . "enquiry WHERE enquiry_id = '" . (int)$enquiry_id . "'";
		
		$query=$this->db->query($sql);
		
		return $query->row;
	}
	
	public function getEnquiryProducts($enquiry_id){
		$sql="SELECT * FROM " . DB_PREFIX . "enquiry_product WHERE enquiry_id = '" . (int)$enquiry_id . "'";
		
		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalEnquiries($data=array()){
		$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry";
		
		$query=$this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getEnquiries($data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry";	
			
		$sql .= " ORDER BY enquiry_id";
		
		$sql .= " DESC";
		

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
}
?>