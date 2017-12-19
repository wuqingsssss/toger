<?php 
class ServiceBaiduPoint extends Service {
	/*百度云存储支持开始*/
	private $ak='';
	private $sk='';
	private $geotable_id='';
	private $uri='';
	
	public function __construct($registry) {
		//header("Access-Control-Allow-Origin: *");//跨域问题
		   parent::__construct($registry,dirname(__FILE__));

	    $this->ak=BDYUN_AK;
	    $this->sk=BDYUN_SK;
	    $this->geotable_id=GEOTABLE_ID;

		$this->uri=BDYUN_URI;
	}
	
	
	/*获取或查询运数据库数据*/
	public function hlist($filters=array()){
	
		
	
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/poi/list';
	
		$url = $this->uri.$uri;//."?geotable_id=%s&page_size=%s&ak=%s&sn=%s";
	
		$page_size=200;
	
		//构造请求串数组
		$querystring_arrays = array (
				'geotable_id'=>$this->geotable_id,
				'page_size'=>$page_size,
				'ak' => $this->ak
		);
	
		$querystring_arrays=array_merge($filters,$querystring_arrays);
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $querystring_arrays);
	
		//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
	
		$querystring_arrays['sn']=$sn;
	
		$resjson=HTTP::getGET($url,$querystring_arrays);
	
		$res=json_decode($resjson,1);
	
		return $res['pois'];
	
	}
	
	
	/*更新云数据库
	 *
	 *
	 * $data['point_code']为识别唯一主键*/
	public function hpoicreate($data){
		 
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/poi/create';
			
		$url = $this->uri.$uri;
		//构造请求串数组
		/*
title 	poi名称 	string(256) 	可选
address 	地址 	string(256) 	可选
tags 	tags 	string(256) 	可选
latitude 	用户上传的纬度 	double 	必选
longitude 	用户上传的经度 	double 	必选
coord_type 	用户上传的坐标的类型 	uint32 	必选
1：GPS经纬度坐标
2：国测局加密经纬度坐标
3：百度加密经纬度坐标
4：百度加密墨卡托坐标
geotable_id 	记录关联的geotable的标识 	string(50) 	必选，加密后的id
ak 	用户的访问权限key 	string(50) 	必选
sn 	用户的权限签名 	string(50) 	可选
{column key} 	用户在column定义的key/value对 	开发者自定义的类型（string、int、double） 	唯一索引字段需要保证唯一，否则会创建失败 */
		$data['coord_type']=3;
		$data['geotable_id']=$this->geotable_id;
		if($id)$data['id']=$id;
		$data['ak']=$this->ak;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data,'POST');
		$data['sn']=$sn;
	
		$this->log_sys->info('hpoicreate host2yun::data:'.serialize($data));
		$res=json_decode(HTTP::getPOST($url,$data),1);
		$this->log_sys->info('hpoicreate host2yun::res:'.serialize($res));
		return $res;
	}
	
	
	/*更新云数据库
	 * 
	 * 
	 * $data['point_code']为识别唯一主键*/
	public function hpoiupdate($data,$id=''){
	  
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/poi/update';
			
		$url = $this->uri.$uri;
		//构造请求串数组
		/*'id'=>'',
		 'title'=>'',
		 'address '=>'',
		 'tags'=>'',
		 'latitude'=>'',
		 'longitude'=>'',
		 'coord_type'=>'',
		 'geotable_id'=>$this->geotable_id,
		 'page_size'=>$page_size,
		 'device_code'=>'',
		 'point_code'=>'',
		 'pick_up_time'=>'',
		 'tel'=>'',
		 'cbd'=>'',
		 'ak' => $this->ak*/
		$data['coord_type']=3;
		$data['geotable_id']=$this->geotable_id;
		if($id)$data['id']=$id;
		$data['ak']=$this->ak;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data,'POST');
		$data['sn']=$sn;
	
		$this->log_sys->info('hpoiupdate host2yun::data:'.serialize($data));
		$res=json_decode(HTTP::getPOST($url,$data),1);
		$this->log_sys->info('hpoiupdate host2yun::res:'.serialize($res));
		return $res;
	}
	
	
	public function hpoidelete($data){
		$res=false;
		if($data){
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/poi/delete';
		$url = $this->uri.$uri;
		$data['geotable_id']=$this->geotable_id;;
		$data['ak']=$this->ak;
		$data['is_total_del']='1';
	
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data,'POST');
		$data['sn']=$sn;

		$this->log_sys->info('hpoidelete host2yun::data:'.serialize($data));
		$res=json_decode(HTTP::getPOST($url,$data),1);
		$this->log_sys->info('hpoidelete host2yun::res:'.serialize($res));
		}
		return $res;
	}
	
	/*更新云数据库*/
	public function hgeotabledetail(){
	
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/geotable/detail';
		$url = $this->uri.$uri;
		$data['id']=$this->geotable_id;;
		$data['ak']=$this->ak;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data);
		$data['sn']=$sn;
	
		$this->log_sys->info('data:'.serialize($data));
		$res=json_decode(HTTP::getGET($url,$data),1);
		$this->log_sys->info('res:'.serialize($res));
		print_r($res);
		//return $res;
	}
	/*更新云数据库*/
	public function hcolumnlist(){
	
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/column/list';
		$url = $this->uri.$uri;
		$data['geotable_id']=$this->geotable_id;
		//$data['key']='point_code';
		$data['ak']=$this->ak;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data);
		$data['sn']=$sn;
	
		$this->log_sys->info('data:'.serialize($data));
		$res=json_decode(HTTP::getGET($url,$data),1);
		$this->log_sys->info('res:'.serialize($res));
		print_r($res);
		//return $res;
	}
	
	public function hcolumnupdate(){
	
		
		//以Geocoding服务为例，地理编码的请求url，参数待填
	
		//get请求uri前缀
		$uri = '/geodata/v3/column/update';
			
		$url = $this->uri.$uri;
	
		//构造请求串数组
		/*id 	     列主键   	uint32 	 必选
		 geotable_id 	所属表主键 	uint32 	必选
		 name 	属性中文名称 	string(45) 	可选
		 default_value 	默认值 	string 	可选
		 max_length 	文本最大长度 	int32 	字符串最大长度，只能改大，不能改小
		 is_sortfilter_field 	是否检索引擎的数值排序字段 	uint32 	1代表是，0代表否，可能会引起批量操作
		 is_search_field 	是否检索引擎的文本检索字段 	uint32 	1代表是，0代表否，会引起批量操作
		 is_index_field 	是否存储引擎的索引字段 	uint32 	1代表是，0代表否
		 is_unique_field 	是否存储索引的唯一索引字段 	uint32 	1代表是，0代表否
		 ak 	用户的访问权限key 	string(50) 	必选
		 sn */
	
		$data['geotable_id']=$this->geotable_id;
		$data['id']='165218';
		$data['is_sortfilter_field']=1;
		//$data['is_index_field']=1;
		//$data['is_search_field']=1;
		$data['default_value']=0;
		$data['ak']=$this->ak;
	
		//调用sn计算函数，默认get请求
		$sn = HTTP::caculateAKSN($this->ak, $this->sk, $uri, $data,'POST');
		$data['sn']=$sn;
		print_r($data);
		$this->log_sys->info('data:'.serialize($data));
		$res=json_decode(HTTP::getPOST($url,$data),1);
		$this->log_sys->info('res:'.serialize($res));
		print_r($res);
		//return $res;
	}
	/* 百度云存储支持结束 */		
}
?>