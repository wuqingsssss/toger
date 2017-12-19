<?php 
class ModelPaymentAlipayBank extends Model {
  	public function getMethod($address,$total=0) {
		$this->load->language('payment/alipay');
		
		if ($this->config->get('alipay_status') && $total > 0) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'         => 'alipaybank',
        		'title'      => $this->language->get('text_title_1'),
				'sort_order' => 5
      		);
      		
    	}
	
    	return $method_data;
  	}
}
?>