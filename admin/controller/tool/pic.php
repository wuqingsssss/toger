<?php
class ControllerToolPic extends Controller {
	private $error = array();

	public function index() {
		if (!isset($this->session->data['token'])) {
			$this->session->data['token'] = 0;
		}
		$this->data['token'] = $this->session->data['token'];

		$this->load_language('tool/pic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/pic', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
      	);
      		 

      	$this->load->model('catalog/manufacturer');

      	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

      	$this->load->model('catalog/category');

      	$this->data['categories'] = $this->model_catalog_category->getCategories(0);
      	
      	$this->load->model('tool/pic');
      	if ($this->request->server['REQUEST_METHOD'] == 'POST'&& $this->validate()) {
      		$this->session->data['success'] = $this->language->get('text_success');
			 $this->model_tool_pic->update($this->request->post);
      		$this->redirect(HTTPS_SERVER . 'index.php?route=tool/pic&token=' . $this->session->data['token']);
      	}

	
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
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tool/pic&token=' . $this->session->data['token'];


		$this->template = 'tool/pic.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/pic')) {
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