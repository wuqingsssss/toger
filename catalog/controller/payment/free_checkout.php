<?php
class ControllerPaymentFreeCheckout extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');

		if(isset($this->session->data['checkout_token']))
			$this->data['token'] =$this->session->data['checkout_token'];
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/free_checkout.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/free_checkout.tpl';
		} else {
            $this->template = 'default/template/payment/free_checkout.tpl';
        }
		
		$this->render();		 
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		$order_id=$this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		if($order_info['total']<=0)
		$this->model_checkout_order->confirm($order_id, $this->config->get('free_checkout_order_status_id'));
	}
}
?>