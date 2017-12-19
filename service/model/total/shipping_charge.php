<?php
class ModelTotalShippingCharge extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
	    if ($this->cart->hasShipping()) {
    		$this->load->language('total/shipping_charge');
    		
    		$step      = intval($this->config->get('shipping_charge_step'));
			$new_step  = intval($this->config->get('shipping_new_step'));//新用户免运费标准
			$new_value     = intval($this->config->get('shipping_new_value'));//新用户运费
    		$value     = intval($this->config->get('shipping_charge_value'));
    		$text      = '';
    		
    		//$sub_total = $this->cart->getSubTotal();
    		if( $this->customer->getCustomerGroupId() == 7 ) {
				if($new_step - $total['total'] < EPSILON){
					$shipping_charge = 0;
				}else{
					$shipping_charge = $new_value;
					$text   = sprintf($this->language->get('text_charge_free'), $new_step);
				}
    		}
    		else if($step - $total['total'] < EPSILON) {
    		    $shipping_charge = 0;
    		}
    		else {
    		    $shipping_charge = $value;
    		    $text   = sprintf($this->language->get('text_charge_free'), $step);
    		}
    		
    		
    		$total_data[] = array( 
    			'code'       => 'shipping_charge',
    			'title'      => $this->language->get('text_shipping_charge'). $text,
    			'text'       => $this->currency->format($shipping_charge),
    			'value'      => $shipping_charge,
    			'sort_order' => $this->config->get('shipping_charge_sort_order')
    		);
    		
    		$total['fee'] += $shipping_charge; 
    		$total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
    	}
	}
}
?>