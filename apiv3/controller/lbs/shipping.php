<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerLbsShipping extends Controller {
	private $deliverys;
	public function test() {

	}
	/* 获取api授权*/
	private function get_sp_key($appid){
	
		$config=array(
				'1829219078'=>'nurvrst31o0msmx8n2l4ryhg9lgyt6os',//测试服务器182.92.190.78
				'1829219072'=>'ffsdfrewrwwesmx8n2l4ryhg9lgyt6os' //测试服务器182.92.190.72
		);
	
		if(isset($config[$appid])) return $config[$appid];
		else
			return false;
	
	}
	protected function init(){
		// header("Access-Control-Allow-Origin: *");//跨域问题

		//$this->deliverys = EnumDelivery::getAllDelivery ();
		$deliverys=$this->config->get('delivery_express');

		foreach ( $deliverys as $key => $item ) {
			if($item['status']){
				
			$this->load->service ( 'shipping/' . $item['code'] . '/shipping'.$item['version'] );
			$this->deliverys[$item['code']]=$item;
			}
		}	

	}

	public function haddRecord()
	{
	  return false;
			
	}
	

	public function getMsAllowarea()
	{//获取可支持的所有配送区域
		$code = $this->request->get ['code'];
	   if($this->deliverys[$code])
		$allowarea=$this->{'service_shipping_'.$code.'_shipping'}->getMssRegion();
		print_r($allowarea);
	}
	
	/* 获取可配送区域
	 * p 打印类型 txt文本换行，str &字符串 ，msjson 美食送标准json格式
	 * return */
	public function getAllowarea()
	{	
		$this->noTimeout('300',false);
		$this->init ();
	
	foreach($this->deliverys as $key=>$delivery){
	$allowarea[$delivery['title']]=$this->{'service_shipping_'.$key.'_shipping'.$delivery['version']}->getAllowRegion();
	}

	
		if ($this->request->get ['p'] == 'txt') {
			foreach ( $allowarea as $key => $item ){
				print_r ( $key  . '<br>' );
			  foreach ( $item as $key2 => $item2 ){
			  	print_r ( $key2. '<br>' );
				foreach ( $item2 as $key3 => $area )
				{	
					print_r ( $area ['region_name'] . '['. $area ['region_id'].'] ' . $area ['region_coord'] . '<br>' );
				}
			}}
		} elseif ($this->request->get ['p'] == 'jd') {
			$this->load->service('qqmap/geocoder');
			foreach ( $allowarea as $code => $data )
			foreach ( $data as $key => $item )
				foreach ( $item as $key2 => $area )
					if($area ['region_coord'])
					{
						//print_r ($area ['region_coord'] . '&<br/>' );
						
						$region_coords=explode(',', $area['region_coord']);
						
						foreach($region_coords as $poi)
						{
							$loaction=explode(' ', $poi);
							$geoconvs[]=$loaction[1].','.$loaction[0];
						}
							
					
						
						$geoconv['coords']=implode(';', $geoconvs);
						$geoconv['from'] = $this->bkey2qkey($location[5]);
						
						//print_r ($geoconv['coords'] . '&<br/>' );
						
						$res=$this->service_qqmap_geocoder->hgeoconv($geoconv);
						if($res['status']=='0'){
							$output=array();
							
							$location['lng']=$res['result'][0]['x'];
							$location['lat']=$res['result'][0]['y'];

							foreach($res['locations'] as $poi)
							$output[]=$poi['lng'].' '.$poi['lat'];
							
							$area ['region_coord']=implode(',', $output);
							print_r ( $area ['region_coord'] . '&' );
						}
						else 
						{
				            print_r($res);
						}
						//die();
						
					}
		} elseif ($this->request->get ['p'] == 'meituan') {
		foreach ( $allowarea as $code => $data ){
			foreach ( $data as $key => $item ) {
				foreach ( $item as $key2 => $area ) {
					$goes = explode ( ';', $area ['region_coord'] );
					foreach ( $goes as $key => $value ) {
						$poi = explode ( ',', $value );
						$poi2 [x] = $poi [0] * 1000000;
						$poi2 [y] = $poi [1] * 1000000;
						$goes2 [] = $poi2;
					}
					
					$allowarea2 [] = array (
							"app_poi_code" => 'qingniancaijun_01',
							"app_shipping_code" => $area ['region_id'],
							'area' => $goes2,
							"min_price" => 0.1 
					); // $area['region_name']
				}
			}
		}
			$this->response->setOutput ( json_encode ( $allowarea2 ) );
		} else {
			//print_r($allowarea);
			
			// $this->response->setOutput(json_encode($allowarea));
			if ($this->request->get ['callback'])
				$res = $this->request->get ['callback'] . '(' . json_encode ( $allowarea ) . ')';
			else
				$res = json_encode ( $allowarea );
			
			$this->response->setOutput ( $res );
		}
	}
	
	
	public function bkey2qkey($key){
		/**百度坐标系
		 * 1：GPS设备获取的角度坐标，wgs84坐标;
		2：GPS获取的米制坐标、sogou地图所用坐标;
		3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标，国测局坐标;
		4：3中列表地图坐标对应的米制坐标;
		5：百度地图采用的经纬度坐标;
		6：百度地图采用的米制坐标;
		7：mapbar地图坐标;
		8：51地图坐标*/
		
		/**
		1 GPS坐标
		2 sogou经纬度
		3 baidu经纬度
		4 mapbar经纬度
		5 [默认]腾讯、google、高德坐标
		6 sogou墨卡托*/
		$arrkey[1]=1;
		$arrkey[2]=2;
		$arrkey[3]=5;
		$arrkey[4]=0;
		$arrkey[5]=3;
		$arrkey[6]=0;
		$arrkey[7]=0;
		$arrkey[8]=0;
		return isset($arrkey[$key])?$arrkey[$key]:0;
	}
	
	
	
	public function getPoiGeoconv(){
		
		$location['lng']  = $this->request->get['lng'];
		$location['lat']  = $this->request->get['lat'];

		
		$geoconv['from'] = $this->request->get['from']?(int)$this->request->get['from']:5;
		$geoconv['to'] = $this->request->get['to']?(int)$this->request->get['to']:5;
		
		if(in_array($geoconv['to'], array('5','6')))
		{	
		$geoconv['coords']=$location['lng'].','.$location['lat'];
		$this->load->service('baidu/geocoder');
		$res=$this->service_baidu_geocoder->hgeoconv($geoconv);
		
		}
		elseif(in_array($geoconv['to'], array('3')))
		{
			$geoconv['coords']=$location['lat'].','.$location['lng'];
			$geoconv['from'] = $this->bkey2qkey($location['from']);
			$this->load->service('qqmap/geocoder');
			$res=$this->service_qqmap_geocoder->hgeoconv($geoconv);
		}
		
		if($res['status']=='0'){		
			$res['result']['location']['lng']=$res['locations'][0]['lng'];
			$res['result']['location']['lat']=$res['locations'][0]['lat'];
			$res['result']['slocation']=$location;
		}
		else
		{
			$res['status']=1;
		}
		if($this->request->get['callback'])
			$resjson=$this->request->get['callback'].'('.json_encode($res).')';
		else
			$resjson=json_encode($res);
			
		$this->response->setOutput($resjson);

	}
	
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function getPoiByAddress(){
		
		$address = $this->request->clean_address ($this->request->get['address']);
		$city    =isset($this->request->get['city'])?$this->request->get['city']:'北京市';
		
		$this->load->service('baidu/geocoder');
		$res=$this->service_baidu_geocoder->hgetLocation($address,$city);
		if($res['status']=='0'){
			
			//$res['result']['location']['lng'].' '.$res['result']['location']['lat'];
			//$res['result']['location']['lng']=$res['result']['location']['lng'];
			//$res['result']['location']['lat']=$res['result']['location']['lat'];
			
			$res['result']['address']=$address;
		}
		else
		{
			$res['status']=1;
		}
		if($this->request->get['callback'])
			$resjson=$this->request->get['callback'].'('.json_encode($res).')';
		else
			$resjson=json_encode($res);
			
		$this->response->setOutput($resjson);

	}


	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function getByAddress(){
		$this->init();
		/*美食送接口测试*/
		$address = $this->request->clean_address ($this->request->get['address']);
		$city    =isset($this->request->get['city'])?$this->request->get['city']:'131';
	
		
		$this->load->service('baidu/geocoder');
		$location=$this->service_baidu_geocoder->hgetLocation($address,$city);
		if($location['status']=='0'){
			if($location['result']['confidence']>=0){
		      $location['lng'] = $location['result']['location']['lng'];
		      $location['lat'] = $location['result']['location']['lat'];
		           foreach($this->deliverys as $key=>$delivery){
		            	$res=$this->{'service_shipping_'.$key.'_shipping'.$delivery['version']}->hgetByLng($location);
			        if($res&&$res['status']=='1') {
			        	if(empty($res['data']['business_hour'])){
			        		$res ['data'] ['business_hours']=json_decode(htmlspecialchars_decode($delivery['business_hour']),1);
			        	}
			        	break;}
		         }
		         if(!isset($res['data']))$res['data']=array();
		         $res['data']=array_merge($location['result'],$res['data']) ;
		         
		         $res['data']['confidence']=$location['result']['confidence'];
		         
		        
		         
		         
			}
			else 
			{
				$res['status']='0';
				$res['message']='地址可信度低于0';
				$res['data']=$location['result'];
				$res['data']['address']=$address;
			}
		}
		else{
			$res['status']='0';
			$res['message']='找不到地址信息';
			$res['data']=$location;
			$res['data']['address']=$address;
		}

		if($this->request->get['callback'])
			$resjson=$this->request->get['callback'].'('.json_encode($res).')';
		else
			$resjson=json_encode($res);
		$this->response->setOutput($resjson);

	}
	
	
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByAddress(){
		$this->init();
		/*美食送接口测试*/
		$address = $this->request->clean_address ($this->request->get['address']);
		$city    =isset($this->request->get['city'])?$this->request->get['city']:'北京市';

		foreach($this->deliverys as $key=>$delivery){
		$res=$this->{'service_shipping_'.$key.'_shipping'.$delivery['version']}->hgetByAddress($address,$city);
		if($res&&$res['status']=='1'){
			        	if(empty($res ['data']['business_hour'])){
			        		$res ['data'] ['business_hours']=json_decode(htmlspecialchars_decode($delivery['business_hour']),1);
			        	}
			        	break;}
		}

		if($this->request->get['callback'])
			$resjson=$this->request->get['callback'].'('.json_encode($res).')';
		else
			$resjson=json_encode($res);
			
		$this->response->setOutput($resjson);
	
	}

	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByLng(){
		$this->init();
	
		$location['lng'] = $this->request->get['lng'];
		$location['lat'] = $this->request->get['lat'];
		$error=false;
		$geoconv['from'] = $this->request->get['from']?(int)$this->request->get['from']:5;
		$geoconv['to'] = $this->request->get['to']?(int)$this->request->get['to']:5;
		
		$slocation=array();
		if($geoconv['from']!='5'){	
			
			$this->load->service('baidu/geocoder');
			
			$geoconv['coords']=$location['lng'].','.$location['lat'];
			
			$res=$this->service_baidu_geocoder->hgeoconv($geoconv);
			
			if($res['status']=='0'){
			$slocation=$location;
			$location=array();
			$location['lng']=$res['locations'][0]['lng'];
			$location['lat']=$res['locations'][0]['lat'];
			}
			else 
			{
				$error=1;
			}
		}
		if(!$error){
			$res=array();
		  foreach($this->deliverys as $key=>$delivery){

			 $res=$this->{'service_shipping_'.$key.'_shipping'.$delivery['version']}->hgetByLng($location);
			 if($res&&$res['status']=='1') {
			        	if(empty($res ['data']['business_hour'])){
			        		$res ['data'] ['business_hours']=json_decode(htmlspecialchars_decode($delivery['business_hour']),1);
			        	}
			        	break;}
		  }
	     }
	     $res['data']['location']=$location;

	     if($slocation){
	     	$res['data']['slocation']=$slocation;
	     	$res['data']['geoconv']=$geoconv;
	     }

		if($this->request->get['callback'])
		    $resjson=$this->request->get['callback'].'('.json_encode($res).')';
		else 
			$resjson=json_encode($res);
		$this->response->setOutput($resjson);

	}
	
	
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetPost(){
		$this->load->service ('shipping/meishisong/shippingo3');
		//print_r($this->request->get['datastring']);
		if(isset($this->request->post['resetsign']))$resetsign=(int)$this->request->post['resetsign'];
		$res=$this->{'service_shipping_meishisong_shippingo3'}->hgetPost(htmlspecialchars_decode($this->request->post['datastring']),$resetsign);
		//print_r(json_decode($res,1));
		$this->response->setOutput($res);
	}
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function chksign(){
		$this->load->service ('shipping/meishisong/shippingo3');
		//print_r($this->request->get['datastring']);
		$res=$this->{'service_shipping_meishisong_shippingo3'}->chksign(htmlspecialchars_decode($this->request->get['datastring']));
		//print_r(json_decode($res,1));
		$this->response->setOutput($res);
	}
	

  }
	
?>