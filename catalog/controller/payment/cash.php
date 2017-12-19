<?php
class ControllerPaymentCash extends Controller {
	protected function index($setting) {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');
		
		if(isset($setting['order_id'])&&$setting['order_id'])
		{
		    $this->data['order_id'] = $setting['order_id'];
		}else
		{
		    $this->data['order_id'] = $this->session->data['order_id'];
		}
		
		
		if(isset($this->session->data['checkout_token']))
			$this->data['token'] =$this->session->data['checkout_token'];
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cash.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cash.tpl';
		} else {
			$this->template = 'default/template/payment/cash.tpl';
		}	
		
		$this->render();
	}
	
	/**
	 *  确认回调
	 */
	public function confirm() {
	    if( isset($this->request->get['order_id'])){
	        $order_id = $this->request->get['order_id'];
	    }
	    else {
	        return  false;
	    }
	    
	    if( isset($this->request->get['salesman'])){
	        $salesman = $this->request->get['salesman'];
	    }
	    else {
	        return  false;
	    }
	    
		$this->load->model('checkout/order');
		$ret = $this->model_checkout_order->confirm($order_id, $this->config->get('cash_order_status_id'));
		
		if(ret) {
    		$this->load->model('checkout/sales');
    		$this->model_checkout_sales->addSalesRecord($order_id, $salesman, $this->customer->getId());
    		return  true;
		}
		else{
		    return  false;
		}
	}
	
	/**
	 * 密码校验
	 */
	public function checkSalesPerson() {
	    $result = array();
	    $username = $this->request->post['username'];
	    $password = $this->request->post['password'];

	    $this->load->model('user/salesman');
	    if($this->model_user_salesman->checkSalesman($username, $password, (int)$this->config->get('cash_user_group_id')) == false)
	    {
	        $result['error']  = 'TRUE';
	    }
	    else {
	        $this->session->data['salesman'] = $username;
	    }
	    
	    echo json_encode($result);
	}
}
?>