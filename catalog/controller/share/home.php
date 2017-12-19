<?php
class ControllerShareHome extends Controller {
	private $error = array();
	protected function init(){

		
	}

	/**
	 * 更新被购买数量
	 */
	public function share_success()
	{
      
        $data['coupon_id']   ='';
        $data['customer_id'] =$this->customer->getId();//当前登录用的id
        $data['point_id']    =$this->request->get['pointid'];
        $data['partner_code']=$this->request->get['partner'];
        $data['remark']      =$this->request->get['title'];
        $data['link_url']    =$this->request->get['link'];
        $data['source_url']  =$this->session->data['source_url'];
         $this->log_sys->info('model_account_sharelink->addShareLink::serialize(data):' . serialize($data));
         if($data['link_url']){
        $this->load->model('account/sharelink');
        $sharelink_id=$this->model_account_sharelink->addShareLink($data);        	
       if(!$this->customer->isLogged()){    	
       	$this->session->data['un_sharelink_id']=$sharelink_id;
       }
       }
        
        $result['error']=0;
        $result['data']=$data;
        
		$this->load->library('json');
		$this->response->setOutput(Json::encode($result));
	} 
	
}
?>