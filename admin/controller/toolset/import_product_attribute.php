<?php 
class ControllerToolsetImportProductAttribute extends Controller { 
	private $error = array();
	
	public function index() {
		$this->load_language('tool/export');
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/export');
	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
		       		'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
		      		'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
		       		'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('tool/export', 'token=' . $this->session->data['token'], 'SSL'),
		      		'separator' => $this->language->get('text_breadcrumb_separator')
		);
	
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tool/export&token=' . $this->session->data['token'];

		$this->data['export_customer'] = HTTPS_SERVER . 'index.php?route=tool/export/customer&token=' . $this->session->data['token'];
		
		$this->data['image'] = HTTPS_SERVER . 'index.php?route=tool/export/image&token=' . $this->session->data['token'];

		$this->template = 'tool/export.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function clearCache() {
		$this->cache->delete('product');

   }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/export_product_attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
}
?>