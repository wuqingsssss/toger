<?php
class ControllerModuleIlexCategoryNav extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load_language('module/ilex_category_nav');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('ilex_category_nav', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}
				
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/boss_quickselect', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/ilex_category_nav', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];
        
        $this->load->model('catalog/category');
        
        $this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {

			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name']
			);	
		}
		
		// image
		$this->load->model('tool/image');
		
		$category_lists = array();
		
		if (isset($this->request->post['category_lists'])) {
			$category_lists = $this->request->post['category_lists'];
		} elseif ($this->config->get('category_lists')) { 
			$category_lists = $this->config->get('category_lists');
		}	
		
		$this->data['iconcates'] = array();
		
		foreach ($category_lists as $category_list) {
			if ($category_list['icon'] && file_exists(DIR_IMAGE . $category_list['icon'])) {
				$icon = $category_list['icon'];
			} else {
				$icon = 'no_image.jpg';
			}			
			
			$this->data['iconcates'][] = array(
				'description'	 			=> $category_list['description'],
				'category_id'               => $category_list['category_id'],
                'image_width'               => $category_list['image_width'],
                'image_height'              => $category_list['image_height'],
				'icon'                      => $icon,
				'thumb'                     => $this->model_tool_image->resize($icon, 50, 50)
			);	
		} 
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);		

		//module		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['ilex_category_nav_module'])) {
			$this->data['modules'] = $this->request->post['ilex_category_nav_module'];
		} elseif ($this->config->get('ilex_category_nav_module')) { 
			$this->data['modules'] = $this->config->get('ilex_category_nav_module');
		}					
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'module/ilex_category_nav.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ilex_category_nav')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['category_lists'])) {
			foreach ($this->request->post['category_lists'] as $key => $value) {
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function getIdLayout($layout_name) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE LOWER(name) = LOWER('".$layout_name."')");
		return (int)$query->row['layout_id'];
	}
}
?>