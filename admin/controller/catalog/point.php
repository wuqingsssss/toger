<?php 
class ControllerCatalogPoint extends Controller { 
	private $error = array();
	
	private function init(){
		$this->load_language('catalog/point');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/point');
		$this->load->service('baidu/point','service');
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
		$this->redirect($this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  	}
              
  	public function insert() {
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_point->addPoint($this->request->post);
      		
      		
      		if($this->request->post['updatebaidu']=='1')
      		{  
      			if($this->request->post['status']=='1'){
      				$location=explode(',', $this->request->post['coordinate']);
      				$data['point_code']=$this->request->post['point_code_new'];
      				$data['title']=$this->request->post['name'];
      				$data['image']=$this->request->post['image'];
      				$data['device_code']=$this->request->post['device_code'];
      				$data['address']  =$this->request->post['address'];
      				$data['pick_up_time']  =$this->request->post['business_hour'];
      				$data['tel']  =$this->request->post['telephone'];
      				$data['cbd']  =$this->request->post['cbd_id'];
      				$data['group']  =$this->request->post['customer_group_id'];
      				$data['latitude'] = $location[1];
      				$data['longitude']= $location[0];
      				$res=$this->service_baidu_point->hpoicreate($data);
      			}
   	
      		}
      		
      		
      		$this->redirectToList();
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->init();	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_point->editPoint($this->request->get['point_id'], $this->request->post);

	  		if($this->request->post['updatebaidu']=='1')
	  		{	

	  			$data['point_code']=$this->request->post['point_code_new'];
	  			$data['title']=$this->request->post['name'];
	  			if($this->request->post['status']=='1'){
	  			$location=explode(',', $this->request->post['coordinate']);
	  			$data['image']=$this->request->post['image'];
	  			$data['device_code']=$this->request->post['device_code'];
	  			$data['address']  =$this->request->post['address'];
	  			$data['group']  =$this->request->post['customer_group_id'];
	  			$data['pick_up_time']  =$this->request->post['business_hour'];
	  			$data['tel']  =$this->request->post['telephone'];
	  			$data['cbd']  =$this->request->post['cbd_id'];
	  			$data['latitude'] = $location[1];
	  			$data['longitude']= $location[0];
	  			$res=$this->service_baidu_point->hpoiupdate($data);
	  			if($res['status']>0)
	  			{
	  				$res=$this->service_baidu_point->hpoicreate($data);
	  			}
	  			}
	  			else 
	  			{if($data['point_code']||$data['title'])
	  				$res=$this->service_baidu_point->hpoidelete($data);
	  			}
	  		}
	  		
	  		$this->redirectToList();
	
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_point->deletePoint($attribute_group_id);
			}

			$this->redirectToList();
   		}
	
