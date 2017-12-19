<?php 
class ControllerInformationInformation extends Controller {
	public function index() {  
    	$this->load_language('information/information');
		
		$this->load->model('catalog/information');
		
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		
		if (isset($this->request->get['information_id'])) {
			$information_id = $this->request->get['information_id'];
		} else {
			$information_id = 0;
		}
		
		$information_info = $this->model_catalog_information->getInformation($information_id);
		
		if(!$information_info){
			$this->redirect($this->url->link('error/not_found'));
		}
		
   		
		$this->document->setTitle($information_info['title']); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $information_info['title'],
			'href'      => $this->url->link('information/information', 'information_id=' .  $information_id),      		
        	'separator' => $this->language->get('text_separator')
      	);		
					
      	$this->data['heading_title'] = $information_info['title'];
      	
      	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
      	
		$this->data['continue'] = $this->url->link('common/home');
		
		$layout_id=getLayoutId();
		
		if($layout_id==18){
			$tpl='information/information.tpl'; //关于我们页
		}else if($layout_id==19){
			$tpl='information/information_19.tpl'; //产业共生页
		}else{
			$tpl='information/information.tpl';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$tpl)) {
			$this->template = $this->config->get('config_template') . '/template/'.$tpl;
		} else {
			$this->template = 'default/template/'.$tpl;
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
	
	public function info() {
		$this->load->model('catalog/information');
		
		if (isset($this->request->get['information_id'])) {
			$information_id = $this->request->get['information_id'];
		} else {
			$information_id = 0;
		}      
		
		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output  = '<html dir="ltr" lang="en">' . "\n";
			$output .= '<head>' . "\n";
			$output .= '  <title>' . $information_info['title'] . '</title>' . "\n";
			$output .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$output .= '</head>' . "\n";
			$output .= '<body>' . "\n";
			$output .= '  <br /><br /><h1>' . $information_info['title'] . '</h1>' . "\n";
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
			$output .= '  </body>' . "\n";
			$output .= '</html>' . "\n";			

			$this->response->setOutput($output);
		}
	}
}
?>