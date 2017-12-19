<?php
class ModelSaleOrderDiscount extends Model {
	public function addOrderDiscount($order_id,$data){
		$sql="INSERT INTO `" . DB_PREFIX . "order_discount` SET order_id='".$this->db->escape($order_id)
		."',total=".(float)$data['discount']
		.",comment='".$this->db->escape($data['comment'])."',date_added=NOW()";
		
		$this->db->query($sql);
		
		return $this->db->getLastId();
		
	}
	
	public function deleteOrderDiscount($order_discount_id){
		$sql="DELETE FROM　`" . DB_PREFIX . "order_discount`　WHERE order_discount_id=".(int)$order_discount_id;
		
		$this->db->query($sql);
	}
	
	public function getDiscountHistories($order_id){
		$sql="SELECT * FROM `" . DB_PREFIX ."order_discount` WHERE order_id='" . $order_id . "'";
		
		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalDiscountHistories($order_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_discount WHERE order_id = '" . $order_id . "'");

		return $query->row['total'];
	}	
	
	public function addDiscountOrderTotal($data){
		$sql="INSERT INTO " . DB_PREFIX 
		. "order_total SET order_id = '" . $data['order_id'] 
		. "', code = '" . $this->db->escape($data['code']) 
		. "', title = '" . $this->db->escape($data['title']) 
		. "', text = '" . $this->db->escape($data['text']) 
		. "', value = '" . (float)$data['value'] 
		. "', sort_order = 8";
		
		$this->db->query($sql);		
	}
	
	public function getDiscountOrderTotalValue($data){
		$sql="SELECT * FROM " . DB_PREFIX 
		. "order_total WHERE order_id = '" . $data['order_id'] 
		. "' AND code = '" . $this->db->escape($data['code']) 
		. "'";
		
		$query=$this->db->query($sql);	
		
		if($query->row){
			return $query->row['value'];
		}else{
			return 0;
		}
	}
	
	public function minusOrderTotal($order_id,$discount){
		$this->db->query("UPDATE `" . DB_PREFIX ."order` SET total=(total-".(float)$discount.") WHERE order_id='".$order_id."'");
		
		$sql="SELECT total FROM `" . DB_PREFIX ."order` WHERE  order_id='".$order_id."'";
		
		$query=$this->db->query($sql);
		
		if($query->row){
			$total=$query->row['total'];
			
			$total_format=$this->currency->format($total);
			
			$this->db->query("UPDATE `" . DB_PREFIX ."order_total` SET value=".(float)$total.",text='".$this->db->escape($total_format)."' WHERE order_id='".$order_id."' AND code='total'");
		}
	}
	
	public function removeOrderDiscount($order_id){
		$sql="DELETE FROM `" . DB_PREFIX . "order_discount` WHERE order_id='".$this->db->escape($order_id)."'";
		
		$this->db->query($sql);
	}
	
	public function deleteDiscountOrderTotal($data){
		$sql="DELETE FROM " . DB_PREFIX 
		. "order_total WHERE order_id = '" . $data['order_id'] 
		. "' AND code = '" . $this->db->escape($data['code']) . "'";
		
		$this->db->query($sql);		
	}
	
	public function plusOrderTotal($order_id,$discount){
		$this->db->query("UPDATE `" . DB_PREFIX ."order` SET total=(total+".(float)$discount.") WHERE order_id='".$order_id."'");
		
		$sql="SELECT total FROM `" . DB_PREFIX ."order` WHERE  order_id='".$order_id."'";
		
		$query=$this->db->query($sql);
		
		if($query->row){
			$total=$query->row['total'];
			
			$total_format=$this->currency->format($total);
			
			$this->db->query("UPDATE `" . DB_PREFIX ."order_total` SET value=".(float)$total.",text='".$this->db->escape($total_format)."' WHERE order_id='".$order_id."' AND code='total'");
		}
	}
}
?>