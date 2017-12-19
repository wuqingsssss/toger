<?php
class ModelSaleTransaction extends Model {
	
	public  function getTransactionByCode($code){
		
		$sql = "SELECT * FROM " .  DB_PREFIX . "trans_code WHERE trans_code='{$this->db->escape($code)}' ";
		$ret = $this->db->query($sql);
return $ret->row;
	}
	
	/**
	 * 拼装 生成充值码 参数
	 * @param type $amount
	 * @return type
	 */
	private function format_data($amount) {
		//构造数据
		$date = date('Y-m-d H:i:s', time());
		$data['operator'] = 'auto_create';
		$data['prefix'] = 'AT';
		$data['length'] = 16;
		$data['batch'] = 1;
		$data['value'] = $amount;
		$data['date_start'] = $date;
		$data['date_end'] = $date;
		return $data;
	}
	
	
	
	public function  existCustomerTransaction($customer_id,$data){
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '{$customer_id}'";
		if($data['order_id']){
			// 当前用户订单关联数量
			$sql .=" AND order_id= '{$data[order_id]}'";
		}
		if($data['reference']){
			// 当前用户订单关联数量
			$sql .=" AND reference= '{$data[reference]}'";
		}
		if($data['amount']===true){
			// 当前用户订单关联数量
			$sql .=" AND amount>0 ";
		}
		elseif($data['amount']===false)
		{
			$sql .=" AND amount<0 ";
		}

		
			$ordercount = $this->db->query($sql,false);
			if((int)$ordercount->row['total']>0)
			{//订单已经被领取
				return true;
			}
			else 
			{
				return false;
			}
	}
	
	

	/**
	 * 追加储值到客户
	 * @param unknown $customer_id
	 * @param unknown $code
	 * @param string $order_id
	 * @param string $info
	 * @return boolean
	 */
	public function addTransaction($customer_id, $code,$order_id='',$info='储值码扫码充值') {
		
		$sql = "SELECT * FROM " .  DB_PREFIX . "trans_code WHERE trans_code='{$this->db->escape($code)}' AND used=0 ";
		$ret = $this->db->query($sql, false);
	    
		if($ret->row && !empty($customer_id)){
		    // 修改储值码记录
		    $this->db->query("UPDATE " . DB_PREFIX . "trans_code 
		                      SET customer_id = {$customer_id},
		                          used = 1,
		                          date_modified = NOW() 
		                      WHERE trans_id={$ret->row['trans_id']}" );
		    
		    // 开通用户储值功能(如果没有)
		    $sql = " SELECT * FROM " . DB_PREFIX . "payment_transaction WHERE customer_id={$customer_id}";
		    $trans = $this->db->query($sql);
		    if($trans->row){
		        $this->db->query("UPDATE " . DB_PREFIX . "payment_transaction 
		                          SET date_modified = NOW() 
		                          WHERE customer_id={$customer_id} 
		                          ");
		    }
		    else{
		        $this->db->query("INSERT INTO " . DB_PREFIX . "payment_transaction 
		                          SET customer_id   ={$customer_id},
		                              date_added    = NOW(),
		                              date_modified = NOW()");
		    }
		    		    
		    // 修改用户储值记录
		    $value =(float)$ret->row['value'];
		    $sql="INSERT INTO ".DB_PREFIX."customer_transaction 
		          SET customer_id='{$customer_id}', 
		          order_id = '{$order_id}',
		          description='{$info}',
		          amount='{$value}',
		          date_added=NOW(),
		          reference = '{$this->db->escape($code)}'";
		    $this->db->query($sql);
				return true;
		}
		else {
		    return false;
		}
	}
	
	// 生成充值码
	public function get_recharge_key($data) {
		$code = $this->getNextCode($data['length'], $data['prefix']);
		$flag = $this->addTransCode($code, $data['date_start'], $data['date_end'], $data['value'], $data['operator'], $data['is_tpl'], $data['tpl_id']);
		if($flag){
			return $code;
		}else{
			return false;
		}
	}

	/**
	 * 生成随机码
	 * @param unknown $codePrefix
	 * @throws exception
	 * @return string
	 */
	private function getNextCode($length, $prefix = null) {
		$tryTimes = 0;
		while ($tryTimes < 100) {
			$tryTimes++;

			$code = Maths::genRandomCode($length, $prefix);

			if (!$this->existSameCode($code)) {
				return $code;
			}
		}
		throw new exception('生成ID失败');
	}

	/**
	 * 追加储值券
	 * @param unknown $code
	 * @param unknown $date_start
	 * @param unknown $date_end
	 * @param unknown $value
	 */
	public function addTransCode($code, $date_start, $date_end, $value, $operator = null,$is_tpl=0,$tpl_id=0) {
		$pendingData = array(
						array('trans_code', $code, true, false),
				        array('is_tpl', $is_tpl, true, true),
				        array('tpl_id', $tpl_id, true, true),
						array('value', $value, false, true),
						array('date_start', $date_start, true, true),
						array('date_end', $date_end, true, true),
						array('used', 0, false, false),
						array('date_added', 'NOW()', false, false),
						array('operator', $operator, true, false),
		);
		$id = DbHelper::insert('trans_code', $pendingData);
		$this->log_sys->info($pendingData);
		return $id;
	}

}
?>