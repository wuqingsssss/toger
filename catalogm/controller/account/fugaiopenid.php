<?php 
class ControllerAccountFugaiopenid extends Controller {
	public function index() {

        if(isset($this->session->data['platform'])){
        $platform=$this->session->data['platform'];
        if($platform['platform_code']=='wechat'&&$platform['openid'])
        $customer_id = $this->customer->getId();
       
        $this->customer->updatePlatForm($openid,$platform['platform_code'],$customer_id);
        
        $this->session->data['is_open_diag']=0;
        }
	  	$this->redirect($this->url->link('common/home', '', 'SSL'));
  	}

}
?>