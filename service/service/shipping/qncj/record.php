<?php 
class ServiceShippingQncjRecord extends Service {
	
	private $key='';
	private $partner_id='';
	private $uri='';
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->key=MEISHI_KEY;
		$this->partner_id=MEISHI_PARTNER_ID;
		$this->uri=MEISHI_URI;//测试系统
		
	}


/* 美食送订单添加接口*/
	public function haddRecord($data)
	{
		/*美食送接口测试*/	

		/* */	
	return false;
				
	}
}
?>