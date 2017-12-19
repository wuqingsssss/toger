<?php  
class ControllerModulePartners extends Controller {
	protected function index($setting) {
		$this->load_language('module/partners');

		static $module = 0;
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');
						
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		  
		foreach ($results as $result) {
			
			if($result['image'] && file_exists(DIR_IMAGE . $result['image'])){
				$image=$result['image'];
			}else{
				$image=false;
			}
			
			if($image){
				if($setting['width'] && $setting['height']){
					$thumb=$this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				}else{
					$thumb=HTTP_IMAGE.$result['image'];
				}
			}else{
				$thumb=false;
			}

			if ($thumb) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'thumb' => $thumb
				);
			}
		}
		
		$this->id = 'partners';
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/partners.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/partners.tpl';
		} else {
			$this->template = 'default/template/module/partners.tpl';
		}
		
		$this->render();
	}
}
?>