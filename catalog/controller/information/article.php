<?php
class ControllerInformationArticle extends Controller {
	public function index() {
		$this->load_language('information/article');
		$this->load->model('catalog/article');
		$this->load->model('catalog/articlecate');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
		);

		if (isset($this->request->get['article_category_id'])) {
			$article_category_id = $this->request->get['article_category_id'];
		} else {
			$article_category_id = 0;
		}

		if (isset($this->request->get['article_id'])) {
			$article_id = $this->request->get['article_id'];
		} else {
			$article_id = 0;
		}
		$category= $this->model_catalog_article->getArticleCategory($article_id);
		
		if($category)
			$category_info = $this->model_catalog_articlecate->getCategoryDescriptions($category['article_category_id']);
			
		$result = $this->model_catalog_article->getOneArticle($article_id);
		
		if(!$result){
			$this->redirect($this->url->link('error/not_found'));
		}
		
		
			$url='';
			
			if(isset($this->request->get['menu_id'])){
				$url='&menu_id='.$this->request->get['menu_id'];
			}
			
			$this->data['title'] = $result['name'];
			$this->data['date_added'] = date('j-M-Y', strtotime($result['date_added']));
			$this->data['content'] = html_entity_decode($result['description']);
			$this->data['image'] = resizeThumbImage($result['image'],0,0,false);


			if(isset($category_info)){
				$this->data['heading_title'] = $category_info['name'];
				$this->document->setTitle($result['name'] . '-' . $category_info['name']  );
				
				$this->document->setKeywords($result['meta_keyword']);
	  			$this->document->setDescription($result['meta_description']);

				$this->data['breadcrumbs'][] = array(
	        		'text'      => $category_info['name'],
					'href'      => $this->url->link('information/article/category', 'article_category_id=' . $category_info['article_category_id']).$url,
	        		'separator' => $this->language->get('text_separator')
				);

				$this->data['breadcrumbs'][] = array(
	        		'text'      => $result['name'],
					'href'      => $this->url->link('information/article', '&article_id='.$result['article_id']).$url,
	        		'separator' => $this->language->get('text_separator')
				);
			}else{
				$this->data['heading_title'] = $result['title'];
				$this->document->setTitle($result['title']);
				$this->data['breadcrumbs'][] = array(
	        		'text'      => $result['title'],
					'href'      => $this->url->link('information/article', '&article_id='.$result['article_id']).$url,
	        		'separator' => $this->language->get('text_separator')
				);
			}


			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['continue'] = $this->url->link('common/home', '');

			$relate_results = $this->model_catalog_article->getArticleRelated($article_id);
			$this->data['text_relate_article'] = $this->language->get('text_relate_article');
			foreach ($relate_results as $relate_result) {
				$action = array();
				$article = $this->model_catalog_article->getArticle($relate_result['related_id']);
				if ($article) {
					$this->data['article'][] = array(
                        'article_id' => $article['article_id'],
                        'title' => $article['name'],
                        'date_added' => date('j-M-Y', strtotime($article['date_added'])),
                        'href' => $this->url->link('information/article', 'article_id=' . $article['article_id'])
					);
				}
			}
			
			
			$download_results = $this->model_catalog_article->getArticleDownloads($article_id);
			
			$this->data['downloads'] = array();
			
