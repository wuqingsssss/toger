<?php 
class ServiceShippingMeishisongShippingO3 extends Service {
	
	private $meiregionarea=array();
	private $key='';
	private $partner_id='';
	private $uri='';
	private $update_coord=false;
	private $city=array(
			'110000'=>'北京市',
			'310000'=>'上海市',
			'440100'=>'广州市',
			'510100'=>'成都市',
			'440300'=>'深圳市',
			'340100'=> '合肥市'
	);
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->key=MEISHI_KEY;
		$this->partner_id=MEISHI_PARTNER_ID_O3;
		$this->uri=MEISHI_URI_O3;//测试系统
		$this->access_key=MEISHI_ACCESS_KEY;//测试系统
		$this->secret_key=MEISHI_SECRET_KEY;//测试系统
		
		$this->code=MEISHI_CODE;//测试系统
		if(defined('MEISHI_UPDATE_COORD'))
		$this->update_coord=MEISHI_UPDATE_COORD;
		
		$this->hsetMssRegion();
	    if(!$this->getMssRegion()){
	        $this->log_sys->warn('hsetMssRegion:empty');
	     }
	}
	
	/* 获取美食送所有可配送区域*/
	public function getMssRegion(){
		return $this->meiregionarea;
	}

	/* 从美食送拉取可配送区域*/
	private function hsetMssRegion()
	{
		if($this->mem){
			$meiregion = $this->mem->get($this->code.'Region');
			$this->log_db->debug('memcache:get:'.$this->code.'Region');
		}
		else
		{
			$meiregion = $this->cache->get($this->code.'Region');
			$this->log_db->debug('cache:get:'.$this->code.'Region');
		}
		
		if(!$meiregion||defined('DEBUG') && DEBUG ){

			$this->load->model('catalog/pointdelivery');
			$ress=$this->model_catalog_pointdelivery->getDeliverys($this->code);

		
			$this->log_sys->debug($ress);
				
			$meiregion=array();
			if($ress){
				foreach($ress as $key=>$value)
				{
					if($value&&isset($value['zone_name'])){
						$value['city']=$value['zone_name'];
						$value['ploygongeo']=$area['region_coord'];
						$meiregion[$value['city']][$value['region_id']]=$value;	
					}
				}

				if($this->mem){
					$this->mem->set($this->code.'Region',$meiregion,0,7200);
					$this->log_db->debug('memcache:set:'.$this->code.'Region');
				}
				else
				{
						
					$this->cache->set($this->code.'Region',$meiregion,strtotime(date("Y/m/d",time()+3600*24)));
					$this->log_db->debug('cache:set:'.$this->code.'Region');
				}
			}
				
		}

		$this->meiregionarea=$meiregion;
	}
	/* 获取美食送菜君允许配送区域*/
	public function getAllowRegion($sall=false)
	{

	return $this->getMssRegion();
	}
	
	/* 根据地址判断是否为美食送配送区域，本地算法*/
	public function getByAddress($address) {

	$this->load->service('baidu/geocoder');
	
	$res=$this->service_baidu_geocoder->hgetLocation($address);

		if($res['status']=='0'){
			$resjson['state']='0';//初始值不在区域或者未找到
			$resjson['data']=$res['result'];
			$resjson['data']['inpolygon']='0';
			$resjson['data']['address']=$address;
	
			$point = $res['result']['location']['lng'].','.$res['result']['location']['lat'];
			$meiregionarea=$this->getAllowRegion();
			foreach($meiregionarea as $key =>$item)
			{
				foreach($item as $key2=>$area){
					$polygon=explode(';',$area['ploygongeo']);
					// The last point's coordinates must be the same as the first one's, to "close the loop"
					$inpolygon='0';
					$inpolygon=LBS::pointInPolygon($point, $polygon);
					if($inpolygon){					
						$resjson['status']=$inpolygon;
						$resjson['data']=array_merge($res['result'],$area);
						$resjson['data']['inpolygon']=$inpolygon;
						$resjson['data']['address']=$address;
						break;
					}
				}
			}
		}
		else {
			$resjson['status']='-1';//初始值不在区域或者未找到
			$resjson['data']=$res['result'];
			$resjson['data']['inpolygon']='0';
			$resjson['data']['address']=$address;
		}
		
		return $resjson;
	
	}

	/* 根据经纬度判断是否为美食送配送区域，本地算法*/
	public function getByLng($location){		
		

			$location['lng'] = $this->request->get['lng'];
			$location['lat'] = $this->request->get['lat'];
			//$location=array('lng'=>'116.471144','lat'=>'40.003241');
		
				$resjson['status']='0';//初始值不在区域或者未找到
				$resjson['data']['inpolygon']='0';
				$resjson['data']['location']=$location;
		
				$point = $location['lng'].','.$location['lat'];
				$meiregionarea=$this->getAllowRegion();
				foreach($meiregionarea as $key =>$item)
				{
					foreach($item as $key2=>$area){
						$polygon=explode(';',$area['ploygongeo']);
						// The last point's coordinates must be the same as the first one's, to "close the loop"
						$inpolygon='0';
						$inpolygon=LBS::pointInPolygon($point, $polygon);
						if(LBS::pointInPolygon($point, $polygon)){
							$resjson['status']=$inpolygon;
							$resjson['data']=array_merge($resjson['data'],$area);
							$resjson['data']['inpolygon']=$inpolygon;
							break;
						}
					}
				}
		

		return $resjson;

	}
	
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByAddress($address,$city="北京市"){
		/*美食送接口测试/*
		 *请求示例 {
"access_key": "123",
"cmd": "o3.utils.region.byaddress",
"version": "v2.0",
"ticket": "19D00B90-DE3F-95CE-2B4A-9B22AD2F5F32",
"body": {
"partner_id": "123456",
"delivery_partner_id": "132928328323",
"production_code": "2312433214",
"address": "星科⼤大厦",
"city_code": "110000"
},
"time": 1458265566,
"sign": "3e1a5cce8b8f4667b300862bd0b1d01e"
}
		 *
		 * */
		$data=array(
				'access_key'=>$this->access_key,
				'body'=>array('partner_id'=>$this->partner_id,
						'delivery_partner_id'=>'',
						'production_code'=>'',
						'address'=>$address,
						'city_code'=>array_search($city,$this->city)
				),
				'cmd'=>'o3.utils.region.byaddress',
				'ticket'=>$this->get_ticket(),
				'version'=>'v2.0',
				'time'=>time()
		);

		$data['sign']=$this->set_sign($data, $this->secret_key);
		
		$url=$this->uri;
		
		$reso3=json_decode(HTTP::getSSCPOST($url,json_encode($data),1),1);
		//$reso3=json_decode($this->curl(json_encode($data),$url));

		if(isset($reso3['body']['code']) && $reso3['body']['code']=='200'){
			
			$res=$reso3['body'];
			$meiregionarea=$this->getAllowRegion();

		   if(isset($meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']])){
		   	$res['data']['region_name']=$res['data']['dc_name'];
		    $res['data']=array_merge($res['data'],is_array($meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']])?$meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']]:array());
				$res['data']['shippingcode']=$this->code;
				$res['status']=1;
                $res['message']='在美食送配送区域';
			}
			else
			{
				$res['status']=2;
				$res['message']='菜君尚未开通此区域';
			}
	
		}else{
			$res['status']=0;
			$res['message']='菜君尚未开通此区域';
				
		}
		return $res;
	}

	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByLng($location){
	/*美食送接口测试/*
		 * {
"access_key": "d166964d35b73112b95863f54345cb87",
"body": {
"test": "testv1",
"test2": "testv2"
},
"cmd": "qh.order.get",
"ticket": "B095F210-D5B8-871C-0913-AAE72B16D5A6",
"time": 1451286153,
"version": "v2.0",
"sign": "c8004583b4b269000e2f070f91c9b1e7"
}
		 * 
		 * */
		$lng = $location['lng'];
		$lat = $location['lat'];

		
		/*
		$json='{"access_key":"87624b852700bb3accbb6f0d31f3450d","body":{"message":"success","status":0},"cmd":"o3.resp.message","ticket":"6693e318-0775-11e6-8681-02865ea02663","version":"v2.0","time":1461211149}';
		$data=json_decode($json,1);
		print_r($data);
		unset($data['sign']);
		$data['sign']=$this->set_sign($data, $this->secret_key);
		print_r($data);
		print_r(json_encode($data));
		die();*/
		$data=array(
				'access_key'=>$this->access_key,
				'body'=>array('partner_id'=>$this->partner_id,
						    'delivery_partner_id'=>'',
						    'production_code'=>'',
						    'mp'=>9,
						    'lng'=>$lng,
						    'lat'=>$lat
				),
				'cmd'=>'o3.utils.region.bylng',
				'ticket'=>$this->get_ticket(),
				'version'=>'v2.0',
				'time'=>time()
		);

		$data['sign']=$this->set_sign($data, $this->secret_key);

		$url=$this->uri;

		//$res=json_decode(HTTP::getPOST($url,json_encode($data)),1);
		$reso3=json_decode(HTTP::getSSCPOST($url,json_encode($data),1),1);	
		//$res=json_decode($this->curl(json_encode($data),$url),1);

	  /* 对搜索地区进行过滤*/
		if(isset($reso3['body']['code']) && $reso3['body']['code']=='200'){
			
			$res=$reso3['body'];
			$meiregionarea=$this->getAllowRegion();
			//print_r($meiregionarea);
			if(isset($meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']])){
				$res['data']['region_name']=$res['data']['dc_name'];
				$res['data']=array_merge($res['data'],is_array($meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']])?$meiregionarea[$this->city[$res['data']['city_code']]][$res['data']['dc_id']]:array());
				$res['data']['shippingcode']=$this->code;
				$res['status']=1;
                $res['message']='在美食送配送区域';
			}
			else
			{
				$res['status']=2;
				$res['message']='菜君尚未开通此区域';
			}
		
		}else{
			$res['status']=0;
			$res['message']='菜君尚未开通此区域';
				
		}
		return $res;
	}
	
	
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetPost($datastring,$resetsign=false){
		$url=$this->uri;
		
		if($resetsign)
		{
			$data=json_decode($datastring,1);
			unset($data['sign']);
			$data['sign']=$this->set_sign($data,$this->secret_key);
			$datastring=json_encode($data);
		}
		
		return HTTP::getSSCPOST($url,$datastring,1);
	}
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function chksign($datastring){

			$data=json_decode($datastring,1);
			unset($data['sign']);
			$data['sign']=$this->set_sign($data,$this->secret_key);
			$datastring=json_encode($data);

		return $datastring;
	}
	/**
* ⽣生成ticket
*
* @return string
*/
function get_ticket() {
$uuid = '';
if(function_exists('com_create_guid')){
$uuid = trim(com_create_guid(), '{}');
}else{
mt_srand((double)microtime()*10000);
$charid = strtoupper(md5(uniqid(rand(), true)));
$hyphen = chr(45);
$uuid = substr($charid, 0, 8) . $hyphen
. substr($charid, 8, 4) . $hyphen
. substr($charid, 12, 4) . $hyphen
. substr($charid, 16, 4) . $hyphen
. substr($charid, 20, 12);
}
return strtoupper($uuid);
}
/**
* 对数组进⾏行排序（规则：按照ASSIC编码升序，递归对数据的key进⾏行排序）
*
* @param array $arr
*
* @return array
*/
function ksort_recusive(array $arr=array()) {
if (!is_array($arr)) {
return $arr;
}
ksort($arr, SORT_REGULAR);
foreach ($arr as $key => $value) {
if (is_array($value)) {
$arr[$key] = $this->ksort_recusive($value);
}
}
return $arr;
}
/**
* 发送请求
*
* @param string $data
* @param string $url
*
* return string
*/
function curl($data, $url){
$output = '';
$retry = 0;
$headers = array(
'Content-Type: application/json',
'Content-Length: ' . strlen($data),
);
do{
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_TIMEOUT, 15);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$output = curl_exec($curl);
$info = curl_getinfo($curl);
curl_close($curl);
if(isset($info['http_code']) && 200 == $info['http_code']){
return $output;
}
$retry++;
}while($retry <= $this->_httpRetry && $retry < 10);
return $output;
}
/**
* ⽣生成签名
*
* @param array $request_params
* @param string $access_key
*
* @return string
*/
function set_sign(array $request_params, $secret_key) {
/* 递归对协议数据的key进⾏行排序，排序规则:按照 ASSIC 编码升序 */
$request_params = $this->ksort_recusive($request_params);
/* 对数据进⾏行json_encode */
$json_data = json_encode($request_params);
/* ⽣生成签名 */
$sign = hash_hmac('md5', $json_data, $secret_key);
return $sign;
}

}
?>