<?php 
class ControllerCatalogReferenceList extends Controller {
	private $error = array();
	
	private function init(){
		$this->load_language('catalog/reference_list');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/reference');
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
					
      	$this->redirect($this->url->link('catalog/reference_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}
              
  	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_reference->addreference($this->request->post);
      		
      		$this->redirectToList();
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->init();
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_reference->editReference($this->request->get['refer_id'], $this->request->post);
			
	  		$this->redirectToList();
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_reference->deletreference($attribute_group_id);
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
			'href'      => $this->url->link('catalog/reference_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('catalog/reference_list/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/reference_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['points'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$point_total = $this->model_catalog_reference->getTotalPoints($data);
	
		$results = $this->model_catalog_reference->getRefer($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/reference_list/update', 'token=' . $this->session->data['token'] . '&refer_id=' . $result['id'] . $url, 'SSL')
			);
						
			$this->data['points'][] = array(
				'refer_id' => $result['id'],
				'name'               => $result['name'],
				'refer_code'               => $result['refer_code'],
				'point_code'               => $result['point_code'],
				's_valid_time'               => $result['s_valid_time'],
                'e_valid_time'               => $result['e_valid_time'],
                'type'               => $result['type'],
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
		
		$this->data['sort_name'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . '&sort=ag.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $point_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/reference_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/reference_list.tpl';
		
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
			'href'      => $this->url->link('catalog/reference_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		if (!isset($this->request->get['refer_id'])) {
			$this->data['action'] = $this->url->link('catalog/reference_list/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/reference_list/update', 'token=' . $this->session->data['token'] . '&refer_id=' . $this->request->get['refer_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url, 'SSL');


		if (isset($this->request->get['refer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$point_info = $this->model_catalog_reference->getreferences($this->request->get['refer_id']);
		}

        if (isset($this->request->post['point_code'])) {
            $this->data['point_code'] = $this->request->post['point_code'];
        } elseif (isset($point_info)) {
            $this->data['point_code'] = $point_info['point_code'];
        } else {
            $this->data['point_code'] = '';
        }
        if (isset($this->request->post['refer_code'])) {
            $this->data['refer_code'] = $this->request->post['refer_code'];
        } elseif (isset($point_info)) {
            $this->data['refer_code'] = $point_info['refer_code'];
        } else {
            $this->data['refer_code'] = '';
        }

		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (isset($point_info)) {
			$this->data['type'] = $point_info['type'];
		} else {
			$this->data['type'] = '1';
		}


        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (isset($point_info)) {
            $this->data['name'] = $point_info['name'];
        } else {
            $this->data['name'] = '';
        }

        if (isset($this->request->post['s_valid_time'])) {
        	$this->data['s_valid_time'] = $this->request->post['s_valid_time'];
        } elseif (isset($point_info)) {
        	$this->data['s_valid_time'] = $point_info['s_valid_time'];
        } else {
        	$this->data['s_valid_time'] = '';
        }

        if (isset($this->request->post['e_valid_time'])) {
        	$this->data['e_valid_time'] = $this->request->post['e_valid_time'];
        } elseif (isset($point_info)) {
        	$this->data['e_valid_time'] = $point_info['e_valid_time'];
        } else {
        	$this->data['e_valid_time'] = '';
        }

       if (isset($point_info)) {
            $this->data['refer_code'] = $point_info['refer_code'];
        } else {
            $this->data['refer_code'] = '';
        }


        if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($point_info)) {
			$this->data['sort_order'] = $point_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		$this->template = 'catalog/reference_form.tpl';

		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();	
  	}
  	
  	private function modifyPermissionCheck(){
  		if (!$this->user->hasPermission('modify', 'catalog/point')) {
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