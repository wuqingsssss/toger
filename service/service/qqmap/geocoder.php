<?php 
class ServiceQqmapGeocoder extends Service {
	/*百度云存储支持开始*/
	private $ak;
	private $sk;
	private $uri;
	
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		 
		   $this->ak=QQMAP_AK;
		   $this->sk=QQMAP_SK;
		   $this->uri=QQMAP_URI;
	}
	
	public function hgeoconv($coords,$city='北京市'){
		//get请求uri前缀
		$uri = '/ws/coord/v1/translate';
		$url = $this->uri.$uri;
		//地理编码的请求output参数
		$output = 'json';
	    $from=$coords['from']?$coords['from']:3;
		
		//构造请求串数组
		/*1 GPS坐标
2 sogou经纬度
3 baidu经纬度
4 mapbar经纬度
5 [默认]腾讯、google、高德坐标
6 sogou墨卡托*/
		$querystring_arrays = array (
				'locations' => $coords['coords'],
				'type'=>$from,
				'output' => $output,
				'key' => $this->ak
		);
	
		//调用sn计算函数，默认get请求
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
		$querystring_arrays['sn']=HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);
	
		$resjson=HTTP::getGET($url,$querystring_arrays);
	
		return json_decode($resjson,1);
	
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