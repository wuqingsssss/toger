<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerSaleShowCoupon extends Controller {
	/**
	 * 后台 检索优惠卷列表页
	 * 2个字段 模糊查询 分页
	 * 选中 批量入库
	 */
	public function index(){
		$search_str = trim(htmlspecialchars($this->request->get['search']));
		$page = $this->request->get['page'] ? intval($this->request->get['page']) : 1;
		
		$this->load->model('sale/coupon_show');
		$limit = 20;
		$start = ($page - 1) * $limit;
		
		$count = $this->model_sale_coupon_show->count_search_list($search_str);
		$list = $this->model_sale_coupon_show->search_list($search_str, $start, $limit);
		$param .= "&search={$search_str}";
		//分页
		$pagination = new Pagination();
		$pagination->total = $count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/show_coupon', 'token=' . $this->session->data['token'] . $param . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//添加入库消息
			$data = $this->format_data($this->request->post);
//			var_dump($data);exit;
			$flag = $this->model_sale_coupon_show->add_datas($data);
			if($flag > 0){
				//添加成功  跳转页面
				$this->redirect($this->url->link('sale/show_coupon/show_list'));
			}else{
				//添加失败 
				$this->data['warning'] = '添加失败,是否已经添加过?';
			}
			
		}
		//赋值
		$this->document->setTitle('可选优惠卷列表');
		$this->data['heading_title'] = '可选优惠卷列表';
		$this->data['breadcrumbs'][] = array(
				'text' => '首页',
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
				'text' => '优惠卷列表',
				'href' => $this->url->link('sale/show_coupon', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		$this->data['action'] = $this->url->link('sale/show_coupon', 'token=' . $this->session->data['token'] . $param, 'SSL');
		$this->data['list'] = $list;
		//渲染模板
		$this->id = 'content';
		$this->template = 'sale/show_coupon.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	public function show_list(){
		$page = $this->request->get['page'] ? intval($this->request->get['page']) : 1;
		$type = $this->request->get['type'] ? intval($this->request->get['type']) : 1;
		
		$this->load->model('sale/coupon_show');
		$limit = 20;
		$start = ($page - 1) * $limit;

		$count = $this->model_sale_coupon_show->count_show_list($type);
		$list = $this->model_sale_coupon_show->show_list($type, $start, $limit);
		
		//分页类
		$pagination = new Pagination();
		$pagination->total = $count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/show_coupon/show_list', 'token=' . $this->session->data['token'] . $param . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$ids = $this->request->post['selected'];
			$type = intval($this->request->get['act']);
			
			//修改显示状态
			$flag = $this->model_sale_coupon_show->audit_show($ids, $type);
			if($flag > 0){
				//添加成功  跳转页面
				$this->redirect($this->url->link('sale/show_coupon/show_list'));
			}else{
				//添加失败 
				$this->data['warning'] = '操作失败,请确认选择至少一条';
			}
			
		}
		//赋值
		$this->document->setTitle('优惠卷领取页列表');
		$this->data['heading_title'] = '优惠卷领取页列表';
		$this->data['breadcrumbs'][] = array(
				'text' => '首页',
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
				'text' => '优惠卷列表',
				'href' => $this->url->link('sale/show_coupon/show_list', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		$this->data['action'] = $this->url->link('sale/show_coupon/show_list', 'token=' . $this->session->data['token'] . $param, 'SSL');
		$this->data['list'] = $list;
		//渲染模板
		$this->id = 'content';
		$this->template = 'sale/show_list.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}
	/**
	 * 筛选出 选中的记录 
	 * @param type $data
	 * @return type
	 */
	public function format_data($data){
		foreach($data['selected'] as $k => $v){
			$return[$k]['coupon_id'] = $k;
			$return[$k]['code'] = $data['code'][$k];
			$return[$k]['start_time'] = $data['start_time'][$k];
			$return[$k]['end_time'] = $data['end_time'][$k];
		}
		return $return;
	}
}