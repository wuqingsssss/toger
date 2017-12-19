<?php
class ControllerPaymentAlipay extends Controller {
	public function alipayWapNotify() {
		$this->load->service('payment/alipay/alipay');
		$res = $this->service_payment_alipay_alipay->alipayWapNotify();
		$this->alipayNotify($res);
	}
	public function alipayAppNotify() {
		$this->load->service('payment/alipay/alipay');
		$res = $this->service_payment_alipay_alipay->alipayAppNotify();
        $this->alipayNotify($res);
	}
	
	private  function alipayNotify($res){
		//trade_create_by_buyer 双接口 ,create_direct_pay_by_user 直接到帐，create_partner_trade_by_buyer 担保接口
		$trade_type = $this->config->get('alipay_trade_type');
		$pay_code = 'alipay';
		
		$order_id = $res["out_trade_no"];
		//支付宝交易号
		$trade_no = $res["trade_no"];
		//交易状态
		$trade_status = $res["trade_status"];
		
		if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
			$this->load->model('checkout/order');
			//$order_info = $this->model_checkout_order->getOrder($order_id);
			//支付宝支付信息（因为有混合支付，必须从ORDER_PAYMENT获取金额）
			$payment_info = $this->model_checkout_order->getOrderPayment($order_id, 'alipay');
		
			$this->log_payment->info("Alipay order_id :: ".$order_id);
			if ($payment_info) {
				$this->log_payment->info("Alipay order_id :: ".$order_id." order_payment_status = ".$payment_info['status']." , trade_status :: ".$trade_status);
					
				// 确定订单没有重复支付
				if ($payment_info['status'] == 0) {//仅待付款状态订单可以付款
					// 根据接口类型动态使用支付方法
					if($trade_type=='trade_create_by_buyer'){
						$this->func_trade_create_by_buyer($order_id,  $pay_code,$trade_status,$trade_no);
						echo "success";
					}else if($trade_type=='create_direct_pay_by_user'){
						$this->func_create_direct_pay_by_user($order_id, $pay_code,$trade_status,$trade_no);
						echo "success";
					}else if($trade_type=='create_partner_trade_by_buyer'){
						$this->func_create_partner_trade_by_buyer($order_id, $pay_code,$trade_status,$trade_no);
						echo "success";
					}
					$this->log_payment->info("Alipay success order_id :: ".$order_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
		
				}else {
					echo "fail";
					$this->log_payment->info("Alipay fail order_id :: ".$order_id." , trade_status :: ".$trade_status.',$trade_no:'.$trade_no);
		
				}
			}
			else{
				$this->log_payment->info("Alipay No Order Found order_id:".$order_id);
		
				echo "fail";
			}
		}else{
			$this->log_payment->info("Alipay fail Status=".$trade_status);
			echo "fail";
		}

	}
	
