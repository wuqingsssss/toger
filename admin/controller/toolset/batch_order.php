<?php
class ControllerToolSetBatchOrder extends Controller {
    private $error = array();
    
    public function index () {
       $this->load_language('toolset/batch_order');
      
       $this->document->setTitle($this->language->get('heading_title'));
	   
   		
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
         $fields=array('origin_order_id','new_order_id','date_added','date_modified','pdate','customer_email','customer_date_added','customer_date_modified');
         
         foreach($fields as $field){
	         if (isset($this->error[$field])) {
				$this->data['error_'.$field] = $this->error[$field];
			} else {
				$this->data['error_'.$field] = '';
			}
         }
         
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_toolset'),
			'href'      => $this->url->link('extension/tool', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('toolset/batch_order', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
        
        $this->data['action_order'] =  $this->url->link('toolset/batch_order/order', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['action_customer'] =  $this->url->link('toolset/batch_order/customer', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['heading_title'] = $this->language->get('heading_title');
        
        foreach($fields as $field){
	        if(isset($this->request->post[$field])){
	        	$this->data[$field]=$this->request->post[$field];
	        }else{
	        	$this->data[$field]='';
	        }
        }
        
        if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
        
		
		
        $this->template = 'toolset/batch_order.tpl';
        $this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
    }
    
    public function order(){
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_order()) {
    		$origin_order_id=$this->request->post['origin_order_id'];
    		$new_order_id=$this->request->post['new_order_id'];
    		$date_added=$this->request->post['date_added'];
    		$date_modified=$this->request->post['date_modified'];
    		$pdate=$this->request->post['pdate'];
    		
    		$this->editOrder($origin_order_id,$date_added,$date_modified,$pdate);
    		
    		$this->addOrderHistory($origin_order_id,5,$date_modified, $this->user->getUserName());
    		
    		$this->editorderProductPdate($origin_order_id,$pdate);
    		
    		$this->editOrderID($origin_order_id,$new_order_id);
    		
    		
    		$this->session->data['success']="成功更改订单ID: $origin_order_id 到  $new_order_id ";
    		
    		$this->redirect($this->url->link('toolset/batch_order', 'token=' . $this->session->data['token'], 'SSL'));
    	
    	}
    	
    	$this->index();
    }
    
    private function editOrder($origin_order_id,$date_added,$date_modified,$pdate,$order_status_id=-1){
    	$sql="UPDATE `" . DB_PREFIX . "order` SET date_added='".$this->db->escape($date_added)
    	."',date_modified='".$this->db->escape($date_modified)
    	."',pdate='".$this->db->escape($pdate)."'";
    	if((int)$order_status_id>-1) 
    		$sql.=",order_status_id='".(int)$order_status_id."'";
    	
    	 $sql.=" WHERE order_id='".$this->db->escape($origin_order_id)."'";
    	
    	$this->db->query($sql);
    }
    
    private function addOrderHistory($origin_order_id,$order_status_id=5,$date_added, $operator){
    	$sql="INSERT INTO `" . DB_PREFIX . "order_history` SET order_id='".$this->db->escape($origin_order_id)
    	."',order_status_id=".(int)$order_status_id.",date_added='".$this->db->escape($date_added)."',opeartor='".$operator."'";
    	
    	$this->db->query($sql);
    }
    
    private function editorderProductPdate($origin_order_id,$pdate){
    	$sql="UPDATE `" . DB_PREFIX . "order_product` SET pdate='".$this->db->escape($pdate)
    	."' WHERE order_id='".$this->db->escape($origin_order_id)."'";
    	
    	$this->db->query($sql);
    }
    
    private function editOrderID($origin_order_id,$new_order_id){
    	$sql = "START TRANSACTION;\n";
    	
    	$sql = "UPDATE `".DB_PREFIX."order` SET order_id='".$this->db->escape($new_order_id)."' WHERE order_id='".$this->db->escape($origin_order_id)."' ;\n";
    	$this->db->query($sql);
    	
    	$sql = "UPDATE `".DB_PREFIX."order_product` SET order_id='".$this->db->escape($new_order_id)."' WHERE order_id='".$this->db->escape($origin_order_id)."' ;\n";
    	$this->db->query($sql);
    	
    	$sql = "UPDATE `".DB_PREFIX."order_history` SET order_id='".$this->db->escape($new_order_id)."' WHERE order_id='".$this->db->escape($origin_order_id)."' ;\n";
    	
    	$this->db->query($sql);
    	
    	
    	$sql = "UPDATE `".DB_PREFIX."order_total` SET order_id='".$this->db->escape($new_order_id)."' WHERE order_id='".$this->db->escape($origin_order_id)."' ;\n";
    	
    	$this->db->query($sql);
    	
    	$this->db->query("COMMIT;");
    }
    
    
    
    public function customer(){
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_customer()) {
    		
    		$email=$this->request->post['customer_email'];
    		
    		$customer_id=$this->getCustomerId($email);
    		
    		$date_added=$this->request->post['customer_date_added'];
    		
    		$date_modified=$this->request->post['customer_date_modified'];
    		
    		if($customer_id){
    			$this->editCustomer($customer_id,$date_added,$date_modified);
    		
	    		$this->editCustomerRegisterReward($customer_id,$date_added);
	    		
	    		$this->session->data['success']="成功更改客户: $email 的信息";
    		}
    		
    		
    		$this->redirect($this->url->link('toolset/batch_order', 'token=' . $this->session->data['token'], 'SSL'));
    	
    	}
    	
    	$this->index();
    }
    
    private function getCustomerId($email){
   	 	$sql="SELECT customer_id FROM  `" . DB_PREFIX . "customer`  WHERE email='".$this->db->escape($email)."'";
   	 	
   	 	$query=$this->db->query($sql);
   	 	
   	 	if($query->row){
   	 		return $query->row['customer_id'];
   	 	}else{
   	 		return 0;
   	 	}
    	
    }
    
    
 	private function editCustomer($customer_id,$date_added,$date_modified){
    	$sql="UPDATE `" . DB_PREFIX . "customer` SET date_added='".$this->db->escape($date_added)
    	."',date_latest_login='".$this->db->escape($date_modified)."' WHERE customer_id=".(int)$customer_id;
    	
    	$this->db->query($sql);
    }
    
    private function editCustomerRegisterReward($customer_id,$date_added){
    	$sql="UPDATE `" . DB_PREFIX . "customer_reward` SET date_added='".$this->db->escape($date_added)
    	."' WHERE order_id=0 AND customer_id=".(int)$customer_id;
    	
    	$this->db->query($sql);
    }
    
    private function validate_customer(){
   		 if ((strlen(utf8_decode($this->request->post['customer_email'])) < 1)) {
        	$this->error['customer_email'] = '请填写客户注册邮箱';
      	}else if(! $this->checkCustomer($this->request->post['customer_email'])){
      		$this->error['customer_email'] = '客户帐号不存在';
      	}
    	
    	if ((strlen(utf8_decode($this->request->post['customer_date_added'])) < 1)) {
        	$this->error['customer_date_added'] = '请填写客户注册时间';
      	}
      	
    	if ((strlen(utf8_decode($this->request->post['customer_date_modified'])) < 1)) {
        	$this->error['customer_date_modified'] = '请填写客户最后登录时间';
      	}
      	
   		 if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    private function validate_order(){
    	
    	if ((strlen(utf8_decode($this->request->post['origin_order_id'])) < 1)) {
        	$this->error['origin_order_id'] = '请填写原始订单ID';
      	}else if(! $this->checkOrder($this->request->post['origin_order_id'])){
      		$this->error['origin_order_id'] = '原始订单不存在，请确认订单数据输入正确';
      	}
      	
    	if ((strlen(utf8_decode($this->request->post['new_order_id'])) < 1)) {
        	$this->error['new_order_id'] = '请填写新订单ID';
      	}else if($this->checkOrder($this->request->post['new_order_id'])){
      		$this->error['new_order_id'] = '新订单ID已存在，请换个订单ID';
      	}
      	
    	if ((strlen(utf8_decode($this->request->post['date_added'])) < 1)) {
        	$this->error['date_added'] = '请填写下单时间';
      	}
    	if ((strlen(utf8_decode($this->request->post['date_modified'])) < 1)) {
        	$this->error['date_modified'] = '请填写最后更新时间';
      	}
    	if ((strlen(utf8_decode($this->request->post['pdate'])) < 1)) {
        	$this->error['pdate'] = '请填写取单时间';
      	}
    	
    	if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    private function checkOrder($order_id){
    	$sql="SELECT COUNT(*) AS total FROM `".DB_PREFIX."order` WHERE order_id='".$this->db->escape($order_id)."'";
    	
    	$query=$this->db->query($sql);
    	
    	return $query->row['total'];
    }
    
    private function checkCustomer($email){
    	$sql="SELECT COUNT(*) AS total FROM `".DB_PREFIX."customer` WHERE email='".$this->db->escape($email)."'";
    	
    	$query=$this->db->query($sql);
    	
    	return $query->row['total'];
    }
    
   
    
    
    
    private function validate () {
        if (! $this->user->hasPermission('modify', 'toolset/batch_order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
} 