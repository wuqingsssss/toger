<?php  
class ControllerModuleCustom extends Controller {
	
	protected function index($setting=array()) {
		$note_id=$setting['note_id'];
		
		$this->data['module_id']='custom_'.$note_id;
		
		$this->load->model('catalog/note');
		
		$result_info=$this->model_catalog_note->getNote($note_id);
		
		
		if($result_info){
			$this->data['heading_title']=$result_info['title'];
			
			$this->data['content']=html_entity_decode($result_info['summary'], ENT_QUOTES, 'UTF-8');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/custom.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/custom.tpl';
			} else {
				$this->template = 'default/template/module/custom.tpl';
			}
			
			$this->render();
		}
	}
}
?>