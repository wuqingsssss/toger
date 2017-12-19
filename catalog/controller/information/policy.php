<?php 
class ControllerInformationPolicy extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->load_language('information/policy');

    	$this->document->setTitle($this->language->get('heading_title'));  
	 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
			

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->link('information/contact');
		$this->data['store'] = $this->config->get('config_name');
    	$this->data['address'] = nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['fax'] = $this->config->get('config_fax');
    	
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/policy.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/policy.tpl';
		} else {
			$this->template = 'default/template/information/policy.tpl';
		}
		
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
		
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

  	public function getFilterArticles($filter){
  		$this->load->model('catalog/article');
  		
  		
  		$results=$this->model_catalog_article->getArticles($filter);
  		
  		$url='';
  		
  		if(isset($filter['article_category_id']) && $filter['article_category_id']){
  			$url.="&article_category_id=" .$filter['article_category_id'];
  		}
  		
  		$articles=array();
  		
  		foreach($results as $result){
  			$articles[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'thumb'  	 => resizeThumbImage($result['image'],80,80),
					'summary'  	 => $result['summary'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].$url)
				);
  			
  		}
  		
  		return $articles;
  	}
  	
}
?>
