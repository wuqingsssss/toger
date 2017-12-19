<?php
class ServiceSmsMontSms extends Service {
	protected $uri;
	protected $key;
	protected $open;
	protected $user;
	protected $timeout;
	public function __construct($registry) {
		
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		$this->uri      = SMS_MONT_URL;
		$this->userId   = $this->config->get('mont_appid');
		$this->password = $this->config->get('mont_appsecret');	
		$this->open     = $this->config->get('mont_status');
 		$this->sign     = $this->config->get('mont_sign');
 		$this->timeout = (int)$this->config->get('mont_timeout');
 		
 		if(!$this->userId){

 			$this->userId   = SMS_MONT_USERID;
 			$this->password = SMS_MONT_PWD;
 			$this->open     = SMS_MONT_OPEN;
 			$this->sign     = SMS_MONT_SIGN;
 			$this->timeout  = 5;
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
			if ($this->log_sys){ 
				$this->log_sys->info ('ServiceSmsMontSms->sms->send::'.$mobile."::".$msg);
			}
			$url=$this->uri.'/MongateSendSubmit';	
		$data['userId']=$this->userId;		
		$data['password']=$this->password;
		$data['pszMobis']=$mobile;
		$data['pszMsg']=$this->sign.$msg;
		$data['iMobiCount']=count(explode(',', $mobile));
		$data['pszSubPort']='*';
		$data['MsgId']=time();

	     //	print_r(HTTP::buildURL($url,$data));
		// $resstr=HTTP::getSSCPOST($url,$data, 1);
		 //$resstr=HTTP::getPOST($url,$data);
		$resstr=HTTP::getGET($url,$data,$this->timeout);

		//print_r($resstr);
		 
		/*
		try {
			$client = new SoapClient($this->uri);
			$xml = '
    <?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <MongateSendSubmit xmlns="http://tempuri.org/">
      <userId>string</userId>
      <password>string</password>
      <pszMobis>string</pszMobis>
      <pszMsg>string</pszMsg>
      <iMobiCount>int</iMobiCount>
      <pszSubPort>string</pszSubPort>
      <MsgId>INT64str</MsgId>
    </MongateSendSubmit>
  </soap:Body>
</soap:Envelope
  ';
  $return = $client->MongateSendSubmit($xml);

			print_r($return);
		} catch (SOAPFault $e) {
			print_r('Exception:'.$e);
		}
		  */
     if(!$resstr) return false;

     if ($this->log_sys){
     	$this->log_sys->info ('ServiceSmsMontSms->return::'.$resstr."::".$msg);
     }
		$res=json_decode(json_encode(simplexml_load_string($resstr)),1);

		if (isset($res[0])&&abs($res[0])<=1000000) {
			if ($this->log_sys){
				$this->log_sys->error ('ServiceSmsMontSms->return::error::'.$resstr."::".$msg);
			}
			return false;
		} else {
			return true;
		}

		}
	}
	
}

?>
