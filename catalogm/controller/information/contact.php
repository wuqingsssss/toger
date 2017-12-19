<?php 
class ControllerInformationContact extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->load_language('information/contact');

    	$this->document->setTitle($this->language->get('heading_title'));  
	 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
    		$this->load->model('catalog/information');
    			
    		$this->model_catalog_information->addMessage($this->request->post);
    		
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
	  		$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
      		$mail->send();

	  		$this->redirect($this->url->link('information/contact/success'));
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->link('information/contact');
		$this->data['store'] = $this->config->get('config_name');
    	$this->data['address'] = nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['fax'] = $this->config->get('config_fax');
    	
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contact.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/contact.tpl';
		} else {
			$this->template = 'default/template/information/contact.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render());		
  	}

  	public function success() {
		$this->language->load('information/contact');

		$this->document->setTitle($this->language->get('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render()); 
	}

	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	public function qrcode() {
		
		$this->load->library ('phpqrcode' );
		$this->load->model('tool/image');
		
		$bl = 30;
		// $data = 'http://www.ezauto.cn/goods/1791.html';
		$bg = intval ( $this->request->get ['bg'] );
		
		
		$data=Http::decodeHash( $this->request->get ['chl'] ,'phpqrcode');
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
	
	
	
  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['enquiry'])) < 10) || (strlen(utf8_decode($this->request->post['enquiry'])) > 3000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}

    	if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}
}
?>
