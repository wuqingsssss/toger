<?php
class ControllerModuleNewsList extends Controller {
	protected function index($setting) {
		$this->load->language('module/news');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['text_read_more'] = $this->language->get('text_read_more');
		
		$this->data['text_headlines'] = $this->language->get('text_headlines');
		
		$this->data['text_comments'] = $this->language->get('text_comments');
		
		$this->load->model('catalog/news');
		
		$this->load->model('catalog/ncomments');
		
		$ncategory_id=$setting['ncategory_id'];
		
		$limit=$setting['news_list_limit'];
		
		$this->load->model('catalog/news');
		
		$this->data['articles']=array();
		
		$data = array(
		    'filter_ncategory_id' => $ncategory_id,
			'start'           => 0,
			'limit'           => $limit 
		);
			
		
		$results = $this->model_catalog_news->getNewsLimited($data);
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $bwidth, $bheight);
			} else {
				$image = false;
			}
			

			if (isset($setting['width']) && $setting['width']) {
           	 	$bwidth = $setting['width'];
			} else {
				$bwidth = 80;
			}
			
			if (isset($setting['height']) && $setting['height']) {
            	$bheight = $setting['height'];
			} else {
				$bheight = 80;
			}
			
			$this->data['articles'][] = array(
				'article_id'  => $result['news_id'],
				'name'        => $result['title'],
				'acom'        => $result['acom'],
				'thumb'       => $image,
				'description' => mb_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 500) . '..',
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'        => $this->url->link('news/article', 'ncat=' . $ncategory_id . '&news_id=' . $result['news_id'])
			);
		}
	
		$this->id = 'news_list';
		
		/*if ($setting['position'] == 'column_left') {
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
		}*/
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news_list_tab.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/news_list_tab.tpl';
		} else {
			$this->template = 'default/template/module/news_list_tab.tpl';
		}
				
		
		$this->render(); 
	
	}
}
?>
