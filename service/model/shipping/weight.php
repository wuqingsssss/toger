<?php 
class ModelShippingWeight extends Model {    
  	public function getQuote($address) {
		$this->load->language('shipping/weight');
		
		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");
	
		foreach ($query->rows as $result) {
			if ($this->config->get('weight_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		
			if ($status) {
				$cost = '';
				$weight = $this->cart->getWeight();
				
				/**
				 * 
				 * 增加重量运算的逻辑，如果填写了费用的比例，则按照费用比例计算
				 * 未填写时按照首重+续重的规则来计算
				 * 
				 * */
				if($this->config->get('weight_' . $result['geo_zone_id'] . '_rate')){
					$rates = explode(',', $this->config->get('weight_' . $result['geo_zone_id'] . '_rate'));
					
					foreach ($rates as $rate) {
						$data = explode(':', $rate);
					
						if ($data[0] >= $weight) {
							if (isset($data[1])) {
								$cost = $data[1];
							}
					
							break;
						}
					}
				}else if($this->config->get('weight_first_price')){
					$cost=$this->config->get('weight_first_price');
					
					if($this->cart->getWeight() > 1){
						$continue_weight=ceil($this->cart->getWeight()-1);
						$cost+= $continue_weight * (float)$this->config->get('weight_continued_price');
					}
				}
				
				
				if ((string)$cost != '') { 
					$quote_data['weight_' . $result['geo_zone_id']] = array(
						'code'         => 'weight.weight_' . $result['geo_zone_id'],
						'title'        => $this->language->get('text_title'). '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('weight_tax_class_id'), $this->config->get('config_tax')))
					);	
				}
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'code'       => 'weight',
        		'title'      => $this->language->get('text_title'),
      			'description'  => $this->config->get('weight_description'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('weight_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
  	}
}
?>