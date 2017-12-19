<?php  
class ControllerModuleSlideshow extends Controller {
	protected function index($setting) {
		$this->load->model('design/banner');
		
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		  
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' =>  resizeThumbImage($result['image'], $setting['width'], $setting['height'],false)
				);
			}
		}

		$this->data['banner_id'] = $setting['banner_id'];
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/slideshow.tpl';
		} else {
			$this->template = 'default/template/module/slideshow.tpl';
		}
		
		$this->render();
	}
}
?>