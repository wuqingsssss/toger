<?php

/* 常见问题 后台
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerCatalogQuestion extends Controller {

	private $error = array();

	public function index() {
		$this->load->model('catalog/question');
		$page = intval($this->request->get['page']) ? intval($this->request->get['page']) : 1;
		$sort = $this->request->get['sort'];

		if (!in_array($sort, array('c_id', 'q_id'))) {
			$this->data['error_warning'] = '参数错误';
		}
		$limit = intval($this->request->get['limit']) ? intval($this->request->get['limit']) : 20;
		$start = ($page - 1) * $limit;
		$count = $this->model_catalog_question->get_list_count();
		$question_list = $this->model_catalog_question->get_list($start, $limit);

		$cat_list = $this->model_catalog_question->get_cat_list('all');
		foreach ($cat_list as $cat) {
			$cat_data[$cat['qa_catagory_id']] = $cat['catagory_name'];
		}
//		var_dump($question_list);exit;
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		//分页类
		$pagination = new Pagination();
		$pagination->total = $count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/question', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		//赋值
		$this->document->setTitle('常见问题列表');
		$this->data['heading_title'] = '常见问题列表';
		$this->data['insert'] = $this->url->link('catalog/question/add_edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/question/del', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cat_data'] = $cat_data;
		$this->data['breadcrumbs'][] = array(
				'text' => '首页',
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
				'text' => '常见问题列表',
				'href' => $this->url->link('catalog/question', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		$this->data['list'] = $question_list;
		//渲染模板
		$this->id = 'content';
		$this->template = 'catalog/question.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	//编辑新增
	public function add_edit() {
		$this->load->model('catalog/question');
		//判断是更新还是新增
		$q_id = intval($this->request->get['id']);
		if ($q_id) {
			$q_info = $this->model_catalog_question->get_question_info($q_id);
		}

		//编辑或者新增动作
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$qid = intval($this->request->post['qid']);
			if (empty($qid)) {
				$data['catagory_id'] = $this->request->post['pid'];
				$data['description'] = $this->request->post['ques'];
				$data['answer'] = $this->request->post['answ'];
				$flag = $this->model_catalog_question->insert_data($data);
			} else {

				$data['catagory_id'] = $this->request->post['pid'];
				$data['description'] = $this->request->post['ques'];
				$data['answer'] = $this->request->post['answ'];
				//update
				$flag = $this->model_catalog_question->update_data($qid, $data);
			}

			if ($flag > 0) {
				$this->redirect($this->url->link('catalog/question', 'token=' . $this->session->data['token'], 'SSL'));
				//添加成功;
			} else {
				$this->error['warning'] = sprintf('操作失败');
			}
//			var_dump($this->request->post);
		}
		$this->data['error_warning'] = $this->error['warning'];
		$this->data['error_ques'] = $this->error['ques'];
		$this->data['error_cat'] = $this->error['cat'];
		//获取顶级分类列表
		$cat_list = $this->model_catalog_question->get_cat_list(1);
		$url = $this->getUrlParameters();

		$this->data['cat_list'] = $cat_list;
		$this->data['breadcrumbs'][] = array(
				'text' => '常见问题列表',
				'href' => $this->url->link('catalog/question', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->language->get('text_breadcrumb_separator')
		);
		$this->document->setTitle('问题信息');
		$this->data['heading_title'] = '问题信息';
		$this->load->model('localisation/language');
		$this->data['action'] = $this->url->link('catalog/question/add_edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['info'] = $q_info;
		//渲染模板
		$this->id = 'content';
		$this->template = 'catalog/question_add.tpl';
		$this->layout = 'layout/default';
		$this->render();
	}

	//删除
	public function del() {
		if (!$this->user->hasPermission('modify', 'catalog/note')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
		}
		$ids = $this->request->post['selected'];
		$this->load->model('catalog/question');
		$this->model_catalog_question->del_data($ids);
//		if(empty($ids)){
//			$this->error['warning'] = sprintf('至少选择一个要删除的问题记录');
//		}
		$this->redirect($this->url->link('catalog/question', 'token=' . $this->session->data['token'], 'SSL'));
	}

	//级联 接口
	public function get_cat_list() {
		$c_id = intval($this->request->get['cid']);
		if (empty($c_id)) {
			$json['code'] = 0;
			$json['msg'] = '参数错误';
			echo json_encode($json);
			exit;
		}
		$this->load->model('catalog/question');
		$cat_list = $this->model_catalog_question->get_cat_list($c_id);
		if ($cat_list) {
			$json['code'] = 1;
			$json['data'] = $cat_list;
		} else {
			$json['code'] = 0;
			$json['msg'] = '无子集分类';
		}
		echo json_encode($json);
		exit;
	}

	private function validateForm() {
		//权限认证
		if (!$this->user->hasPermission('modify', 'catalog/question')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'), '问题信息');
		}

		if ((strlen(utf8_decode($this->request->post['ques'])) < 1)) {
			$this->error['ques'] = '问题不能为空';
		}

		if (empty(intval($this->request->post['pid']))) {
			$this->error['cat'] = sprintf('请选择分类');
		}
		if (!$this->error) {
			return true;
		} else {
			$this->error['warning'] = $this->language->get('error_required_data');
			return false;
		}
	}

	private function getUrlParameters() {
		$url = '';

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		return $url;
	}

}
