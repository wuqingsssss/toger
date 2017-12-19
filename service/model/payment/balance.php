<?php 
class ModelPaymentBalance extends Model {
  	public function getMethod($address=0, $total=0) {
		$this->load->language('payment/balance');
		//$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE user_group_id = " . (int)$this->config->get('cash_user_group_id') . " AND username =" . $user['username'] . " AND password = MD5('" . $user['password'] . "') ";
		//$query = $this->db->query($sql);
		$status = true;
		
		if ($this->config->get('balance_status')/*&& $total > EPSILON*/) {
		    $status = TRUE;
		} else {
		    $status = FALSE;
		} 
		 
	    // 检查用户是否开通储值支付
		if (!$this->checkUser()) {
		    $status = false; 
		}
		
		//检查是否满足最低支付金额
		if ($this->config->get('balance_total') > $total) {
			$status = false;
		}
	
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'balance',
        		'title'      => $this->language->get('text_title'),
      		    'description'=> $this->language->get('text_description'),
				'sort_order' => $this->config->get('balance_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
  	
  	/** 
  	 * 检查当前用户支付方式
  	 */
  	public function checkUser() {
  	    if($this->customer->isLogged()){
  	        $sql = " SELECT * FROM " . DB_PREFIX . "payment_transaction WHERE customer_id={$this->customer->getId()} LIMIT 1";
  	        $ret = $this->db->query($sql); 	  

  	        if($ret->row){
  	            // 判断支付密码是否设置
  	          //  if (!empty($ret->row['paycode'])){
  	                return true;
  	          //  } 	             
  	        }
  	        else {
  	            return false;
  	        }
  	    }
  	    else{
  	        return false;
  	    }
  	}	
  	
  	/**
  	 * 校验用户支付密码
  	 * @param unknown $customer_id
  	 * @param unknown $password
  	 */
  	public function checkPassword($customer_id, $password) {
  	    $sql = " SELECT * FROM " . DB_PREFIX . "payment_transaction WHERE customer_id={$this->customer->getId()} AND paycode='{$this->db->escape(md5($password))}' LIMIT 1";
  	    $ret = $this->db->query($sql);
  	    
  	    if($ret->row){
  	        return true;
  	    }
  	    else {
  	        return false;
  	    }
  	}
  	
  	/**
  	 * 确认订单
  	 * @param unknown $customer_id
  	 * @param unknown $order_id
  	 */
  	public function check_money_enough($customer_id,$order_id)
  	{
  	    if(!$order_id){
  	        $result['error']='true';
  	        $result['msg']='订单为空';
  	        echo json_encode($result);
  	        exit;
  	    }
  	    if(!$customer_id){
  	        $result['error']='true';
  	        $result['msg']='用户ID为空';
  	        echo json_encode($result);
  	        exit;
  	    }
  	    $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
  	    $total = (float)$query->row['total'];
  	
  	    $order_money_sql="select * from ".DB_PREFIX."order_payment where payment_code='balance' and order_id = '{$order_id}'";
  	    $order_money_tmp=$this->db->query($order_money_sql);
  	    $order_money=(float)$order_money_tmp->row['value'];
  	    if(($order_money - $total)>EPSILON){
  	        $result['error']='true';
  	        $result['msg']='余额不足';
  	        echo json_encode($result);
  	        exit;
  	    }
  	    $newmoney=(float)(-$order_money);
  	    $sql="insert into ".DB_PREFIX."customer_transaction set customer_id={$customer_id}, order_id='{$order_id}',description='余额消费',amount=$newmoney,date_added=now()";
  	    $this->db->query($sql);
  	    
  	    $result['id'] = $this->db->getLastId();
  	    return $result;
  	}
}
?>