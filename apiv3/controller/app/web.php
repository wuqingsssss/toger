<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerAppWeb extends Controller {
	/* 获取api授权*/
	private function get_sp_key($appid){
	
		$config=array(
				'app14070810'=>'qncj201614070810',//测试服务器182.92.190.78
		);
	
		if(isset($config[$appid])) return $config[$appid];
		else
			return false;
	
	}
	public function test(){
		// header("Access-Control-Allow-Origin: *");//跨域问题

		$this->request->get = Array (
				'appid' => 'app14070810',
				'sign_method' => '1',
				'device_code' => 'aaaaaaaaaaaaaaaaaa',
				'route'=>'app/web/lastVersion',
				'hversion'=>'v2.0.20160202.1454402119'
		);
		
		$chkdata=$this->request->get;
		unset($chkdata['route']);
		//print_r(HTTP::createLinkstring($chkdata, $this->get_sp_key ( $this->request->get ['appid'] )));
		$this->request->get ['sign'] = HTTP::make_sign ( $chkdata, $this->get_sp_key ( $this->request->get ['appid'] ) );

		//print_r($this->request->get);
		
		//print_r(HTTP::buildURL('http://test.qingniancaijun.com.cn/apiv3/index.php',$this->request->get));
		
         $this->lastVersion();
         
	}
/*获取api授权获取最新版本号并返回更新包路径*/
	public function lastVersion()
	{
		$chkdata=$this->request->get;
		unset($chkdata['route']);
		if (HTTP::check_sign ( $chkdata, $this->get_sp_key ( $this->request->get ['appid'] ))){
			$this->log_sys->info ( '校验成功' . serialize ( $chkdata ) );
		} else {
			$this->log_sys->warn ( '校验失败' . serialize ( $chkdata ) );
			$result ['error'] = '1';
			$result ['message'] ='校验失败';
			$this->response->setOutput ( json_encode ( $result ) );
			return;
		}
		$oldversion=$this->request->get['hversion'];
		$device_code=$this->request->get['device_code'];
		$platform=$this->request->get['platform'];
		$version=$this->config->get('app_web_version');
		
		$historyversion=$this->historyversion('web-pic');
		
		//$chkres=$this->oss->is_object_exist($bucket,$object_name);
		$updateurl='';
		//if($oldversion&&isset($historyversion[$oldversion])){
			//if(file_exists(DIR_ROOT.'update/data/app/web/upgrade/'.$oldversion.'_'.$version.'.zip'))
			if($this->oss->is_object_exist('web-pic','update/data/app/web/upgrade/'.$oldversion.'_'.$version.'.zip'))
			{
			$updateurl=(defined('OSS_SERVER')&&OSS_SERVER?OSS_SERVER: HTTP_BASE).'update/data/app/web/upgrade/'.$oldversion.'_'.$version.'.zip';
			}	
		//}
		else 
		{
			$updateurl=(defined('OSS_SERVER')&&OSS_SERVER?OSS_SERVER: HTTP_BASE).'update/data/app/web/release/'.$version.'.zip';
		}

		$res['error']='0';
		$res['data']['url']=$updateurl;
		$res['data']['version']=$version;
		$this->response->setOutput ( json_encode($res) );
	}
//重新生成新的差分文件
	public function buildWeb()
	{//获取可支持的所有配送区域

	}
	
	public function diffVersion(){
		
		
	}
	/*
	private function  historyversion(){
		$dir=@opendir(DIR_ROOT.'update/data/app/version/');
		while ($entry = @readdir($dir))
		{
			if ($entry != '.' && $entry != '..')
			{
				$check_files[basename($entry,'.md5')]=$entry;
			}
		}
		return $check_files;
	}*/
	function historyversion($bucket){
		$check_files=array();
		$options = array(
				'delimiter' => '',
				'prefix' => 'update/data/app/web/version/',
				//列表个数,默认最大100个
				'max-keys' =>100,
				'marker' => $marker
		);
		$filelist= $this->oss->list_object($bucket,$options);
		if(isset($filelist['Contents']))
		{
			if($filelist['Contents']['Key']){
				$check_files[basename($filelist['Contents']['Key'],'.md5')]=$filelist['Contents']['Key'];
			}
			else
			{
				foreach($filelist['Contents'] as $obj)
				{
					$check_files[basename($obj['Key'],'.md5')]=$obj['Key'];
				}
			}
		}
		return $check_files;
	}

  }
	
?>