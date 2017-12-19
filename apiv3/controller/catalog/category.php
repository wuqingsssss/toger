<?php

class ControllerCatalogCategory extends Controller {
	
 	private $debug = DEBUG;
 	
 	
 	public function init(){
 		header("Access-Control-Allow-Origin: *");
 	}
    
    public function _test() {
    	
    	$this->data['list']= $this->url->link('catalog/category/list');
    	$this->data['detail']= $this->url->link('catalog/category/detail');
    	$this->renderAPITest('catalog/category/test.tpl');
    	
    }
    
  public function _detail() {
      	$this->init();
  		# -- $_GET params ------------------------------
        $data= array();        
      	if(!$this->_checkDetailRequest($data))
      	{
      		 $this->m->setError(mCartResult::ERROR_SYSTEM_INVALID_API);
      		 $this->response->setOutput(json_encode($this->m->returnResult()));
      		 return ;
      	}
        
        # -- End $_GET params --------------------------

        $this->load->model('catalog/category');
        $this->load->model('tool/image');
      	
        if(is_null($data['category_id'] )){
        	$category_info_by_code = $this->model_catalog_category->getCategoryByCode($data['code']);
        	if($category_info_by_code){
	        	$data['category_id'] = $category_info_by_code['category_id'];
        	}
        }
        
	    $category_info = $this->model_catalog_category->getCategory($data['category_id']);
       	
       	if(isset($category_info)&&$category_info!=null)
       	{
       		
       		if (isset($category_info['image'])&&file_exists(DIR_IMAGE . $category_info['image'])) {
       			$category_info['image'] = $this->model_tool_image->getImage($category_info['image']);
       			$category_info['images'] = array(
		       						'big' => $this->model_tool_image->resize($category_info['image'] , 768, 1024),
		       						'popup' => $this->model_tool_image->resize($category_info['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
		       						'middle' => $this->model_tool_image->resize($category_info['image'] ,$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
		       						'thumb' => $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
    							);
			}else{
				$category_info['image'] = false;
				$category_info['images'] = false;
			}
       		
       		
	        $this->m->setSuccess($category_info);
       	}
       	else{
       		$this->m->setSuccess($category_info,null,0);
       	}
        
        if ($this->debug) {
            echo '<pre>';
            print_r($this->m->returnResult());
        } else {
            $this->response->setOutput(json_encode($this->m->returnResult()));
        }
    }
    
    public function _sub() {
    	 $this->init();
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
    	
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
		
        if(is_null($data['parent_id']))
        {
        	$categoryInfo = $this->model_catalog_category->getCategoryByCode($data['code']);
        	if($categoryInfo)
        	{
	        	$data['parent_id'] = $categoryInfo['category_id'];
        	}else{
        		$data['parent_id'] = -1;
        	}
        }
        
		$total = $this->model_catalog_category->getTotalCategories($data['parent_id']);
    	$category_infos = $this->model_catalog_category->getCategories($data['parent_id']);
    	$return_info = array();
    	foreach ($category_infos as $category_info) {
    		$return_info[] = array(
		    	"id" => $category_info["category_id"],
		    	"parent_id" => $category_info["parent_id"],
		    	"top" => $category_info["top"],
		    	"column" => $category_info["column"],
		    	"sort_order" => $category_info["sort_order"],
		    	"status" => $category_info["status"],
		    	"code" => $category_info["code"],
		    	"language_id" => $category_info["language_id"],
		    	"name" => $category_info["name"],
		    	"description" => $category_info["description"],
		    	"meta_description" => $category_info["meta_description"],
		    	"meta_keyword" => $category_info["meta_keyword"],
		    	'images' => (isset($category_info['image'])&&$category_info['image']!='')?array(
		       						'big' => $this->model_tool_image->resize($category_info['image'] , 768, 1024),
		       						'popup' => $this->model_tool_image->resize($category_info['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
		       						'middle' => $this->model_tool_image->resize($category_info['image'] ,$this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
		       						'thumb' => $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
    							):"",
    		);
    	}
    	$this->m->setSuccess($return_info,null,$total);
        
        if ($this->debug) {
        	echo '<pre>';
        	print_r($this->m->returnResult());
        } else {
        	$this->response->setOutput(json_encode($this->m->returnResult()));
        }
       
    }
    
    public function _count() {
    
    }
    
    
    public function _checkDetailRequest(&$data){
    	$flag = false;
    	//have id  no coce
    	 $data['category_id']  = null;
    	  $data['code']  = null;
    	if ($this->_getIsNotEmpty("id")) {
            $category_id = $this->request->get['id'];
            $data['category_id'] = $category_id;
            $flag =  true;
        } 
        //hava code no id
        else if($this->_getIsNotEmpty("code")){
             $code = $this->request->get['code'];
             $data['code'] = $code;
             $flag =  true;
        }
        return $flag;
    }
    
    private function _getIsNotEmpty($param){
    	return (isset($this->request->get[$param])&&$this->request->get[$param]!=null);
    }
    
    private function _getIsEmpty($param){
    	return (!isset($this->request->get[$param])||$this->request->get[$param]==null);
    }
    
    
    public function _checkListRequest(&$data){
    	
    	$flag = false;
		$data['parent_id']  = null;
		$data['code'] = null;
		//have id  no coce
		if ($this->_getIsNotEmpty("parent_id")) {
			$parent_id = $this->request->get['parent_id'];
			$data['parent_id'] = $parent_id;
			$flag = true;
		}
		
		//hava code no id
		else if($this->_getIsNotEmpty("code")){
			$code = $this->request->get['code'];
			$data['code'] = $code;
			$flag = true;
		}


	/*	if ($this->_getIsNotEmpty("pagenum")&&$this->_getIsNotEmpty("pagesize")) {
			$pagenum = (int)$this->request->get['pagenum'];
			if($pagenum<0)
			{
				$pagenum = 0;
			}
			
			$pagesize = $this->request->get['pagesize'];
			
			$data['pagesize'] = $pagesize;
			$data['pagenum'] = $pagenum;
			$flag = true;
		} 
		*/
		
		 if (LOG) {
			__log('[Category List] Interface .Request From ['.$this->getIp().']'.'Params[parent_id:'.$data['parent_id'].']');
		 }
		return $flag;
    	
    }
    
    function __call( $methodName, $arguments ) {
        //call_user_func(array($this, str_replace('.', '_', $methodName)), $arguments);
        call_user_func(array($this, "_$methodName"), $arguments);
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