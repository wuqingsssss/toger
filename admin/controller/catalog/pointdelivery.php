<?php 
class ControllerCatalogPointDelivery extends Controller { 
	private $error = array();
	
	private function init(){
		$this->load_language('catalog/pointdelivery');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/pointdelivery');
		$delivery=$this->config->get('delivery_express');
		foreach($delivery as $key=>$item)
		
		$this->data['delivery'][$item['code']]=$item;
	} 
   
  	public function index() {
		$this->init();
		$this->setbackparent();
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
		$this->redirect($this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}
              
  	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_pointdelivery->addDelivery($this->request->post);
      		$this->redirectToList();
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->init();	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_pointdelivery->editDelivery($this->request->get['delivery_id'], $this->request->post);
	  		$this->goback();
	  		//$this->redirectToList();
    	}
    	$this->setbackparent();
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_pointdelivery->deleteDelivery($attribute_group_id);
			}

			$this->redirectToList();
   		}
	
    	$this->getList();
  	}
  	
  	public function updates() {
  		$this->init();

  		if (isset($this->request->post['selected']) && $this->validateDelete()) {
  			/*
  			foreach ($this->request->post['selected'] as $attribute_group_id) {

  					$this->model_catalog_pointdelivery->updateDelivery($attribute_group_id,array('status'=>$this->request->get['status']));

  			}*/
  			$this->model_catalog_pointdelivery->updateDelivery($this->request->post['selected'],array('status'=>$this->request->get['status']));
  				
  			$this->redirectToList();
  			
  			//$this->goback();
  		}
  		
  		$this->setbackparent();
  	
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
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		if (isset($this->request->get['filter_zone_name'])) {
			$filter_zone_name =  $this->request->get['filter_zone_name'];
		} else {
			$filter_zone_name = '';
		}
		if (isset($this->request->get['filter_zone_id'])) {
			$filter_zone_id =  $this->request->get['filter_zone_id'];
		} else {
			$filter_zone_id = null;
		}
		if (isset($this->request->get['filter_name'])) {
		    $filter_name =  $this->request->get['filter_name'];
		} else {
		    $filter_name = '';
		}
		if (isset($this->request->get['filter_region_name'])) {
			$filter_region_name =  $this->request->get['filter_region_name'];
		} else {
			$filter_region_name = '';
		}
  	    if (isset($this->request->get['filter_code'])) {
		    $filter_code =  $this->request->get['filter_code'];
		} else {
		    $filter_code = '';
		}
		
		if (isset($this->request->get['filter_address'])) {
		    $filter_address =  $this->request->get['filter_address'];
		} else {
		    $filter_address = '';
		}
		
		if (isset($this->request->get['filter_telephone'])) {
		    $filter_telephone =  $this->request->get['filter_telephone'];
		} else {
		    $filter_telephone = '';
		}
		
		if (isset($this->request->get['filter_status'])) {
		    $filter_status =  $this->request->get['filter_status'];
		} else {
		    $filter_status = null;
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
				'href'      => $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
			
		
		$this->data['insert'] = $this->url->link('catalog/pointdelivery/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/pointdelivery/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['updates'] = $this->url->link('catalog/pointdelivery/updates', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['deliverys'] = array();

		$data = array(
		    'filter_zone_name'  => $filter_zone_name,
			'filter_zone_id'  => $filter_zone_id,
		    'filter_name' => $filter_name,
		    'filter_region_name' => $filter_region_name,
			'filter_code' => $filter_code,
			'filter_telephone' => $filter_telephone,
		    'filter_address' => $filter_address,
		    'filter_status' => $filter_status,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$delivery_total = $this->model_catalog_pointdelivery->getTotalDeliverys($data);
	
		$results = $this->model_catalog_pointdelivery->getDeliverys($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/pointdelivery/update', 'token=' . $this->session->data['token'] . '&delivery_id=' . $result['delivery_id'] . $url, 'SSL')
			);

			    $delivery_name = '';


			    
			    if($result['delivery_id'])
			    { 
			    
			$this->data['deliverys'][$result['delivery_id']] = array(
				'delivery_id'          => $result['delivery_id'],
				'name'                 => $result['name'],
				'region_name'          => $result['region_name'],
				'region_id'            => $result['region_id'],
				'region_code'            => $result['region_code'],
				'zone_id'              => $result['zone_id'],
				'zone_name'            => $result['zone_name'],
				'address'              => $result['address'],
			    'code'                 => $this->data['delivery'][$result['code']]['title'],
				'telephone'            => $result['telephone'],
				'sort_order'          => (int)$result['sort_order'],
				'delivery_name'        => $delivery_name,
			    'status'               => EnumPointStatus::getPointStatusTitle($result['status']),
				'selected'             => isset($this->request->post['selected']) && in_array($result['delivery_id'], $this->request->post['selected']),
				'action'               => $action,
				'status_bd' => $res
			);
			
			$filter_data = array(
					'filter_p_delivery_id' => $result['delivery_id'],
			);

			$children=$this->model_catalog_pointdelivery->getDeliverys( $filter_data );
			foreach($children as $key=> $child)
			{
				$action = array();
					
				$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('catalog/pointdelivery/update', 'token=' . $this->session->data['token'] . '&delivery_id=' . $child['delivery_id'] . $url, 'SSL')
				);
				
				$children[$key]['region_name']  = '——'.$child['region_name'];
				$children[$key]['code']  =$this->data['delivery'][$result['code']]['title'];
				$children[$key]['status']= EnumPointStatus::getPointStatusTitle($result['status']);
				$children[$key]['action']=$action;

			}
			
			$this->data['deliverys'][$result['delivery_id']]['children']=$children;
			}
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
		
		$this->data['sort_name'] = $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . '&sort=agd.name' . $url, 'SSL');

		// 参数保存
		$url = $this->getCommonUrlParameters();
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $delivery_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_zone_name']           = $filter_zone_name;
		$this->data['filter_name']           = $filter_name;
		$this->data['filter_region_name']            = $filter_region_name;
		$this->data['filter_code'] = $filter_code;
		$this->data['filter_address'] = $filter_address;
		$this->data['filter_telephone']     = $filter_telephone;
		$this->data['filter_status']         = $filter_status;
		
		
		$this->data['status_options'] = EnumPointStatus::getOptions();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/point_delivery_list.tpl';
		
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

		if (isset($this->error['zone_name'])) {
			$this->data['error_zone_name'] = $this->error['zone_name'];
		} else {
			$this->data['error_zone_name'] = array();
		}
		
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		
		if (isset($this->error['region_name'])) {
			$this->data['error_region_name'] = $this->error['region_name'];
		} else {
			$this->data['error_region_name'] = array();
		}
		if (isset($this->error['region_id'])) {
			$this->data['error_region_id'] = $this->error['region_id'];
		} else {
			$this->data['error_region_id'] = array();
		}
		if (isset($this->error['region_code'])) {
			$this->data['error_region_code'] = $this->error['region_code'];
		} else {
			$this->data['error_region_code'] = array();
		}
		if (isset($this->error['address'])) {
			$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = array();
		}
		
		if (isset($this->error['region_coord'])) {
			$this->data['error_region_coord'] = $this->error['region_coord'];
		} else {
			$this->data['error_region_coord'] = array();
		}
		if (isset($this->error['poi'])) {
			$this->data['error_poi'] = $this->error['poi'];
		} else {
			$this->data['error_poi'] = array();
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
				'href'      => $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
			
		
		
		if (!isset($this->request->get['delivery_id'])) {
			$this->data['action'] = $this->url->link('catalog/pointdelivery/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/pointdelivery/update', 'token=' . $this->session->data['token'] . '&delivery_id=' . $this->request->get['delivery_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/pointdelivery', 'token=' . $this->session->data['token'] . $url, 'SSL');



		if (isset($this->request->get['delivery_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$delivery_info = $this->model_catalog_pointdelivery->getDelivery($this->request->get['delivery_id']);
		}
				

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($delivery_info)) {
			$this->data['name'] = $delivery_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($delivery_info)) {
			$this->data['zone_id'] = $delivery_info['zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}
		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($delivery_info)) {
			$this->data['country_id'] = $delivery_info['country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}
		
		if (isset($this->request->post['zone_name'])) {
			$this->data['zone_name'] = $this->request->post['zone_name'];
		} elseif (isset($delivery_info)) {
			$this->data['zone_name'] = $delivery_info['zone_name'];
		} else {
			$this->data['zone_name'] = '';
		}
		if (isset($this->request->post['business_hour'])) {
			$this->data['business_hour'] = $this->request->post['business_hour'];
		} elseif (isset($delivery_info)) {
			$this->data['business_hour'] = $delivery_info['business_hour'];
		} else {
			$this->data['business_hour'] = '';
		}

		if (isset($this->request->post['p_delivery_id'])) {
			$this->data['p_delivery_id'] = $this->request->post['p_delivery_id'];
		} elseif (isset($delivery_info)) {
			$this->data['p_delivery_id'] = $delivery_info['p_delivery_id'];
		} else {
			$this->data['p_delivery_id'] = '';
		}
		
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (isset($delivery_info)) {
			$this->data['code'] = $delivery_info['code'];
		} else {
			$this->data['code'] = '';
		}
        if (isset($this->request->post['region_name'])) {
            $this->data['region_name'] = $this->request->post['region_name'];
        } elseif (isset($delivery_info)) {
            $this->data['region_name'] = $delivery_info['region_name'];
        } else {
            $this->data['region_name'] = '';
        }
        if (isset($this->request->post['region_id'])) {
        	$this->data['region_id'] = $this->request->post['region_id'];
        } elseif (isset($delivery_info)) {
        	$this->data['region_id'] = $delivery_info['region_id'];
        } else {
        	$this->data['region_id'] = '';
        }
        if (isset($this->request->post['region_code'])) {
        	$this->data['region_code'] = $this->request->post['region_code'];
        } elseif (isset($delivery_info)) {
        	$this->data['region_code'] = $delivery_info['region_code'];
        } else {
        	$this->data['region_code'] = '';
        }
        if (isset($this->request->post['address'])) {
        	$this->data['address'] = $this->request->post['address'];
        } elseif (isset($delivery_info)) {
        	$this->data['address'] = $delivery_info['address'];
        } else {
        	$this->data['address'] = '';
        }

		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($delivery_info)) {
			$this->data['telephone'] = $delivery_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['region_coord'])) {
			$this->data['region_coord'] = $this->request->post['region_coord'];
		} elseif (isset($delivery_info)) {
			$this->data['region_coord'] = $delivery_info['region_coord'];
		} else {
			$this->data['region_coord'] = '';
		}
		if (isset($this->request->post['poi'])) {
			$this->data['poi'] = $this->request->post['poi'];
		} elseif (isset($delivery_info)) {
			$this->data['poi'] = $delivery_info['poi'];
		} else {
			$this->data['poi'] = '';
		}
		if (isset($this->request->post['poihash'])) {
			$this->data['poihash'] = $this->request->post['poihash'];
		} elseif (isset($delivery_info)) {
			$this->data['poihash'] = $delivery_info['poihash'];
		} else {
			$this->data['poihash'] = '';
		}
		if (isset($this->request->post['smodel'])) {
			$this->data['smodel'] = $this->request->post['smodel'];
		} elseif (isset($delivery_info)) {
			$this->data['smodel'] = $delivery_info['smodel'];
		} else {
			$this->data['smodel'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($delivery_info)) {
			$this->data['status'] = $delivery_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($delivery_info)) {
			$this->data['sort_order'] = $delivery_info['sort_order'];
		} else {
			$this->data['sort_order'] = 100;
		}
		

		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries(array('status' => 1));
		
		
		
		$data = array(
				'filter_zone_name'  => $delivery_info['zone_name'],
				'filter_zone_id'  => $delivery_info['zone_id'],
				'filter_code' => $delivery_info['code'],
				'filter_p_delivery_id' => 0,
		);
		
		$this->data['p_pointdeliverys'] = $this->model_catalog_pointdelivery->getDeliverys($data);
		
		
		$this->template = 'catalog/point_delivery_form.tpl';

		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();	
  	}
  	
  	private function modifyPermissionCheck(){
  		if (!$this->user->hasPermission('modify', 'catalog/pointdelivery')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
  	}
  	
	private function validateForm() {
    	$this->modifyPermissionCheck();
	
		if($this->request->post['region_name']&&$this->model_catalog_pointdelivery->existDelivery(array('zone_name'=>$this->request->post['zone_name'],'code'=>$this->request->post['code'],'region_name'=>$this->request->post['region_name']),$this->request->get['delivery_id']))
		{
			$this->error['region_name'] = $this->language->get('error_exist_region_name');
		}
	
		if($this->request->post['zone_id'])
		{
		$this->load->model('localisation/zone');
		$zone=$this->model_localisation_zone->getZone($this->request->post['zone_id']);
		$this->request->post['zone_name']= $zone['name']. $zone['name_fix'];
		}
		
		if($this->request->post['smodel']=='1'){
			if(empty($this->request->post['region_coord']))
			{
				$this->error['region_coord'] = $this->language->get('error_exist_region_coord');
			}
		}
		elseif($this->request->post['smodel']=='2')
		{
			if(empty($this->request->post['poi']))
			{
				$this->error['poi'] = $this->language->get('error_exist_poi');
			}
		}
		
		
		if($this->request->post['poi']){
			$location=explode(' ',$this->request->post['poi']);
			if($location[1]&&$location[0])
		  $this->request->post['poihash']=$this->geohash->encode($location[1], $location[0]);
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
  	
  	/**
  	 * @return string
  	 */
  	private function getCommonUrlParameters() {
  	    $url = '';
  
  	    if (isset($this->request->get['filter_code'])) {
  	    	$url .= '&filter_code=' . $this->request->get['filter_code'];
  	    }
  	    
  	    if (isset($this->request->get['filter_zone_name'])) {
  	    	$url .= '&filter_zone_name=' . $this->request->get['filter_zone_name'];
  	    }
  	    
  	    if (isset($this->request->get['filter_name'])) {
  	        $url .= '&filter_name=' . $this->request->get['filter_name'];
  	    }
  	    
  	    if (isset($this->request->get['filter_region_name'])) {
  	    	$url .= '&filter_region_name=' . $this->request->get['filter_region_name'];
  	    }
  	
  	    if (isset($this->request->get['filter_address'])) {
  	        $url .= '&filter_address=' . $this->request->get['filter_address'];
  	    }
  	
  	    if (isset($this->request->get['filter_telephone'])) {
  	        $url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
  	    }

  	    if (isset($this->request->get['filter_status'])) {
  	        $url .= '&filter_status=' . $this->request->get['filter_status'];
  	    }
  	
  	    return $url;
  	}

  	public function point_delivery_form() {
  		$output = '';
  		$this->init();
  		$data = array(
  				'filter_zone_id'  => $this->request->get['zone_id'],
  				'filter_code' => $this->request->get['code'],
  				'filter_p_delivery_id' => 0,
  		);
  		
  		if (!$this->request->get['p_delivery_id']) {
  			$output .= '<option value="0" selected="selected">' . $this->language->get('text_select') . '</option>';
  		} else {
  			$output .= '<option value="0">' . $this->language->get('text_select'). '</option>';
  		}
  		
  		$results = $this->model_catalog_pointdelivery->getDeliverys($data);
  		
  		foreach ($results as $result) {
  			$output .= '<option value="' . $result['delivery_id'] . '"';
  	
  			if (isset($this->request->get['p_delivery_id']) && ($this->request->get['p_delivery_id'] == $result['delivery_id'])) {
  				$output .= ' selected="selected"';
  			}
  	
  			$output .= '>' . $result['region_name'] . '</option>';
  		}
  	
  		

  	
  	
  		$this->response->setOutput($output);
  	}
  	 
  	
}
?>