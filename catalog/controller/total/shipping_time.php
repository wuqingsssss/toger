<?php 
class ControllerTotalShippingTime extends Controller {
	public function index() {
		$this->load_language('total/shipping_time');
		$this->load->model('checkout/order');		
		
		$shipping_time=1;
		$shipping_confirm=1;
			
		if(isset($this->session->data['order_id'])){
			$order_id=$this->session->data['order_id'];
			
			$order_info=$this->model_checkout_order->getOrder($order_id);
	
			if($order_info && $order_info['shipping_time']){
				$shipping_time=(int)$order_info['shipping_time'];
			}
			
			if($order_info && $order_info['shipping_confirm']){
				$shipping_confirm=(int)$order_info['shipping_confirm'];
			}
		}
		
		$this->data['shipping_time']=$shipping_time;
		$this->data['shipping_confirm']=$shipping_confirm;
				
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/shipping_time.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/shipping_time.tpl';
		} else {
			$this->template = 'default/template/total/shipping_time.tpl';
		}
					
		$this->render();
  	}
  	
  	
  	public function click() {
	  	$this->load->model('checkout/order');		
	  	
	  	$json = array();
	  	
  		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
  			if(isset($this->session->data['order_id'])){
  				$order_id=$this->session->data['order_id'];
  				
  				$data=array(
  					'shipping_time' => $this->request->post['CODTime'],
  					'shipping_confirm' => $this->request->post['isInformRad']
  				);
  				
  				$this->model_checkout_order->editShippingTime($order_id,$data);
  				
  				$json['success']="送货日期保存成功!";
  			}
		}
  		
	  	$this->response->setOutput(json_encode($json));		
	 }
}
?>