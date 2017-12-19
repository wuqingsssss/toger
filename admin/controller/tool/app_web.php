<?php 
class ControllerToolAppWeb extends Controller { 
	private $error = array();
	public function index() {	
		$this->load_language('tool/app_web');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_done'] = $this->language->get('button_done');

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/app_web', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		$res_obj = $this->oss->list_bucket();

   		$buckets=$res_obj['Buckets']['Bucket'];
   		$this->data['buckets']=$buckets;

		$this->template = 'tool/app_web.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';

		$this->render();
	}

	private function set_app($data){
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('app', $data);
	}
	
	public function clear(){
		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];
		$maxkeys=	isset($this->request->get ['maxkeys'])?(int)$this->request->get ['maxkeys']:10;
		$marker=$this->request->get ['marker'];
		
		$res['status']=1;
		$res['message']='成功查询到结果';
		
		$options = array(
				//分隔符
				'delimiter' => '',
				//前缀
				'prefix' => $prefix,
				//列表个数,默认最大100个
				'max-keys' => $maxkeys,
				'marker' => 		$marker
		);

		$objects=$this->oss->clear_objects($bucket,$options);
		$res['objects']=$objects;
		foreach ($objects as $key=> $file){
			$objects[$key]='delete->'.$file;
		}
		$res['options']=$options;
		$res['data']=$objects;
		$res['frontmarker']='false';
		$res['nextmarker']=$filelist['NextMarker'];
		
		
		$this->response->setOutput (json_encode ( $res ));
		
		/*
		$res['status']=1;
		$res['message']='成功查询到结果';
		
		$options = array(
				'delimiter' => '',
				'prefix' => 'update/data/app/web',
				//列表个数,默认最大100个
				'max-keys' =>1,
				'marker' => $marker
		);
		////$this->load->service ( 'ali/alioss' ,'service');
		
		$filelist= $this->oss->list_object($bucket,$options);

		$version='';

		if(isset($filelist['Contents']['Key']))$version=basename($filelist['Contents']['Key'],'.zip');
*/
		
		$this->upload_file_by_dir($bucket,DIR_ROOT.$prefix,false);
		
		$version=$this->config->get('app_web_version');
		
		$chkres=$this->oss->is_object_exist($bucket,"update/data/app/web/".$version.".zip");
		if(!$chkres){
		$this->build();	
		}
		//$this->set_app(array('app_web_version'=>$version));
	}
	

	public function build(){

		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];
		
		
		include_once(DIR_SYSTEM . 'library/zip.php');
		$zip = new Zip;

		$version_files=$this->historyversion($bucket);
		
		
		$version="v2.0.".date("Ymd").".".time();

		$this->set_app(array('app_web_version'=>$version));

		//$zip->add_dir('www');
		//$zip->Add(DIR_ROOT."update/data/app/www","www/".$version.".zip");
		$out=$zip->CompileZipFile(DIR_ROOT."update/data/app/www",DIR_ROOT."update/data/app/web/release/".$version.".zip",DIR_ROOT."update/data/app/www");
		
		$files=$zip->filelist;
		$md5data=array();
		foreach($files as $file)
		{
			$md5data[$file] = @md5_file($file);
		}
		$file= $this->savefiles($md5data,$version);

		
		$chkfile=array();
		if($version_files){
		foreach($version_files as $vfile)
		{
			$appobject=$this->oss->get_object($bucket,$vfile);
			if($appwebfiles = explode('@r@n',$appobject->body))
			{
				$md5datanew=array();
				$modifylist=array();
				$md5datanew=array();
				$dellist=array();
				$addlist=array();
				foreach ($appwebfiles as $line)
				{if($line){
					$file = trim(substr($line, 34));
					$md5datanew[$file] = substr($line, 0, 32);
					if ($md5datanew[$file] != $md5data[$file])
					{
						$modifylist[$file] = $md5data[$file];
					}
					$md5datanew[$file] = $md5data[$file];
				}
				}
			//$chkfile[$vfile]['md5data']=$md5datanew;
			$chkfile[$vfile]['addlist']    =$addlist= @array_diff_assoc($md5data, $md5datanew);
			$chkfile[$vfile]['dellist']    =$dellist= @array_diff_assoc($md5datanew, $md5data);
			$chkfile[$vfile]['modifylist'] =$modifylist= @array_diff_assoc($modifylist, $dellist);
			$chkfile[$vfile]['updatelist'] = @array_merge($addlist, $modifylist);
			//$chkfile[$vfile]['showlist'] = @array_merge($md5data, $md5datanew);
			$zip=new Zip;
			$out=$zip->CompileZipFile(array_keys($chkfile[$vfile]['updatelist']),DIR_ROOT."update/data/app/web/upgrade/".basename($vfile,'.md5').'_'.$version.".zip",DIR_ROOT."update/data/app/www",'filelist');
			}
		}
		}
		//print_r($chkfile);

		$res['status']=1;
		$res['message']='成功查询到结果';
		$res['data']=$this->upload_file_by_dir($bucket,DIR_ROOT.$prefix,false);
		$res['frontmarker']='false';
		$res['error']=$this->error;
		$this->response->setOutput (json_encode ( $res ));
	}
	
	
	private function upload_file_by_dir($bucket,$dir,$type=true){		
		$res=array();
		if(is_dir($dir)){
			$dir=rtrim($dir,'/');
			$files=glob($dir.'/*');

			foreach ($files as $file)
			{
				$res=array_merge($res,$this->upload_file_by_dir($bucket,$file,$type));
			}
		}
		else {
			if(stripos('.db', $dir)===false){
				$object_name=str_replace(DIR_ROOT, '', $dir);
				try{
				
					//$this->load->service ( 'ali/alioss' ,'service');
					$chkres=$this->oss->is_object_exist($bucket,$object_name);
					if(!$chkres||$type){
					$res[$object_name]='upload_file_by_file->'.$object_name;
					////$this->load->service ( 'ali/alioss' ,'service');
					$this->oss->upload_file_by_file($bucket,$object_name,$dir);
					}
					
				}
				catch (Exception $e)
				{
					$this->error[$object_name]=$e->getMessage();
				}
			}
	
		}
	return $res;
	}
	
private function savefiles($md5datanew, $filename) {
		$data = '';
		foreach ( $md5datanew as $file => $md5key ) {
			$data .= $md5key . ' *' . $file . "@r@n";
		}
		if (file_put_contents ( DIR_ROOT . 'update/data/app/web/version/' . $filename . '.md5', $data, LOCK_EX ) === false) {
			trigger_error ( 'can\'t write:' . DIR_ROOT . 'update/data/app/web/version' . $filename . '.md5' );
			return false;
		} else {
			return $filename . '.md5';
		}
	}
	
private function historyversion($bucket){
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