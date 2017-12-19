<?php
class ControllerCatalogNote extends Controller { 
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/note');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/note');
	}

	public function index() {
		$this->init();

		$this->getList();
	}

	
	public function insert() {
		$this->init();
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_note->addNote($this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}
	

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_note->editNote($this->request->get['note_id'], $this->request->post);
			
//			$this->generate_html('hello');
			
			$this->redirectToList();
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $note_id) {
				$this->model_catalog_note->deleteNote($note_id);
			}
			
			$this->redirectToList();
		}
		
		if(isset($this->request->get['note_id'])){
			$note_id=$this->request->get['note_id'];
			$this->model_catalog_note->deleteNote($note_id);
			
			$this->redirectToList();
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url =  $this->getUrlParameters();
			
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/note/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/note/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['notes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$note_total = $this->model_catalog_note->getTotalNotes();
	
		$results = $this->model_catalog_note->getNotes($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/note/update', 'token=' . $this->session->data['token'] . '&note_id=' . $result['note_id'] . $url, 'SSL')
			);
			
			$action['delete'] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->link('catalog/note/delete', 'token=' . $this->session->data['token'] . '&note_id=' . $result['note_id'] . $url, 'SSL')
			);
						
			global $si_code;
				
			$this->data['notes'][] = array(
				'note_id' => $result['note_id'],
				'title'          => $result['title'],
				'used'		  => false,
				'code'          => $result['code'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'sort_order'     => $result['sort_order'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['note_id'], $this->request->post['selected']),
				'action'         => $action
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $note_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		
		$this->template = 'catalog/note_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
	}

	private function getForm() {
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}
		
		$url = $this->getUrlParameters();
					
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['note_id'])) {
			$this->data['action'] = $this->url->link('catalog/note/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/note/update', 'token=' . $this->session->data['token'] . '&note_id=' . $this->request->get['note_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/note', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['note_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$note_info = $this->model_catalog_note->getNote($this->request->get['note_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['note_description'])) {
			$this->data['note_description'] = $this->request->post['note_description'];
		} elseif (isset($this->request->get['note_id'])) {
			$this->data['note_description'] = $this->model_catalog_note->getNoteDescriptions($this->request->get['note_id']);
		} else {
			$this->data['note_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($note_info)) {
			$this->data['status'] = $note_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (isset($note_info)) {
			$this->data['code'] = $note_info['code'];
		} else {
			$this->data['code'] = '';
		}
		
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($note_info)) {
			$this->data['image'] = $note_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
  		$this->load->model('tool/image');

		if (isset($article_info) && $article_info['image'] && file_exists(DIR_IMAGE . $note_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['note_store'])) {
			$this->data['note_store'] = $this->request->post['note_store'];
		} elseif (isset($note_info)) {
			$this->data['note_store'] = $this->model_catalog_note->getNoteStores($this->request->get['note_id']);
		} else {
			$this->data['note_store'] = array(0);
		}		
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($note_info)) {
			$this->data['keyword'] = $note_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($note_info)) {
			$this->data['sort_order'] = $note_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['note_layout'])) {
			$this->data['note_layout'] = $this->request->post['note_layout'];
		} elseif (isset($note_info)) {
			$this->data['note_layout'] = $this->model_catalog_note->getNoteLayouts($this->request->get['note_id']);
		} else {
			$this->data['note_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
				
		$this->template = 'catalog/note_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/note')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		foreach ($this->request->post['note_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 1)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			$this->error['warning'] =$this->language->get('error_required_data');
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/note')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $note_id) {
			if ($this->config->get('config_account_id') == $note_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $note_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			if ($this->config->get('config_affiliate_id') == $note_id) {
				$this->error['warning'] = $this->language->get('error_affiliate');
			}
						
			
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function getUrlParameters(){
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		return $url;
	}
	
	private function redirectToList(){
		$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));

		$url = $this->getUrlParameters();
		
		$this->redirect($this->url->link('catalog/note', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
}
?>