<?php
class ControllerModuleSingleArticle extends Controller {
	protected $category_id = 0;
	protected $path = array();

	protected function index($setting) {
		static $module = 0;
		
		$this->load_language('module/article');

		if(isset($setting['article_category_id'])){
			$categoryId=$setting['article_category_id'];
		}else if(isset($setting['article_category'])){
			$categoryId=$setting['article_category'];
		}
		

		$this->data['category_id']=$categoryId;

		$this->data['categories'] =  array();

		$this->load->model('catalog/articlecate');
		$this->load->model('catalog/article');
		$this->load->model('tool/image');

		$article_category=$this->model_catalog_articlecate->getArticleCategory($categoryId);
		
		if(!$article_category){
			return;
		}
		
		$this->data['code']=$article_category['code'];
		$this->data['heading_title']=$article_category['name'];

		if($setting['limited']){
			$limit=$setting['limited'];
		}else{
			$limit=10;
		}

		$results=$this->model_catalog_article->getArticleByCategoryId($categoryId);
		
		$article_array = array();

		$feature_status=0;

		foreach($results as $result){
			if(!$feature_status && $result['featured']){

				$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'thumb'  	 => $this->model_tool_image->resize($result['image'],80,80),
					'summary'  	 => $result['summary'],
					'quantity'=> isset($result['quantity'])?$result['quantity']:"",
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].'&article_category_id=' .$categoryId)
				);

				$feature_status=$result['article_id'];
			}
		}

		foreach($results as $result){
			if(($result['article_id'] != $feature_status) && count($article_array) < $limit){
				$thumb=resizeThumbImage($result['image'],40,40,TRUE);
				
				$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'summary'  	 => $result['summary'],
					'thumb'  	 => $thumb,
					'quantity'=>isset($result['quantity'])?$result['quantity']:"",
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].'&article_category_id=' .$categoryId)
				);
			}
		}

		if($feature_status){
			$this->data['feature_status']=1;
		}else{
			$this->data['feature_status']=0;
		}

		$this->data['articles']=$article_array;

		$this->data['more']=$this->url->link('information/article/category', 'article_category_id=' .$categoryId);

		$this->id = 'article';
		
		if(isset($setting['style'])){
			if($setting['style']=='tab-lsit'){
				$tpl='module/article_tab.tpl';
			}else if($setting['style']=='cate-list'){
				$tpl='module/article_cate_list.tpl';
			}else if($setting['style']=='download'){
				$tpl='module/download_cate.tpl';
			}else if($setting['style']=='cate'){
				$tpl='module/article_cate_only.tpl';
			}else if($setting['style']=='list'){
				$tpl='module/article.tpl';
			}else if($setting['style']=='thumb_list'){
				$tpl='module/article_thumb_list.tpl';
			}else{
				$tpl='module/article.tpl';
			}
		}
		
		if(isset($setting['show_heading'])){
			$this->data['show_heading']=$setting['show_heading'];
		}else{
			$this->data['show_heading']=TRUE;
		}
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$tpl)) {
			$this->template = $this->config->get('config_template') . '/template/'.$tpl;
		} else {
			$this->template = 'default/template/'.$tpl;
		}
		
		$this->render();
  	}
  	
  	public function lists($setting=array()){
  		$this->getArticleLists($setting);
  		
  		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/article_list_2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/article_list_2.tpl';
		} else {
			$this->template = 'default/template/module/article_list_2.tpl';
		}
		
		$this->render();
  	}
  	
  	protected function getArticleLists($setting=array()){
  		$categoryId=$setting['article_category_id'];
		
		$this->data['category_id']=$categoryId;

		$this->data['categories'] =  array();

		$this->load->model('catalog/articlecate');
		$this->load->model('catalog/article');
		$this->load->model('tool/image');

		$article_category=$this->model_catalog_articlecate->getArticleCategory($categoryId);
		
		if(!$article_category){
			return;
		}
		
		$this->data['code']=$article_category['code'];
		$this->data['heading_title']=$article_category['name'];

		if($setting['limited']){
			$limit=$setting['limited'];
		}else{
			$limit=10;
		}

		$results=$this->model_catalog_article->getArticleByCategoryId($categoryId);
		
		$article_array = array();
		
		foreach($results as $result){
			if(count($article_array) < $limit){
				$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'summary'  	 => $result['summary'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].'&article_category_id=' .$categoryId)
				);
			}
		}

		$this->data['articles']=$article_array;

		$this->data['more']=$this->url->link('information/article/category', 'article_category_id=' .$categoryId);

		if(isset($setting['style'])){
			if($setting['style']=='tab-lsit'){
				$tpl='module/article_tab.tpl';
			}else if($setting['style']=='cate-list'){
				$tpl='module/article_cate_list.tpl';
			}else if($setting['style']=='download'){
				$tpl='module/download_cate.tpl';
			}else if($setting['style']=='cate'){
				$tpl='module/article_cate_only.tpl';
			}else if($setting['style']=='list'){
				$tpl='module/article_list.tpl';
			}
		}
  	}
  	
  	public function partial($setting=array()){
  		$this->load_language('module/article');

		$categoryId=$setting['article_category_id'];
		
		$this->data['category_id']=$categoryId;

		$this->data['categories'] =  array();

		$this->load->model('catalog/articlecate');
		$this->load->model('catalog/article');
		$this->load->model('tool/image');

		$article_category=$this->model_catalog_articlecate->getArticleCategory($categoryId);
		
		if(!$article_category){
			return;
		}
		
		$this->data['code']=$article_category['code'];
		$this->data['heading_title']=$article_category['name'];

		if($setting['limited']){
			$limit=$setting['limited'];
		}else{
			$limit=10;
		}
		
		$results=$this->model_catalog_article->getArticleByCategoryId($categoryId);
		
		$article_array = array();
		
  		$feature_status=0;

		foreach($results as $result){
			if(!$feature_status && $result['image']){

				$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'thumb'  	 =>resizeThumbImage($result['image'],80,80),
					'summary'  	 => $result['summary'],
					'quantity'=>isset($result['quantity'])?$result['quantity']:"",
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].'&article_category_id=' .$categoryId)
				);

				$feature_status=$result['article_id'];
			}
		}
		
		foreach($results as $result){
			if(count($article_array) < $limit){
				$article_array[] = array(
					'article_id' 	=> $result['article_id'],
					'title'  	 => $result['name'],
					'thumb'  	 => FALSE,
					'summary'  	 => $result['summary'],
					'quantity'=>isset($result['quantity'])?$result['quantity']:"",
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href' 		=>  $this->url->link('information/article', 'article_id=' . $result['article_id'].'&article_category_id=' .$categoryId)
				);
			}
		}
		
  	
		if($feature_status){
			$this->data['feature_status']=1;
		}else{
			$this->data['feature_status']=0;
		}

		$this->data['articles']=$article_array;
		

		$this->data['more']=$this->url->link('information/article/category', 'article_category_id=' .$categoryId);

		if(isset($setting['style'])){
			if($setting['style']=='tab-lsit'){
				$tpl='module/article_tab.tpl';
			}else if($setting['style']=='cate-list'){
				$tpl='module/article_cate_list.tpl';
			}else if($setting['style']=='download'){
				$tpl='module/download_cate.tpl';
			}else if($setting['style']=='cate'){
				$tpl='module/article_cate_only.tpl';
			}else if($setting['style']=='list'){
				$tpl='module/article_list.tpl';
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$tpl)) {
			$this->template = $this->config->get('config_template') . '/template/'.$tpl;
		} else {
			$this->template = 'default/template/'.$tpl;
		}
		
		$this->render();
		
  	}
}
?>