<?php 
class ControllerCatalogInformationGroup extends Controller { 
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/information_group');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/information_group');
	}
   
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');

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
					
      	$this->redirect($this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	
  	public function index() {
  		$this->init();
		
    	$this->getList();
  	}
              
  	public function insert() {
		$this->init();
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_information_group->addInformationGroup($this->request->post);
		  	
      		$this->redirectToList();
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->init();
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_information_group->editInformationGroup($this->request->get['information_group_id'], $this->request->post);
			
			$this->redirectToList();
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $selected_id) {
				$this->model_catalog_information_group->deleteInformationGroup($selected_id);
			}
			      		
			$this->redirectToList();
   		}
	
    	$this->getList();
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('catalog/information_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/information_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['information_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_group_total = $this->model_catalog_information_group->getTotalInformationGroups();
	
		$results = $this->model_catalog_information_group->getInformationGroups($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/information_group/update', 'token=' . $this->session->data['token'] . '&information_group_id=' . $result['information_group_id'] . $url, 'SSL')
			);
						
			$this->data['information_groups'][] = array(
				'information_group_id' => $result['information_group_id'],
				'name'               => $result['name'],
				'code'               => $result['code'],
				'sort_order'         => $result['sort_order'],
				'selected'           => isset($this->request->post['selected']) && in_array($result['information_group_id'], $this->request->post['selected']),
				'action'             => $action
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
		
		$this->data['sort_name'] = $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/information_group_list.tpl';
		
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
			$this->data['error_name'] = array();
		}
		
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),    		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		if (!isset($this->request->get['information_group_id'])) {
			$this->data['action'] = $this->url->link('catalog/information_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/information_group/update', 'token=' . $this->session->data['token'] . '&information_group_id=' . $this->request->get['information_group_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/information_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['information_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_group_info = $this->model_catalog_information_group->getInformationGroup($this->request->get['information_group_id']);
		}
				
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['information_group_description'])) {
			$this->data['information_group_description'] = $this->request->post['information_group_description'];
		} elseif (isset($this->request->get['information_group_id'])) {
			$this->data['information_group_description'] = $this->model_catalog_information_group->getInformationGroupDescriptions($this->request->get['information_group_id']);
		} else {
			$this->data['information_group_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($information_group_info)) {
			$this->data['sort_order'] = $information_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_group_info)) {
			$this->data['status'] = $information_group_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (isset($information_group_info)) {
			$this->data['code'] = $information_group_info['code'];
		} else {
			$this->data['code'] = '';
		}

		$this->template = 'catalog/information_group_form.tpl';

		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();	
  	}
  	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/information_group')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['information_group_description'] as $language_id => $value) {
      		if ((strlen(utf8_decode($value['name'])) < 1) || (strlen(utf8_decode($value['name'])) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/information_group')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
//		$this->load->model('catalog/information');
//		
//		foreach ($this->request->post['selected'] as $information_group_id) {
//			$attribute_total = $this->model_catalog_attribute->getTotalAttributesByInformationGroupId($information_group_id);
//
//			if ($attribute_total) {
//				$this->error['warning'] = sprintf($this->language->get('error_attribute'), $attribute_total);
//			}
//	  	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}	  
}
?>