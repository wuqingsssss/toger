<?php
class ServiceSmsApp2eSms extends Service {
	protected $uri;
	protected $key;
	protected $open;
	protected $user;
	protected $timeout;
	public function __construct($registry) {
		
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		
		$this->uri  = SMS_APP2E_URL;
		$this->user = $this->config->get('app2e_appid');
		$this->key  = $this->config->get('app2e_appsecret');	
		$this->open = $this->config->get('app2e_status');
 		$this->sign = $this->config->get('app2e_sign');
 		$this->timeout = (int)$this->config->get('app2e_timeout');
 		if(!$this->user){
 			$this->user = SMS_APP2E_USER;
 			$this->key  = SMS_APP2E_KEY;
 			$this->open = SMS_APP2E_OPEN;
 			$this->sign = SMS_APP2E_SIGN;
 			$this->timeout=5;
 		}
 		
	}
 
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	public function send($mobile,$msg){
		if($this->open){
			$url=$this->uri;
			if ($this->log_sys){ 		
				$this->log_sys->info ('ServiceSmsApp2eSms->sms->send::'.$mobile."::".$msg);
			}
			
			$varInfo["username"]		        = $this->user;
			$varInfo["pwd"]				= $this->key;
			//内容必须GPK编码
			$varInfo["msg"]				= iconv('UTF-8', 'GB2312', $this->sign.$msg);
			$varInfo["p"]				= $mobile;
/*
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->uri);
		
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSLVERSION , 3);
		
			curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
		//	curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.$this->key);

			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $varInfo);
		
			$resstr = curl_exec( $ch );
			curl_close( $ch );
*/
			
			$resstr=HTTP::getSSCPOST($url,$varInfo, 1,$this->timeout);
//print_r($resstr);
		 //$resstr=HTTP::getPOST($url,$data);
			
			if ($this->log_sys){
				$this->log_sys->info ('ServiceSmsApp2eSms->sms->send::return::'.$resstr);
			}
			$res=json_decode($resstr,1);
			if($res['status']=='100'){
				return true;
			}
			else 
			{
				if ($this->log_sys){
					$this->log_sys->error ('ServiceSmsApp2eSms->sms->send::return::error::'.$resstr);
				}
				return false;
			}

		}
	}
	
}

?>
