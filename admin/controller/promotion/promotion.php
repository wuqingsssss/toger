<?php
class ControllerPromotionPromotion extends Controller {
	private $error = array();

	/**
	 * 加载必要语言，model
	 */
	protected function init(){
		$this->load_language('promotion/promotion');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('promotion/promotion');
	}

	public function index() {
		$this->init();
		
		$this->getList();
	}

	public function insert() {
		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//插入操作
			$this->model_promotion_promotion->insert($this->request->post);
			$this->redirectToList();
		}
		$this->getForm();
	}

	public function update() {
		$this->init();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//更新操作
			$this->model_promotion_promotion->update($this->request->post);
			$this->redirectToList();
		}
		$this->getForm();
	}

	public function delete() {
		$this->init();

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $pb_id) {
				//删除操作
				$this->model_promotion_promotion->delete($pb_id);
			}

			$this->redirectToList();
		}

		$this->getList();
	}


	private function getList() {

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
			
		$url=$this->getCommonUrlParameters();
			
		$this->data['insert'] = $this->url->link('promotion/promotion/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('promotion/promotion/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$limit =$this->config->get('config_admin_limit');
		$page = $this->getParamValue('page',true,'int', 1);
		$order = $this->getParamValue('order',false,'string', 'ASC');
		$sort = $this->getParamValue('sort',false,'string', 'pb.pb_id');

		$filter = array(
			'order'           => $order,
			'$sort'           => $sort,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);

		$total=$this->model_promotion_promotion->getTotalPromotions($filter);
		$results = $this->model_promotion_promotion->getPromotions($filter);

		foreach ($results as $result) {
			$action = array();

			
			if($result['pb_key'])$strparmes='&pkey='.$result['pb_key'];
			else 
				$strparmes='&pid='.$result['pb_id'];
			$preview=array(
					'text' => $this->language->get('text_preview'),
					'href' =>$result['link_url']?$result['link_url']: HTTP_CATALOG.'index.php?route=promotion/promotion'.$strparmes,
					'target'=>"_blank"
			);
			$action[] = $preview;
			$editLink = $this->url->link('promotion/promotion/update', 'token=' . $this->session->data['token'] . '&pb_id=' . $result['pb_id'].$url, 'SSL');
			$action[] = $this->createActions($this->language->get('text_edit'), $editLink);

			$this->data['promotions'][] = array(
				'pb_id' => $result['pb_id'],
				'pb_name'        => $result['pb_name'],
				'start_time'        => $result['start_time'],
				'end_time'        => $result['end_time'],
				'action'      => $action
			);
		}

		//创建分页对象
		$pagination = $this->getPageObj($total,$page,'promotion/promotion', 'token=' . $this->session->data['token'] .$url. '&page={page}');
		$this->data['pagination'] = $pagination->render();

		//初始化操作状态
		$this->initOperStatus();

		$this->template = 'promotion/promotion_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		
		$this->render();
	}

	private function getForm() {

		$this->data['token'] = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('text_home'), $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), false);
		$this->data['breadcrumbs'][] = $this->createBreadcrumbs($this->language->get('heading_title'), $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
			
		$this->initOperStatus();

		if (!isset($this->request->get['pb_id'])) {
			$this->data['action'] = $this->url->link('promotion/promotion/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('promotion/promotion/update', 'token=' . $this->session->data['token'] . '&pb_id=' . $this->request->get['pb_id'], 'SSL');
		}
		$this->data['cancel'] = $this->url->link('promotion/promotion', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->get['pb_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$promotion_info = $this->model_promotion_promotion->getPromotionInfo($this->request->get);

			$this->data['breadcrumbs'][] = $this->createBreadcrumbs($promotion_info['pb_name'], $this->url->link('promotion/promotion/update', 'token=' . $this->session->data['token'].'&pb_id='.$promotion_info['pb_id'], 'SSL'), $this->language->get('text_breadcrumb_separator'));
			
			
			if($promotion_info['share_link']){
			$this->load->service ('baidu/dwz','service');
			$dwz=$this->service_baidu_dwz->hcreate(htmlspecialchars_decode($promotion_info['share_link']));
			$promotion_info['share_short_link']=$dwz['tinyurl'];}
			
			$this->load->model('tool/image');
			
			if (isset($promotion_info) && $promotion_info['share_image'] && file_exists(DIR_IMAGE . $promotion_info['share_image'])) {
				$this->data['preview'] = $this->model_tool_image->resize($promotion_info['share_image'], 100, 100);
			} else {
				$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			
			$this->data['promotionInfo']= $promotion_info;

			
			
			
			$promotion_prs=$this->model_promotion_promotion->getPromotionPrs($this->request->get['pb_id']);
			$this->data['promotion_prs']= $promotion_prs;
			
			
		}

		$this->template = 'promotion/promotion_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}


	

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'pormotion/pormotion')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}

		if (!$this->error) {
			return TRUE;
		} else {
			$this->error['warning'] = $this->language->get('error_required_data');
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'promotion/promotion')) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'),$this->language->get('heading_title'));
		}
		return true;
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}




	private function redirectToList(){
		$this->session->data['success'] = sprintf($this->language->get('text_success'),$this->language->get('heading_title'));

		$url=$this->getUrlParameters();

		$this->redirect(HTTPS_SERVER . 'index.php?route=promotion/promotion&token=' . $this->session->data['token'] . $url);
	}

	private function getCommonUrlParameters(){
		$url = '';


		return $url;
	}

	public function getUrlParameters(){
		$url = '';

		$url=$this->getCommonUrlParameters();

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

	/**
	 * 面包屑
	 */
	private function createBreadcrumbs($text,$href,$separator)
	{
		return array(
       		'text'      =>$text,
			'href'      => $href,
      		'separator' => $separator
		);
	}

	private function createActions($text,$href)
	{
		return  array(
				'text' => $text,
				'href' => $href
		);
	}

	/**
	 *
	 * 获取get请求中的参数值，没有指定参数返回默认值
	 * @param unknown_type $param   ：   参数名
	 * @param unknown_type $isCover : 是否强制转换
	 * @param unknown_type $type    : 转换类型
	 * @param unknown_type $default : 默认值
	 */
	private function getParamValue($param,$isCover=false,$type='int',$default)
	{
		$value = null;
		if(isset($this->request->get[''.$param])){
			if($isCover)
			{
				if($type=='int')
				{
					$value=(int)$this->request->get[''.$param];
				}
				else{
					$value=$this->request->get[''.$param];
				}
			}
			else {
				$value=$this->request->get[''.$param];
			}
		}else{
			$value=$default;
		}
		return $value;
	}

	private function getPageObj($total,$page,$link,$param)
	{
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link($link, $param, 'SSL');
		return $pagination;
	}


	private function initOperStatus()
	{
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
	}
	
	

}
?>