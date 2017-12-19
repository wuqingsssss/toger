<?php  
class ControllerProductConsulation extends Controller {
	private $error = array();
	
	private function validateForm(){
		if (! isset($this->request->post['type'])) {
			$this->error['type'] = $this->language->get('error_type');
		}
		
		if (strlen(utf8_decode($this->request->post['content'])) < 1) {
			$this->error['content'] = $this->language->get('error_content');
		}
				
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function submit(){
		$json = array();

		$this->load_language('product/consulation');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (! isset($this->request->post['type'])) {
				$json['error']['type'] = $this->language->get('error_type');
			}
			
			if (strlen(utf8_decode($this->request->post['content'])) < 1) {
				$json['error']['content'] = $this->language->get('error_content');
			}
			
			if (!$json) {
				if(isset($this->request->get['product_id'])){
					$this->request->post['product_id']=$this->request->get['product_id'];
				}else{
					$this->request->post['product_id']=0;
				}
				
				$this->request->post['customer_id']=$this->customer->getId();
				$this->request->post['customer_name']=$this->customer->getDisplayName();
				$this->request->post['status']=0;
				$this->request->post['email_alert']=isset($this->request->post['isemail']) ? 1 : 0;
				
				
				$this->load->model('catalog/consulation');
				
				$this->model_catalog_consulation->addConsulation($this->request->post);
				
				$json['success']=$this->language->get('text_success');
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	
	public function insert(){
		$this->load_language('product/consulation');
		

		$this->data['types']=EnumConsulationTypes::getConsulationTypes();
		
		$url='';
		
		if(isset($this->request->get['product_id'])){
			$url.='&product_id='.$this->request->get['product_id'];
		}
		
		$this->data['action']=$this->url->link('product/consulation/submit'.$url);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/consulation_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/consulation_form.tpl';
		} else {
			$this->template = 'default/template/product/consulation_form.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function index(){
		$this->load_language('product/consulation');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/consulation.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/consulation.tpl';
		} else {
			$this->template = 'default/template/product/consulation.tpl';
		}
		
//		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
					
		$this->response->setOutput($this->render());
	}
	
	
	
	

	public function filter(){
		$url='';
		
		if(isset($this->request->get['product_id'])){
			$filter_product_id=$this->request->get['product_id'];
		}else{
			$filter_product_id=NULL;
		}
		
		if(isset($this->request->get['type'])){
			$filter_type=$this->request->get['type'];
		}else{
			$filter_type=NULL;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'consulation_id'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		$limit=$this->config->get('config_catalog_limit');
			
		$filter_data = array(
			'page'                     => $page,
			'sort'                     => $sort,
			'order'                    => $order,
			'filter_status'                    => TRUE,
			'filter_type'                    => $filter_type,
			'filter_product_id'                    => $filter_product_id,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
		
		
		$this->data['lists'] = array();
		
		$this->load->model('catalog/consulation');
		
		$total=$this->model_catalog_consulation->getTotalConsulations($filter_data);
		$results=$this->model_catalog_consulation->getConsulations($filter_data);
		
		foreach($results as $result){
			$this->data['lists'][]=array(
				'consulation_id'  => $result['consulation_id'],
				'name'       => $result['name'],
				'content'       => $result['content'], 
				'reply'       => $result['reply'], 
				'customer_name'     => $result['customer_name'],
				'customer_group'     => '',
				'type'     => EnumConsulationTypes::getConsulationType($result['type']),
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		
		$url='';

		if(isset($this->request->get['product_id'])){
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		
		if(isset($this->request->get['type'])){
			$url .= '&type=' . $this->request->get['type'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=product/consulation/filter'. $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/consulation_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/consulation_list.tpl';
		} else {
			$this->template = 'default/template/product/consulation_list.tpl';
		}
		
    	$this->response->setOutput($this->render());
	}
		
	public function lists(){
		//常规选项 商品评论
		if(!$this->config->get('config_consulation_status')){
			return;
		}
		$this->data['types']=EnumConsulationTypes::getConsulationTypes();
		
		$this->data['product_id']=$this->getProductId();
		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product_consulation_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/product_consulation_list.tpl';
		} else {
			$this->template = 'default/template/product/product_consulation_list.tpl';
		}
				
		$this->response->setOutput($this->render());
  	}
  	
	private function getProductId(){
  		if(isset($this->request->get['product_id'])){
  			return $this->request->get['product_id'];
  		}else{
  			return 0;
  		}
  		
  	}
}
?>