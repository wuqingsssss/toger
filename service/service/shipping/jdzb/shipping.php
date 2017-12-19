<?php 
class ServiceShippingJdzbShipping extends Service {
	
	private $regionarea=array();
	private $allowarea=array();
	private $key='';
	private $partner_id='';
	private $uri='';
	private $code='';
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->key=JDZB_KEY;
		$this->partner_id=JDZB_PARTNER_ID;
		$this->uri=JDZB_URI;//测试系统
		$this->code=JDZB_CODE;//测试系统
		$this->hsetRegion();
	    if(!$this->getRegion()){
	        $this->log_sys->error('hsetRegion:faile');
	     }

	     // $this->allowarea=json_decode(JDZB_ALLOWAREA,1);   
	}
	/* 获取所有可配送区域*/
	public function getRegion(){
		return $this->regionarea;
	}

	/* 拉取可配送区域*/
	private function hsetRegion()
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
		
		if(!$meiregion||defined('DEBUG')&&DEBUG){
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
					if($value['poi']){
						$value['local']=explode(' ', $value['poi']);
					    $value['local']['lng']=$value['local'][0];
					    $value['local']['lat']=$value['local'][1];
					}
					
					$value['children']=$this->model_catalog_pointdelivery->getDeliverys($this->code,'',$value['delivery_id']);
					foreach($value['children'] as $key=>$item)
					{
						$value['children'][$key]['city']=$item['zone_name'];
						$value['children'][$key]['ploygongeo']=$item['region_coord'];
						if($item['poi']){
							$item['local']=explode(' ', $item['poi']);
							$item['local']['lng']=$item['local'][0];
							$item['local']['lat']=$item['local'][1];
							$value['children'][$key]['local']=$item['local'];
						}
					}
					
					$meiregion[$value['zone_name']][$value['region_id']]=$value;
					
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
		$this->regionarea=$meiregion;
	}
	
	/* 获取菜君允许配送区域*/
	public function getAllowRegion()
	{
		return $this->getRegion();
	}
	
	/* 根据地址判断是否为配送区域，本地算法*/
	public function getByAddress($address,$city="北京市") {

	$this->load->service('baidu/geocoder');
	$res=$this->service_baidu_geocoder->hgetLocation($address,$city);
	$resjson['data']=$res['result'];
	$res['address']=$address;
    $this->log_sys->debug($res);
		if($res['status']=='0'){
			$resjson['status']='0';//初始值不在区域或者未找到
			$resjson['data']['inpolygon']='0';
			$resjson['data']['address']=$address;
			
			$point = $res['result']['location']['lng'].' '.$res['result']['location']['lat'];
			//$point="116.558765 39.83749";
			$this->log_sys->debug($point);
			$regionarea=$this->getAllowRegion();
			foreach($regionarea as $key =>$item)
			{
				foreach($item as $key2=>$area){
					
					if($area['smodel']=='2'){
					
					if($this->neighbors ($area['poihash'], $res['result']['location']))
						{
							$resjson ['status'] = 1;
							$resjson['data']=array_merge($res['result'],$area);
							$resjson['data']['inpolygon']=1;
							$resjson['data']['address']=$address;
							break;
						}
				
					}
					else{//默认使用围栏检索模式
						$polygon=explode(',',$area['region_coord']);
						// The last point's coordinates must be the same as the first one's, to "close the loop"
						$this->log_sys->debug($polygon);
						$inpolygon='0';
						$inpolygon=LBS::pointInPolygon($point, $polygon);
							
						$this->log_sys->debug($inpolygon);
						if(LBS::pointInPolygon($point, $polygon)){
							$resjson['status']=$inpolygon;
							$resjson['data']=array_merge($res['result'],$area);
							$resjson['data']['inpolygon']=$inpolygon;
							$resjson['data']['address']=$address;
							
							$children=$area['children'];
							if($children&&$area['poi'])//如果主站存在则把父站也作为子站的一个检测点
								$children[]=$area;
							break;
						}
						
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

		
		/* 如果在此区域内，则计算点距*/
		if($resjson['status']==1&&$children){
			
			$mindistance=0;
			foreach($children as $key=> $child){

				$distance[$key] = $this->geohash->getDistance($location['lat'],$location['lng'],$child['local']['lat'],$child['local']['lng']);
		    if(!$mindistance||$distance[$key]<$mindistance) {
		    	$mindistance=$distance[$key];
		    	$minchild=$child;
		    }
			}
			//print_r($distance);
			//print_r($minchild);
			$resjson ['data'] = array_merge ( $resjson ['data'], $minchild );
		}
		
		return $resjson;
	
	}
	/* 根据经纬度判断是否为配送区域，算法*/
	public function hgetByAddress($address,$city="北京市"){
		
		$ress=$this->getByAddress($address,$city="北京市");
		$this->log_sys->debug($ress);
		if($ress['status']>'0' ){//&& $this->allowarea
			$res['status']=1;
			$res['message']='地址在菜君可配送区域';
			$res['data']=$ress['data'];
			$res['data']['shippingcode']=$this->code;

		}else{
			$res['status']=0;
			$res['message']='菜君尚未开通此区域';
			$res['data']=$ress['data'];
			
		}

		return $res;
	
	}
	/* 根据经纬度判断是否为配送区域，本地算法*/
	public function getByLng($location){
		// $location=array('lng'=>'116.471144','lat'=>'40.003241');	
		$resjson ['status'] = '0'; // 初始值不在区域或者未找到
		$resjson ['data'] ['inpolygon'] = '0';
		$resjson ['data'] ['location'] = $location;

		$this->load->service('baidu/geocoder');
		$address=$this->service_baidu_geocoder->hgetAddress($location);
		$resjson ['data']   =$address['result'];
		$resjson ['data']['address']=$address['result']['formatted_address'].$address['result']['sematic_description'];

		$point = $location ['lng'] . ' ' . $location ['lat'];
		$regionarea = $this->getAllowRegion ();
		$children=array();
		foreach ( $regionarea as $key => $item ) {
			foreach ( $item as $key2 => $area ) {

				if ($area ['smodel'] == '2') {
						if($this->neighbors ($area['poihash'], $location))
						{
							$resjson ['status'] = 1;
							$resjson ['data'] = array_merge ( $address ['result'], $area );
							$resjson ['data'] ['inpolygon'] = 1;
							$resjson ['data'] ['location'] = $location;
							$resjson ['data'] ['address'] = $address ['result'] ['formatted_address'] . $address ['result'] ['sematic_description'];
							$children=$area['children'];
							break;
						}
				} else { // 默认使用围栏检索模式
					$polygon = explode ( ',', $area ['region_coord'] );
					// The last point's coordinates must be the same as the first one's, to "close the loop"
					$inpolygon = '0';
					$inpolygon = LBS::pointInPolygon ( $point, $polygon );
					
					$this->log_sys->debug ( $inpolygon );
					if (LBS::pointInPolygon ( $point, $polygon )) {
						$resjson ['status'] = $inpolygon;
						$resjson ['data'] = array_merge ( $address ['result'], $area );
						$resjson ['data'] ['inpolygon'] = $inpolygon;
						$resjson ['data'] ['location'] = $location;
						$resjson ['data'] ['address'] = $address ['result'] ['formatted_address'] . $address ['result'] ['sematic_description'];
						$children=$area['children'];
						if($children&&$area['poi'])//如果主站存在则把父站也作为子站的一个检测点
						$children[]=$area;
						break;
					}
				}
			}
		}
		
		
		/* 如果在此区域内，则计算点距*/
		if($resjson['status']==1&&$children){
			
			$mindistance=0;
			foreach($children as $key=> $child){

				$distance[$key] = $this->geohash->getDistance($location['lat'],$location['lng'],$child['local']['lat'],$child['local']['lng']);
		    if(!$mindistance||$distance[$key]<$mindistance) {
		    	$mindistance=$distance[$key];
		    	$minchild=$child;
		    }
			}
			//print_r($distance);
			//print_r($minchild);
			$resjson ['data'] = array_merge ( $resjson ['data'], $minchild );
		}
		return $resjson;
	}

	/* 根据经纬度判断是否为配送区域，算法*/
	public function hgetByLng($location){
	
		$ress=$this->getByLng($location);

	  /* 对搜索地区进行过滤*/
	if($ress['status']>'0' ){//&& $this->allowarea
			$res['status']=1;
			$res['message']='地址在菜君可配送区域';
			$res['data']= $ress['data'];
			$res['data']['shippingcode']=$this->code;

		}else{
			$res['status']=0;
			$res['message']='菜君尚未开通此区域';
			$res['data']= $ress['data'];
			
		}

		return $res;
	}

	/* 根据经纬度计算附件的点，本地算法*/
	public function neighbors($spoihash,$location,$level=5){
		//11:±0.0001492km 10:0.0005971km 9:±0.00478km 8:0.01911km 7:0.076km 6 0.6km 5：2.4km  4:20km 3:78km 2:230km
		//得到这点的hash值
		$hash = $this->geohash->encode($location['lat'], $location['lng']);
		//取前缀，前缀约长范围越小
		$prefix = substr($hash, 0, $level);
		//取出相邻八个区域
		$neighbors = $this->geohash->neighbors($prefix);
		array_push($neighbors, $prefix);
		$spoihash=substr($spoihash, 0, $level);
		return in_array($spoihash,$neighbors);
	}
	
}
?>