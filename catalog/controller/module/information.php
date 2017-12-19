<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		if(isset($this->request->get['information_id'])){
			
			$this->load->model('catalog/information');
			$this->load->model('catalog/information_group');
			
			$information_info=$this->model_catalog_information->getInformation($this->request->get['information_id']);
			
			if($information_info){
				$group_id=$information_info['group_id'];
			}else{
				$group_id=0;
			}
			
			$code='';
			
			if($group_id){
				$information_group=$this->model_catalog_information_group->getInformationGroup($group_id);
				
				if($information_group){
					$code=$information_group['code'];
				}
			}
			

			$section1=array('001','002','003','004','005');
	    	
	    	$section2=array('006','007','008');
	    	
			if(in_array($code,$section1)){
				$this->data['section']=$section1;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information_1.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/information_1.tpl';
				} else {
					$this->template = 'default/template/module/information_1.tpl';
				}
				
				$this->render();
			}
			
			if(in_array($code,$section2)){
				$this->data['section']=$section2;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information_2.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/information_2.tpl';
				} else {
					$this->template = 'default/template/module/information_2.tpl';
				}
				
				$this->render();
			}
		}
		
		if(isset($this->request->get['article_category_id']) || isset($this->request->get['article_id'])){
			$section2=array('006','007','008');
			
			$this->data['section']=$section2;
				
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information_2.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/information_2.tpl';
			} else {
				$this->template = 'default/template/module/information_2.tpl';
			}
			
			$this->render();
		}
	}
}
?>