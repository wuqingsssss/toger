<?php 
class ServiceShippingMeishiRecord extends Service {
	
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
        $postdata=$data;

        $postdata ['sign'] = md5 ( 'partner_id=' . $this->partner_id . '#partner_order_id=' . $postdata ['partner_order_id'] . '#push_time=' . $postdata ['push_time'] . '#notify_url=' . $postdata ['notify_url'] . '#key=' . $this->key );
		
		$this->log_order->info ( 'meishi->haddRecord:' . serialize ( $postdata ) );
		
		$url = $this->uri . '/order/addRecord';
		
		$res = HTTP::getPOST ( $url, json_encode ( $postdata ) );
		$this->log_order->info ( 'meishi->haddRecord:res:' . serialize ( $res ) );
		/* */
		
	return json_decode ($res,1);
				
	}

}
?>