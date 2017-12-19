<?php
class ServiceMeituantgCoupon extends Service {
	
	private $appid='';
	private $key='';
	private $uri='';
		public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->appid=MT_APPID;
		$this->key=MT_KEY;
		$this->uri=MT_URI;
	}
	
	/* 远程美团团购核销接口*/
	
	public function HMTTGVerify($data){
		if(empty($data)){
			return false;
		}
		//以Geocoding服务为例，地理编码的请求url，参数待填
		//get请求uri前缀
		$uri = '/eticketconsume';
		$url = $this->uri.$uri;
		$data['appid']=$this->appid;
		$data['key']=$this->key;
		$data['sign_method']=1;
		
		//调用sn计算函数，默认get请求
		$sn = HTTP::make_sign($data,$this->key);
		$data['sn']=$sn;

		$this->log_sys->debug($url);
		$this->log_order->info('HMTUpdate::data:'.serialize($data));
		
		$resstr=HTTP::getGET($url,$data);

		$this->log_sys->debug($resstr);
		$res=json_decode($resstr,1);

		$this->log_sys->debug($res);
		
		//$res=array('C172','C057');
		
		return $res;
	}
	

	/* 远程美团接口结束*/

}
?>