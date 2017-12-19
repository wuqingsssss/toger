<?php
class ServiceMeituanProduct extends Service {
	
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
	
	public function HMTProList($data=array()){
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
		//get请求uri前缀
		$uri = '/food/list';
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

		$this->log_admin->info($url.serialize( $data));
	
		$resstr=HTTP::getGET($url,$data);

		$res=json_decode($resstr,1);
if($res===null)
		$this->log_admin->error($resstr);
else 
	$this->log_admin->info($res);
		
		//$res=array('C172','C057');
		
		return $res;
	}
	
	
	/*美团数据更新接口
	 * $data['product_id']为识别唯一主键*/
	public function HMTProCreate($data){		
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
		//get请求uri前缀
		$uri = '/food/save';		
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

		$this->log_admin->info($url.serialize( $data));

		$resstr=HTTP::getPOST($url,$data);

	
		$res=json_decode($resstr,1);
		if($res===null)
			$this->log_admin->error($resstr);
		else
			$this->log_admin->info($res);
		return $res;
	}
	/*美团数据更新接口
	 * $data['product_id']为识别唯一主键*/
	public function HMTProDelete($data){
	
		
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
		//get请求uri前缀
		$uri = '/food/delete';		
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

		$this->log_admin->info($url.serialize( $data));
		

		
		$resstr=HTTP::getPOST($url,$data);



		$res=json_decode($resstr,1);
		
		if($res===null)
		$this->log_admin->error($resstr);
else 
	$this->log_admin->info($res);

		return $res;
	}
	
	/* 远程美团接口结束*/

}
?>