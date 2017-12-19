<?php
class ControllerNewsGallery extends Controller {
	public function index() {
		$this->load->language('news/gallery');
		
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
      	
      	$this->data['heading_title']=$this->language->get('heading_title');
      	
				
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/gallery.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/news/gallery.tpl';
		} else {
			$this->template = 'default/template/news/gallery.tpl';
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
