<?php
class ControllerExtensionThirdplatform extends Controller {
	protected function init(){
		$this->load_language('extension/thirdplatform');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/partnercode');

	}
	public function index(){
		$this->init();
		$this->data['breadcrumbs'][] = array(
						'text' => $this->language->get('text_home'),
						'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
						'separator' => false
		);
	
		$this->data['breadcrumbs'][] = array(
						'text' => $this->language->get('heading_title'),
						'href' => $this->url->link('extension/thirdplatform', 'token=' . $this->session->data['token'], 'SSL'),
						'separator' => $this->language->get('text_breadcrumb_separator')
		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		
		
		//获取第三方平台信息列表
	
		$list = $this->model_catalog_partnercode->get_platform_list();

		
		$this->data['default'] =$this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		//新增平台动作URL
		
		$url=$this->getUrlParameters();
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=extension/thirdplatform/add_edit&token=' . $this->session->data['token'] . $url;
		
		//修改动作URL
		foreach($list as &$li){
			$li['action'][] = array(
						'text' => '修改',
						'href' => $this->url->link('extension/thirdplatform/add_edit', 'token=' . $this->session->data['token'].'&partner_id='.$li['id'], 'SSL')
					);
		}
	
		$this->data['platform_list'] = $list;
		$this->template = 'extension/platform.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
	public function add_edit(){
		$this->init();
		//编辑页面
		$id = intval($this->request->get['partner_id']);
		if($id > 0){
			$info = $this->model_catalog_partnercode->get_platform_info(intval($id));
			$param = '&partner_id='.$id;
		}
		
		if (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		// 提交表单操作
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$plat_id = intval($this->request->post['id']);
			$name = $this->request->post['name'];
			$code = $this->request->post['code'];
			$is_action = true;
			if(empty($name)){
				$this->data['error_name'] = '平台名称不能为空';
				$is_action = false;
			}
			if(empty($plat_id) && empty($code)){
				$this->data['error_code'] = '平台代号不能为空';
				$is_action = false;
			}
			if($is_action){
				if($plat_id > 0){
					$flag = $this->model_catalog_partnercode->update_info($plat_id, $this->request->post);
				}else{
					$flag = $this->model_catalog_partnercode->insert_info($this->request->post);	
				}
			}
			$info = $this->request->post;
			//添加成功页面跳转
			if($flag > 0 ){
				$this->session->data['success'] = $this->language->get('text_success');
				return $this->redirect(HTTPS_SERVER . 'index.php?route=extension/thirdplatform&token=' . $this->session->data['token'].$param);
			}else{
				$this->session->data['error_warning'] = $this->language->get('error_warning');
			}
		}
		
		//编辑页面回显
		$this->data['info'] = $info;
		$this->data['action'] = $this->url->link('extension/thirdplatform/add_edit'.$param, 'token=' . $this->session->data['token'], 'SSL');
		//渲染模版
		$this->template = 'extension/platform_form.tpl';
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
}