			foreach ($download_results as $download_result) {
				$result = $this->model_catalog_article->getArticleDownload($download_result['download_id']);

				if($result){
					if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
						$size = filesize(DIR_DOWNLOAD . $result['filename']);
	
						$i = 0;
	
						$suffix = array(
							'B',
							'KB',
							'MB',
							'GB',
							'TB',
							'PB',
							'EB',
							'ZB',
							'YB'
						);
	
						while (($size / 1024) > 1) {
							$size = $size / 1024;
							$i++;
						}
	
						$this->data['downloads'][] = array(
							'download_id'   => $result['download_id'],
							'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
							'name'       => $result['name'],
							'remaining'  => $result['remaining'],
							'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
							'href'       => $this->url->link('information/article/download', 'download_id=' . $result['download_id'], 'SSL')
						);
					}
				}
			}
			
			
			if($article_id){
				$this->model_catalog_article->updateViewed($article_id);
			}
			
			
			$this->document->setBreadcrumbs($this->data['breadcrumbs']);
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/article.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/article.tpl';
			} else {
				$this->template = 'default/template/information/article.tpl';
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

	public function category() {
		$this->load_language('information/article');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
		);

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['article_category_id'])) {
			$article_category_id = $this->request->get['article_category_id'];
		} else {
			$article_category_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limited = $this->config->get('config_admin_limit');
		
		$this->load->model('catalog/articlecate');
		$this->load->model('catalog/article');
		
		$data = array(
            'article_category_id' => $article_category_id,
            'start' => ($page - 1) * $limited,
            'limit' => $limited
		);
		

		$category_info = $this->model_catalog_articlecate->getCategoryDescriptions($article_category_id);
		
		if(!$category_info){
			$this->redirect($this->url->link('error/not_found'));
		}

		$this->data['category'] = $category_info['name'];
		$this->data['heading_title'] = $category_info['name'];
		$this->document->setTitle($category_info['name']);
		$this->data['breadcrumbs'][] = array(
        		'text'      => $category_info['name'],
				'href'      => $this->url->link('information/article/category', '&article_category_id=' . $this->request->get['article_category_id']),
        		'separator' => $this->language->get('text_separator')
		);
		$this->document->setTitle($category_info['name']);
		$this->document->setDescription($category_info['meta_description']);
		$this->document->setKeywords($category_info['meta_keyword']);
		
		
		$total = $this->model_catalog_article->getTotalArticleByCategoryId($article_category_id);
		
		$results = $this->model_catalog_article->getArticles($data);
		
		$this->load->model('tool/image');
		
		$this->data['articles']=array();
		
		foreach ($results as $result) {
			$action = array();
			
			$href=$this->url->link('information/article', 'article_id=' . $result['article_id']);
			
			if(isset($this->request->get['menu_id'])){
				$href.='&menu_id='.$this->request->get['menu_id'];
			}
			
			
			$image=resizeThumbImage($result['image'],0,0 ,TRUE);
			
			$this->data['articles'][] = array(
                    'article_id' => $result['article_id'],
                    'title' => $result['name'],
                    'summary' => html_entity_decode($result['summary']),
                    'date_added' => date('Y-m-d', strtotime($result['date_added'])),
                    'image' => $image,
                    'href' => $href
			);
			
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limited;
		
		$pagination->url =  $this->url->link('information/article/category', 'article_category_id=' . $this->request->get['article_category_id'].'&page={page}');
			
		$this->data['pagination'] = $pagination->render();


		$this->data['continue'] =  $this->url->link('common/home', '');
		
		$category=$this->model_catalog_articlecate->getCategory($article_category_id);
		
		$this->data['template_id']=$category['template_id'];
		
		if($category['template_id']==0){
			$template='information/article_list.tpl';
		}else{
			switch($category['template_id']){
				case 1:$template='information/article_list_summary.tpl';break;
				case 2:$template='information/article_list_image.tpl';break;
				case 3:$template='information/article_list_image_summary.tpl';break;
			}
		}
		
		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$template)) {
			$this->template = $this->config->get('config_template') . '/template/'.$template;
		} else {
			$this->template = 'default/template/'.$template;
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

	public function all() {
		$this->language->load('information/article');
		$this->document->setTitle($this->language->get('heading_title')); 
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
		);

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['article_category_id'])) {
			$article_category_id = $this->request->get['article_category_id'];
		} else {
			$article_category_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limited = $this->config->get('article_page');
		$this->load->model('catalog/articlecate');
		$this->load->model('catalog/article');

		$data = array(
            'start' => ($page - 1) * $limited,
            'limit' => $limited
		);

		$this->data['text_no_record'] = $this->language->get('text_no_record');
		 
		$results = $this->model_catalog_article->getArticle($data);
		$total = $this->model_catalog_article->getTotalArticleByCategoryId($article_category_id);

		foreach ($results as $result) {
			$link= $this->url->link('information/article', 'article_id=' .$result['article_id']); 
			if($result['download_only']==0){
				$this->data['article'][] = array(
	                    'article_id' => $result['article_id'],
						'title'  	 =>html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8') ,
						'content'  	 => html_entity_decode($result['content'], ENT_QUOTES, 'UTF-8'),
						'date_added' => date('j-M-Y', strtotime($result['date_added'])),
						'href' 		 =>$link
				);
			}
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limited;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url =  $this->url->link('information/article/all', 'page={page}');
			
		$this->data['pagination'] = $pagination->render();
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] =  $this->url->link('common/home', '');

		$this->renderFrontLayout('information/article_list.tpl');

	}

	public function download() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('information/article/download');

			$this->redirect($this->url->link('account/login'));
		}

		$this->load->model('catalog/article');
		
		if (isset($this->request->get['download_id'])) {
			$download_id = $this->request->get['download_id'];
		} else {
			$download_id = 0;
		}
		
		$download_info = $this->model_catalog_article->getArticleDownload($download_id);
		
		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);
			$mime = 'application/octet-stream';
			$encoding = 'binary';

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Pragma: public');
					header('Expires: 0');
					header('Content-Description: File Transfer');
					header('Content-Type: ' . $mime);
					header('Content-Transfer-Encoding: ' . $encoding);
					header('Content-Disposition: attachment; filename=' . ($mask ? $mask : basename($file)));
					header('Content-Length: ' . filesize($file));
				
					$file = readfile($file, 'rb');
				
					print($file);
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		
			$this->model_catalog_article->updateRemaining($this->request->get['order_download_id']);
		} else {
			$this->redirect($this->url->link('common/home'));
		}
	}
}

?>