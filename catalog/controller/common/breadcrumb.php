<?php   
class ControllerCommonBreadcrumb extends Controller {
	protected function index() {
		$this->data['breadcrumbs']=$this->document->getBreadcrumbs();
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/breadcrumb.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/breadcrumb.tpl';
		} else {
			$this->template = 'default/template/common/breadcrumb.tpl';
		}
		
    	$this->render();
	} 	
}
?>