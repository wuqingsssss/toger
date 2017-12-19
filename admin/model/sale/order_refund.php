<?php

class ModelSaleOrderRefund extends Model {
    public function getOrderRefund($orderrefundId) {
        $sql = "SELECT * FROM " . DB_PREFIX . "order_refund t WHERE t.order_refund_id='" . $orderrefundId . "'";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        return isset($rows) && count($rows) > 0 ? $rows[0] : null;
    }

    private function getfilters($data)
    {
        $sql .= " WHERE o.is_delete = '0'";//o.order_status_id in(13,11,8) AND
        
        if (isset($data['filter_order_refund_status']) && !is_null($data['filter_order_refund_status'])&& $data['filter_order_refund_status']!='all') {
        	$sql .= " AND t.status = '" . $data['filter_order_refund_status'] . "'";
        }
        if (isset($data['filter_order_refund_id']) && !is_null($data['filter_order_refund_id'])) {
        	$sql .= " AND t.order_refund_id = '" . $data['filter_refund_order_id'] . "'";
        }
        
        if (isset($data['filter_order_refund_ids']) && !is_null($data['filter_order_refund_ids'])) {
        	$sql .= " AND t.order_refund_id in ('" .implode( "','",$data['filter_order_refund_ids'])  . "')";
        }
        
    	if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
    		$sql .= " AND o.order_id = '" . $data['filter_order_id'] . "'";
    	}
    	
    	if (isset($data['filter_order_ids']) && !is_null($data['filter_order_ids'])) {
    		$sql .= " AND o.order_id in ('" .implode( "','",$data['filter_order_ids'])  . "')";
    	}
    
    	if (isset($data['filter_partner_code']) && !is_null($data['filter_partner_code'])) {
    		if(empty($data['filter_partner_code'])){
    			$sql .= " AND (o.partner_code = '0' OR o.partner_code = '')";
    
    		}else {
    			$sql .= " AND o.partner_code = '" . $data['filter_partner_code'] . "'";
    		}
    	}
    
    	if (isset($data['filter_source_from']) && !is_null($data['filter_source_from'])) {
    		$sql .= " AND o.source_from = '" . (int)$data['filter_source_from'] . "'";
    	}
    
    	if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
    		$sql .= " AND CONCAT(email) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    	}
    
    	$filter_customer_phone = $data['filter_customer_phone'];
    	if (isset($filter_customer_phone) && !is_null($filter_customer_phone)) {
    		$sql .= " AND CONCAT(IFNULL(o.telephone,''),IFNULL(o.shipping_mobile,''),IFNULL(o.shipping_phone,'')) LIKE '%" . $this->db->escape($data['filter_customer_phone']) . "%'";
    	}
    
