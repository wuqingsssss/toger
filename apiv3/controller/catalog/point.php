<?php

class ControllerCatalogPoint extends Controller {

	private $debug = DEBUG;

	public function init(){
 		header("Access-Control-Allow-Origin: *");
 	}
	
	public function _test() {
		 
		$this->data['list']= $this->url->link('catalog/product/list');
		$this->data['detail']= $this->url->link('catalog/product/detail');
		$this->renderAPITest('catalog/test.tpl');
	}
	
	
	public function _index(){
		
	}

	public function _detail() {
		$this->init();
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		 

		# -- $_GET params ------------------------------
		$data= array();
		if(!$this->_checkDetailRequest($data))
		{
			$this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
			$this->response->setOutput(json_encode($this->m->returnResult()));
			return ;
		}
		
		# -- End $_GET params --------------------------
		$this->load->model('catalog/point');
		$point_info = $this->model_catalog_point->getPoint($data['point_id']);
	
		if($point_info){
			$this->m->setSuccess($point_info,null,1);
		}
		else{
			$this->m->setSuccess(array(),null,0);
		}

		if ($this->debug) {
			echo '<pre>';
			print_r($this->m->returnResult());
		} else {
			$this->response->setOutput(json_encode($this->m->returnResult()));
		}
	}
	

	public function _list() {
		$this->init();
		$this->load->model('catalog/point');
		$this->load->model('tool/image');


		# -- $_GET params ------------------------------

		$data= array();
		if(!$this->_checkListRequest($data))
		{
			$this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
			$this->response->setOutput(json_encode($this->m->returnResult()));
			return ;
		}
		if(isset($this->request->get['debug'])){
			$this->debug = TRUE;
		}
		# -- End $_GET params --------------------------

		$data = array(
			'start' => $data['start'],
			'limit' => $data['limit'],
			'filter_status'=>1,
		);
		
		$point_total = $this->model_catalog_point->getTotalPoints($data);
	
		$results = $this->model_catalog_point->getPoints($data);
		
    	$this->m->setSuccess($results,null,$point_total);
    	
		if ($this->debug) {
			echo '<pre>';
			print_r($this->m->returnResult());
		} else {
			$this->response->setOutput(json_encode($this->m->returnResult()));
		}
		 
	}

	public function _count() {
		$this->init();
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		# -- $_GET params ------------------------------

		$data= array();
		if(!$this->_checkListCount($data))
		{
			$this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
			$this->response->setOutput(json_encode($this->m->returnResult()));
			return ;
		}
		if(isset($this->request->get['debug'])){
			$this->debug = TRUE;
		}

		$category_id = $data['category_id'];
		$code = $data['code'];
		# -- End $_GET params --------------------------

		$products = $this->model_catalog_product->getTotalProducts(array(
            'filter_category_id'        => $category_id,
        	'filter_category_code'        => $code,
		));

		$this->m->setSuccess($products);

		$this->response->setOutput(json_encode($this->m->returnResult()));
	}

	function __call( $methodName, $arguments ) {
		//call_user_func(array($this, str_replace('.', '_', $methodName)), $arguments);
		call_user_func(array($this, "_$methodName"), $arguments);
	}


	private function _getIsNotEmpty($param){
		return (isset($this->request->get[$param])&&$this->request->get[$param]!=null);
	}

	private function _getIsEmpty($param){
		return (!isset($this->request->get[$param])||$this->request->get[$param]==null);
	}


	public function _checkDetailRequest(&$data){
		$flag = false;
		if ($this->_getIsNotEmpty("point_id")) {
			$point_id = $this->request->get['point_id'];
			$data['point_id'] = $point_id;
			$flag =  true;
		}
		
		return $flag;
	}
	
	public function _checkIndexRequest(&$data){
		$flag = false;
		if ($this->_getIsNotEmpty("id")) {
			$product_id = $this->request->get['id'];
			$data['product_id'] = $product_id;
			$flag =  true;
		}
		return $flag;
	}

	public function _checkListRequest(&$data){
		 
		$flag = true;
		if ($this->_getIsNotEmpty("pagenum")&&$this->_getIsNotEmpty("pagesize")) {
			$pagenum = (int)$this->request->get['pagenum'];
			if($pagenum<0)
			{
				$pagenum = 0;
			}else{
				$pagenum=$pagenum-1;
			}
			
			$pagesize = $this->request->get['pagesize'];
			$data['limit'] = $pagesize;
			$data['start'] = $pagenum*$pagesize;
		} else{
			$data['limit'] = 20;
			$data['start'] = 0;
		}
		return $flag;
	}
	
public function _checkListCount(&$data){
		 
		$flag = true;
		$data['category_id']  = null;
		$data['code'] = null;
		//have id  no coce
		if ($this->_getIsNotEmpty("category_id")&&$this->_getIsEmpty("code")) {
			$category_id = $this->request->get['category_id'];
			$data['category_id'] = $category_id;
			$data['code'] = null;
		}
		//hava code no id
		else if($this->_getIsNotEmpty("code")&&$this->_getIsEmpty("category_id")){
			$code = $this->request->get['code'];
			$data['code'] = $code;
			$data['category_id'] = null;
		}
		//have code and id
		else if ($this->_getIsNotEmpty("code")&&$this->_getIsNotEmpty("category_id")) {
			$code = $this->request->get['code'];
			$category_id = $this->request->get['category_id'];
			$data['category_id'] = $category_id;
			$data['code'] = $code;
		}
		
		return $flag;
	}

}

?>