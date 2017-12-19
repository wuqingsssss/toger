<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language ( 'total/total' );

			$total ['total'] = $total ['promotion'] + $total ['general'] + $total ['fee'] - $total ['discount'];

		$total_data [] = array (
				'code' => 'total',
				'title' => $this->language->get ( 'text_total' ),
				'text' => $this->currency->format ( max ( 0, $total ['total'] ) ),
				'value' => max ( 0, $total ['total'] ),
				'sort_order' => $this->config->get ( 'total_sort_order' ) 
		);
	}
}
?>