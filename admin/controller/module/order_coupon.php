<?php
class ControllerModuleOrderCoupon extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load_language('module/order_coupon');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('order_coupon', array());

			
			
			$this->model_design_layout->updateModules('order_coupon',  $this->request->post['order_coupon_module']);
			
			$this->session->data['success'] = $this->language->get('text_success');		
			//$this->redirect($this->url->link('module/order_coupon', 'token=' . $this->session->data['token'], 'SSL'));
			$this->goback();
		}
				
		$this->setback();
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/order_coupon', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		$this->data['action'] = $this->url->link('module/order_coupon', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['order_coupon_module'])) {
			$this->data['modules'] = $this->request->post['order_coupon_module'];
		} else { 
			//兼容老版本写法，可以自动升级到新版
			$this->data['modules'] =array_merge($this->config->get('order_coupon_module')?$this->config->get('order_coupon_module'):array(),$this->model_design_layout->getLayoutModules(array('code'=>'order_coupon')));	
		}	

		$this->load->model('design/layout');
		
		$layouts=$this->model_design_layout->getLayouts();
		
		foreach($layouts as $row)
			$this->data['layouts'][$row['layout_id']]=$row;
		
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['templates'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
		
		$this->template = 'module/order_coupon.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/order_coupon')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach($this->request->post['order_coupon_module'] as $key1=> $module){
		if($module['rule']){
			$sort_order = array();
			foreach($module['rule'] as $key=> $rule)
			{
				if(empty($rule['order_total'])&&empty($rule['coupon_code'])){
					unset($this->request->post['order_coupon_module'][$key1]['rule'][$key]);
				}else {
				$sort_order[$key]=$rule['order_total'];
				}
			}
			array_multisort($sort_order, SORT_ASC, $this->request->post['order_coupon_module'][$key1]['rule']);
		}
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>