    	if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
    		$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    	}
    
    	if (isset($data['filter_date_pick']) && !is_null($data['filter_date_pick'])) {
    		$sql .= " AND (o.pdate = '" . $this->db->escape($data['filter_date_pick']) . "' or date_format(o.shipping_time,'%Y-%m-%d')= '" . $this->db->escape($data['filter_date_pick']) . "')";
    	}
    
    
    	if (isset($data['filter_payment_code']) && !is_null($data['filter_payment_code'])) {
    		$sql .= " AND t.payment_code = '{$data['filter_payment_code']}' ";
    	}
    
    	if (isset($data['order_type']) && !is_null($data['order_type'])) {
    		$sql .= " AND order_type = {$data['order_type']} ";
    	}
    
    	return $sql;
    }
    public function getOrderRefunds($data) {
    	$sql = "SELECT t.*,op.payment_trade_no,op.payment_code as payment_code1,op.value as value1,o.date_added,o.telephone,o.shipping_mobile,o.customer_id,o.total,o.date_added,o.date_modified,o.order_type,o.shipping_time,o.pdate,o.partner_code,o.source_from FROM " . DB_PREFIX . "order_refund t
                LEFT JOIN " . DB_PREFIX . "order_payment op ON t.order_payment_id=op.order_payment_id
    		    LEFT JOIN " . DB_PREFIX . "order o ON t.order_id=o.order_id";
    	
    	$sql.=$this->getfilters($data);
    	
    	$sort_data = array(
    			'o.date_added',
    			'o.pdate',
    			'o.order_id',
    			'customer',
    			'status',
    			'o.total'
    	);
		if (isset ( $data ['sort'] ) && $data ['sort'] == 'o.order_id') {
			$sql .= " ORDER BY o.order_id " . $data ['order'] . " ";
		} else if (isset ( $data ['sort'] ) && $data ['sort'] == 'o.date_added') {
			$sql .= " ORDER BY o.date_added " . $data ['order'] . ", o.order_id DESC ";
		} else if (isset ( $data ['sort'] ) && $data ['sort'] == 'o.pdate') {
			$sql .= " ORDER BY o.pdate " . $data ['order'] . ", o.order_id DESC";
		} else {
			$sql .= " ORDER BY o.date_added DESC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}

    	$query = $this->db->query($sql,false);

    	return $query->rows;
    }
    public function getTotalOrderRefunds($data = array()) {

    	$sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "order_refund t
    			 LEFT JOIN " . DB_PREFIX . "order o  ON t.order_id=o.order_id";
    	$sql.=  $this->getfilters($data);
    
    	$query = $this->db->query($sql);
    
    	return $query->row['total'];
    }
   
    public function updatePhase1($order_refund_id, $status, $refuseReason = '') {
        $this->load->model('sale/order');

        $data = array(
            array('status', $status, true),
            array('phase1_refused_reason', $refuseReason, true),
            array('phase1_updated_at', 'now()', false),
            array('phase1_user_name', $this->user->getUserName(), true)
        );
        $pkArr = array(
            array('order_refund_id',"('".implode( "','",$order_refund_id)."')", false,false,'in'),
        );
        DbHelper::update('order_refund', $pkArr, $data);
/*
        $order_info = $this->model_sale_order->getOrder($order_id);
        if ($status == 'PHASE1_REFUSED') {
            //$this->sendSms($order_id,$order_info['telephone'],'很抱歉，您的订单退款申请被拒,原因: '.$refuseReason);
        }*/
    }

    private function updateOrder2Refunded($order_id){
        $data = array(
            array('order_status_id', 11, false),
        );
        $pkArr = array(
            array('order_id', $order_id, true),
        );
        DbHelper::update('order', $pkArr, $data);

        $history=array(
            'order_status_id'=>11,
            'notify'=>1,
            'comment'=>'已处理退款申请'

        );
        $this->model_sale_order-> insertHistoryData($order_id,$history);
    }

    public function updatePhase2($order_refund_id, $status, $refuseReason = '') {
        $this->load->model('sale/order');

        $data = array(
            array('status', $status, true),
            array('phase2_refused_reason', $refuseReason, true),
            array('phase2_updated_at', 'now()', false),
            array('phase2_user_name', $this->user->getUserName(), true)
        );
        $pkArr = array(
           array('order_refund_id',"('".implode( "','",$order_refund_id)."')", false,false,'in'),
        );
        DbHelper::update('order_refund', $pkArr, $data);

       /*
        $order_info = $this->model_sale_order->getOrder($order_id);
        if ($status == 'PHASE2_REFUSED') {
            $this->sendSms($order_id,$order_info['telephone'],'很抱歉，您的订单退款申请被拒,原因: '.$refuseReason);
        } else {
            $this->updateOrder2Refunded($order_id);
            $this->sendSms($order_id,$order_info['telephone'],'您的订单退款申请已经受理，请留意之后的银行通知');
        }
        */

    }
    
    public function updateStatus($order_refund_id, $status,$comment) {
    	
    	$data = array(
    			array('status', $status, true),
    			array('modify_at', 'now()', false)
    	);
    	
    	if($comment)$data[]=array('comment', $comment, true);
    	
    	if(is_array($order_refund_id)){
    	$pkArr = array(
    			 array('order_refund_id',"('".implode( "','",$order_refund_id)."')", false,false,'in')//支持批量id传递
    	);
    	}
    	else 
    	{
    	  $pkArr = array(
    			array('order_refund_id', $order_refund_id, true),
    	  );
    	}
    	DbHelper::update('order_refund', $pkArr, $data);

    	$history=array(
    			'order_status_id'=>11,
    			'notify'=>0,
    			'comment'=>$comment
    	
    	);
    	//$this->load->model('sale/order');
    	//$this->model_sale_order->insertHistoryData($order_id,$history,$this->user->getUserName());
    	
    }

    private function sendSms($order_id,$mobile_no,$msg){
//        $language = new Language($order_info['language_directory']);
//        $language->load($order_info['language_filename']);
//        $language->load('sms/order');

        if(SMS_OPEN=='ON'){
            $pdate= new DateTime();

            $this->log_admin->debug('IlexDebug:: Send SMS for order '.$order_id);
            if($mobile_no!=''&&SMS_OPEN=='ON'){
                $mobilephone=trim($mobile_no);
                //手机号码的正则验证
                if(mobile_check($mobilephone)){
                    // send sms
                     $sms=new Sms();

                    $this->log_admin->debug('IlexDebug:: 发送短信: '.$msg);

                    $msg =$msg;
					$sms->send($mobilephone, $msg);
                    $this->log_admin->debug('IlexDebug::Already Sended SMS for order '.$order_id);
                    $this->log_admin->debug('IlexDebug::Already Sended SMS '.$mobilephone.',content '.$msg);
                    return true;
                }else{
                    //手机号码格式不对
                    $this->log_admin->debug('IlexDebug:: Wrong Number,dun send sms : sub_order_id '.$order_id);
                    return false;
                }
            }
        }else{
            $this->log_admin->debug('IlexDebug:: SMS_OPEN :'.SMS_OPEN.' sub_order_id '.$order_id);
            return false;
        }
    }
}

?>