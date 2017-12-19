<?php
class ServiceMeituanOrder extends Service {
	
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
	
	/* 远程美团更新接口*/
	
	public function HMTUpdate($data){
		//以Geocoding服务为例，地理编码的请求url，参数待填
		//get请求uri前缀
		$uri = '/order/cancel';
		$url = $this->uri.$uri;
		//构造请求串数组
		/*
		 *
		 */
		$data['appid']=$this->appid;
		$data['sign_method']=1;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::make_sign($data,$this->key);
		$data['sn']=$sn;

		$this->log_admin->debug($url);
		$this->log_order->info('HMTUpdate::data:'.serialize($data));
	
		$resstr=HTTP::getGET($url,$data);

		$this->log_admin->debug($resstr);
		$res=json_decode($resstr,1);
	
		$this->log_admin->info($res);
		
		//$res=array('C172','C057');
		
		return $res;
	}
	

	/* 远程美团接口结束*/

}
?>