<?php
class ControllerNewsBroadcast extends Controller {
	public function index() {
		$this->load->language('news/broadcast');
		
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
      	
      	$this->data['heading_title']=$this->language->get('heading_title');
      	
      	$media_banner_id=13;
      	$magazine_banner_id=14;
      	
      	$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$this->data['media_banners']=array();
		$this->data['magazine_banners']=array();
		
      	$results=$this->model_design_banner->getBanner($media_banner_id);
      	
      	foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				
				$image=HTTPS_IMAGE.$result['image'];
				
				$this->data['media_banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $image
				);
			}
		}
		

      	$results=$this->model_design_banner->getBanner($magazine_banner_id);
      	
      	foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				
				$image=HTTPS_IMAGE.$result['image'];
				
				$this->data['magazine_banners'][] = array(
					'title' => $result['title'],
					'link'  => HTTPS_IMAGE.$result['image'],
					'image' => $image
				);
			}
		}
		
				
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/broadcast.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/news/broadcast.tpl';
		} else {
			$this->template = 'default/template/news/broadcast.tpl';
		}
		
		$this->children = array(
		'common/column_left',
		'common/column_right',
		'common/content_top',
		'common/content_bottom',
		'common/footer',
		'common/header'
		);
	
	
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>
