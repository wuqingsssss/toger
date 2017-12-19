<?php 
class ControllerToolOss extends Controller { 
	private $error = array();
	public function index() {	
		$this->load_language('tool/oss');

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
			'href'      => $this->url->link('tool/oss', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   	//	////$this->load->service ( 'ali/alioss' ,'service');
   		
   		$res_obj = $this->oss->list_bucket();

   		$buckets=$res_obj['Buckets']['Bucket'];
   		$this->data['buckets']=$buckets;

		$this->template = 'tool/oss.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
public function find(){
		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];
		$marker=$this->request->get ['marker'];
		$frontmarker=$this->request->get ['frontmarker'];
		$maxkeys=	isset($this->request->get ['maxkeys'])?(int)$this->request->get ['maxkeys']:10;
	    
	   
		$res['status']=1;
		$res['message']='成功查询到结果';
		
		$options = array(
				//分隔符
				'delimiter' => '',
				//前缀
				'prefix' => $prefix,
				//列表个数,默认最大100个
				'max-keys' => $maxkeys,
				'marker' => $marker,
		);
		////$this->load->service ( 'ali/alioss' ,'service');
		
		$filelist= $this->oss->list_object($bucket,$options);
		//foreach ($filelist['Contents'] as $file){
		//	print_r($file[Key].'<br/>');
		//}
		$res['filelist']=$filelist;
		$res['frontmarker']=$marker?($frontmarker):'false';
		$res['marker']=$marker;
		$res['nextmarker']=$filelist['NextMarker'];
		$res['data']=$filelist['Contents'];

		$this->response->setOutput (json_encode ( $res ));

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
		////$this->load->service ( 'ali/alioss' ,'service');
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
		
	}
	
	public function upload(){
		
		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];	
		$res['status']=1;
		$res['message']='成功查询到结果';		
		$res['data']=$this->upload_file_by_dir($bucket,DIR_ROOT.$prefix);
		$res['frontmarker']='false';
		$res['error']=$this->error;
		$this->response->setOutput (json_encode ( $res ));
	}
	public function update(){
	
		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];
		$res['status']=1;
		$res['message']='成功查询到结果';
		$res['data']=$this->upload_file_by_dir($bucket,DIR_ROOT.$prefix,false);
		$res['frontmarker']='false';
		$res['error']=$this->error;
		$this->response->setOutput (json_encode ( $res ));
	}
	
	public function check(){
	
		$this->noTimeout(300,false);
		$bucket=$this->request->get ['bucket'];
		$prefix=$this->request->get ['prefix'];
		$res['status']=1;
		$res['message']='成功查询到结果';
		$res['data']=$this->check_file_by_dir($bucket,DIR_ROOT.$prefix);
		$res['frontmarker']='false';
		$res['error']=$this->error;
		$this->response->setOutput (json_encode ( $res ));
	}
	

	private function check_file_by_dir($bucket,$dir){
		$res=array();
		if(is_dir($dir)){
			$dir=rtrim($dir,'/');
			$files=glob($dir.'/*');
	
			foreach ($files as $file)
			{
				$res=array_merge($res,$this->check_file_by_dir($bucket,$file));
			}
		}
		else {
			
			if(stripos('.db', $dir)===false){
				$object_name=str_replace(DIR_ROOT, '', $dir);
			
				try{
					////$this->load->service ( 'ali/alioss' ,'service');
					$chkres=$this->oss->is_object_exist($bucket,$object_name);
				
					if($chkres){
						//$res[$object_name]='true::check_file_by_file->'.$object_name;
					}
					else 
					{
						$res[$object_name]='false::check_file_by_file->'.$object_name;
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
	
}
?>