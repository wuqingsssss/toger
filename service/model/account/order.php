<?php

class ModelAccountOrder extends Model {
    public function getOrder($order_id,$chkuid=true) {
    	$sql="SELECT o.*, os.name as status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.order_id = '" . $order_id."'";
    	
    	if($chkuid)$sql.= " AND o.customer_id = '" .  (int)$this->customer->getId() . "'";
    	
    	$sql.= " AND o.order_status_id > '0'";
    	$order_query = $this->db->query($sql,false);
        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            return array(
                'order_id' => $order_query->row['order_id'],
                'p_order_id' => $order_query->row['p_order_id'],
                'pdate' => $order_query->row['pdate'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
            	'shipping_code' => $order_query->row['shipping_code'],
            	'shipping_data' => $order_query->row['shipping_data'],
            	'shipping_time' => $order_query->row['shipping_time'],
            	'shipping_point_id' => $order_query->row['shipping_point_id'],
                'shipping_firstname' => $order_query->row['shipping_firstname'],
                'shipping_lastname' => $order_query->row['shipping_lastname'],
                'shipping_company' => $order_query->row['shipping_company'],
                'shipping_address_1' => $order_query->row['shipping_address_1'],
                'shipping_address_2' => $order_query->row['shipping_address_2'],
                'shipping_mobile' => $order_query->row['shipping_mobile'],
                'shipping_phone' => $order_query->row['shipping_phone'],
                'shipping_postcode' => $order_query->row['shipping_postcode'],
                'shipping_city' => $order_query->row['shipping_city'],
                'shipping_zone_id' => $order_query->row['shipping_zone_id'],
                'shipping_zone' => $order_query->row['shipping_zone'],
                'shipping_zone_code' => $shipping_zone_code,
                'shipping_country_id' => $order_query->row['shipping_country_id'],
                'shipping_country' => $order_query->row['shipping_country'],
                'shipping_iso_code_2' => $shipping_iso_code_2,
                'shipping_iso_code_3' => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method' => $order_query->row['shipping_method'],
                'payment_firstname' => $order_query->row['payment_firstname'],
                'payment_lastname' => $order_query->row['payment_lastname'],
                'payment_company' => $order_query->row['payment_company'],
                'payment_address_1' => $order_query->row['payment_address_1'],
                'payment_address_2' => $order_query->row['payment_address_2'],
                'payment_postcode' => $order_query->row['payment_postcode'],
                'payment_city' => $order_query->row['payment_city'],
                'payment_zone_id' => $order_query->row['payment_zone_id'],
                'payment_zone' => $order_query->row['payment_zone'],
                'payment_zone_code' => $payment_zone_code,
                'payment_country_id' => $order_query->row['payment_country_id'],
                'payment_country' => $order_query->row['payment_country'],
                'payment_iso_code_2' => $payment_iso_code_2,
                'payment_iso_code_3' => $payment_iso_code_3,
                'payment_address_format' => $order_query->row['payment_address_format'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
            	'status' => $order_query->row['status'],
                'language_id' => $order_query->row['language_id'],
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'ip' => $order_query->row['ip'],
                'express' => $order_query->row['express'],
                'express_website' => $order_query->row['express_website'],
                'express_no' => $order_query->row['express_no'],
                'pickup_code' => $order_query->row['pickup_code'],
            	'partner_code' => $order_query->row['partner_code'],
                'certification' => $order_query->row['certification'],
                'order_type' => $order_query->row['order_type'],
                'addition_info' => $order_query->row['addition_info']
            );
        } else {
            return false;
        }
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(1) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'";

        $sql = $this->getFilterSql($sql, $data);

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    /**
     * 添加过滤
     * @param unknown $sql
     * @param unknown $data
     * @return string
     */
    private function getFilterSql($sql, $data) {	
        if (isset($data['filter_order_status']) && !is_null($data['filter_order_status'])) {
            $sql .= " AND o.order_status_id=" . (int)$data['filter_order_status'];
        }
        if (isset($data['filter_order_status_ids']) && !is_null($data['filter_order_status_ids'])) {
        	$sql .= " AND o.order_status_id in(" . $this->db->escape(implode(',', $data['filter_order_status_ids'])).")";
        }
        if (isset($data['filter_not_order_status_ids']) && !is_null($data['filter_not_order_status_ids'])) {
        	$sql .= " AND o.order_status_id NOT IN (" . $this->db->escape(implode(',', $data['filter_not_order_status_ids'])) . ")";
        }
        
        $sql .= " AND o.date_added > '" . $this->customer->getDateAdded()."'";
        
        if(isset($data['filter_order_type'])){
            if(defined('ORDER_PAY_TIMEOUT_MINS')&&(int)ORDER_PAY_TIMEOUT_MINS) {
                if (isset($data['filter_timelimit']) && !is_null($data['filter_timelimit'])) {
                    $sql .= " AND (TIMESTAMPDIFF(MINUTE,o.date_added,NOW()) > ".(int)ORDER_PAY_TIMEOUT_MINS . " OR order_type = '" . $data['filter_order_type'] . "')";
                }
            }
        }
        else{
            if(defined('ORDER_PAY_TIMEOUT_MINS')&&(int)ORDER_PAY_TIMEOUT_MINS) {
                if (isset($data['filter_timelimit']) && !is_null($data['filter_timelimit'])) {
                    $sql .= " AND TIMESTAMPDIFF(MINUTE,o.date_added,NOW()) > ".(int)ORDER_PAY_TIMEOUT_MINS;
                }
            }
        }
        
        $sql .= " AND (o.partner_code ='' or o.partner_code=0) ";
        
        return $sql;
    }

    public function getOrders($data = array()) {
        $sql = "SELECT o.order_id,o.p_order_id,o.pdate, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value,o.order_status_id, o.shipping_firstname as shipping_name,o.shipping_point_id,o.shipping_time,o.partner_code FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId()
            . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id')
            . "'";

        $sql = $this->getFilterSql($sql, $data);

        $sql .= " ORDER BY o.date_added DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql,false);

        return $query->rows;
    }

    public function getSubOrders($p_order_id, $data = array()) {
    	
    	/*取消子订单逻辑*/
    	return array();
    	
        $sql = "SELECT o.order_id,o.p_order_id,o.pdate, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value,o.order_status_id, o.shipping_firstname as shipping_name FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId()
            . "' AND o.order_status_id > '0' AND o.p_order_id = '" . (int)$p_order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id')
            . "'";

        $sql .= " ORDER BY o.date_added,o.pdate DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql,false);

        return $query->rows;
    }


    public function getOrderProducts($order_id) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");

        return $query->rows;
    }

    public function getOrderOptions($order_id, $order_product_id) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . $order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {

        $sql = "SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' ORDER BY sort_order";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderHistories($order_id) {

        $query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . $order_id . "' AND oh.notify = '1' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

        return $query->rows;
    }

    public function getOrderDownloads($order_id) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . $order_id . "' ORDER BY name");

        return $query->rows;
    }


