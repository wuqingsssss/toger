<?php
class ModelAccountTransaction extends Model {	
	public function getTransactions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "'";
		   
		$sort_data = array(
			'amount',
			'description',
			'date_added'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
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
		
	public function getTotalTransactions() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
		return $query->row['total'];
	}	
			
	public function getTotalAmount() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "' GROUP BY customer_id");
		
		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
	
	public function getChargeProducts(){
	    $sql = "SELECT tp.price, tp.product_id, tp.sku, tt.value  
	            FROM ts_product tp 
	            LEFT JOIN ts_product_trans_code tpc ON tpc.product_id = tp.product_id  
	            LEFT JOIN ts_trans_code tt ON tt.trans_id = tpc.trans_code_id 
	            WHERE tp.`status`=1 
                AND tp.prod_type =2 
                AND date(NOW())>=date_available 
                AND date(NOW())<=date_unavailable";
	    $query = $this->db->query($sql);
	    
	    return $query->rows;
	}
	
	
	public function getChargeProduct($product_id){
	    $sql = "SELECT tp.price, tp.product_id, tp.sku, tt.value,  tpd.`name`, tp.prod_type, tp.shipping 
	            FROM ts_product tp
	            LEFT JOIN ts_product_trans_code tpc ON tpc.product_id = tp.product_id
	            LEFT JOIN ts_trans_code tt ON tt.trans_id = tpc.trans_code_id 
	            LEFT JOIN ts_product_description tpd ON tpd.product_id = tp.product_id 
	            WHERE tp.`status`=1
                AND tp.prod_type =2 
	            AND tp.product_id = {$product_id} 
                AND date(NOW())>=date_available
                AND date(NOW())<=date_unavailable 
                LIMIT 1";
	    $query = $this->db->query($sql);
	     
	    return $query->row;
	}
}

?>