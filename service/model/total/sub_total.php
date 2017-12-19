<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/sub_total');
		if($this->session->data['platformpparam']['sub_total'])
		{  
			$sub_total=$this->session->data['platformpparam']['sub_total'];
		}
			else {
		$sub_total = $this->cart->getSubTotal();
		
		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total['fee'] += $voucher['amount'];
			}
		}}

		$total['promotion'] += $sub_total['promotion'];
		$total['general']   += $sub_total['general'];
		$total['fee']       += $sub_total['fee'];
		$total['total']=$total['promotion']+$total['general']+$total['fee']-$total['discount'];
		
		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($total['total']),
			'value'      => $total['total'],
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
	}
}
?>