<?php

class ControllerApiOrder extends Controller {
    
    private $debug = false;
    
    public function _update() {
    	if($this->_checkSid($this->request->post['api_key'])){
    		$this->load->model('sale/order');
    		$this->model_sale_order->updateOrders($this->request->post['orders']);
    		$this->m->setSuccess();
    	}else{
    		$this->m->setError('0x0016');
    	}
        if ($this->debug) {
            echo '<pre>';
            print_r($this->m->returnResult());
        } else {
            $this->response->setOutput(json_encode($this->m->returnResult()));
        }
    }
    
    public function _download() {
    	if($this->_checkSid($this->request->post['api_key'])){
    		$this->load->model('sale/order');
	    	$orders = $this->model_sale_order->populateOrders2($this->request->post['api_date_pdate'],$this->request->post['pos_id']);
	        $this->m->setSuccess($orders,null,count($orders));
    	}else{
    		$this->m->setError('0x0016');
    	}
        if ($this->debug) {
        	echo '<pre>';
        	print_r($this->m->returnResult());
        } else {
        	$this->response->setOutput(json_encode($this->m->returnResult()));
        }
       
    }
    
    private function _checkSid($api_key) {
    	if($api_key==MD5(API_KEY))
      	  return true;
    	else
    	 return false;
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "_$methodName"), $arguments);
    }
    
}

?>