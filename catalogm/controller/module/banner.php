<?php  
class ControllerModuleBanner extends Controller {
	public function index($setting=array()) {
		
		if($this->request->get['banner_id']){
		
			$setting=array_merge($setting,$this->request->get);
			$this->data['ajax'] = TRUE;
		
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
		}
		$this->data['setting']=$setting;
		$this->data['banner_id'] = $setting['banner_id'];
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/banner.tpl';
		} else {
			$this->template = 'default/template/module/banner.tpl';
		}
		
		$output=$this->render();
		if($this->request->get['banner_id']){
			$json['setting']=$setting;
			$json['output']=$output;
			$this->response->setOutput(json_encode($json));
		}
	}
}
?>