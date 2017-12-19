<?php
class ControllerPaymentBalance extends Controller {
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
	    
	    $this->data['transaction_total'] = $this->currency->format($this->customer->getBalance());
	    
	    
	    if(isset($this->session->data['checkout_token']))
	        $this->data['token'] =$this->session->data['checkout_token'];
	    
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/balance.tpl')) {
	        $this->template = $this->config->get('config_template') . '/template/payment/balance.tpl';
	    } else {
	        $this->template = 'default/template/payment/balance.tpl';
	    }
	    
	    $this->render();
	}
	
	/**
	 * 确认订单
	 */
	public function confirm() {
	    if( isset($this->request->get['order_id'])){
	        $order_id = $this->request->get['order_id'];
	    }
	    else {
	        return  false;
	    }
	    
        $this->load->service('payment/balance');
        $this->service_payment_balance->check_money_enough($this->customer->getId(),$order_id);
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($order_id, $this->config->get('cash_order_status_id'));
        $json['error']="false";
        $json['msg']='';
        echo json_encode($json);
        //$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('balance_order_status_id'));

        /*
		$this->load->model('checkout/sales');
		$this->model_checkout_sales->addSalesRecord($this->session->data['order_id'], $this->session->data['salesman'] , $this->customer->getId());

        */

        }
	
        /*
	public function checkSalesPerson() {
	    $result = array();
	    $username = $this->request->post['username'];
	    $password = $this->request->post['password'];

	    $this->load->model('user/salesman');
	    if(!$this->model_user_salesman->checkuserpassword($username, $password))
	    {
	        $result['error']  = 'TRUE';
	    }
	    else {
	        $result['error']  = 'TRUE';
	        $this->session->data['salesman'] = $username;
	    }
	    
	    echo json_encode($result);
	}*/
	
	/**
	 * 验证用户
	 */
	public function validate() {
	    $result = array();
	    $password = $this->request->post['password'];
	
	    $this->load->service('payment/balance');
	    if(!$this->service_payment_balance->checkPassword($this->customer->getId(), $password))
	    {
	        $result['error']  = 'TRUE';
	    }
//	    else {
//	        $this->session->data['salesman'] = $username;
//	    }
	     
	    echo json_encode($result);
	}
}
?>