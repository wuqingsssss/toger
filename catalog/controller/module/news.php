<?php
class ControllerModuleNews extends Controller {
	protected function index($setting) {
		$this->load->language('module/news');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['text_read_more'] = $this->language->get('text_read_more');
		
		$this->data['text_headlines'] = $this->language->get('text_headlines');
		
		$this->data['text_comments'] = $this->language->get('text_comments');
		
		$this->load->model('catalog/news');
		
		$this->load->model('catalog/ncomments');
		
		$this->data['newslink'] = $this->url->link('news/headlines');
		
		$this->data['news'] = array();
		
		$results = $this->model_catalog_news->getNewsTop5($setting['news_limit']);
		
		foreach ($results as $result) {
     		$this->data['news'][] = array(
			    'title' => $result['title'],
				'acom' => $result['acom'],
				'short_description' => strip_tags(substr(html_entity_decode($result['description']),0,140)),
				'short_description2' => strip_tags(substr(html_entity_decode($result['description']),0,350)),
				'total_comments' => $this->model_catalog_ncomments->getTotalNcommentsByNewsId($result['news_id']),
				'href'  => $this->url->link('news/article', 'news_id=' . $result['news_id'])
				
     		);
    	}
		
	
	$this->id = 'news';
		
		if ($setting['position'] == 'column_left') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news_side.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/news_side.tpl';
			} else {
				$this->template = 'default/template/module/news_side.tpl';
			}
		} else {
		if ($setting['position'] == 'column_right') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news_side.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/news_side.tpl';
			} else {
				$this->template = 'default/template/module/news_side.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/news.tpl';
			} else {
				$this->template = 'default/template/module/news.tpl';
			}
		}
		}
		$this->render(); 
	
	}
}
?>
