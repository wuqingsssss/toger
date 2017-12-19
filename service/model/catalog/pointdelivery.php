<?php
class ModelCatalogPointDelivery extends Model {
	
	public function getDelivery($delivery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."Point_Delivery WHERE delivery_id = '" . (int)$delivery_id . "' AND status=1");
		
		return $query->row;
	}
	
	public function getDeliveryByName($shippingcode,$shipping_data) {

		$sql = "SELECT * FROM " . DB_PREFIX . "point_delivery where status=1 ";	
		$sql.=" AND  code='".$shippingcode."'";
		$sql.=" AND region_name='".$shipping_data."'";

		$query=$this->db->query($sql,false);
		return $query->row;
	}
	
	public function getDeliverys($shippingcode='',$shipping_data='',$p_delivery_id=0){
		$sql = "SELECT * FROM " . DB_PREFIX . "point_delivery where status=1 AND p_delivery_id='$p_delivery_id' ";
		
		if($shippingcode)$sql.=" AND  code='".$shippingcode."'";
		if($shipping_data)$sql.=" AND region_name='".$shipping_data."'";
		
		$sql.=" ORDER BY p_delivery_id DESC";

		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	public function getTotalPoints($shippingcode='',$shipping_data='') {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "point where status=1 ";
		if($shippingcode)$sql.=" AND  code='".$shippingcode."'";
		if($shipping_data)$sql.=" AND region_name='".$shipping_data."'";
		
      	$query = $this->db->query($sql);
      	
		return $query->row['total'];
	}	
	public function updatePointDelivery($area,$code) {
	
		$this->db->query("UPDATE " . DB_PREFIX . "point_delivery SET region_id = '" . (int)$area['region_id'] . "',region_coord = '" . $area['region_coord'] . "' WHERE zone_name='".$area['city']."' AND region_name = '" . $area['region_name'] . "' AND code='{$code}'");
	
	}
	
}