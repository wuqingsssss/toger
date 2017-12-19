<?php
class ModelTotalLowOrderFee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		
		$sub_total=$this->cart->getSubTotal(); 
		if ($sub_total && ($sub_total['total'] < $this->config->get('low_order_fee_total'))) {
			$this->load->language('total/low_order_fee');
			$total_data[] = array( 
				'code'       => 'low_order_fee',
        		'title'      => $this->language->get('text_low_order_fee'),
        		'text'       => $this->currency->format($this->config->get('low_order_fee_fee')),
        		'value'      => $this->config->get('low_order_fee_fee'),
				'sort_order' => $this->config->get('low_order_fee_sort_order')
			);
			
			if ($this->config->get('low_order_fee_tax_class_id')) {
				if (!isset($taxes[$this->config->get('low_order_fee_tax_class_id')])) {
					$taxes[$this->config->get('low_order_fee_tax_class_id')] = $this->config->get('low_order_fee_fee') / 100 * $this->tax->getRate($this->config->get('low_order_fee_tax_class_id'));
				} else {
					$taxes[$this->config->get('low_order_fee_tax_class_id')] += $this->config->get('low_order_fee_fee') / 100 * $this->tax->getRate($this->config->get('low_order_fee_tax_class_id'));
				}
			}
			
			$total['fee'] += $this->config->get('low_order_fee_fee');
			$total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
		}
	}
}
?>