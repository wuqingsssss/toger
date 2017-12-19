<?php

class ControllerUserUserPermission extends Controller {
	private $error = array();
 
	protected function init(){
		$this->left_menu = $this->config->get('menu');
		$this->load_language('user/user_group');
		if(!$this->user->hasPermission('modify','user/user_permission')){
		   return $this->forward('error/permission');
		}
		$this->document->setTitle($this->language->get('heading_title'));
 		$this->super = $this->user->hasPermission('','super_admin') ? 1 : 0;
		$this->load->model('user/user_group');
	}
	
	protected function redirectToList(){
		$this->session->data['success'] = $this->language->get('text_success');

		$url = $this->getCommonUrlParameters();
					
		$this->redirect($this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
	
	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->validateForm()) {
//			var_dump($this->request->post);exit;
			$this->model_user_user_group->addUserGroup($this->request->post);
			$this->redirectToList();
		}
		$this->getForm();
	}

	public function update() { 
		$this->init();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_user_user_group->editUserGroup($this->request->get['user_group_id'], $this->request->post);

			$this->redirectToList();
		}

		$this->getForm();
	}

	public function delete() { 
		$this->init();
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_group_id) {
				$this->model_user_user_group->deleteUserGroup($user_group_id);	
			}

			$this->redirectToList();
		}

		$this->getList();
	}

	private function getList() {
		$uid = $this->user->getId();
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($uid);
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
			'href'      => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
							
		$this->data['insert'] = $this->url->link('user/user_permission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('user/user_permission/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
	
		$this->data['user_groups'] = array();

		$data = array(
			'gid'	=> intval($user_info['user_group_id']),
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$user_group_total = $this->model_user_user_group->getTotalUserGroups();
		
		$results = $this->model_user_user_group->getUserGroups($data);
		foreach($results as $res){
			if($res['parent_group_id'] == 0){
				$p_groups[] = $res;
			}
		}
		foreach($p_groups as $p){
			$new_results[] = $p;
			foreach($results as $r){
				if($p['user_group_id'] == $r['parent_group_id']){
					$new_results[] = $r;
				}
			}
		}
		$results = $new_results;
		$this->data['is_admin'] = $user_info['is_admin'];
		$this->data['super'] = $this->super;
		if($user_info['is_admin'] == 0 && $this->super == 0){
			$results = '';
			$this->error['warning'] = '没有权限';
		}
		
		foreach ($results as $result) {
			$action = array();
			$show_add = 0;
			if($result['parent_group_id'] == 0){
				$show_add = 1;
			}
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('user/user_permission/update', 'token=' . $this->session->data['token'] . '&user_group_id=' . $result['user_group_id'] . $url, 'SSL')
			);		
		
			$this->data['user_groups'][] = array(
				'user_group_id' => $result['user_group_id'],
				'parent_group_id' => $result['parent_group_id'],
				'name'          => $result['name'],
				'show_add'          => $show_add,
				'selected'      => isset($this->request->post['selected']) && in_array($result['user_group_id'], $this->request->post['selected']),
				'action'        => $action
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

		$this->data['sort_name'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $user_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;

		$this->template = 'user/user_group_list.tpl';
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
			'href'      => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
			
		if (!isset($this->request->get['user_group_id'])) {
			$this->data['action'] = $this->url->link('user/user_permission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('user/user_permission/update', 'token=' . $this->session->data['token'] . '&user_group_id=' . $this->request->get['user_group_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$uid = $this->user->getId();
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($uid);
		$super = $this->super;
		$user_right=array();
		$user_group_id = $this->user->getGroupId();
		$user_group_info = $this->model_user_user_group->getUserGroup($this->user->getGroupId());
		
		if (isset($this->request->get['user_group_id'])) {
			$edit_id = intval($this->request->get['user_group_id']);
			$edit_group_info = $this->model_user_user_group->getUserGroup($edit_id);
			
		}else{
			$user_group_id = $this->user->getGroupId();
		}
		
		if($edit_id==1){
			$this->data['error_warning'] = '超级管理员组拥有至高无上的权利无需分配';	
		}elseif(!($this->user->isAdmin()||$super)){
			$this->data['error_warning'] = '您无权分配改组权限';
		}
		elseif($user_group_id==$edit_id){
			$this->data['error_warning'] = '您无权分配改组权限';
		}else{
			
        if(!$super){

			 $list = $this->model_user_user_group->get_right_list($user_group_id, $super);
	
			 $user_right =$this->user->getUserPermissions();

		}
		
		$arr = $this->get_group_right($user_right);
		
		$this->data['show_right'] = $arr;
		$this->data['user_groups'] = $this->model_user_user_group->get_fist_goup();
			
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($user_group_info)) {
			$this->data['name'] = $edit_group_info['name'];
		} else {
			$this->data['name'] = '';
		}
		$this->data['group_info'] = $user_group_info;
		$this->data['edit_info'] = $edit_group_info;
		if (isset($this->request->post['user_right'])) {
			$this->data['user_right'] = $this->request->post['user_right'];
		} elseif (isset($user_group_info)) {
			$this->data['user_right'] = $user_group_info['user_right'];
		} else {
			$this->data['user_right'] = 0;
		}
		$this->data['super'] = $this->super;
		$this->data['is_admin'] = $this->user->isAdmin() ;
			
		$this->data['pid'] = $user_group_id;
			
		$postPermission = $this->request->post['permission'];
		if (isset($postPermission)) {
			$this->data['userPerms'] = $postPermission;
		} elseif (isset($edit_group_info['permission'])) {
			$this->data['userPerms'] = $edit_group_info['permission'];
		} else {
			$this->data['userPerms'] = array();
		}
		}

		$this->template = 'user/user_group_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
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
		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('user/user');
      	
		foreach ($this->request->post['selected'] as $user_group_id) {
			$user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);

			if ($user_total) {
				$this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_group_right($user_right){
		$arr = $this->left_menu;
		$routes = array_keys($user_right);
		$super = $this->super;
		foreach ($arr as $k => $v) {
			foreach ($v as $route => $info) {

				if (!$super && !isset($user_right[$route])) {

					unset($arr[$k][$route]);
					continue;
				}
				if($this->super){
					if ($info['action'] != '') {
						$arr[$k][$route]['action'] = explode(":", $info['action']);
					}
				}else{
					//if(!isset($user_right[$route][]))
					
					$arr[$k][$route]['action']  = array_keys($user_right[$route]);
				}
			}
		}
		return $arr;
	}
}
?>