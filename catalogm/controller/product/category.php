<?php 
class ControllerProductCategory extends Controller {  
	public function index() { 	
		//如果带活动代码，记入SESSION
		if(!empty($this->request->get['promo'])){
			$this->session->data['promo'] = $this->request->get['promo'];
		}	
	
		$this->data['cat_id']=(int)$this->request->get['path'];
		$this->data=array_merge($this->data,$data);
		$this->data['header_type'] = 'normal';
		
		if($this->detect->is_weixin_browser()){
			$this->data['header_type'] = 'weixin';
		}
		else if(isset($this->session->data ['platform']['platform_code'])&&$this->session->data ['platform']['platform_code']=='app'){
			$this->data['header_type'] = 'app';
		}

		//$this->data['navtop']=$navtop;
		$this->document->setTitle( '点餐');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
		} else {
			$this->template = 'default/template/product/category.tpl';
		}
		
		$this->data['tplpath'] = DIR_DIR.'view/theme/'.$this->config->get('config_template').'/';
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer35',
			'common/header35'
		);
			
		$this->response->setOutput($this->render());										

  	}

}
?>