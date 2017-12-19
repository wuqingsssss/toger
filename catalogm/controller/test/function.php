<?php
class ControllerTestFunction extends Controller {  
	public function genOrderSN() {
		$common=new Common($this->registry);
		
		echo $common->genOrderSN();
	}
	
	public function date(){
		$common=new Common($this->registry);

		$order_id  = $common->genOrderSN();
		
		echo $order_id;
		
		echo '<br />';
		
		echo date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
		echo '<br />';
		echo date('Y-m-d H:i:s',strtotime('+1 day'));
		echo '<br />';
		
		echo date('Y-m-d H:i:s');
	}
	
	public function test3(){
		if(($this->request->server['REQUEST_METHOD'] == 'POST') ){
			$order_total=array();
			
			$order_total['code']='coupon';
			$order_total['title']='折扣券(test)';
			$order_total['value']='-0.0100';
			
			$order_info=array();
			
			$order_info['order_id']='140321989';
			$order_info['customer_id']='171';
			
			$this->load->model('total/' . $order_total['code']);
					
			if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
				$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
			}
			
			echo 'Coupon History Add Success!';
		}
		
		$this->data['action3']=$this->url->link('test/function/test3');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/test/test.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/test/test.tpl';
		} else {
			$this->template = 'default/template/test/test.tpl';
		}
		
						
		$this->response->setOutput($this->render());
	}
	
	
	public function testxml(){
		$subject="<notify><payment_type>1</payment_type><subject>青年菜君订单号:140418707</subject><trade_no>2014041810023180</trade_no><buyer_email>uuilex@gmail.com</buyer_email><gmt_create>2014-04-18 14:20:35</gmt_create><notify_type>trade_status_sync</notify_type><quantity>1</quantity><out_trade_no>140418707</out_trade_no><notify_time>2014-04-18 14:20:47</notify_time><seller_id>2088311062628944</seller_id><trade_status>TRADE_FINISHED</trade_status><is_total_fee_adjust>N</is_total_fee_adjust><total_fee>0.01</total_fee><gmt_payment>2014-04-18 14:20:47</gmt_payment><seller_email>qncaijun@qingniancaijun.com</seller_email><gmt_close>2014-04-18 14:20:47</gmt_close><price>0.01</price><buyer_id>2088002158700803</buyer_id><notify_id>2cfcdb993139af33d568abd5b0be00bd6g</notify_id><use_coupon>N</use_coupon></notify>";
		
		$doc2 = new DOMDocument();
		
		$doc2->loadXML($subject);
		
		echo $doc2->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
	}
}