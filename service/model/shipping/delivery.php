<?php
class ModelShippingDelivery extends Model {
	function getQuote() {
		$this->load->language('shipping/delivery');
		
		
		$status = true;

		$method_data = array();

		if ($status) {
			$quote_data = array();

			/*
			$expresses = $this->config->get('delivery_express');			
			foreach ($expresses as $express) {
				if($expresses['status']){
					$quote_data['delivery_'.$delivery['sort_order']] = array(
						   'code'         => 'delivery.delivery_'.$addr['sort_order'],
						   'title'        => $delivery['title'].'[ '.$delivery['addr'].' ]',
						   'cost'         => 0.00,
						   'tax_class_id' => 0,
						   'text'         => ''//$this->currency->format(0.00)
					);
				}
			}
            */

            $this->load->model('account/address');
            
		    $addresses=$this->model_account_address->getAddresses();
		    
			foreach($addresses as $address){
					$quote_data['delivery_'.$address['address_id']] = array(
						   'address_id'   => $address['address_id'],
						   'code'         => 'delivery.'.$address['shipping_data'].'_'.$address['shipping_code'],
						   'title'        => $address['name'].'[ '.$address['address'].' ]',
						   'name'         => $address['name'],
						   'address'      => $address['address'],
						   'cost'         => 0.00,
						   'tax_class_id' => 0,
						   'text'         => ''//$this->currency->format(0.00)
					);
			}



			$method_data = array(
        		'code'       => 'pickupaddr',
        		'title'      => $this->language->get('text_title'),
      			'description'  => $this->config->get('addr_description'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('addr_sort_order'),
        		'error'      => false
			);
		}

		return $method_data;
	}
	

}
?>