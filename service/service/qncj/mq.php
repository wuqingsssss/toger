<?php
class ServiceQncjMq extends Service {
	protected $uri;
	protected $key;
	protected $timeout;
	public function __construct($registry) {
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		$this->uri = QNCJMQ_URL;
		
		$this->timeout = ( int ) QNCJMQ_TIME_OUT;
	}
	public function __get($key) {
		return $this->registry->get ( $key );
	}
	public function __set($key, $value) {
		$this->registry->set ( $key, $value );
	}
	public function send_msg($mobile, $msg) {
		if ($this->log_sys) {
			$this->log_sys->info ( 'ServiceSmsQncjMqSms->sms->send::' . $mobile . "::" . $msg );
		}
		$url = $this->uri . '/mq/producer';
		//$url = 'http://qncjv3/test.php';
		//$url = 'http://www.baidu.com';
		$msgdata = array (
				'p' => $mobile,
				'model' => 'order',
				'msg' => $msg 
		);
		
		$data ['topic'] = 'qncj_tset';
		$data ['tag'] = 'sms';
		$data ['key'] = 'web';
		$data ['msg'] = json_encode ( $msgdata );
		//$data ['msg'] =$msgdata;
		//$json ['json'] = json_encode ( $data );

		//print_r(http::buildURL($url,$data));
		
		//$resstr=HTTP::getSSCPOST($url,$data, 1);

		$resstr = HTTP::getPOST ( $url, $data);
		  
        // print_r($resstr);
         
		if (! $resstr)
			return false;
		
		if ($this->log_sys) {
			$this->log_sys->info ( 'ServiceQncjMqSendSms->return::' . $resstr . "::" . $msg );
		}
		
		$res = json_decode ($resstr, 1 );
		//print_r($res);
		if(!$res) return false;
		
		if (!empty($res['messageId'])) {
			if ($this->log_sys){
				$this->log_sys->error ('ServiceSmsQncjMqSms->return::error::'.json_encode($res)."::".$msg);
			}
			return false;
		} else {
			return true;
		}
	}
}
?>