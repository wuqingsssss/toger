<?php 
class ControllerTotalInvoice extends Controller {
	public function index() {
		$this->load_language('total/invoice');
		
		$this->load->model('checkout/order');	
		
		
		$invoice_type=1;
		$invoice_head=1;
		$invoice_name='';
		$invoice_content=1;
			
		if(isset($this->session->data['order_id'])){
			$order_id=$this->session->data['order_id'];
			
			$order_info=$this->model_checkout_order->getOrder($order_id);
				
			if($order_info && $order_info['invoice_type']){
				$invoice_type=(int)$order_info['invoice_type'];
			}
			
			if($order_info && $order_info['invoice_head']){
				$invoice_head=(int)$order_info['invoice_head'];
			}
			
			if($order_info && $order_info['invoice_name']){
				$invoice_name=$order_info['invoice_name'];
			}
			
			if($order_info && $order_info['invoice_content']){
				$invoice_content=(int)$order_info['invoice_content'];
			}
		}
		
		$this->data['invoice_type']=$invoice_type;
		$this->data['invoice_head']=$invoice_head;
		$this->data['invoice_name']=$invoice_name;
		$this->data['invoice_content']=$invoice_content;
		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/invoice.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/invoice.tpl';
		} else {
			$this->template = 'default/template/total/invoice.tpl';
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
  					'invoice_type' => $this->request->post['invoince_type'],
  					'invoice_head' => $this->request->post['invoince_pttt'],
  					'invoice_name' => $this->request->post['invoice_Unit_TitName'],
  					'invoice_content' => $this->request->post['invoince_content_1']
  				);
  				
  				$this->model_checkout_order->editInvoiceDetail($order_id,$data);
  				
  				$json['success']='申请发票保存成功!';
  			}
		}
  		
	  	$this->response->setOutput(json_encode($json));		
	 }
}
?>