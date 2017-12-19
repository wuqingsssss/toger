<?php

class ControllerCatalogProduct extends Controller {

	private $debug = DEBUG;

	public function init(){
		header("Access-Control-Allow-Origin: *");
	}

	public function _test() {
		$this->data['list']= $this->url->link('catalog/product/list');
		$this->data['detail']= $this->url->link('catalog/product/detail');
		$this->renderAPITest('catalog/test.tpl');
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
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$product_id = $data['product_id'];
		$product_info = $this->model_catalog_product->getProduct($product_id);


		if($product_info){
			if (isset($product_info['image'])&&file_exists(DIR_IMAGE . $product_info['image'])) {
				$product_info['image'] = $this->model_tool_image->getImage($product_info['image']);
				$product_info['images'] = array(
	       						'big' => $this->model_tool_image->resize($product_info['image'] , 768, 1024),
	       						'popup' => $this->model_tool_image->resize($product_info['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
	       						'middle' => $this->model_tool_image->resize($product_info['image'] ,$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
	       						'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}else{
				$product_info['image'] = false;
				$product_info['images'] = false;
			}



			//$product_info['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');


			$this->m->setSuccess($product_info,null,1);
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
		$this->load->model('catalog/product');
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

		if(isset($data['filter_category_code']))
		{
			$this->load->model('catalog/category');
			$category_info = $this->model_catalog_category->getCategoryByCode($data['filter_category_code']);
			if($category_info)
			{
				$data['filter_category_id'] = $category_info['category_id'];
			}
		}

		$total= $this->model_catalog_product->getTotalProducts($data);
		$products = $this->model_catalog_product->getProducts($data);

		foreach($products as $index => $result){
			$products[$index]['image']=resizeThumbImage($result['image'],0,0,false);
		}

		$result = array();

		if(isset($products)){
			$result['products'] = $products;
			$this->m->setSuccess($result,null,$total);
		}else{
			$this->m->setSuccess($result,null,0);
		}
			
		if ($this->debug) {
			echo '<pre>';
			print_r($this->m->returnResult());
		} else {
			$this->response->setOutput(json_encode($this->m->returnResult()));
		}
			
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
		if ($this->_getIsNotEmpty("product_id")) {
			$product_id = $this->request->get['product_id'];
			$data['product_id'] = $product_id;
			$flag =  true;
		}

		if (LOG) {
			__log('[Product Detail] Interface .Request By ['.$this->getIp().']'.'Params[product_id:'.$product_id.']');
		}
		return $flag;
	}

	public function _checkListRequest(&$data){
			
		$flag = true;
		$data['filter_category_id']  = null;
		$data['filter_category_code'] = null;
		$data['filter_sub_category'] = null;
		$data['pagenum'] = null;
		$data['pagesize'] = null;
		//have id  no coce
		if ($this->_getIsNotEmpty("category_id")) {
			$category_id = $this->request->get['category_id'];
			$data['filter_category_id'] = $category_id;
		}
		//hava code no id
		if($this->_getIsNotEmpty("filter_category_code")){
			$code = $this->request->get['filter_category_code'];
			$data['filter_category_code'] = $code;
		}

		if($this->_getIsNotEmpty("filter_sub_category")){
			$filter_sub_category = $this->request->get['filter_sub_category'];
			$data['filter_sub_category'] = $filter_sub_category;
		}


		if ($this->_getIsNotEmpty("pagenum")&&$this->_getIsNotEmpty("pagesize")) {
			$data['pagenum'] = (int)$this->request->get['pagenum'];
			$data['pagesize'] = (int)$this->request->get['pagesize'];
				
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
		if (LOG) {
			__log('[Product List] Interface .Request From ['.$this->getIp().']'.'Params[category_id:'.$data['filter_category_id'].'][pagenum:'.$data['pagenum'].'][pagesize:'.$data['pagesize'].']');
		}
		return $flag;
	}


	public function getIp(){
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])
		{
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}
		elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
		{
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}
		elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"])
		{
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("HTTP_CLIENT_IP"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("REMOTE_ADDR"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		else
		{
			$ip = "Unknown";
		}
		return $ip;

	} }

	?>
