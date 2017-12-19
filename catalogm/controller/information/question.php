<?php

/* 常见问题 控制器
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControllerInformationQuestion extends Controller {
   	public function index() {
		$this->load->model('catalog/question');
		
		//分页
		$page  =  1;
		$limit =  10;
		$start = ($page - 1) * $limit;

		$question_model = $this->model_catalog_question;
	
		//列表
		$normal_list = $question_model->get_list( $start, $limit);
		$this->data['question_list'] = $normal_list;

		$this->data['tplpath'] = DIR_DIR . 'view/theme/' . $this->config->get('config_template') . '/';
	
		//第一次加载页面 模版渲染
		$this->document->setTitle('常见问题');
		$tpl = 'information/question.tpl';
		$this->template = $this->config->get('config_template') . '/template/' . $tpl;

		$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer35',
				'common/headersimple'
		);
		$this->response->setOutput($this->render());
	}
	
	/**
	 * AJAX刷新
	 */
	public function update(){
	    $json = array();
	    
	    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    	    $this->load->model('catalog/question');
    	    
    	    if( isset($this->request->post['page'])&& $this->request->post['page']){
    	        $page = $this->request->post['page'];
    	    }
    	    else {
    	        $page = 1;
    	    }
    	    $limit =  10;
		    $start = ($page - 1) * $limit;
    	    
    	    $normal_list = $this->model_catalog_question->get_list( $start, $limit);
    	    
    	    if (!$normal_list) {
    	        $json['code'] = false;
    	        $json['msg'] = '木有啦~，别点啦！';
    	    }
    	    else{  	
    	        $this->data['question_list'] = $normal_list;
        	    $tpl = 'information/ajax_question.tpl';
        	    $this->template = $this->config->get('config_template') . '/template/' . $tpl;
        	    $json['code'] = true;
        	    $json['output'] = $this->render();
    	    }
	
	    }
	    
	    $this->response->setOutput(json_encode($json));		
	}
}
