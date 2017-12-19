<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerSaleGroup extends Controller {

	private $error = array();
	private $status_arr = array(
			1 => '进行中',
			2 => '已成团',
			-1 => '已取消',
			-2 => '未付款'
	);
	//列表页
	public function index() {
		$page = $this->request->get['page'] ? intval($this->request->get['page']) : 1;

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$ids = $this->request->post['selected'];

			if (!empty($ids)) {
				$this->load->model('sale/group_buy');
				$flag = $this->model_sale_group_buy->del_group($ids);
				if ($flag > 0) {
					$this->data['$success'] = '操作成功';
				} else {
					$this->data['warning'] = '操作失败';
				}
			}
		}
		$this->load->model('sale/group_buy');
		$limit = 20;
		$start = ($page - 1) * $limit;

		$count = $this->model_sale_group_buy->count_list();
		$list = $this->model_sale_group_buy->group_list($start, $limit);
		
		//图片URL
        $this->load->model('tool/image');
		foreach ($list as &$info) {
			if (isset($info) && $info['image'] && file_exists(DIR_IMAGE . $info['image'])) {
				$info['preview'] = $this->model_tool_image->resize($info['image'], 100, 100);
			} else {
				$info['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
		}
		//分页
		$pagination = new Pagination();
		$pagination->total = $count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/group', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();

		
		//赋值
		$this->document->setTitle('团购信息列表');
		$this->data['heading_title'] = '团购信息列表';
		$this->data['breadcrumbs'][] = array(
				'text' => '首页',
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
				'text' => '团购信息列表',
				'href' => $this->url->link('sale/group', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);

		$this->data['url'] = $this->url->link('sale/group/add_edit', 'token=' . $this->session->data['token'], 'SSL');
//		$this->data['action'] = $this->url->link('sale/group/del', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['list'] = $list;
		//渲染模板
		$this->id = 'content';
		$this->template = 'sale/group_list.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	
	//添加编辑团购信息页面
	public function add_edit() {
		$this->load->model('sale/group_buy');

		if($this->request->get['g_id'])
		{
		    $g_id = $this->request->get['g_id'];
		    $info = $this->model_sale_group_buy->get_group_info($g_id);
		}
		
		//编辑或者新增动作
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    
		    if($this->validateForm()){
		    
    			$g_id = $this->request->post['g_id'];
    			$data = $this->request->post;
    			
    			if (empty($g_id)) {//新增
    				$flag = $this->model_sale_group_buy->insert_data($data);
    			} else {//更新
    				$flag = $this->model_sale_group_buy->update_data($g_id, $data);
    			}
    
    			if ($flag > 0) {
    				$this->redirect($this->url->link('sale/group', 'token=' . $this->session->data['token'], 'SSL'));
    				//添加成功;
    			} else {
    				$this->error['error_warning'] = sprintf('操作失败');
    			}
		    }
		    else{
		        $info = $this->request->post;
		    }
		}
	

		//上传图片信息
		$this->load->model('tool/image');
		if (isset($info) && $info['image'] && file_exists(DIR_IMAGE . $info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($info) && $info['share_image'] && file_exists(DIR_IMAGE . $info['share_image'])) {
		    $this->data['preview_share'] = $this->model_tool_image->resize($info['image'], 100, 100);
		} else {
		    $this->data['preview_share'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (trim($this->request->post['filter_name'] == '')) {
		    $this->data['filter_name'] = '';
		}else{
		    $this->data['filter_name'] = $this->request->post['filter_name'];
		}
		
		if (trim($this->request->post['product_id'] == '')) {
		    $this->data['product_id'] = '';
		}else{
		    $this->data['product_id'] = $this->request->post['product_id'];
		}
		
		$this->data['cancel'] = $this->url->link('sale/group', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['pre_url'] = HTTPS_IMAGE;
		$this->data['action'] = $this->url->link('sale/group/add_edit', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['info'] = $info;
		$this->data['error'] = $this->error;
		$this->id = 'content';
		$this->template = 'sale/group_item.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	//停用团购
	public function del() {
		$ids = $this->request->post['selected'];

		if (!empty($ids)) {
			$this->load->model('sale/group_buy');
			$flag = $this->model_sale_group_buy->del_group($ids);
			if ($flag) {
				$this->data['$success'] = '操作成功';
			} else {
				$this->data['warning'] = '操作失败';
			}
		}
	}
	
	//菜品团购信息
	public function group_info_list(){
		$g_id = intval($this->request->get['gid']);
		$c_id = intval($this->request->get['cid']);
//		$order_id = $this->request->get['oid'];
		$page = intval($this->request->get['page']) ? intval($this->request->get['page']) : 1;
		$limit = 10;
		$start = ($page -1) * $limit;
		
//		if (isset($this->request->get['sort'])) {
//			$url .= '&sort=' . $this->request->get['sort'];
//		}
//												
//		if (isset($this->request->get['order'])) {
//			$url .= '&order=' . $this->request->get['order'];
//		}
		if(isset($this->request->get['create_time'])){
			$filter['create_time'] = $this->request->get['create_time'];
		}
		if(isset($this->request->get['end_time'])){
			$filter['end_time'] = $this->request->get['end_time'];
		}
		if(isset($this->request->get['finish_time'])){
			$filter['date(finish_time)'] = $this->request->get['finish_time'];
		}
		if(isset($this->request->get['status']) && !empty($this->request->get['status'])){
			$filter['status'] =$this->request->get['status'];
		}
//		$sort = $this->request->get['sort'];
//		$order = $this->request->get['order'];
//		if(in_array($sort, array('create_time','finish_time','status')) && in_array($order, array('asc','desc'))){
//			$sort_arr['key'] = $sort;
//			$sort_arr['order'] = $order;
//		}
//		if($order == '' || $order == 'desc'){
//			$this->data['order'] = 'asc';
//		}else{
//			$this->data['order'] = 'desc';
//		}
		$this->load->model('sale/group_buy');
		$this->load->model('sale/group_create');
		$this->load->model('sale/group_member');
		
		if($c_id > 0){//单条信息
//			$mem_info = $this->model_sale_group_member->get_by_order_id($order_id);
//			$c_id = $mem_info['c_id'];
			$c_info = $this->model_sale_group_create->get_create_info($c_id);
			$g_id = $c_info['g_id'];
			$c_list[] = $c_info;
		}else{//多条
			$c_list = $this->model_sale_group_create->get_create_list($g_id, $start, $limit, $filter);
			$count = $this->model_sale_group_create->get_create_count($g_id, $filter);
			$url = '&gid='.$g_id;
			$this->data['url'] = "index.php?route=sale/group/group_info_list&token=".$this->session->data['token'].$url;
			$pagination = new Pagination();
			$pagination->total = $count;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('sale/group/group_info_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

			$this->data['pagination'] = $pagination->render();
		}
		$cids = array_column($c_list, 'c_id');
		$members = $this->model_sale_group_member->get_member_by_cids($cids);
		$g_info = $this->model_sale_group_buy->get_group_info($g_id);
		
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => '团购产品列表',
			'href'      => $this->url->link('sale/group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		$this->data['status_arr'] = $this->status_arr;
		$this->data['heading_title'] = '建团列表';
		$this->data['g_info'] = $g_info;
		$this->data['c_list'] = $c_list;
		$this->data['members'] = $members;
		$this->id = 'content';
		$this->template = 'sale/group_info_list.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	/**
	 * 验证表单信息
	 * @return boolean
	 */
	private function validateForm() {
		//权限认证
		if (!$this->user->hasPermission('modify', 'sale/group')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'), '拼团信息');
		}
		
		if (trim($this->request->post['p_name'] == '')) {
			$this->error['p_name'] = '关联菜品不能为空';
		}
		
		if (trim($this->request->post['product_id'] == '')) {
			$this->error['product_id'] = '关联菜品ID不能为空';
		}
		
		if (trim($this->request->post['name'] == '')) {
			$this->error['name'] = '标题不能为空';
		}
        /*
		if (trim($this->request->post['desc'] == '')) {
			$this->error['desc'] = '描述不能为空';
		}*/
	    if (trim($this->request->post['sell_price'] == '')) {
			$this->error['sell_price'] = '价格不能为空';
		}
		if (trim($this->request->post['quantity'] <1)) {
		    $this->error['quantity'] = '数量不能小于1';
		}
		if (intval(trim($this->request->post['member_num'] <2))) {
			$this->error['member_num'] = '人数不能小于2';
		}
		elseif(intval(trim($this->request->post['member_num'] >10 ))){
		    $this->error['member_num'] = '人数不能大于10';
		}
		
		if (intval(trim($this->request->post['duration'] < 1))) {
			$this->error['duration'] = '有效天数不能小于1';
		}
		if (intval(trim($this->request->post['group_num'] == 0))) {
			$this->error['group_num'] = '请限制团购数量';
		}
		if (trim($this->request->post['start_time'] == '') || trim($this->request->post['end_time'] == '')) {
			$this->error['time'] = '时间不能为空';
		}
/*		if (trim($this->request->post['send_time'] == '')) {
			$this->error['send_time'] = '发货时间不能为空';
		}
*/
		if (!$this->error) {
			return true;
		} else {
			$this->error['error_warning'] = '操作失败';
			return false;
		}
	}

}
