<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerSaleSendMsg extends Controller {
	protected function init() {
		header ( "Access-Control-Allow-Origin: *" );
	}
	private $error;
	private $debug = DEBUG;
	public function _test() {

	}
	private function get_sp_key($appid) {
		$config = array (
				'1829219078' => 'nurvrst31o0msmx8n2l4ryhg9lgyt6os', // 测试服务器182.92.190.78
				'1829219072' => 'ffsdfrewrwwesmx8n2l4ryhg9lgyt6os', // 测试服务器182.92.190.72
				'qncjhost' => 'ffsddfrewrwsdmx8n2l4rsewrwhgfkty' 
		); // 青年菜君本地服务器

		
		if (isset ( $config [$appid] ))
			return $config [$appid];
		else
			return false;
	}
	/**
	 * 发送配送通知消息
	 * @param unknown $order_id
	 * @param unknown $order_info
	 * @return boolean
	 */
	public function sendMsgShipping(){
		$this->load->model ( 'sale/order' );
		$this->init ();
		if (HTTP::check_sign ( $this->request->post, $this->get_sp_key ( $this->request->post ['appid'] ) )) {
			$this->log_sys->info ( '校验成功' . serialize ( $this->request->post ) );
		} else {
			$this->log_sys->info ( '校验失败' . serialize ( $this->request->post ) );
				
			$post = $this->request->post;
			unset ( $post ['sign'] );
			$sign = HTTP::make_sign ( $post, $this->get_sp_key ( $this->request->post ['appid'] ) );
			$this->log_sys->info ( 'sign:' . $sign );
				
			ksort ( $post );
				
			$arr_temp = array ();
			foreach ( $post as $key => $val ) {
				$arr_temp [] = $key . '=' . $val;
			}
			$sign_str = implode ( '&', $arr_temp );

			$this->log_sys->warn ( '校验失败::syssign:' . $sign . '::sign_str:' . $sign_str );
			// print_r('校验失败::syssign:'.$sign.'::sign_str:'.$sign_str);
			$this->response->setOutput ( '校验失败' );
			$this->error ['create'] ['error'] = '1';
			$this->error ['create'] ['message'] = '校验失败';
			$this->response->setOutput ( json_encode ( $this->error ['create'] ) );
			return;
		}
		
		$order_id=$this->request->post['order_id'];	
		$this->load->model ( 'checkout/order' );
		$order_info = $this->model_checkout_order->getOrder ($order_id);
		if(!$this->model_checkout_order->sendWeixinMsgHasShipping($order_id,$order_info))
		{
		     if(!$this->model_checkout_order->sendSmsHasShipping($order_id,$order_info))
		     {
		     	$this->error ['create'] ['error'] = '1';
		     	$this->error ['create'] ['message'] = '发送失败';

		     }
		     else 
		     {
		     	$this->error ['create'] ['error'] = '0';
		     	$this->error ['create'] ['message'] = '短信成功';
		     }
		}
		else
		{
			$this->error ['create'] ['error'] = '0';
			$this->error ['create'] ['message'] = '微信成功';
		}
		
		
		$this->response->setOutput ( json_encode ( $this->error ['create'] ) );
		return;		
	}

}

?>