    public function getTotalOrderProductsByOrderId($order_id) {

        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order_id . "'");

        return $query->row['total'];
    }

    public function editOrderStatus($order_id, $order_status_id) {
		if(empty($order_id)||!$this->customer->isLogged()){
			return false;
		}
		if(is_array($order_id)){
			$id_str = '('.implode(',', $order_id).')';
			$query = $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id=" . (int)$order_status_id . " WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_id in " . $id_str );
		}else{
			$query = $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id=" . (int)$order_status_id . " WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_id='" . $this->db->escape($order_id) . "'");
		}
        $this->log_order->info('editOrderStatus：'.$id_str.':$order_status_id:'.$order_status_id);
		if(is_array($order_id)){
			foreach($order_id as $id){
				 $this->saveHistory($id, $order_status_id);
				 //如果取消订单，取消支付方法记录，返回储值
				 if((int)$order_status_id == (int)$this->config->get('config_order_cancel_status_id')){
				     $this->load->model('checkout/order');
				     $this->model_checkout_order->clearOrderPayments($id);
				 }
			}
		}else{
			$this->saveHistory($order_id, $order_status_id);
			//如果取消订单，取消支付方法记录，返回储值
			if((int)$order_status_id == (int)$this->config->get('config_order_cancel_status_id')){
			    $this->load->model('checkout/order');
			    $this->model_checkout_order->clearOrderPayments($order_id);
			}
		}
    }

    public function refundOrder($order_id, $order_status_id, $reason) {
    	
    	$order_info=$this->getOrder($order_id);
    	if($order_info['order_status_id']!=$order_status_id){//如果不是退款状态则更改为退款状态
        $this->editOrderStatus($order_id, $order_status_id);
        $this->saveHistory($order_id, $order_status_id, $reason);
        //原路返回模式 添加退款请求记录
        $this->load->model('checkout/order');
        $payments=$this->model_checkout_order->getOrderPayments($order_id);
        
        
        foreach($payments as $refund){
 
        	$sql="SELECT order_payment_id FROM " . DB_PREFIX ."order_refund WHERE order_payment_id='{$refund['order_payment_id']}'";
        	$query = $this->db->query($sql);
        	
        	if ((int)$query->num_rows <=0) {//如果不存在退款记录，则添加退款记录
        	$data = array(
        			array('order_id', $order_id, true),
        			array('order_payment_id', $refund['order_payment_id'], true),
        			array('payment_code', $refund['payment_code'], true),
        			array('status', 'PENDING', true),
        			array('reason', $reason, true),
        			array('value', $refund['value'], true),
        			array('created_at', 'now()', false),
        	);
        	$creatdId = DbHelper::insert('order_refund', $data);
        	}

        }
    	}
    }


    public function saveHistory($order_id, $order_status_id, $comment) {
    	$this->log_order->info('model->account->saveHistory::'.$order_id.';order_status_id:'.$order_status_id.';comment:'.$comment.'user_id'.$this->customer->getId());
    	
    	$sql = "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '0', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), operator='user_id".$this->customer->getId()."'";
    
    	$this->db->query($sql);
    }
}

?>