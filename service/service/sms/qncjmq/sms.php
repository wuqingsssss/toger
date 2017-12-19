<?php
class ServiceSmsQncjMqSms extends Service {
	protected $open;
	protected $sign;
	public function __construct($registry) {
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		$this->open     = $this->config->get('qncjmq_status');
 		$this->sign     = $this->config->get('qncjmq_sign');
        if(is_null($this->open))
 		   $this->open  =SMS_QNCJMQ_OPEN;
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
				$this->log_sys->info ('ServiceSmsQncjMqSms->sms->send::'.$mobile."::".$msg);
			}
			$this->load->service('qncj/mq');
         return $this->{'service_qncj_mq'}->send_msg($mobile,$msg);
		}
	}
	
}
?>