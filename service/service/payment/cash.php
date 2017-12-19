<?php 
class ServicePaymentCash extends Service {
  	public function getMethod($address, $total) {
		$this->load->language('payment/cash');
		//$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE user_group_id = " . (int)$this->config->get('cash_user_group_id') . " AND username =" . $user['username'] . " AND password = MD5('" . $user['password'] . "') ";
		//$query = $this->db->query($sql);
	
		if ($this->config->get('cash_total') > $total) {
			$status = false;
		}
		else{
		    $status = true;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'cash',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('cash_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>