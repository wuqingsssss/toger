<?php  
class ControllerModuleHotWord extends Controller {
	protected function index() {
		$this->load_language('module/hotword');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->load->model('catalog/search');
    	
    	$results=$this->model_catalog_search->getFeaturedKeyword(6);
    	
    	$this->data['hotwords']=array();
    	
    	foreach($results as $result){
    		$this->data['hotwords'][]=array(
    			'keyword' => $result['term'],
    			'href' => $this->url->link('product/search', 'filter_name='.$result['term'], 'SSL')
    		);
    	}
    	
		
		$this->id = 'hotword';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/hotword.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/hotword.tpl';
		} else {
			$this->template = 'default/template/module/hotword.tpl';
		}
		
		$this->render();
  	}
}
?>