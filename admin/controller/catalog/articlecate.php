<?php
class ControllerCatalogArticlecate extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/articlecate');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/articlecate');
	}

	public function index() {
		$this->init();

		$this->getList();
	}
	
	private function redirectToList(){
		$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));

		$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/articlecate&token=' . $this->session->data['token']);
	}

	public function insert() {
		$this->init();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_articlecate->addCategory($this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_articlecate->editCategory($this->request->get['article_category_id'], $this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function delete() {
		$this->init();

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $article_category_id) {
				$this->model_catalog_articlecate->deleteCategory($article_category_id);
			}

			$this->redirectToList();
		}
		
		if(isset($this->request->get['article_category_id'])){
			$article_category_id=$this->request->get['article_category_id'];
			$this->model_catalog_articlecate->deleteCategory($article_category_id);
			
			$this->redirectToList();
		}

		$this->getList();
	}

	private function getList() {
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/articlecate', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate/insert&token=' . $this->session->data['token'];
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate/delete&token=' . $this->session->data['token'];

		$this->data['categories'] = array();

		$results = $this->model_catalog_articlecate->getCategories(0);
		
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/articlecate/update&token=' . $this->session->data['token'] . '&article_category_id=' . $result['article_category_id']
			);
			
			$action['delete'] = array(
				'text' => $this->language->get('text_delete'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/articlecate/delete&token=' . $this->session->data['token'] . '&article_category_id=' . $result['article_category_id']
			);
			
			$action[] = array(
				'text' => $this->language->get('text_manage'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . '&filter_article_category_id=' . $result['article_category_id']
			);

			$this->data['categories'][] = array(
				'article_category_id' => $result['article_category_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'articles'        => $this->model_catalog_articlecate->getTotalArticles($result['article_category_id']),
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['article_category_id'], $this->request->post['selected']),
				'action'      => $action
			);
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

		
		$this->template = 'catalog/articlecate_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
	}

	private function getForm() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/articlecate', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);


		if (!isset($this->request->get['article_category_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate/insert&token=' . $this->session->data['token'];
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate/update&token=' . $this->session->data['token'] . '&article_category_id=' . $this->request->get['article_category_id'];
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/articlecate&token=' . $this->session->data['token'];

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['article_category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_catalog_articlecate->getCategory($this->request->get['article_category_id']);
    	}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['article_category_description'])) {
			$this->data['article_category_description'] = $this->request->post['article_category_description'];
		} elseif (isset($category_info)) {
			$this->data['article_category_description'] = $this->model_catalog_articlecate->getCategoryDescriptions($this->request->get['article_category_id']);
		} else {
			$this->data['article_category_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		if (isset($this->request->post['template_id'])) {
			$this->data['template_id'] = $this->request->post['template_id'];
		} elseif (isset($category_info)) {
			$this->data['template_id'] = $category_info['template_id'];
		} else {
			$this->data['template_id'] = 0;
		}

		$this->data['categories'] = $this->model_catalog_articlecate->getCategories(0);

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (isset($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$this->data['article_category_to_store'] = $this->request->post['category_store'];
		} elseif (isset($category_info)) {
			$this->data['article_category_to_store'] = $this->model_catalog_articlecate->getCategoryStores($this->request->get['article_category_id']);
		} else {
			$this->data['article_category_to_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (isset($category_info)) {
			$this->data['code'] = $category_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->request->post['download_only'])) {
			$this->data['download_only'] = $this->request->post['download_only'];
		} elseif (isset($category_info)) {
			$this->data['download_only'] = $category_info['download_only'];
		} else {
			$this->data['download_only'] = 0;
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
	
		if (isset($this->request->post['article_category_layout'])) {
			$this->data['article_category_layout'] = $this->request->post['article_category_layout'];
		} elseif (isset($category_info)) {
			$this->data['article_category_layout'] = $this->model_catalog_articlecate->getArticleCateLayoutId($this->request->get['article_category_id']);
		} else {
			$this->data['article_category_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'catalog/articlecate_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
		
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/articlecate')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		foreach ($this->request->post['article_category_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['name'])) < 1)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}

			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/articlecate')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>