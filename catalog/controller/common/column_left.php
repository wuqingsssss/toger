<?php  
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$layout_id = getLayoutId();
		
		$module_data = array();
		
		$this->load->model('setting/extension');
		$this->load->model('design/layout');
		$extensions = $this->model_setting_extension->getExtensions('module');	
		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
		
			if ($modules) {
				foreach ($modules as $module) {
					if (($module['layout_id'] == $layout_id || $module['layout_id'] ==0) && $module['position'] == 'column_left' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		//增加对新layoutmodules的支持
		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_left',$this->config->get('config_template'));
		foreach ($modules as $module) {
			if ($module['status']) {
				$module_data[] = array(
						'code'       => $module['code'],
						'setting'    =>unserialize($module['setting']),
						'sort_order' => $module['sort_order']
				);
			}
		}
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/column_left.tpl';
		} else {
			$this->template = 'default/template/common/column_left.tpl';
		}
								
		$this->render();
	}
}
?>