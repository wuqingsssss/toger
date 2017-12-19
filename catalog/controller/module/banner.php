<?php  
class ControllerModuleBanner extends Controller {
	protected function index($setting) {
		$this->load->model('design/banner');
	
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		  
		foreach ($results as $result) {
			$thumb=resizeThumbImage($result['image'],$setting['width'],$setting['height']);
				
			$this->data['banners'][] = array(
				'title' => $result['title'],
				'link'  => $result['link'],
				'image' => $thumb
			);
		}
		
		$this->data['banner_id'] = $setting['banner_id'];
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/banner.tpl';
		} else {
			$this->template = 'default/template/module/banner.tpl';
		}
		
		$this->render();
	}
}
?>