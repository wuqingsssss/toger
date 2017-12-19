<?php  
class ControllerModuleLink extends Controller {
	public function index($setting) {
		$this->load_language('module/link');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/link');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/image');
		
		$results=$this->model_catalog_link->getLinks($setting['limit']);
		
		foreach($results as $result)
		{
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			if($setting['width'] && $setting['height']){
				$thumb=$this->model_tool_image->resize($image, $setting['width'], $setting['height']);
			}else{
				$thumb=HTTPS_IMAGE.$image;
			}
			
			$this->data['links'][]=array(
				'link_id'		=>$result['link_id'],
				'name'			=>$result['name'],
				'description'	=>$result['description'],
				'thumb'   		=> $thumb,
				'friend'		=>$result['friend'],
				'uri'			=>$result['uri']
			);
		}
		
		$this->id="link";
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/link.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/link.tpl';
		} else {
			$this->template = 'default/template/module/link.tpl';
		}
		
		$this->render();
  	}
}
?>