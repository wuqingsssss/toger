<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerWechatToken extends Controller {
	private $deliverys;
	public function test() {
                $this->init();
                
                $res  = $this->service_weixin_interface->get_jsapi_ticket ();
                
                $this->response->setOutput ( $res );
                
                
	}
	/* 获取api授权*/
	private function get_sp_key($appid){
	
		$config=array(
				'1829219078'=>'nurvrst31o0msmx8n2l4ryhg9lgyt6os',//测试服务器182.92.190.78
				'1829219072'=>'ffsdfrewrwwesmx8n2l4ryhg9lgyt6os', //测试服务器182.92.190.72
				'14070810'=>'qncj14070810' //生产服务器
		);
	
		if(isset($config[$appid])) return $config[$appid];
		else
			return false;
	
	}
	protected function init(){
			header("Access-Control-Allow-Origin: *");//跨域问题
		if (HTTP::check_sign ( $this->request->post, $this->get_sp_key ( $this->request->post ['appid'] ) )) {
			$this->log_sys->info ( '校验成功' . serialize ( $this->request->post ) );
		} else {
			$this->log_sys->warn ( '校验失败' . serialize ( $this->request->post ) );
		
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
			$this->error ['getAccessToken'] ['error'] = '1';
			$this->error ['getAccessToken'] ['message'] = '校验失败';
			$this->response->setOutput ( json_encode ( $this->error ['getAccessToken'] ) );
			return;
		}	
		    $this->load->service ( 'weixin/interface' );
		    //强制启用非中控模式
		    $this->service_weixin_interface->set_runc(false);
	}
	public function getAccessToken()
	{
		
		$this->init();
		
		$res  = $this->service_weixin_interface->get_weixin_access_token();

		$this->response->setOutput (json_encode($res) );
			;
	}	
	public function getJsapiTicket()
	{
		$this->init();
	
		$res  = $this->service_weixin_interface->get_jsapi_ticket ();
	
		$this->response->setOutput ( json_encode($res) );
			
	}
	

  }
	
?>