    	$this->getList();
  	}
  	
  	public function updates() {
  		$this->init();
  	
  		if (isset($this->request->post['selected']) && $this->validateDelete()) {
  			
  			foreach ($this->request->post['selected'] as $attribute_group_id) {
  				
  			if($this->request->get['status']=='-1'){
  				$point = $this->model_catalog_point->getPoint($attribute_group_id);
  				$data['point_code']=$point['point_code_new'];
  				$data['title']=$point['name'];
  			if($point['status']=='1'){
	  			$location=explode(',', $point['coordinate']);  	
	  			$data['device_code']=$point['device_code'];
	  			$data['image']=$point['image'];  			
	  			$data['address']  =$point['address'];
	  			$data['pick_up_time']  =$point['business_hour'];
	  			$data['tel']  =$point['telephone'];
	  			$data['cbd']  =$point['cbd_id'];
	  			$data['latitude'] = $location[1];
	  			$data['longitude']= $location[0];
	  			$data['group']  =$point['customer_group_id'];
	  			$res=$this->service_baidu_point->hpoiupdate($data);
	  			if($res['status']>0)
	  			{//如果更新失败则新建
	  				$res=$this->service_baidu_point->hpoicreate($data);
	  			}
	  		}
	  		else 
	  			{if($data['point_code']||$data['title'])
	  				$res=$this->service_baidu_point->hpoidelete($data);
	  			}	
  			}
  			elseif($this->request->get['status']=='-2'){
  				$point = $this->model_catalog_point->getPoint($attribute_group_id);
  				$res=$this->service_baidu_point->hlist(array('point_code'=>$point['point_code_new']));
  				if($res&&$res[0]['point_code']==$point['point_code_new']){
  				$point['coordinate']=$res[0]['location'][0].','.$res[0]['location'][1];
  				$point['name']=$res[0]['title'];
  				$point['address']=$res[0]['address'];
  				$point['cbd_id']=$res[0]['cbd'];
  				$point['business_hour']=$res[0]['pick_up_time'];
  				$point['telephone']=$res[0]['tel'];
  				$point['customer_group_id']  =$res[0]['group'];
  				$point['status']='1';
  		
  				$this->model_catalog_point->updatePoint($attribute_group_id,$point);
  				}
  			}
  			elseif($this->request->get['status']>=0)
  			{

  					$this->model_catalog_point->updatePoint($attribute_group_id,array('status'=>$this->request->get['status']));
  			}
  			}
  	
  			$this->redirectToList();
  		}
  	
  		$this->getList();
  	}
    
  public function host2yun(){//发起异步请求
  	 	
  	    $url=HTTP_CATALOG.'apiv3/index.php?route=lbs/baidu/ssctest';
  	   /* 
  	    $this->log_admin->info($url);
  	    $s=Http::getSSCGET($url);
  	   
  */
 
  	    $this->log_admin->info($url);
  	    $s=Http::getSSCPOST($url,array('point_id'=>'1208'));

  	    die();
  	    $this->redirectToList();
  	    
  }
  	
  	/*本地数据同步更新到云*/
  	public function sschost2yun(){
  		ignore_user_abort(TRUE);//如果客户端断开连接，不会引起脚本abort		
  		ini_set("max_execution_time",1800);
  		$this->init(); 		
  		$data = array(
  				'filter_status'=>1,
  				'limit'=>20
  		);/*
  		$hostdata = $this->model_catalog_point->getPoints($data);
  		foreach($hostdata as $key=>$point)
  		{
  			$data['point_code']=$point['point_code_new'];
  			$data['title']=$point['name'];
  		     if($point['status']=='1'){
	  			$location=explode(',', $point['coordinate']);  	
	  			$data['device_code']=$point['device_code'];
	  			$data['image']=$point['image'];  			
	  			$data['address']  =$point['address'];
	  			$data['pick_up_time']  =$point['business_hour'];
	  			$data['tel']  =$point['telephone'];
	  			$data['cbd']  =$point['cbd_id'];
	  			$data['latitude'] = $location[1];
	  			$data['longitude']= $location[0];
	  			$res[$point['point_id']]=$this->service_baidu_point->hpoiupdate($data);
	  			if($res[$point['point_id']]['status']>0)
	  			{//如果更新失败则新建
	  				$res[$point['point_id']]=$this->service_baidu_point->hpoicreate($data);
	  			}
	  		}
	  		else 
	  		  {  if($data['point_code'])
	  			  $res[$point['point_id']]=$this->service_baidu_point->hpoidelete($data);
	  		  }	
  	
  		}*/
  		$res=$data;

  		$this->log_order->info($res);
  		$this->log_admin->info($res);

  		echo(json_encode($res));	
  	}
  	
  	/*从云库更新本地数据库*/
  	public function yun2host(){
  		$this->init();
  		
  		$yundata=$this->service_baidu_point->hlist();
  		
  		foreach($yundata as $key=>$item)
  		{
  		        $point['point_code_new']=$item['point_code'];
  				$point['coordinate']=$item['location'][0].','.$item['location'][1];
  				$point['name']=$item['title'];
  				$point['address']=$item['address'];
  				$point['cbd_id']=$item['cbd'];
  				$point['business_hour']=$item['pick_up_time'];
  				$point['telephone']=$item['tel'];
  				$point['status']='1';
  		        if($point['point_code_new'])
  		        {
  				    $point_id=getPointId($point['point_code_new']);
  				    $this->model_catalog_point->updatePoint($point_id,$point);
  		        }
  
  		}
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
				
		if (isset($this->request->get['filter_name'])) {
		    $filter_name =  $this->request->get['filter_name'];
		} else {
		    $filter_name = null;
		}
		
  	    if (isset($this->request->get['filter_cbd'])) {
		    $filter_cbd =  $this->request->get['filter_cbd'];
		} else {
		    $filter_cbd = null;
		}
		
		if (isset($this->request->get['filter_point_code_new'])) {
		    $filter_point_code_new =  $this->request->get['filter_point_code_new'];
		} else {
		    $filter_point_code_new = null;
		}
		
		if (isset($this->request->get['filter_point_code'])) {
		    $filter_point_code =  $this->request->get['filter_point_code'];
		} else {
		    $filter_point_code = null;
		}
		
		if (isset($this->request->get['filter_status'])) {
		    $filter_status =  $this->request->get['filter_status'];
		} else {
		    $filter_status = null;
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id =  $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
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
			'href'      => $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('catalog/point/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/point/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['updates'] = $this->url->link('catalog/point/updates', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['host2yun'] = $this->url->link('catalog/point/host2yun', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['yun2host'] = $this->url->link('catalog/point/yun2host', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['points'] = array();

		$data = array(
		    'filter_point_cbd_id'  => $filter_cbd,
		    'filter_name' => $filter_name,
		    'filter_point_code' => $filter_point_code,
		    'filter_point_code_new' => $filter_point_code_new,
		    'filter_status' => $filter_status,
			'filter_customer_group_id' => $filter_customer_group_id,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$point_total = $this->model_catalog_point->getTotalPoints($data);
	
		$results = $this->model_catalog_point->getPoints($data);
 
		$this->load->model('catalog/cbd');
 
		$this->load->service('baidu/point','service');
		
		$this->load->model('sale/customer_group');
		$cgroups = $this->model_sale_customer_group->getCustomerGroups();
		foreach($cgroups as $group){
			$customergroups[$group['customer_group_id']]=$group;	
		}
		$this->data['customer_groups']=$customergroups;
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/point/update', 'token=' . $this->session->data['token'] . '&point_id=' . $result['point_id'] . $url, 'SSL')
			);
						
			$rowdata = $this->model_catalog_cbd->getCbd($result['cbd_id']);
			if($rowdata){
			    $cbd = $rowdata['name'];
			}
			else{
			    $cbd = 'Unknown';
			}
			
			
			$res=$this->service_baidu_point->hlist(array('point_code'=>$result['point_code_new'],'title'=>$result['name']));
			
	
			$this->data['points'][] = array(
				'point_id' => $result['point_id'],
				'name'                 => $result['name'],
				'point_code'           => $result['point_code'],
				'point_code_new'           => $result['point_code_new'],
                'device_code'           => $result['device_code'],
			    'status'             => EnumPointStatus::getPointStatusTitle($result['status']),
				'customer_group'     =>isset($customergroups[$result['customer_group_id']])?$customergroups[$result['customer_group_id']]['name']:'全部',
				'customer_group_id'     =>$result['customer_group_id'],
			    'cbd'                => $cbd, 
				'telephone'            => $result['telephone'],
				'selected'             => isset($this->request->post['selected']) && in_array($result['point_id'], $this->request->post['selected']),
				'action'               => $action,
				'status_bd' => $res,
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

		// 参数保存
		$url = $this->getCommonUrlParameters();
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
		$pagination->url = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']           = $filter_name;
		$this->data['filter_cbd']            = $filter_cbd;
		$this->data['filter_point_code_new'] = $filter_point_code_new;
		$this->data['filter_point_code']     = $filter_point_code;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status']         = $filter_status;
		
		// 获取自提点列表
		$cbds  =  $this->model_catalog_cbd->getCbds();
		$cbd_options = array();
		
		foreach ($cbds as $cbd) {
		    $cbd_options[] = array (
		        'value' => $cbd['id'],
		        'name'  => $cbd['name']
		    );
		}
		$this->data['cbd_options']    = $cbd_options;
		
		$this->data['status_options'] = EnumPointStatus::getOptions();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/point_list.tpl';
		
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
		
		if (isset($this->error['point_code'])) {
			$this->data['error_point_code'] = $this->error['point_code'];
		} else {
			$this->data['error_point_code'] = array();
		}
		if (isset($this->error['point_code_new'])) {
			$this->data['error_point_code_new'] = $this->error['point_code_new'];
		} else {
			$this->data['error_point_code_new'] = array();
		}
		if (isset($this->error['device_code'])) {
			$this->data['error_device_code'] = $this->error['device_code'];
		} else {
			$this->data['error_device_code'] = array();
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
			'href'      => $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		
		if (!isset($this->request->get['point_id'])) {
			$this->data['action'] = $this->url->link('catalog/point/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/point/update', 'token=' . $this->session->data['token'] . '&point_id=' . $this->request->get['point_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/point', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/cbd');

		if (isset($this->request->get['point_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$point_info = $this->model_catalog_point->getPoint($this->request->get['point_id']);
		}
				
		$this->data['cbds'] = $this->model_catalog_cbd->getCbds();

		$this->load->model('sale/customer_group');
		$cgroups = $this->model_sale_customer_group->getCustomerGroups($data);
		foreach($cgroups as $group){
			$customergroups[$group['customer_group_id']]=$group;
		}
		$this->data['customer_groups']=$customergroups;
		
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($point_info)) {
			$this->data['name'] = $point_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['point_code'])) {
			$this->data['point_code'] = $this->request->post['point_code'];
		} elseif (isset($point_info)) {
			$this->data['point_code'] = $point_info['point_code'];
		} else {
			$this->data['point_code'] = '';
		}
        if (isset($this->request->post['point_code_new'])) {
            $this->data['point_code_new'] = $this->request->post['point_code_new'];
        } elseif (isset($point_info)) {
            $this->data['point_code_new'] = $point_info['point_code_new'];
        } else {
            $this->data['point_code_new'] = '';
        }

		
		$this->load->model('tool/image');

		if (isset($point_info) && $point_info['image'] && file_exists(DIR_IMAGE . $point_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($point_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['device_code'])) {
			$this->data['device_code'] = $this->request->post['device_code'];
		} elseif (isset($point_info)) {
			$this->data['device_code'] = $point_info['device_code'];
		} else {
			$this->data['device_code'] = '';
		}
		
		if (isset($this->request->post['coordinate'])) {
			$this->data['coordinate'] = $this->request->post['coordinate'];
		} elseif (isset($point_info)) {
			$this->data['coordinate'] = $point_info['coordinate'];
		} else {
			$this->data['coordinate'] = '';
		}

		if (isset($this->request->post['cbd_id'])) {
			$this->data['cbd_id'] = $this->request->post['cbd_id'];
		} elseif (isset($point_info)) {
			$this->data['cbd_id'] = $point_info['cbd_id'];
		} else {
			$this->data['cbd_id'] = '';
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($point_info)) {
			$this->data['name'] = $point_info['name'];
		} else {
			$this->data['name'] = '';
		}
		if (isset($this->request->post['address'])) {
			$this->data['address'] = $this->request->post['address'];
		} elseif (isset($point_info)) {
			$this->data['address'] = $point_info['address'];
		} else {
			$this->data['address'] = '';
		}
		
		if (isset($this->request->post['business_hour'])) {
			$this->data['business_hour'] = $this->request->post['business_hour'];
		} elseif (isset($point_info)) {
			$this->data['business_hour'] = $point_info['business_hour'];
		} else {
			$this->data['business_hour'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($point_info)) {
			$this->data['telephone'] = $point_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($point_info)) {
			$this->data['description'] = $point_info['description'];
		} else {
			$this->data['description'] = '';
		}
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (isset($point_info)) {
			$this->data['customer_group_id'] = $point_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '1';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($point_info)) {
			$this->data['status'] = $point_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($point_info)) {
			$this->data['sort_order'] = $point_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		$this->template = 'catalog/point_form.tpl';

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
		}elseif($this->model_catalog_point->existPoint(array('name'=>$this->request->post['name']),$this->request->get['point_id'])){     		
			$this->error['name'] = $this->language->get('error_exist_name');
		}
		
		if($this->request->post['point_code']&&$this->model_catalog_point->existPoint(array('point_code'=>$this->request->post['point_code']),$this->request->get['point_id']))
		{
				
			$this->error['point_code'] = $this->language->get('error_exist_point_code');
		}
		if($this->request->post['point_code_new']&&$this->model_catalog_point->existPoint(array('point_code_new'=>$this->request->post['point_code_new']),$this->request->get['point_id']))
		{
		
			$this->error['point_code_new'] = $this->language->get('error_exist_point_code_new');
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
  
  	    if (isset($this->request->get['filter_name'])) {
  	        $url .= '&filter_name=' . $this->request->get['filter_name'];
  	    }
  	
  	    if (isset($this->request->get['filter_cbd'])) {
  	        $url .= '&filter_cbd=' . $this->request->get['filter_cbd'];
  	    }
  	
  	    if (isset($this->request->get['filter_point_code'])) {
  	        $url .= '&filter_point_code=' . $this->request->get['filter_point_code'];
  	    }
  	
  	    if (isset($this->request->get['filter_point_code_new'])) {
  	        $url .= '&filter_point_code_new=' . $this->request->get['filter_point_code_new'];
  	    }
  	
  	    if (isset($this->request->get['filter_status'])) {
  	        $url .= '&filter_status=' . $this->request->get['filter_status'];
  	    }
  	
  	    return $url;
  	}
  	 
}
?>