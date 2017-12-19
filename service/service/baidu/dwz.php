<?php 
class ServiceBaiduDwz extends Service {
	/*百度云存储支持开始*/
	private $ak;
	private $sk;
	private $uri;
	
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		 
		   $this->ak=BDYUN_AK;
		   $this->sk=BDYUN_SK;
		   $this->uri='http://dwz.cn/create.php';
	}
	
	public function hcreate($linkurl,$surl=''){	
		//get请求uri前缀
		$uri = '';
		$url = $this->uri.$uri;
		//地理编码的请求output参数
		$output = 'json';
		
		//构造请求串数组
		$querystring_arrays = array (
				'url' => $linkurl,
				'alias'=>$surl
		);
		
		//调用sn计算函数，默认get请求
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
		//$querystring_arrays['sn']=HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);

		$resjson=HTTP::getPOST($url,$querystring_arrays);
		
		return json_decode($resjson,1);
		
	}
		
}
?>