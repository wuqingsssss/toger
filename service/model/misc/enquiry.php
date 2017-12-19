<?php
class ModelMiscEnquiry extends Model {
	public function addEnquiry($data) {
		$sql="INSERT INTO " . DB_PREFIX . "enquiry SET name='".$this->db->escape($data['name'])."',telephone='".$this->db->escape($data['telephone'])."',description='".$this->db->escape($data['description'])."',date_added=NOW()";
				
		$this->db->query($sql);
		
		$enquiry_id=$this->db->getLastId();
		
		if(isset($data['product'])){
			foreach($data['product'] as $product){
				$this->addEnquiryProduct($enquiry_id,$product);
			}
		}
	}
	
	private function addEnquiryProduct($enquiry_id,$product){
		$sql="INSERT INTO " . DB_PREFIX . "enquiry_product SET product='".$this->db->escape($product['name'])
		."',price='".$this->db->escape($product['price'])
		."',unit='".$this->db->escape($product['unit'])
		."',quantity='".$this->db->escape($product['quantity'])."',enquiry_id=".(int)$enquiry_id;
				
		$this->db->query($sql);
	}
	
}
?>