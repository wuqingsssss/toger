<?php  
class ControllerSaleCoupon extends Controller {
	private $error = array();
     
  	public function index() {
		$this->load_language('sale/coupon');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/coupon');
		
		$this->getList();
  	}
  
  	public function insert() {
    	$this->load_language('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/coupon');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
    		
    		ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
    		ini_set("max_execution_time", 0);
    		
			$postData = array_merge(array(),$this->request->post);
			$creator_id=$this->user->getId();
			$owner_id=(!empty($postData['owner_id'])?$postData['owner_id']:$creator_id);
			$postData['creator_id']=$creator_id;
			$postData['owner_id']=$owner_id;
			$this->model_sale_coupon->batchAddCoupon($postData);
			
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
						
			$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
    
    	$this->getForm();
  	}

  	public function update() {
    	$this->load_language('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/coupon');
				
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$postData = $this->request->post;
			$this->model_sale_coupon->editCoupon($this->request->get['coupon_id'], $postData);
      		
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
						
			$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	}

  	public function delete() {
    	$this->load_language('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/coupon');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) { 
			foreach ($this->request->post['selected'] as $coupon_id) {
				$this->model_sale_coupon->deleteCoupon($coupon_id);
			}
      		
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
						
			$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getList();
  	}

  	private function getList() {
		$params = $this->request->get;
		foreach($params as $k=>$v){
			$this->data[$k]=$v;
		}


		if (isset($this->request->get['owner_id'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'coupon_id';
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
		
		if (isset($this->request->get['name'])) {
			$url .= '&name=' . $this->request->get['name'];
		}

  		$this->data['breadcrumbs'] = array();

		$token = $this->session->data['token'];
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $token, 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/coupon', 'token=' . $token . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('sale/coupon/insert', 'token=' . $token . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/coupon/delete', 'token=' . $token . $url, 'SSL');
		$this->data['link-list'] = $this->url->link('sale/coupon', 'token=' . $token . $url, 'SSL');
		$this->data['token'] = $token;


		$this->data['coupons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit'),
			'name'=>$this->request->get['name'],
			'owner_id'=>$this->request->get['owner_id']
		);

		$dbResults = $this->model_sale_coupon->getCoupons($data);
		$coupon_total = $dbResults['total'];
		$results=$dbResults['rows'];

		foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/coupon/update', 'token=' . $token . '&coupon_id=' . $result['coupon_id'] . $url, 'SSL')
			);
						
			$this->data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'ownerName'       => $result['ownerName'],
				'owner_id'       => $result['owner_id'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
			    'duration'   => $result['duration'],
			    'usage'      => $result['usage'],
				'free_get'     => ($result['free_get'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['coupon_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getAllUsersExceptSuperAdmin();

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
		if (isset($this->request->get['name'])) {
			$url .= '&name=' . $this->request->get['name'];
		}
		if (isset($this->request->get['owner_id'])) {
			$url .= '&owner_id=' . $this->request->get['owner_id'];
		}
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=name' . $url;
		$this->data['sort_code'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=code' . $url;
		$this->data['sort_discount'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=discount' . $url;
		$this->data['sort_date_start'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=date_start' . $url;
		$this->data['sort_date_end'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=date_end' . $url;
		$this->data['sort_duration'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=duration' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . '&sort=status' . $url;
				
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}	
		if (isset($this->request->get['name'])) {
			$url .= '&name=' . $this->request->get['name'];
		}
		if (isset($this->request->get['owner_id'])) {
			$url .= '&owner_id=' . $this->request->get['owner_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $token . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/coupon_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}

  	
  	/**
  	 * 补全菜品信息
  	 */
  	public function autocomplete() {
  	
  		$json = array();
  	
  		if(isset($this->request->post['filter_name'])){
  			$this->request->get['filter_name']=trim($this->request->post['filter_name']);
  		}
  	
  		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_code'])) {
		
  			$this->load->model('sale/coupon');
  				
  			$requestes=array(
  					'filter_name' => '',
  					'filter_code' => '',
  					'limit' => 20
  			);
  				
  			foreach ($requestes as $key => $value) {
  				if (isset($this->request->get[$key])&&$this->request->get[$key]!='') {
  					$$key = trim($this->request->get[$key]);
  				} else {
  					$$key = $value;
  				}
  			}
  	
  			$data = array(
  					'name'         => $filter_name,
  					'code'         => $filter_model,
  					'start'        => 0,
  					'limit'        => $limit
  					//,'filter_status'       => 1 菜品状态判断暂时关闭 20150404
  			);
  				
  			$results = $this->model_sale_coupon->getCoupons($data);
  				
  			foreach ($results['rows'] as $result) {
  				$json[] = array(
  						'coupon_id' => $result['coupon_id'],
  						'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
  						'code'      => $result['code']
  				);
  			}
  		}
  	
  		$this->response->setOutput(json_encode($json));
  	}
  	
  	
  	private function getForm() {
    	$this->data['token'] = $this->session->data['token'];
	
		if (isset($this->request->get['coupon_id'])) {
			$this->data['coupon_id'] = $this->request->get['coupon_id'];
		} else {
			$this->data['coupon_id'] = 0;
		}
				
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
		
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}		
		
		if (isset($this->error['date_start'])) {
			$this->data['error_date_start'] = $this->error['date_start'];
		} else {
			$this->data['error_date_start'] = '';
		}	
		
		if (isset($this->error['date_end'])) {
			$this->data['error_date_end'] = $this->error['date_end'];
		} else {
			$this->data['error_date_end'] = '';
		}	

		if (isset($this->error['duration'])) {
		    $this->data['error_duration'] = $this->error['duration'];
		} else {
		    $this->data['error_duration'] = '';
		}
		
		if (isset($this->error['usage'])) {
		    $this->data['error_usage'] = $this->error['usage'];
		} else {
		    $this->data['error_usage'] = '';
		}

		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
									
		if (!isset($this->request->get['coupon_id'])) {
			$this->data['action'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/coupon/update', 'token=' . $this->session->data['token'] . '&coupon_id=' . $this->request->get['coupon_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL');
  		
		if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$coupon_info = $this->model_sale_coupon->getCoupon($this->request->get['coupon_id']);
      		
      		if($coupon_info['share_link']){
      			$this->load->service ('baidu/dwz','service');
      			$dwz=$this->service_baidu_dwz->hcreate(htmlspecialchars_decode($coupon_info['share_link']));
      			$coupon_info['share_short_link']=$dwz['tinyurl'];}
      		
    	}
		
    	if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} elseif (isset($coupon_info)) {
			$this->data['name'] = $coupon_info['name'];
		} else {
      		$this->data['name'] = '';
    	}
		
    	if (isset($this->request->post['code'])) {
      		$this->data['code'] = $this->request->post['code'];
    	} elseif (isset($coupon_info)) {
			$this->data['code'] = $coupon_info['code'];
		} else {
      		$this->data['code'] = '';
    	}
		
    	if (isset($this->request->post['type'])) {
      		$this->data['type'] = $this->request->post['type'];
    	} elseif (isset($coupon_info)) {
			$this->data['type'] = $coupon_info['type'];
		} else {
      		$this->data['type'] = '';
    	}
		
    	if (isset($this->request->post['discount'])) {
      		$this->data['discount'] = $this->request->post['discount'];
    	} elseif (isset($coupon_info)) {
			$this->data['discount'] = $coupon_info['discount'];
		} else {
      		$this->data['discount'] = '';
    	}

    	if (isset($this->request->post['logged'])) {
      		$this->data['logged'] = $this->request->post['logged'];
    	} elseif (isset($coupon_info)) {
			$this->data['logged'] = $coupon_info['logged'];
		} else {
      		$this->data['logged'] = '';
    	}
		
    	if (isset($this->request->post['shipping'])) {
      		$this->data['shipping'] = $this->request->post['shipping'];
    	} elseif (isset($coupon_info)) {
			$this->data['shipping'] = $coupon_info['shipping'];
		} else {
      		$this->data['shipping'] = '';
    	}

    	if (isset($this->request->post['total'])) {
      		$this->data['total'] = $this->request->post['total'];
    	} elseif (isset($coupon_info)) {
			$this->data['total'] = $coupon_info['total'];
		} else {
      		$this->data['total'] = '';
    	}
    	
    	if (isset($this->request->post['share_title'])) {
    		$this->data['share_title'] = $this->request->post['share_title'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_title'] = $coupon_info['share_title'];
    	} else {
    		$this->data['share_title'] = '';
    	}
    	if (isset($this->request->post['share_desc'])) {
    		$this->data['share_desc'] = $this->request->post['share_desc'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_desc'] = $coupon_info['share_desc'];
    	} else {
    		$this->data['share_desc'] = '';
    	}
    	if (isset($this->request->post['share_image'])) {
    		$this->data['share_image'] = $this->request->post['share_image'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_image'] = $coupon_info['share_image']; 		
    	} else {
    		$this->data['share_image'] = '';
    	}
    	if (isset($this->request->post['share_image1'])) {
    		$this->data['share_image2'] = $this->request->post['share_image1'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_image1'] = $coupon_info['share_image1'];
    	} else {
    		$this->data['share_image1'] = '';
    	}
    	if (isset($this->request->post['share_image2'])) {
    		$this->data['share_image2'] = $this->request->post['share_image2'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_image2'] = $coupon_info['share_image2'];
    	} else {
    		$this->data['share_image2'] = '';
    	}
    	if (isset($this->request->post['share_image3'])) {
    		$this->data['share_image3'] = $this->request->post['share_image3'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_image3'] = $coupon_info['share_image3'];
    	} else {
    		$this->data['share_image3'] = '';
    	}
    	if (isset($this->request->post['share_btn'])) {
    		$this->data['share_btn'] = $this->request->post['share_btn'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_btn'] = $coupon_info['share_btn'];
    	} else {
    		$this->data['share_btn'] = '';
    	}
    	if (isset($this->request->post['share_bg'])) {
    		$this->data['share_bg'] = $this->request->post['share_bg'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_bg'] = $coupon_info['share_bg'];
    	} else {
    		$this->data['share_bg'] = '';
    	}
    	
    	$this->load->model('tool/image');
    	
    	if ($this->data['share_image']) {
    		$this->data['preview'] = $this->model_tool_image->resize($this->data['share_image'], 100, 100);
    	} else {
    		$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    	}
    	if ($this->data['share_image1']) {
    		$this->data['preview_1'] = $this->model_tool_image->resize($this->data['share_image1'], 100, 100);
    	} else {
    		$this->data['preview_1'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    	}
    	if ($this->data['share_image2']) {
    		$this->data['preview_2'] = $this->model_tool_image->resize($this->data['share_image2'], 100, 100);
    	} else {
    		$this->data['preview_2'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    	}
    	if ($this->data['share_btn']) {
    		$this->data['preview_btn'] = $this->model_tool_image->resize($this->data['share_btn'], 100, 100);
    	} else {
    		$this->data['preview_btn'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    	}
    	if ($this->data['share_bg']) {
    		$this->data['preview_bg'] = $this->model_tool_image->resize($this->data['share_bg'], 100, 100);
    	} else {
    		$this->data['preview_bg'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    	}
    	
    	if (isset($this->request->post['share_link'])) {
    		$this->data['share_link'] = $this->request->post['share_link'];
    	} elseif (isset($coupon_info)) {
    		$this->data['share_link'] = $coupon_info['share_link'];
    		$this->data['share_short_link'] = $coupon_info['share_short_link'];
    	} else {
    		$this->data['share_link'] = '';
    	}
    	
    	if (isset($this->request->post['owner_id'])) {
      		$this->data['owner_id'] = $this->request->post['owner_id'];
    	} elseif (isset($coupon_info)) {
			$this->data['owner_id'] = $coupon_info['owner_id'];
		}
		if(isset($coupon_info)){
			$this->data['creator_id'] = $coupon_info['creator_id'];
		}

		if (isset($this->request->post['coupon_product'])) {
			$products = $this->request->post['coupon_product'];
		} elseif (isset($coupon_info)) {		
			$products = $this->model_sale_coupon->getCouponProducts($this->request->get['coupon_id']);
		} else {
			$products = array();
		}
		
		$this->load->model('catalog/product');
		
		$this->data['coupon_product'] = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['coupon_product'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
			
		if (isset($this->request->post['date_start'])) {
       		$this->data['date_start'] = $this->request->post['date_start'];
		} elseif (isset($coupon_info)) {
			$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
		} else {
			$this->data['date_start'] = date('Y-m-d', time());
		}

		if (isset($this->request->post['date_end'])) {
       		$this->data['date_end'] = $this->request->post['date_end'];
		} elseif (isset($coupon_info)) {
			$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
		} else {
			$this->data['date_end'] = date('Y-m-d', time());
		}

    	if (isset($this->request->post['uses_total'])) {
      		$this->data['uses_total'] = $this->request->post['uses_total'];
		} elseif (isset($coupon_info)) {
			$this->data['uses_total'] = $coupon_info['uses_total'];
    	} else {
      		$this->data['uses_total'] = 1;
    	}
  
    	if (isset($this->request->post['uses_customer'])) {
      		$this->data['uses_customer'] = $this->request->post['uses_customer'];
    	} elseif (isset($coupon_info)) {
			$this->data['uses_customer'] = $coupon_info['uses_customer'];
		} else {
      		$this->data['uses_customer'] = 1;
    	}
 
    	if (isset($this->request->post['duration'])) {
    	    $this->data['duration'] = $this->request->post['duration'];
    	} elseif (isset($coupon_info)) {
    	    $this->data['duration'] =  $coupon_info['duration'];
    	} else {
    	    $this->data['duration'] = 1;
    	}
    	
    	if (isset($this->request->post['usage'])) {
    	    $this->data['usage'] = $this->request->post['usage'];
    	} elseif (isset($coupon_info)) {
    	    $this->data['usage'] =  $coupon_info['usage'];
    	} else {
    	    $this->data['usage'] = '';
    	}

    	
    	if (isset($this->request->post['free_get'])) {
    		$this->data['free_get'] = $this->request->post['free_get'];
    	} elseif (isset($coupon_info)) {
    		$this->data['free_get'] = $coupon_info['free_get'];
    	} else {
    		$this->data['free_get'] = 1;
    	}
    	if (isset($this->request->post['mutual_prom'])) {
    		$this->data['mutual_prom'] = $this->request->post['mutual_prom'];
    	} elseif (isset($coupon_info)) {
    		$this->data['mutual_prom'] = $coupon_info['mutual_prom'];
    	} else {
    		$this->data['mutual_prom'] = 1;
    	}

    	
    	
    	if (isset($this->request->post['status'])) { 
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (isset($coupon_info)) {
			$this->data['status'] = $coupon_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getAllUsersExceptSuperAdmin();
		
		$this->template = 'sale/coupon_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();		
  	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
      	
		if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 128)) {
        	$this->error['name'] = $this->language->get('error_name');
      	}
			
      //	if (strlen(utf8_decode($this->request->post['usage'])) > 128){
      	    //$this->error['usage'] = $this->language->get('error_usage');
      //	}
			
    	if ((strlen(utf8_decode($this->request->post['code'])) < 1) || (strlen(utf8_decode($this->request->post['code'])) > 10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
		$batch = $this->request->post['batch'];
		if (!empty($batch) && !preg_match("/^\d*$/",$batch) ){
      		$this->error['batch'] = $this->language->get('error_batch');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}	
	
	public function history() {
    	$this->language->load('sale/coupon');
		
		$this->load->model('sale/coupon');
				
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_order_id'] = $this->language->get('column_order_id');
		$this->data['column_mobile']   = $this->language->get('column_mobile');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_date_added'] = $this->language->get('column_date_added');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['histories'] = array();
			
		$results = $this->model_sale_coupon->getCouponHistories($this->request->get['coupon_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
        	    'mobile'     => $result['mobile'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_coupon->getTotalCouponHistories($this->request->get['coupon_id']);
			
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/coupon/history', 'token=' . $this->session->data['token'] . '&coupon_id=' . $this->request->get['coupon_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'sale/coupon_history.tpl';		
		
		$this->response->setOutput($this->render());
  	}		
}
?>