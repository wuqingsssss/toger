<?php
class ControllerCommonEntryPage extends Controller {
	public function index() {
	    
		$infomation= new Common($this->registry);
		$infomation->get_openid();
		
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');
		$this->data['template']=$this->config->get('config_template');

		$this->document->setPageId('entry');
		$this->document->setPageRole('entry-page');
		
		//首页跳转页路径，缺省时为common/home
		$this->data['homepage'] = $this->config->get('config_homepage');
		
		// 礼包参数保存到SESSION
		if( !empty($this->request->get['campaign'])){
			$this->session->data['campaign'] = $this->request->get['campaign'];
		}
		 
		//如果带活动代码，记入SESSION
		if(!empty($this->request->get['promo'])){
			$this->session->data['promo'] = $this->request->get['promo'];
		}

		$this->load->model('design/banner');
		
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner('17');

		if(!$results){
	  		$this->redirect($this->url->link('common/home'));
		}
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
						'title' => $result['title'],
						'link'  => $result['link'],
						'image' =>  resizeThumbImage($result['image'], $setting['width'], $setting['height'],false)
				);
			}
		}
		
		
		$this->children = array(
				'common/header'
		);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/entrypage.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/entrypage.tpl';
		} else {
			$this->template = 'default/template/common/entrypage.tpl';
		}

		
		$this->response->setOutput($this->render());
		
	}
}
?>