<?php
class ControllerModuleSingleArticle extends Controller {
	private $error = array();

	public function index() {
		$this->load_language('module/single_article');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		    
			$this->model_setting_setting->editSetting('single_article', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}

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
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/single_article', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/single_article', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'); 

		$this->load->model('catalog/articlecate');

		$this->data['categories'] = array();

		$results = $this->model_catalog_articlecate->getCate4DefaultStore(0);

		foreach ($results as $result) {
			$this->data['categories'][] = array(
				'article_category_id' => $result['article_category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order']
			);
		}

		if (isset($this->request->post['article_categories'])) {
			$this->data['article_category'] = $this->request->post['article_categories'];
		} else {
			$this->data['article_category'] = explode(',',$this->config->get('article_categories')) ;
		}
		
		$this->data['styles']=array('list','thumb_list');

		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['single_article_module'])) {
			$this->data['modules'] = $this->request->post['single_article_module'];
		} elseif ($this->config->get('single_article_module')) { 
			$this->data['modules'] = $this->config->get('single_article_module');
		}	

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/single_article.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();	
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/single_article')) {
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