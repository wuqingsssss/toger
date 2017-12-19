<?php  
class ControllerMiscEnquiry extends Controller {
	public function index() {
		$this->load_language('misc/enquiry');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('misc/enquiry');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if($this->validate()){
				//如果有附件 保存附件.
				
				$this->model_misc_enquiry->addEnquiry($this->request->post);
			}
			
		} 
		
		$this->data['products']=array();

		$this->data['action']=$this->url->link('misc/enquiry');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/misc/enquiry.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/misc/enquiry.tpl';
		} else {
			$this->template = 'default/template/misc/enquiry.tpl';
		}
				
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
										
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
}
?>