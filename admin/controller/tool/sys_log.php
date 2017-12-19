<?php 
class ControllerToolSysLog extends Controller { 
	private $error = array();
	
	public function index() {	

		$this->load_language('tool/sys_log');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_done'] = $this->language->get('button_done');



		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/sys_log', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
		

		$this->template = 'tool/sys_log.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	
public function find(){
		$this->noTimeout(300,false);
		$loggers=isset($this->request->get ['loggers'])?explode(',', trim($this->request->get ['loggers'])):array();
		$logger_text=isset($this->request->get ['logger_text'])? str_replace(',', ' ',trim($this->request->get ['logger_text'])) :'';
		$fileds=isset($this->request->get ['loggers'])?explode(',', trim($this->request->get ['fileds'])):array();
	    //$this->response->setOutput (json_encode( $loggers ));
	 
		$keyword=$this->request->get ['keyword'];
		$date_start=$this->request->get ['date_start'];
		$date_end=$this->request->get ['date_end'];
		
		$page=(int)$this->request->get ['page'];

		$source=$this->request->get ['source'];
		$server=$this->request->get ['server'];
	    $message=	isset($this->request->get ['message'])?$this->request->get ['message']:'';
	    $size=	isset($this->request->get ['size'])?(int)$this->request->get ['size']:'10';
	    
	    $level=	isset($this->request->get ['level'])?$this->request->get ['level']:'';
		$res['status']=1;
		$res['message']='成功查询到结果';

		/*
		if(count($fileds)==1){
		$filter=array(
				$fileds[0]=>new MongoRegex("/{$keyword}/i")
		);}
		else 
		{
			foreach($fileds as $filed)
				$filter['$or'][]=array($filed=>new MongoRegex("/{$keyword}/i"));
		}
		if($level)
			$filter['level']=new MongoRegex("/{$level}/i");
      */
		
		$pyarray= array();
		$pyexe='';
		$pyexe='python '.DIR_PYTHON.'grep_log_file.py '; 
		$pyexe.=date('Ymd',strtotime($date_start)).' '.date('Ymd',strtotime($date_end)).' ';
		$pyexe.="\"$keyword\" ";
		
		//$pyexe.=($page*$size+1)." ".(($page+1)*$size)." ";
		$pyexe.=($size)." ";
		$pyexe.= "$server ";
		$pyexe.= "$source ";
		if(!$logger_text){
		foreach($loggers as $logger)
		{
		  // $data[$logger]= $this->{$logger}->find($filter,$size);

			//if($source=='www'){
		   //$pyexe.="catalog_$logger.log ";
		  // $pyexe.="catalogm_$logger.log ";
		  // $pyexe.="admin_$logger.log ";
		   //$pyexe.="apiv3_$logger.log ";
		   //}
		  // else 
		   {
		   	$pyexe.="$logger.log ";
		   }

		}
		}
		else {
			
		$pyexe.=$logger_text." ";
		}
		//$pyexe.='2>&1';

		exec($pyexe.'2>&1',$pyarray,$return_val);

		   // $data[]=array($pyexe);
			$data[$pyexe]= $pyarray;

		$res['data']=$data;
		$res['frontpage']=$page?($page-1):'false';
		$res['page']=$page;
		//$res['nextpage']=count($pyarray)>=$size?$page+1:'';
		$res['nextpage']=false;
		
		
		$this->response->setOutput (json_encode ( $res ));

	}
	
}
?>