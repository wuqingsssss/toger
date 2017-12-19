<?php    
class ControllerErrorMsg extends Controller {
	public function index($setting=array()) { 
    	$this->load->language('error/permission');
  
    	$this->document->setTitle("错误信息");
		
    	$this->data['heading_title'] = $this->request->get['heading_title'];

		$this->data['msg'] = $this->session->data['errormsg']?$this->session->data['errormsg']:$setting;
													
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => "错误信息",
			'href'      => $this->url->link('error/msg', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);


		$this->template = 'error/msg.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
  	}
}
?>