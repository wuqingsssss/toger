<?php 
class ServiceBaiduGeocoder extends Service {
	/*百度云存储支持开始*/
	private $ak;
	private $sk;
	private $uri;
	
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		 
		   $this->ak=BDYUN_AK;
		   $this->sk=BDYUN_SK;
		   $this->uri=BDYUN_URI;
	}
	
	public function hgeoconv($coords,$city='北京市'){
		//get请求uri前缀
		$uri = '/geoconv/v1/';
		$url = $this->uri.$uri;
		//地理编码的请求output参数
		$output = 'json';
	    $from=$coords['from']?$coords['from']:3;
		$to=$coords['to']?$coords['to']:5;
		
		//构造请求串数组
		/* from取值为如下：1：GPS设备获取的角度坐标，wgs84坐标;2：GPS获取的米制坐标、sogou地图所用坐标;3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标，国测局坐标;
		 4：3中列表地图坐标对应的米制坐标;5：百度地图采用的经纬度坐标;6：百度地图采用的米制坐标;7：mapbar地图坐标;8：51地图坐标*/
		$querystring_arrays = array (
				'coords' => $coords['coords'],
				'from'=>$from,
				'to'=>$to,
				'output' => $output,
				'ak' => $this->ak
		);
	
		//调用sn计算函数，默认get请求
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
		$querystring_arrays['sn']=HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);
	
		$resjson=HTTP::getGET($url,$querystring_arrays);
	
		$res=json_decode($resjson,1);
		if($res['status']==0){
			foreach($res['result'] as $key => $value){
				$value['lng']=$value['x'];
				$value['lat']=$value['y'];
				$res['result'][$key]=$value;
			}	
           $res['locations']=$res['result'];
		}
		
		
		return $res;
	
	}
	
	public function hgetLocation($address,$city='北京市'){	
		//get请求uri前缀
		$uri = '/geocoder/v2/';
		$url = $this->uri.$uri;
		//地理编码的请求output参数
		$output = 'json';
		
		//构造请求串数组
		$querystring_arrays = array (
				'address' => $address,
				'city'=>$city,
				'output' => $output,
				'ak' => $this->ak
		);
		
		//调用sn计算函数，默认get请求
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
		$querystring_arrays['sn']=HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);

		$resjson=HTTP::getGET($url,$querystring_arrays);
		
		return json_decode($resjson,1);
		
	}
	public function hgetAddress($location,$city='北京市'){
		//get请求uri前缀
		$uri = '/geocoder/v2/';
		$url = $this->uri.$uri;
		//地理编码的请求output参数
		$output = 'json';
	
		//构造请求串数组
		$querystring_arrays = array (
				'location' => $location['lat'].','.$location['lng'],
				'pois'=>'0',
				'output' => $output,
				'ak' => $this->ak
		);
	
		//调用sn计算函数，默认get请求
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
		$querystring_arrays['sn']=HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);
	
		$resjson=HTTP::getGET($url,$querystring_arrays);

		return json_decode($resjson,1);
	
	}
		
}
?>