	public function refundnotify() {			
		$this->load->service('payment/alipay/alipay');
    	$res = $this->service_payment_alipay_alipay->reFundNotify();
    	$this->log_payment->info('批量退款结果：'.':alipay:'.serialize($res));
    	if ($res ['return_code'] == 'success') {
			$res ['batch_no'];
			$res ['success_num'];
			$res ['result_details'];
			
			$this->load->model ( 'sale/order_refund' );
			
			foreach ( $res ['result_details'] as $refinfo ) {
				/* 订单状态修改变更逻辑 */
				
				
					$refundinfo = $this->model_sale_order_refund->getOrderRefundByTradeNo ( $refinfo ['order_trade_no'], $res ['batch_no'] );
					if ($refundinfo&&$refundinfo ['status'] = 'PAYING') {
						if ($refinfo ['status'] == 'SUCCESS') { // 退款成功的处理
				
							$this->model_sale_order_refund->updateStatus ( $refundinfo ['order_refund_id'], 'DONE' );
							
							/* 此处增加退款订单处理逻辑,也可以定时批量处理 */
							$this->load->model ( 'checkout/order', 'service' );
							$this->model_checkout_order->confirmOrderRefund ( $refund_info ['order_id'] );
		
					} else { // 退款失败的处理
							
							$this->model_sale_order_refund->updateStatus ( $refundinfo ['order_refund_id'], 'ERROR' ,$refund_info['status']);
						}
				}
			}
		}  
	
    	echo $res['return_code'];
    	
	}	
	public function batchTransNotify() {
		$this->load->service('payment/alipay/alipay');
		$res = $this->service_payment_alipay_alipay->batchTransNotify();
		$this->log_payment->info('批量付款结果：'.':alipay:'.serialize($res));
		if ($res['return_code']=='success') {
			$res['batch_no'];
			$res['success_num'];
			$res['result_details'];
	
			$this->load->model('sale/order_refund');
			$this->load->model('checkout/order');
			foreach($res['result_details'] as $refinfo){
				/* 订单状态修改变更逻辑*/
				$refundinfo=$this->model_sale_order_refund->getOrderRefund($refinfo['order_refund_id']);
				
				if($refundinfo&&$refundinfo ['status'] = 'PAYING')
				{
				if($refinfo['status']=='S')
				{//退款成功的处理

						$this->model_sale_order_refund->updateStatus($refundinfo['order_refund_id'],'DONE');
							
						/*此处增加退款订单处理逻辑,也可以定时批量处理*/
						
						$this->model_checkout_order->confirmOrderRefund($refundinfo['order_id']);
					
				}
				else
				{//退款失败的处理
	
					$this->model_sale_order_refund->updateStatus($refundinfo['order_refund_id'],'ERROR',$refund_info['status'].':'.$refund_info['message']);
	
				}
				}
			}
		}
		
		echo $res['return_code'];
		 
	}
	
	// 双接口
	private function func_trade_create_by_buyer($order_id,  $pay_code, $trade_status, $trade_no){
		if($trade_status == 'WAIT_BUYER_PAY') {
			$this->log_payment->debug("Alipay order_id :: ".$order_id." WAIT_BUYER_PAY");
			$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
			$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		}
		/*else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_payment_status");
		 //$this->model_checkout_order->confirm($order_id,  $pay_code, $this->config->get('alipay_order_status_id'),'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
		 $this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		 }
		 else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS,update order_payment_status");
		 $this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
	
		 }
		 else if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED / TRADE_SUCCESS, update order_payment_status");
		 $this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		 }*/
	}
	
	// 直接到帐
	private function func_create_direct_pay_by_user($order_id, $pay_code, $trade_status, $trade_no){
		if($trade_status == 'TRADE_FINISHED' ||$trade_status == 'TRADE_SUCCESS') {
			$this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status ==TRADE_FINISHED / TRADE_SUCCESS,  update order_payment_status");
			$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
			$this->log_payment->debug("Alipay order_id :: ".$order_id." update order_payment_status");
		}
	}
	
	// 双接口
	private function func_create_partner_trade_by_buyer($order_id, $pay_code,$trade_status,$trade_no){
		if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {
			$this->log_payment->debug("Alipay order_id :: ".$order_id."  trade_status ==  WAIT_BUYER_PAY,  update order_payment_status");
			$this->model_checkout_order->confirmOrderPayment($order_id,  $pay_code, $trade_no);
			$this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		}
		/*			else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_SELLER_SEND_GOODS, update order_payment_status");
		 $this->model_checkout_order->confirm($order_id,  $pay_code, $this->config->get('alipay_order_status_id'),'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		 }
		 else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == WAIT_BUYER_CONFIRM_GOODS, update order_payment_status");
		 $this->model_checkout_order->confirm($order_id,  $pay_code, $order_status['Shipped'],'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		 }
		 else if($trade_status == 'WAIT_BUYER_PAY' ) {
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." trade_status == TRADE_FINISHED ,update order_payment_status");
		 $this->model_checkout_order->confirm($order_id,  $pay_code, $order_status['Complete'],'alipay::trade_no:'.$trade_no,array('trade_no'=>$trade_no));
		 $this->log_payment->debug("Alipay order_id :: ".$order_id." Update Successfully.");
		 }*/
	}
		
}
?>