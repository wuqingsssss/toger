<?php
class ControllerCatalogArticle extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/article');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/article');
	}

  	public function index() {
  	    $this->log_admin->trace("");
  	    
		$this->init();

		$this->getList();
  	}

  	public function insert() {
  	    $this->log_admin->trace("");
  	    
    	$this->init();

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_article->addArticle($this->request->post);

			$this->redirectToList();
    	}

    	$this->getForm();
  	}

  	public function update() {
  	    $this->log_admin->trace("");
  	    
    	$this->init();

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_article->editArticle($this->request->get['article_id'], $this->request->post);

			$this->redirectToList();
		}

    	$this->getForm();
  	}

  	public function delete() {
  	    $this->log_admin->trace("");
  	    
    	$this->init();

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $article_id) {
				$this->model_catalog_article->deleteArticle($article_id);
	  		}

			$this->redirectToList();
		}

    	$this->getList();
  	}
  	
  	
	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = NULL;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added= $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}
		
		if (isset($this->request->get['filter_article_category_id'])) {
			$filter_article_category_id = $this->request->get['filter_article_category_id'];
		} else {
			$filter_article_category_id = NULL;
		}

		$url=$this->getUrlParameters();
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/article', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/article/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/article/delete&token=' . $this->session->data['token'] . $url;

		$this->data['article'] = array();

		$data = array(
			'filter_title'	  => $filter_title,
			'filter_status'   => $filter_status,
			'filter_date_added'   => $filter_date_added,
			'filter_article_category_id'   => $filter_article_category_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');
		$this->load->model('catalog/articlecate');

		$this->data['categories'] = $this->model_catalog_articlecate->getCategories(0);

		$product_total = $this->model_catalog_article->getTotalArticles($data);

		$results = $this->model_catalog_article->getArticles($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/article/update&token=' . $this->session->data['token'] . '&article_id=' . $result['article_id'] . $url
			);
			
			$category_name=$this->model_catalog_article->getArticleCategoryName($result['article_id']);

			$this->data['article'][] = array(
				'article_id' => $result['article_id'],
				'title'       => $result['name'],
				'category'       => $category_name,
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

 		$this->data['token'] = $this->session->data['token'];

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
		$this->load->model('catalog/articlecate');
		$this->data['articlecates']=$this->model_catalog_articlecate->getCategories();

		$url=$this->getCommonUrlParameters();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . '&sort=p.name' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . '&sort=p.status' . $url;
		$this->data['sort_order'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . '&sort=p.date_added' . $url;

		$url=$this->getCommonUrlParameters();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();
		$this->data['filter_title'] = $filter_title;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_add'] = $filter_date_added;
		$this->data['filter_article_category_id'] = $filter_article_category_id;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/article_list.tpl';
		$this->id = 'content';
		
		$this->layout = 'layout/default';
		
		$this->render();
  	}

  	private function getForm() {
    	if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['error_date'])) {
			$this->data['error_date'] = $this->error['error_date'];
		} else {
			$this->data['error_date'] = '';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$url=$this->getUrlParameters();
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/article', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['article_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/article/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/article/update&token=' . $this->session->data['token'] . '&article_id=' . $this->request->get['article_id'] . $url;
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . $url;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['article_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$article_info = $this->model_catalog_article->getArticle($this->request->get['article_id']);
    	}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['article'])) {
			$this->data['article'] = $this->request->post['article'];
		} elseif (isset($article_info)) {
			$this->data['article'] = $this->model_catalog_article->getArticleContent($this->request->get['article_id']);
		} else {
			$this->data['article'] = array();
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($article_info)) {
			$this->data['keyword'] = $article_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['featured'])) {
			$this->data['featured'] = $this->request->post['featured'];
		} elseif (isset($article_info)) {
			$this->data['featured'] = $article_info['featured'];
		} else {
			$this->data['featured'] = '0';
		}
		
		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (isset($article_info)) {
			$this->data['quantity'] = $article_info['quantity'];
		} else {
			$this->data['quantity'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($article_info)) {
			$this->data['image'] = $article_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
  		$this->load->model('tool/image');

		if (isset($article_info) && $article_info['image'] && file_exists(DIR_IMAGE . $article_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		if (isset($this->request->post['editor'])) {
			$this->data['editor'] = $this->request->post['editor'];
		} elseif (isset($article_info)) {
			$this->data['editor'] = $article_info['editor'];
		} else {
			$this->data['editor'] = $this->user->getUserName();
		}

		if (isset($this->request->post['date_added'])) {
       		$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($article_info)) {
			$this->data['date_added'] = date('Y-m-d', strtotime($article_info['date_added']));
		} else {
			$this->data['date_added'] = date('Y-m-d', time()-86400);
		}

		if (isset($this->request->post['article_tags'])) {
			$this->data['article_tags'] = $this->request->post['article_tags'];
		} elseif (isset($article_info)) {
			$this->data['article_tags'] = $this->model_catalog_article->getArticleTags($this->request->get['article_id']);
		} else {
			$this->data['article_tags'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($article_info)) {
      		$this->data['sort_order'] = $article_info['sort_order'];
    	} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else if (isset($article_info)) {
			$this->data['status'] = $article_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
    	
		$this->data['language_id'] = $this->config->get('config_language_id');

		$this->load->model('catalog/articlecate');

		$this->data['categories'] = $this->model_catalog_articlecate->getCategories(0);

		if (isset($this->request->post['article_category'])) {
			$this->data['article_category'] = $this->request->post['article_category'];
		} elseif (isset($article_info)) {
			$this->data['article_category'] = $this->model_catalog_article->getArticleCategories($this->request->get['article_id']);
		} else if(isset($this->request->get['filter_article_category_id'])){
			$this->data['article_category'] = array($this->request->get['filter_article_category_id']);
		} else{
			$this->data['article_category'] = array();
		}

 		if (isset($this->request->post['article_related'])) {
			$this->data['article_related'] = $this->request->post['article_related'];
		} elseif (isset($article_info)) {
			$this->data['article_related'] = $this->model_catalog_article->getArticleRelated($this->request->get['article_id']);
		} else {
			$this->data['article_related'] = array();
		}

  		$this->load->model('catalog/download');

		$this->data['downloads'] = $this->model_catalog_download->getDownloads();

		if (isset($this->request->post['article_download'])) {
			$this->data['article_download'] = $this->request->post['article_download'];
		} elseif (isset($article_info)) {
			$this->data['article_download'] = $this->model_catalog_article->getArticleDownloads($this->request->get['article_id']);
		} else {
			$this->data['article_download'] = array();
		}
		
		if (isset($this->request->post['article_layout'])) {
			$this->data['article_layout'] = $this->request->post['article_layout'];
		} elseif (isset($article_info)) {
			$this->data['article_layout'] = $this->model_catalog_article->getArticleLayouts($this->request->get['article_id']);
		} else {
			$this->data['article_layout'] = array();
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'catalog/article_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/article')) {
      		$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
    	}

    	foreach ($this->request->post['article'] as $language_id => $value) {
      		if ((strlen(utf8_decode($value['title'])) < 1)) {
        		$this->error['title'][$language_id] = $this->language->get('error_name');
      		}
    	}
    	
    	if (!$this->error) {
			return TRUE;
    	} else {
			$this->error['warning'] = $this->language->get('error_required_data');
			
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/article')) {
      		$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

	public function category() {
	    $this->log_admin->trace("");
	    
		$this->load->model('catalog/article');

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$article_data = array();

		$results = $this->model_catalog_article->getArticleByCategoryId($category_id);

		foreach ($results as $result) {
			$article_data[] = array(
				'article_id' => $result['article_id'],
				'title'       => $result['name']
			);
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($article_data));
	}

	public function related() {
	    $this->log_admin->trace("");
	    
		$this->load->model('catalog/article');

		if (isset($this->request->post['article_related'])) {
			$article = $this->request->post['article_related'];
		} else {
			$article = array();
		}

		$article_data = array();

		foreach ($article as $article_id) {
			$article_info = $this->model_catalog_article->getArticleName($article_id);

			if ($article_info) {
				$article_data[] = array(
					'article_id' => $article_info['article_id'],
					'title'       => $article_info['name']
				);
			}
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($article_data));
	}
	
	private function redirectToList(){
  		$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));

		$url=$this->getUrlParameters();

		$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/article&token=' . $this->session->data['token'] . $url);
  	}
  	
  	private function getCommonUrlParameters(){
  		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . $this->request->get['filter_title'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_add'])) {
			$url .= '&filter_date_add=' . $this->request->get['filter_date_add'];
		}
		
		if (isset($this->request->get['filter_article_category_id'])) {
			$url .= '&filter_article_category_id=' . $this->request->get['filter_article_category_id'];
		}

		return $url;
  	}
  	
  	public function getUrlParameters(){
  	    $this->log_admin->trace("");
  	    
  		$url = '';
  		
  		$url=$this->getCommonUrlParameters();
  		
  		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		return $url;
  	}
	
}
?>