<?php
class ControllerCatalogLinkGroup extends Controller {
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/link_group');
 
		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->load->model('catalog/link_group');
	}
	
	protected function redirectToList(){
		$this->session->data['success'] =sprintf($this->language->get('text_success'),$this->language->get('heading_title')) ;

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
		
		$this->redirect($this->url->link('catalog/link_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	
 
	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_link_group->addLinkGroup($this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function update() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_link_group->editLinkGroup($this->request->get['link_group_id'], $this->request->post);
			
			$this->redirectToList();
		}

		$this->getForm();
	}

	public function delete() { 
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $selected_id) {
				$this->model_catalog_link_group->deleteLinkGroup($selected_id);	
			}
						
			$this->redirectToList();
		}

		$this->getList();
	}
	
	private function getCommonUrlParameters(){
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
	
	private function initBreadcrumbs(){
		$url=$this->getCommonUrlParameters();
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/link_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
		
		$url = $this->getCommonUrlParameters();
		
		$this->initBreadcrumbs();
							
		$this->data['insert'] = $this->url->link('catalog/link_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/link_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
	
		$this->data['link_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$customer_group_total = $this->model_catalog_link_group->getTotalLinkGroups();
		
		$results = $this->model_catalog_link_group->getLinkGroups($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/link_group/update', 'token=' . $this->session->data['token'] . '&link_group_id=' . $result['link_group_id'] . $url, 'SSL')
			);		
		
			$this->data['link_groups'][] = array(
				'link_group_id' => $result['link_group_id'],
				'name'              => $result['name'],
				'selected'          => isset($this->request->post['selected']) && in_array($result['link_group_id'], $this->request->post['selected']),
				'action'            => $action
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

		$this->data['sort_name'] = $this->url->link('catalog/link_group', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/link_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;

		$this->template = 'catalog/link_group_list.tpl';
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

		$url = $this->getCommonUrlParameters();
			
		$this->initBreadcrumbs();
		
		if (!isset($this->request->get['link_group_id'])) {
			$this->data['action'] = $this->url->link('catalog/link_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/link_group/update', 'token=' . $this->session->data['token'] . '&link_group_id=' . $this->request->get['link_group_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('catalog/link_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['link_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$result_info = $this->model_catalog_link_group->getLinkGroup($this->request->get['link_group_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($result_info)) {
			$this->data['name'] = $result_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($result_info)) {
			$this->data['status'] = $result_info['status'];
		} else {
			$this->data['status'] = '1';
		}
	
		$this->template = 'catalog/link_group_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/link_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/link_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>