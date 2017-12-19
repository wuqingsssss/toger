<?php
class ModelShippingFlat extends Model {
	function getQuote($address) {
		$this->load->language('shipping/flat');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
			if($this->cart->getSubTotal() >= $this->config->get('free_total')){
				$cost=0;
			}else{
				$cost=$this->config->get('flat_cost');
			}
			
      		$quote_data['flat'] = array(
        		'code'         => 'flat.flat',
        		'title'        => $this->language->get('text_title'),
        		'cost'         => $cost,//$this->config->get('flat_cost'),
        		'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'flat',
        		'title'      => $this->language->get('text_title'),
      			'description'  => $this->config->get('flat_description'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
        		'error'      => false
      		);
		}
			
		return $method_data;
	}
}
?>