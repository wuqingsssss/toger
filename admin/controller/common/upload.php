<?php
require_once('uploader.php');
class ControllerCommonUpload extends Controller {
	public function index() {
		//Upload
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 1024*1024*10;
		$uploader = new qqFileUploader($this->registry,$allowedExtensions, $sizeLimit);
	
		$directory = rtrim('data/'.str_replace('../', '', $this->request->get['directory']),'/');
	
		$result = $uploader->handleUpload(DIR_IMAGE .$directory.'/');
		
		$filename=$uploader->filename;
		$code=array(
			'id' => 'data/'.$filename,
			'filename' => $filename,
		);

		//$this->load->service ( 'ali/alioss' ,'service');
	    $this->oss->upload_file_by_file($this->oss->open_bucket,'image/'.$directory . '/' . $filename,DIR_IMAGE .$directory . '/' . $filename);
		$this->log_sys->info('uploat::upload_file_by_file::image/'.$directory . '/' . $filename);
		
		$this->load->library('json');
		$this->response->setOutput(Json::encode(array_merge($code,$result)));

	}
}
?>