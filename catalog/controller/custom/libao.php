<?php 
class ControllerCustomLibao extends Controller {
	public function index() {
		$this->load_language('custom/libao');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/custom/libao.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/custom/libao.tpl';
		} else {
			$this->template = 'default/template/custom/libao.tpl';
		}
					
		$this->render();
  	}
  	
  	
  	public function click() {
	  	$this->load->model('checkout/order');		
	  	
	  	$json = array();
	  	
  		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
  			if(isset($this->session->data['order_id'])){
  				$order_id=$this->session->data['order_id'];
  				
  				if(isset($this->request->post['point']) && $this->request->post['point']){
  					$point_id=$this->request->post['point'];
  					
  					$this->session->data['shipping_point_id']=$point_id;
  						
  					
  					$this->model_checkout_order->editShippingPoint($order_id,$point_id);
  					
 
  					$json['success']="自提点信息保存成功!";
  				}
  			}
		}
  		
	  	$this->response->setOutput(json_encode($json));		
	 }
}
?>