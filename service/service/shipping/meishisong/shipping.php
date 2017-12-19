<?php 
class ServiceShippingMeishisongShipping extends Service {
	
	private $meiregionarea=array();
	private $allowarea=array();
	private $key='';
	private $partner_id='';
	private $uri='';
	private $update_coord=false;
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->key=MEISHI_KEY;
		$this->partner_id=MEISHI_PARTNER_ID;
		$this->uri=MEISHI_URI;//测试系统
		$this->code=MEISHI_CODE;//测试系统
		if(defined('MEISHI_UPDATE_COORD'))
		$this->update_coord=MEISHI_UPDATE_COORD;
		
		$this->hsetMssRegion();
	    if(!$this->getMssRegion()){
	        $this->log_sys->warn('hsetMssRegion:empty');
	     }
	     $this->setAllowarea();
	     if(!$this->getAllowarea()){
	     	$this->log_sys->warn('setAllowarea:empty');
	     }
	     // $this->allowarea=json_decode(QNCJ_ALLOWAREA,1);   
	}
	public function setAllowarea(){

		if($this->mem){
			$this->allowarea = $this->mem->get($this->code.'_shipping_allowarea');
		}
		if(!$this->allowarea){
			$this->load->model('catalog/pointdelivery');
			$allowareas=$this->model_catalog_pointdelivery->getDeliverys($this->code);

		    foreach($allowareas as $key=>$point)
		      {
			    $this->allowarea[$point['zone_name']][]=$point['region_name'];
		     }

		     $this->log_sys->info($this->allowarea);
		   if($this->mem){
			$this->mem->set($this->code.'_shipping_allowarea',$this->allowarea,0,1800);
			$this->log_db->debug('memcache:set:'.$this->code.'_shipping_allowarea');
		    }
		}
		else
		{
			$this->log_db->debug('memcache:get:'.$this->code.'_shipping_allowarea');
		}
	}
	
	public function getAllowarea(){
		
		return $this->allowarea;
	}
	/* 获取美食送所有可配送区域*/
	public function getMssRegion(){
		return $this->meiregionarea;
	}
	/* 设置美食送所有可配送区域*/
	private function setMssRegion($meiregionarea)//本地算法
	{
		foreach($meiregionarea as $key =>$item)
		{
			if($item['subzone']){
				foreach($item['subzone'] as $key2=>$area){
					if($area['region_coord'])
					{
						$resarea["$item[region_name]"][$key2]=$meiregionarea[$key]['subzone'][$key2];
						$resarea["$item[region_name]"][$key2]['city']=$item['region_name'];
						$resarea["$item[region_name]"][$key2]['ploygongeo']=$area['region_coord'];
					}
				}
			}
		}
		$this->meiregionarea=$resarea;
	}
	/* 从美食送拉取可配送区域*/
	private function hsetMssRegion()
	{
		
		if($this->mem){
			$meiregion = $this->mem->get('MssRegion');
			$this->log_db->debug('memcache:get:MssRegion');
		}
		else 
		{
		$meiregion = $this->cache->get('MssRegion');
		$this->log_db->debug('cache:get:MssRegion');
		}
		
		
		if(!$meiregion){
			$this->load->model('catalog/pointdelivery');
			$url=$this->uri.'/Getregion/getMssRegion';
						
			$ress=json_decode(HTTP::getGET($url),1);
		
			$this->log_sys->info($ress);
			
			$meiregion=array();
			if($ress){
			foreach($ress as $key=>$value)
			{
				if($value&&isset($value['city'])){
					$meiregion[$value['city']][$value[region_id]]=$value;
					$value['region_coord']=$value['ploygongeo'];
					if($this->update_coord){
					$this->model_catalog_pointdelivery->updatePointDelivery($value,$this->code);
					$this->log_sys->info('model_catalog_pointdelivery::'.$this->code."::".serialize($value));
					}
					
				}
			}	
			
	
			
			if($this->mem){	
				$this->mem->set('MssRegion',$meiregion,0,7200);
				$this->log_db->debug('memcache:set:MssRegion');
			}
			else 
			{
			
			$this->cache->set('MssRegion',$meiregion,strtotime(date("Y/m/d",time()+3600*24)));
			$this->log_db->debug('cache:set:MssRegion');
			}
			}
			
		}
		$this->meiregionarea=$meiregion;
	}
	
