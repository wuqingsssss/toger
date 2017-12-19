<?php
class ControllerTestOrder extends Controller {
	public function index() {
		$this->load->model('checkout/order');
		$orderId=$this->request->get['order_id'];

		$this->load->model('checkout/order');

		$this->model_checkout_order->genSubOrder($orderId,2);
		echo 'ok';
	}
}