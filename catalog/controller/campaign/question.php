<?php 
class ControllerCampaignQuestion extends Controller {
	public function index() {
		$this->language->load('campaign/question');
        $this->load->model('campaign/question');
        $userid=$this->session->data['customer_id'];
        $this->data['question_info']=$this->model_campaign_question->getquestion($userid);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		
		$this->data['button_comment'] = $this->language->get('button_comment');
				
		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/campaign/question.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/campaign/question.tpl';
		} else {
			$this->template = 'default/template/campaign/comment.tpl';
		}
					
		$this->render();
  	}
  	
  	public function add() {
        //error_reporting(E_ALL);
	  	$this->language->load('campaign/question');
        $this->load->model('campaign/question');
        //$this->model_total_question->getquestion();
        $result=array();
        foreach($_POST as $key=>$value){
            if(strstr($key,"question_value")){
                $tmp=intval(str_replace("question_value_","",$key));
                $result[$tmp]=$value;
            }
        }

        $userid=$this->session->data['customer_id'];
      
        $this->model_campaign_question->save_question($userid,$result);
        $this->session->data['discount'] = 'question';

	  	
	  	$json = array();
		$json['success'] = $this->language->get('text_success');

  		
	  	$this->response->setOutput(json_encode($json));		
	 }
}
?>