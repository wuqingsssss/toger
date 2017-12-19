<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {
		
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}
			
			$image = new Image(DIR_IMAGE . $old_image);
			
			if($width&&!$height)
			     $height=$width/$image->info['width']*$image->info['height'];
			if(!$width&&$height)
				$width=$height/$image->info['height']*$image->info['width'];
			
			$image->resize($width, $height);
			
			$image->save(DIR_IMAGE . $new_image);
			//$this->load->service ( 'ali/alioss' );
			$this->oss->upload_file_by_file($this->oss->open_bucket,'image/'.$new_image,DIR_IMAGE.$new_image,NULL);
		}
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return HTTPS_IMAGE . $new_image;
		} else {
			return HTTP_IMAGE . $new_image;
		}	
	}
	
	public function getImage($filename) {
	
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		}
	
		$info = pathinfo($filename);
		$extension = $info['extension'];
	
		$old_image = $filename;
		
	
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return HTTPS_IMAGE . $old_image;
		} else {
			return HTTP_IMAGE . $old_image;
		}
	}
	
	public function getImageQrcode($text,$wi=''){
		
		return HTTP_CATALOG."index.php?route=information/contact/qrcode&chl=".Http::encodeHash($text,'phpqrcode')."&wi=$wi";
		
	}
	
	public function img_resource($img_file, $mime_type)
	{
		switch ($mime_type)
		{
			case 1:
			case 'image/gif':
				$res = imagecreatefromgif($img_file);
				break;
	
			case 2:
			case 'image/pjpeg':
			case 'image/jpeg':
				$res = imagecreatefromjpeg($img_file);
				break;
	
			case 3:
			case 'image/x-png':
			case 'image/png':
				$res = imagecreatefrompng($img_file);
				//	imagesavealpha($res,true);
				break;
	
			default:
				return false;
		}
	
		return $res;
	}
}
?>