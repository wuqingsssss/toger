<?php
class ServiceAlibabaAlioss extends Service {

	public $alioss;

	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		parent::__construct($registry,dirname(__FILE__));
		require_once(dirname(__FILE__) . '/oss/alioss.class.php');
		$this->alioss=new ALIOSS();
	}
	
	public function __get($key) {
		return $this->alioss->{$key};
	}
	public function __set($key, $value) {
		    $this->alioss->{$key}= $value;
	}
	
	public function __call($methodName, $arguments) {
		 return call_user_func_array(
		 		array($this->alioss, $methodName),
		 		$arguments
		 );
	}
}
?>