/* 美食送订单添加接口*/
	public function haddRecord($data)
	{
		
		/*$data='{"partner_id":"100049"
		 ,"partner_order_id":"123456"
		 ,"invoice":"梅赛德斯"
		 ,"push_time":"1367583472"
		 ,"notify_url": "http://www.meishisong.com"
		 ,"total_price":"500"
		 ,"add_time":"1367583472"
		 ,"request_time":"1367583472"
		 ,"remark":"用户的需求，比如免辣什么的"
		 ,"if_store_pay":"1"
		 ,"city":"北京"
		 ,"if_pay":"1"
		 ,"payment_name":"货到付款"
		 ,"shipping_fee":"0"
		 ,"shipping_name":"及时送达"
		 ,"expectmeal_time":""
		 ,"order_placed":"true"
		 ,"order_plat":"false"
		 ,"custom_info":{"buyer_id":"11","buyer_name":"饿了么","consignee":"Mr","phone_mob":"13590292020","phone_tel":"64352456","address":"海天盛筵"}
		 ,"order_items":{
		 "order_goods":[{"goods_id":"2667"
		 ,"goods_name":"goods name"
		 ,"price":"8.50"
		 ,"quantity":"12"
		 ,"specification":"spe"
		 ,"goods_remark":"第一篮","discount":"1.00","packing_fee":"0"
		 ,"garnish":[{"goods_id":"","goods_name":"","price":"","quantity":"","specification":""}]
		 }]
		 }
		 ,"store_info":{"seller_id":"184","seller_name":"seller name","address":"street A503","tel":"64311111","store_lng":"","store_lat":""}
		 }
		 ';*/
		 
		//$data='{"partner_id":"100049","partner_order_id":"15062411899","invoice":"","push_time":"1435484300","notify_url":"http://apid.qingniancaijun.com.cn:8080/BPMCommon/services/rs/mss","total_price":"0.0000","add_time":"1435484300","request_time":"1435478400","remark":"","if_store_pay":"2","city":"北京","if_pay":"1","payment_name":"在线支付","shipping_fee":"0","shipping_name":"及时送达","expectmeal_time":"","order_placed":"true","order_plat":"false","custom_info":{"buyer_id":"25","buyer_name":"绣菊园","consignee":"绣菊园","phone_mob":"","phone_tel":"400-882-1551","address":""},"order_items":{"order_goods":[{"goods_id":"150","goods_name":"鱼香肉丝","price":"0.0000","quantity":"1","specification":"盒","goods_remark":"","discount":"1.00","packing_fee":"0","garnish":[{"goods_id":"000","goods_name":"","price":"","quantity":"","specification":""}]},{"goods_id":"11","goods_name":"木须肉","price":"0.0000","quantity":"1","specification":"盒","goods_remark":"","discount":"1.00","packing_fee":"0","garnish":[{"goods_id":"000","goods_name":"","price":"","quantity":"","specification":""}]},{"goods_id":"94","goods_name":"宫保鸡丁","price":"0.0000","quantity":"1","specification":"盒","goods_remark":"","discount":"1.00","packing_fee":"0","garnish":[{"goods_id":"000","goods_name":"","price":"","quantity":"","specification":""}]}],"store_info":{"seller_id":"100049","seller_name":"青年菜君（北苑）","address":"北京市朝阳区立水桥江安家园C101","tel":"18518960778","store_lng":"","store_lat":""}},"sign":"2eb37257188b9dcaf1f86d456dbef032"}
		//';
		
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
	/* 获取美食送菜君允许配送区域*/
	public function getAllowRegion($sall=false)
	{
		
	
		$meiregionarea=$this->getMssRegion();
		
		$resarea=array();
		if(!$sall){//$this->allowarea&&
			foreach($meiregionarea as $key =>$item)
			{
				if(isset($this->allowarea[$key]))
				{
					if($this->allowarea[$key]!=''){
			
						foreach($item as $key2=>$area){
							if(in_array($area['region_name'],$this->allowarea[$key])&&$area['ploygongeo'])
							{
								
								$area['region_coord']=$area['ploygongeo'];
								$resarea[$key][$key2]=$area;
								
								
							}
						}}
						else
						{
							$resarea[$key]=$item;
								
						}
	
				}
			}
		}
		else
		{
			$resarea=$meiregionarea;
		}
	
		return $resarea;
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
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByAddress($address,$city="北京市"){
		
		/*美食送接口测试*/
		$data=array(
				'partner_id'=>$this->partner_id,
				'address'=>$address,
				'city'=>'北京市');
	
		$data['sign']=md5('address='.$data['address'].'city='.$data['city'].'partner_id='.$data['partner_id']);
	
	
		$url=$this->uri.'/Getregion/getByAddress';
	
		$res=json_decode(HTTP::getGET($url,$data),1);
	
		if($res['status']=='1'){// && $this->allowarea
			if(isset($this->allowarea[$res['data']['city']])){
				if((array_search($res['data']['region_name'],$this->allowarea[$res['data']['city']])===false)){
					$res['status']=2;
					$res['message']='菜君尚未开通此区域';
				}
			$res['data']['shippingcode']=$this->code;
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
	public function hgetByLng($location){
		
	/*美食送接口测试*/
		
		$lng = $location['lng'];
		$lat = $location['lat'];
		$data=array('partner_id'=>$this->partner_id,
				'lng'=>$lng,
				'lat'=>$lat);
	
		$data['sign']=md5('lng='.$data['lng'].'lat='.$data['lat'].'partner_id='.$data['partner_id']);
	
	
		$url=$this->uri.'/Getregion/getByLng';

		$res=json_decode(HTTP::getGET($url,$data),1);

	  /* 对搜索地区进行过滤*/
		if($res['status']=='1'){// && $this->allowarea
			if(isset($this->allowarea[$res['data']['city']])){
				if((array_search($res['data']['region_name'],$this->allowarea[$res['data']['city']])===false)){
					$res['status']=2;
					$res['message']='菜君尚未开通此区域';
				}
				$res['data']['shippingcode']=$this->code;
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

}
?>