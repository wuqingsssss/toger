<?php 
class ControllerCatalogCbd extends Controller { 
	private $error = array();
	
	private function init(){
		$this->load_language('catalog/cbd');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/cbd');
	} 
   
  	public function index() {
		$this->init();
		
    	$this->getList();
  	}
  	
  	private function redirectToList(){
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
					
      	$this->redirect($this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}
              
  	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_cbd->addCbd($this->request->post);
      		
      		$this->redirectToList();
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->init();
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_cbd->editCbd($this->request->get['cbd_id'], $this->request->post);
			
	  		$this->redirectToList();
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_cbd->deleteCbd($id);
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
			'href'      => $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   	
							
		$this->data['insert'] = $this->url->link('catalog/cbd/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/cbd/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['cbds'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$cbd_total = $this->model_catalog_cbd->getTotalCbds();
	
		$results = $this->model_catalog_cbd->getCbds($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/cbd/update', 'token=' . $this->session->data['token'] . '&cbd_id=' . $result['id'] . $url, 'SSL')
			);
						
			$this->data['points'][] = array(
				'cbd_id' => $result['id'],
				'name'               => $result['name'],
				'selected'           => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
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
		
		$this->data['sort_name'] = $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $cbd_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/cbd_list.tpl';
		
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
			'href'      => $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		

		$this->data['country_id'] = $this->config->get('config_country_id');
		$this->data['zone_id'] = $this->config->get('config_zone_id');

		$this->load->model('localisation/city');
		$this->data['cities'] = $this->model_localisation_city->getCitiesByZoneId($this->config->get('config_zone_id'));
		
		
		if (!isset($this->request->get['cbd_id'])) {
			$this->data['action'] = $this->url->link('catalog/cbd/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/cbd/update', 'token=' . $this->session->data['token'] . '&cbd_id=' . $this->request->get['cbd_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/cbd', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['cbd_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cbd_info = $this->model_catalog_cbd->getCbd($this->request->get['cbd_id']);
		}
				
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($cbd_info)) {
			$this->data['name'] = $cbd_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['city_id'])) {
			$this->data['city_id'] = $this->request->post['city_id'];
		} elseif (isset($cbd_info)) {
			$this->data['city_id'] = $cbd_info['city_id'];
		} else {
			$this->data['city_id'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($cbd_info)) {
			$this->data['status'] = $cbd_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($cbd_info)) {
			$this->data['sort_order'] = $cbd_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		$this->template = 'catalog/cbd_form.tpl';

		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();	
  	}
  	
  	private function modifyPermissionCheck(){
  		if (!$this->user->hasPermission('modify', 'catalog/cbd')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
  	}
  	
	private function validateForm() {
    	$this->modifyPermissionCheck();
	
		if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 200)) {
        	$this->error['name'] = $this->language->get('error_name');
      	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		$this->modifyPermissionCheck();
	
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}	  
}
?>