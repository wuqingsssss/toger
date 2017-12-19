<?php
/**
 * v3 订单接口
 * @author Lance
 */
class ControllerToolsString extends Controller {
	public function json_encode(){
		//print_r($this->request->get ['str']);
		$string=eval('return '.htmlspecialchars_decode($this->request->get ['str']).';');
		echo json_encode($string);  
	}
	public function json_decode(){
		echo json_decode(htmlspecialchars_decode($this->request->get ['str'],1));
	}
	public function hash_hmac(){
		$algo=isset($this->request->get ['algo'])?trim($this->request->get ['algo']):'md5';
		$data =htmlspecialchars_decode($this->request->get ['str']);
		$key =trim($this->request->get ['key']);
		$raw_output=isset($this->request->get ['raw_output'])?trim($this->request->get ['raw_output']):false;
		echo hash_hmac($algo,$data,$key,$raw_output);
	}

	public function qrcode() {
	
		$this->load->library ('phpqrcode' );
		$this->load->model('tool/image');
	
		$bl = 30;

		//$data=Http::decodeHash( $this->request->get ['chl'] ,'phpqrcode');
		$data=$this->request->get ['chl'];
		if($data===false){
			die('error_no_allow');
		}
		$data = urldecode ( $data ) ;
		$watermark = ROOT_PATH . htmlspecialchars ( $this->request->get ['wi'] );
		// $watermark=ROOT_PATH.'/images/201403/thumb_img/1791_thumb_P_1393799963859.jpg';
	
	
		// 获得水印文件以及源文件的信息,并对水印文件进行背景处理
		if (! file_exists ( $watermark ) || empty ( $this->request->get ['wi'] )){
	
			$watermark = $this->model_tool_image->resize($this->config->get('config_share_image'), 200, 200);
			$watermark = HTTP_IMAGE.$this->config->get('config_share_image');
			
		}
	
		$watermark_info = @getimagesize ( $watermark );
		$watermark_handle = $this->model_tool_image->img_resource ( $watermark, $watermark_info [2] );
	
		// 水印文件背景生成
		$base_image = imagecreatetruecolor ( $watermark_info [0], $watermark_info [0] ); // 此处不能用imagecreate,否则png透明度不能保留
	
		$col [0] = ImageColorAllocate ( $base_image, 255, 255, 255 );
		$col [1] = ImageColorAllocate ( $base_image, 10, 163, 154 );
		$col [2] = ImageColorAllocate ( $base_image, 245, 160, 0 );
	
		imagefill ( $base_image, 0, 0, $col [$bg] );
		$x = $watermark_info [0] / 2 - $watermark_info [0] / 2;
		$y = $watermark_info [0] / 2 - $watermark_info [1] / 2;
		// 水印文件背景融合
		if (strpos ( strtolower ( $watermark_info ['mime'] ), 'png' ) !== false) {
			imagecopy ( $base_image, $watermark_handle, $x, $y, 0, 0, $watermark_info [0], $watermark_info [1] );
		} else {
			imagecopymerge ( $base_image, $watermark_handle, $x, $y, 0, 0, $watermark_info [0], $watermark_info [1], 100 );
		}
		$watermark_handle = $base_image;
		$watermark_info [1] = $watermark_info [0];
	
		// 二维码数据 声称二维码图片
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'H';
		// 点的大小：1到10
		$matrixPointSize = 10;
	
		// QRcode::png($data,false, $errorCorrectionLevel, $matrixPointSize, 0,0);
	
		$simage = QRcode::image ( $data, $errorCorrectionLevel, $matrixPointSize, 0 );
	
		$source_handle = $simage ['data'];
		$source_info = $simage ['sizeinfo'];
	
		// 根据二维码源文件尺寸对水印原件进行缩放
		$_watermark = imagecreate ( $source_info [0] * $bl / 100, $source_info [1] * $bl / 100 );
		imagecopyresampled ( $_watermark, $watermark_handle, 0, 0, 0, 0, $source_info [0] * $bl / 100, $source_info [0] * $bl / 100, $watermark_info [0], $watermark_info [1] );
	
		$watermark_handle = $_watermark;
		$watermark_info [0] = $source_info [0] * $bl / 100;
		$watermark_info [1] = $source_info [0] * $bl / 100;
	
		$x = $source_info [0] / 2 - $watermark_info [0] / 2;
		$y = $source_info [1] / 2 - $watermark_info [1] / 2;
	
		// imagepng($source_handle);
	
		if (imagecopymerge ( $source_handle, $watermark_handle, $x, $y, 0, 0, $watermark_info [0], $watermark_info [1], 100 )) {
			header ( "Content-type: image/png" );
			imagepng ( $source_handle );
			;
		} else {
			echo ('发生错误');
		}
		imagedestroy ( $base_image );
		imagedestroy ( $source_handle );
		imagedestroy ( $watermark_handle );
	}
	
	
  }
?>