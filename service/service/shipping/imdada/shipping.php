<?php 
class ServiceShippingImdadaShipping extends Service {
	
	private $allowarea=array();
	private $key='';
	private $partner_id='';
	private $uri='';
	private $code='';
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));
		$this->key=IMDADA_KEY;
		$this->partner_id=IMDADA_PARTNER_ID;
		$this->uri=IMDADA_URI;//测试系统
		$this->code=IMDADA_CODE;//测试系统
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
	
/* 配送订单添加接口*/
	public function haddRecord($data)
	{
		
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
		$resarea=array();
	
			$resarea=$this->allowarea;
	
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
					if(LBS::pointInPolygon($point, $polygon)){					
						$resjson['state']=$inpolygon;
						$resjson['data']=array_merge($res['result'],$area);
						$resjson['data']['inpolygon']=$inpolygon;
						$resjson['data']['address']=$address;
						break;
					}
				}
			}
		}
		else {
			$resjson['state']='-1';//初始值不在区域或者未找到
			$resjson['data']=$res['result'];
			$resjson['data']['inpolygon']='0';
			$resjson['data']['address']=$address;
		}
		
		return $resjson;
	
	}
	/* 根据经纬度判断是否为美食送配送区域，美食送算法*/
	public function hgetByAddress($address,$city="北京市"){
		
		/*达达送接口测试*/
		$data=array(
				'partner_id'=>$this->partner_id,
				'address'=>$address,
				'city'=>'北京市');
	
		$data['sign']=md5('address='.$data['address'].'city='.$data['city'].'partner_id='.$data['partner_id']);
	
	
		$url=$this->uri.'/v1_0/checkOutDistrict/';
	
		$res=json_decode(HTTP::getGET($url,$data),1);
	
		if($res['status']=='1' ){//&& $this->allowarea
			$res['message']='地址在菜君可配送区域';
			if(isset($this->allowarea[$res['data']['city']])){
				if((array_search($res['data']['region_name'],$this->allowarea[$res['data']['city']])===false)){
					$res['status']=2;
					$res['message']='菜君尚未开通此区域';
				}
				$res['data']['shippingcode']='qncj';
			}
			else
			{
				$res['status']=2;
				$res['message']='菜君尚未开通此区域';
			}
	
		}
		else 
		{
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
		
				$resjson['state']='0';//初始值不在区域或者未找到
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
							$resjson['state']=$inpolygon;
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
		
		
		$res['status']=2;
		$res['message']='菜君尚未开通此区域';
		
		 return $res;
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
		if($res['status']=='1' ){//&& $this->allowarea
			if(isset($this->allowarea[$res['data']['city']])){
				if((array_search($res['data']['region_name'],$this->allowarea[$res['data']['city']])===false)){
					$res['status']=2;
					$res['message']='菜君尚未开通此区域';
				}
			}
			else
			{
				$res['status']=2;
				$res['message']='菜君尚未开通此区域';
			}
		
		}

		return $res;
	
	}

	/* 根据经纬度计算附件的点，本地算法*/
	public function lbs($location){

		//得到这点的hash值
		
		$hash = $this->geohash->encode($location['lat'], $location['lng']);
		
		//取前缀，前缀约长范围越小
		
		$prefix = substr($hash, 0, 6);
		
		//取出相邻八个区域
		
		$neighbors = $this->geohash->neighbors($prefix);
		
		array_push($neighbors, $prefix);
		
		return $neighbors;
	}
	
}
?>