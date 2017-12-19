<?php
class ModelCheckoutSales extends Model {
	public function addSalesRecord($order_id, $salesman, $cid) {
      	$this->db->query("INSERT INTO td_sales SET order_id = '" . $order_id . "',  username = '" . $salesman."', customer_id = '" . $cid . "', date_added = NOW()");

	}
}
?>