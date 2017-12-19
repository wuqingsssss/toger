<?php 
class ServicePaymentBalanceBalance extends Service {
  	public function getMethod($address, $total=0) {
		$this->load->language('payment/balance');
		//$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE user_group_id = " . (int)$this->config->get('cash_user_group_id') . " AND username =" . $user['username'] . " AND password = MD5('" . $user['password'] . "') ";
		//$query = $this->db->query($sql);
		$status = true;
		
		if ($this->config->get('balance_status')&& $total > EPSILON) {
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
  	
  	    $order_money_sql="select * from ".DB_PREFIX."order_total where code='total' and order_id = '{$order_id}'";
  	    $order_money_tmp=$this->db->query($order_money_sql);
  	    $order_money=(float)$order_money_tmp->row['value'];
  	    if($total<$order_money){
  	        $result['error']='true';
  	        $result['msg']='余额不足';
  	        echo json_encode($result);
  	        exit;
  	    }
  	    $newmoney=(float)(-$order_money);
  	    $sql="insert into ".DB_PREFIX."customer_transaction set customer_id={$customer_id}, order_id='{$order_id}',description='余额消费',amount=$newmoney,date_added=now()";
  	    $this->db->query($sql);
  	}
  	
  	public function reFund($refund_list){
  		$refundResult=array();
  		foreach($refund_list as $refund_info){
			$sql = "SELECT status FROM " . DB_PREFIX . "order_refund WHERE order_refund_id='{$refund_info['order_refund_id']}'";
			$res = $this->db->query ( $sql );
			$sql = "";
			if ($res->row ['status'] == 'PHASE2_PASSED') {//仅当状态为已审核才退款
				$sql .= "insert into " . DB_PREFIX . "customer_transaction set customer_id='{$refund_info['customer_id']}', order_id='{$refund_info['order_id']}',description='菜君退款',amount={$refund_info['value']},date_added=now();";
			}
			
			$sql .= "UPDATE " . DB_PREFIX . "order_refund SET status='DONE' WHERE order_refund_id='{$refund_info['order_refund_id']}';";
			if (! empty ( $order_info ['order_payment_id'] ))
				$sql .= "UPDATE " . DB_PREFIX . "order_payment SET status='9' WHERE order_payment_id='{$refund_info['order_payment_id']}'";
			
			 $this->db->multi_query ( $sql );
			 $refund_info['status']='DONE';
  		     $refundResult[]=$refund_info;

  		     $this->log_payment->info('refundResult::'.serialize(refundResult));
  		     /*此处增加退款订单处理逻辑,也可以定时批量处理*/
  		    $this->load->model('checkout/order','service');
  		    $this->model_checkout_order->confirmOrderRefund($refund_info['order_id']);
  		     
  		}
  		return $refundResult;
  	}
  	
}
?>