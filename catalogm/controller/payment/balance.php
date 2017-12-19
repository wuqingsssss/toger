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
	public function confirm($order_id) {
	    $ret = array();
        $this->load->model('payment/balance');
        $ret = $this->model_payment_balance->check_money_enough($this->customer->getId(),$order_id);
        
        if(!isset($ret['error'])){
    		$this->load->model('checkout/order');
    		if($this->model_checkout_order->confirmOrderPayment($order_id, 'balance', $ret['id'])){
    		    $ret['success'] = true;
    		}
    		else{
    		    $ret['success'] = false;
    		}
        }
        else {
            $this->log_payment->error($ret['msg']);
        }
        
        return $ret;
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