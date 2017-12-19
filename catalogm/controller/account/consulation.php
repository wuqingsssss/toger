<?php
class ControllerAccountConsulation extends Controller { 
	public function index() {
		$this->load_language('account/consulation');
		
		$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/consulation', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
		$this->load->model('catalog/consulation');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
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
		
		$limit=$this->config->get('config_catalog_limit');
		
		$filter = array(
			'filter_customer_id'  => $this->customer->getId(),
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		
		$total=$this->model_catalog_consulation->getTotalConsulations($filter);
		
		$results=$this->model_catalog_consulation->getConsulations($filter);
	
		$this->data['consulations']=array();
		
		foreach($results as $result){
			$this->data['consulations'][]=array(
				'content' => $result['content'],
				'reply' => $result['reply'],
				'type' => EnumConsulationTypes::getConsulationType($result['type']) ,
				'date_added' => $result['date_added'],
				'date_modified' => $result['date_modified'],
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/consulation', 'page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/consulation.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/consulation.tpl';
		} else {
			$this->template = 'default/template/account/consulation.tpl';
		}
		
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
}