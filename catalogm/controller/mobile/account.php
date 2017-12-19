<?php 
class ControllerMobileAccount extends Controller {  
	
  	public function menu(){
		$this->load_language('account/account');
		
		// 储值信息根据用户判断
		$this->load->service('payment/balance');
		if( $this->service_payment_balance->checkUser()) {
		    $this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mobile/account_menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/mobile/account_menu.tpl';
		} else {
			$this->template = 'default/template/mobile/account_menu.tpl';
		}
		
		$this->render();				
  	}
  	
	protected function getTotalOrderCount($order_status_id){
  		$sql="SELECT  COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE customer_id=".(int)$this->customer->getId()." AND order_status_id=".(int)$order_status_id;
  		
  		$query=$this->db->query($sql);
  		
  		return $query->row['total'];
  	}
}
?>