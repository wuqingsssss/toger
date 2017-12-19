<?php 
class ControllerTotalPoint extends Controller {
	public function index() {
		$this->load_language('total/point');
		
		$this->load->model('catalog/point');
		
		$point_results=$this->model_catalog_point->getPoints();
		
		$this->data['points']=array();
		
		foreach($point_results as $result){
			$this->data['points'][]=array(
				'point_id' => $result['point_id'],
				'name' => $result['name'],
				'address' => $result['address'],
				'telephone' => $result['telephone'],
			);	
		}
		
		if(isset($this->session->data['shipping_point_id'])){
			$this->data['selected_point']=$this->session->data['shipping_point_id'];
		}else{
			$this->data['selected_point']=$this->data['points'][0]['point_id']; //Assert point always exists
		}

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/point.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/point.tpl';
		} else {
			$this->template = 'default/template/total/point.tpl';
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