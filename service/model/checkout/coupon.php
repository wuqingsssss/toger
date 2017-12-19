<?php
class ModelCheckoutCoupon extends Model {

    // 改为参照优惠券绑定表  cww 2015.4.17
	//public function getCoupon($code) {
    public function getCoupon($customer_coupon_id) {
		$status = true;
		
	    $coupon_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_to_customer cc, " . DB_PREFIX . "coupon c WHERE cc.coupon_id = c.coupon_id AND cc.coupon_customer_id = '" . $this->db->escape($customer_coupon_id)
			. "' AND cc.used = '0' AND ((c.date_start = '0000-00-00' OR c.date_start <= DATE(NOW())) AND (cc.date_limit >= DATE(NOW()))) AND c.status = '1' ");
			
		if ($coupon_query->num_rows) {
			
	
			// 历史里确认优惠券绑定号没记录
			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX
			    . "coupon_history` ch WHERE ch.coupon_customer_id = '" . (int)$coupon_query->row['coupon_customer_id'] . "'");

			if($coupon_history_query->row['total']>0 ){  //只能用一次
			    $status = false;
			}
		    elseif ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}
			else{
			    $coupon_product_data = array();
				
    			$coupon_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
    			
    			$subTotal=$this->cart->getSubTotal();

    		     	foreach ($coupon_product_query->rows as $result) {
    				   $coupon_product_data[] = $result['product_id'];
    		        }

    			if ($coupon_product_data) {
    				$coupon_product = false;

    				if ($coupon_query->row['total'] >= $subTotal['total']+0.001) {//+$subTotal['promotion']
    					$coupon_product = false;
    				}
    				else {
    				foreach ($this->cart->getProducts() as $product) {
    					if (in_array($product['product_id'], $coupon_product_data)) {
    						$coupon_product = true;
    							
    						break;
    					}
    				}
    				}
    					
    				if (!$coupon_product) {
    					$status = false;
    				}
    			}
    			else 
    			{
    				if ($coupon_query->row['total'] >= $subTotal['total']+0.001) {//+$subTotal['promotion']
    					$status  = false;
    				}
    				
    				
    			}
			}

		}else {
			$status = false;
		}
		
		if ($status) {
			return array(
			    'coupon_customer_id'=>$coupon_query->row['coupon_customer_id'],
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $coupon_product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added'],
			    'used'          => $coupon_query->row['used'],
				 'mutual_prom'  => $coupon_query->row['mutual_prom']
			);
		}
	}
	
	/*
	public function getCodeCoupon($code){
		$coupon_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");
		
		if ($coupon_query->num_rows) {
			return array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => array(),
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
		}
	}
	*/
	
	//public function redeem($coupon_id, $order_id, $customer_id, $amount) {
	public function redeem($coupon_customer_id, $order_id, $customer_id, $amount) {
		//FIXED #333:优惠券记录已存在的订单不再记录。
		$sql="SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` WHERE coupon_customer_id='".$this->db->escape($coupon_customer_id)."'";

		$query=$this->db->query($sql);
		
		$sqlcoupon = "SELECT coupon_id  FROM `" . DB_PREFIX . "coupon_to_customer` WHERE coupon_customer_id='".$this->db->escape($coupon_customer_id)."'";
		$querycoupon=$this->db->query($sqlcoupon);
		
		if($querycoupon->num_rows){
    		if($query->row['total']==0){
    		    $coupon_id = (int)$querycoupon->row['coupon_id'];
    			$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_customer_id = '".(int)$coupon_customer_id. "', coupon_id = '" . (int)$coupon_id . "', order_id = '" . $order_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (float)$amount . "', date_added = NOW()");
    		    $this->db->query("UPDATE `" . DB_PREFIX . "coupon_to_customer` SET used='1', date_update = NOW() WHERE coupon_customer_id = '".(int)$coupon_customer_id. "'");
    		}
		}
	}
}
?>