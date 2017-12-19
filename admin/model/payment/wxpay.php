<?php 
class ModelPaymentWxpay extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/wxpay');
		
		if ($this->config->get('wxpay_status')) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'wxpay',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('wxpay_sort_order')
      		);
    	}
	
    	return $method_data;
  	}
}
?>