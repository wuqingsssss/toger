<?php  
class ControllerModuleSlideshow extends Controller {
	public function index($setting=array()) {
		
		if($this->request->get['banner_id']){
				
			$setting=array_merge($setting,$this->request->get);
			$this->data['ajax'] = TRUE;

		$this->load->model('design/banner');
		
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		$banner=array();
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$banner=array(
						'title' => $result['title'],
						'link'  => $result['link'],
						'image' =>  resizeThumbImage($result['image'], $setting['width'], $setting['height'],false)
				);
				$this->data['banners'][] =$banner ;
			}
		}
		}
		
		if($setting['height']&&$setting['width']){
			$this->data['imageheight']=(int)$setting['height'];
			$this->data['imagewidth']=(int)$setting['width'];
		}
		/*elseif($banner){
		    $image = new Image($banner['image']);
		    $this->data['imageheight']=(int)$image->info['height'];
		    $this->data['imagewidth']=(int)$image->info['width'];
		}*/else {
			$this->data['imageheight']=116;
			$this->data['imagewidth']=320;
		}
		$this->data['setting']=$setting;
		$this->data['banner_id'] = $setting['banner_id'];
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/slideshow.tpl';
		} else {
			$this->template = 'default/template/module/slideshow.tpl';
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