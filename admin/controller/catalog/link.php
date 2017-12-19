<?php  
class ControllerCatalogLink extends Controller {  
	private $error = array();
	
	protected function init(){
		$this->load_language('catalog/link');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('catalog/link');
	}
   
  	public function index() {
		$this->init();
		
    	$this->getList();
  	}
  	        
  	public function insert() {
		$this->init();
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_catalog_link->addLink($this->request->post);
			
			$this->redirectToList();
		}
	
    	$this->getForm();
  	}
  	
  	private function redirectToList(){
  		$this->session->data['success'] = $this->language->get('text_success');
			
  		$url=$this->url->link('catalog/link', 'token=' . $this->session->data['token']. $this->getUrlParameters() , 'SSL');
  		
  		$this->redirect($url);
  	}

	public function update() {
		$this->init();
			
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_catalog_link->editLink($this->request->get['link_id'], $this->request->post);
	  		
			$this->redirectToList();
		}
		
    	$this->getForm();
  	}

  	public function delete() {
		$this->init();
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {	  
    		foreach ($this->request->post['selected'] as $link_id) {
				$this->model_catalog_link->deleteLink($link_id);
			}
			
			$this->redirectToList();
    	}
    	
    	if(isset($this->request->get['link_id'])&& $this->validateDelete())
    	{
    		$this->model_catalog_link->deleteLink($this->request->get['link_id']);
    		
    		$this->redirectToList();
    	}

    	$this->getList();
  	}
  	
  	private function getList() {
  		
  		if(isset($this->request->get['page'])){
  			$page=$this->request->get['page'];
  		}else{
  			$page=1;
  		}
  		
  		if(isset($this->request->get['sort'])){
  			$sort=$this->request->get['sort'];
  		}else{
  			$sort='ld.name';
  		}
  		
  		if(isset($this->request->get['order'])){
  			$order=$this->request->get['order'];
  		}else{
  			$order='ASC';
  		}
  		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/link', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);
		
					
		$this->data['insert'] =$this->url->link('catalog/link/insert', 'token=' . $this->session->data['token'].$this->getUrlParameters() , 'SSL');
		$this->data['delete'] =$this->url->link('catalog/link/delete', 'token=' . $this->session->data['token'].$this->getUrlParameters() , 'SSL');

		$this->data['downloads'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$download_total = $this->model_catalog_link->getTotalLinks();
	
		$results = $this->model_catalog_link->getLinks($data);
 
    	foreach ($results as $result) {
			$action = array();
			$delete=array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' =>$this->url->link('catalog/link/update','token=' . $this->session->data['token']. '&link_id=' . $result['link_id'] . $this->getUrlParameters() , 'SSL')
			);
			
			$delete[] = array(
				'text' => $this->language->get('text_delete'),
				'href' =>$this->url->link('catalog/link/delete','token=' . $this->session->data['token']. '&link_id=' . $result['link_id'] . $this->getUrlParameters() , 'SSL')
			);
						
			$this->data['links'][] = array(
				'link_id' => $result['link_id'],
				'name'        => $result['name'],
				'uri'   => $result['uri'],
				'sort_order'   => $result['sort_order'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    => isset($this->request->post['selected']) && in_array($result['link_id'], $this->request->post['selected']),
				'action'      => $action,
				'delete'      => $delete
			
			);
		}	
	
  		if (isset($this->error['warning'])) {
			$this->data['error_warning']=$this->error['warning'];
		} else {
			$this->data['error_warning']='';
		}
			
 		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		$this->data['sort_name'] =$this->url->link('catalog/link', 'token=' . $this->session->data['token']. '&sort=ld.name' . $this->getSortUrlParameters($order) , 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/link', 'token=' . $this->session->data['token']. '&sort=l.sort_order' . $this->getSortUrlParameters($order) , 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/link', 'token=' . $this->session->data['token']. '&sort=l.status' . $this->getSortUrlParameters($order) , 'SSL');
		
		$pagination = new Pagination();
		$pagination->total = $download_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url =$this->url->link('catalog/link', 'token=' . $this->session->data['token'] . $this->getPageUrlParameters() . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		

		$this->template = 'catalog/link_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
  	}
  	
  	private function addBreadcrumbs(){
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/link', 'token=' . $this->session->data['token'] , 'SSL'),
      		'separator' => ' :: '
   		);
  	}

  	private function getForm() {
 		$arr_error=array('warning','name','url');
 		
 		foreach($arr_error as $key){
	 		if (isset($this->error[$key])) {
				$this->data['error_'.$key]=$this->error[$key];
			} else {
				$this->data['error_'.$key]='';
			}
 		}
 		
 		$this->addBreadcrumbs();
		
   		$this->data['token'] = $this->session->data['token'];
   		
		if (!isset($this->request->get['link_id'])) {
			$this->data['action'] =$this->url->link('catalog/link/insert', 'token=' . $this->session->data['token']. $this->getUrlParameters(), 'SSL');
		} else {
			$this->data['action'] =$this->url->link('catalog/link/update', 'token=' . $this->session->data['token']. '&link_id=' . $this->request->get['link_id'] . $this->getUrlParameters(), 'SSL');
		}
		
		$this->data['cancel'] =$this->url->link('catalog/link', 'token=' . $this->session->data['token']. $this->getUrlParameters(), 'SSL');
 		

    	if (isset($this->request->get['link_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$link_info = $this->model_catalog_link->getLink($this->request->get['link_id']);
    	}
    	
  		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['link_description'])) {
			$this->data['link_description'] = $this->request->post['link_description'];
		} elseif (isset($link_info)) {
			$this->data['link_description'] = $this->model_catalog_link->getLinkDescriptions($this->request->get['link_id']);
		} else {
			$this->data['link_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($link_info)) {
			$this->data['status'] = $link_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
  		if (isset($this->request->post['friend'])) {
			$this->data['friend'] = $this->request->post['friend'];
		} elseif (isset($link_info)) {
			$this->data['friend'] = $link_info['friend'];
		} else {
			$this->data['friend'] = 1;
		}
		
  		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($link_info)) {
			$this->data['image'] = $link_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($link_info) && $link_info['image'] && file_exists(DIR_IMAGE . $link_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($link_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($link_info)) {
			$this->data['sort_order'] = $link_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
  		if (isset($this->request->post['uri'])) {
			$this->data['uri'] = $this->request->post['uri'];
		} elseif (isset($link_info)) {
			$this->data['uri'] = $link_info['uri'];
		} else {
			$this->data['uri'] = '';
		}
		
		$this->load->model('catalog/link_group');
		$this->data['link_options']=$this->model_catalog_link_group->getLinkGroupOptions();
		
		if (isset($link_info)) {
			$this->data['link_group']=$this->model_catalog_link->getLinkGroups($link_info['link_id']);
		}else{
			$this->data['link_group']=array();
		}
		
		$this->template = 'catalog/link_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
  	}
  	
	private function getUrlParameters()
	{
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
		
		return $url;
	}
	
	private function getSortUrlParameters($order)
	{
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		return $url;
	}
	
	private function getPageUrlParameters()
	{
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		return $url;
	}

  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/link')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['link_description'] as $language_id => $value) {
      		if ((strlen(utf8_decode($value['name'])) < 1) || (strlen(utf8_decode($value['name'])) > 100)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}	
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/link')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
			  	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		} 
  	}
}
?>