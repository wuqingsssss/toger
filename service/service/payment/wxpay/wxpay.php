<?php 
class ServicePaymentWxpayWxpay extends Service {
  	public function getMethod($address,$total=0) {
		$this->load->language('payment/wxpay');
		
		if ($this->config->get('wxpay_status') && $total > 0) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'wxpay',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('wxpay_sort_order')
      		);
    	}
	
    	return $method_data;
  	}
  	public function reFund($order_info){
  		
  		//加载微信支付基类
  		require_once "lib/WxPay.Api.php";
  		
  		$appid = $this->config->get('wxpay_appid');
  		$appsecret = $this->config->get('wxpay_appsecret');
  		$partnerid = $this->config->get('wxpay_partnerid');
  		$apikey = $this->config->get('wxpay_apikey');
  		 
  		$out_trade_no = $order_info['order_id'];
  		 
  		$refund_fee = (int)($order_info["value"]*100+0.05);//退款金额
  		//商户退款单号，商户自定义，此处仅作举例
  		$out_refund_no = $order_info['order_refund_id'];//.date('Ymdhis', time())
  		//总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
  		$total_fee = $refund_fee;//订单金额
  		
  	    $this->log_payment->info(serialize($order_info));
  	    
  		//使用退款接口
  	   
  		$input = new WxPayRefund();

  		$input->SetAppid($appid);
  		$input->SetMch_id($partnerid);
  		$input->SetKey($apikey);
  		
  		$input->SetOut_trade_no($out_trade_no);
  		$input->SetTotal_fee($total_fee);
  		$input->SetRefund_fee($refund_fee);
  		$input->SetOut_refund_no($out_refund_no);
  		$input->SetOp_user_id($partnerid);
  		
  		$refundResult=WxPayApi::refund($input);

  		$refundResult=array_merge($order_info,$refundResult);

  		//商户根据实际情况设置相应的处理流程,此处仅作举例
  		if ($refundResult["return_code"] == "FAIL") {
  			$msg= "通信出错：".$refundResult['return_msg']."<br>";
  		}
  		else
  		{
  			$msg.= "业务结果：".$refundResult['result_code']."<br>";
  			$msg.= "错误代码：".$refundResult['err_code']."<br>";
  			$msg.= "错误代码描述：".$refundResult['err_code_des']."<br>";
  			$msg.= "公众账号ID：".$refundResult['appid']."<br>";
  			$msg.= "商户号：".$refundResult['mch_id']."<br>";
  			$msg.= "子商户号：".$refundResult['sub_mch_id']."<br>";
  			$msg.= "设备号：".$refundResult['device_info']."<br>";
  			$msg.= "签名：".$refundResult['sign']."<br>";
  			$msg.= "微信订单号：".$refundResult['transaction_id']."<br>";
  			$msg.= "商户订单号：".$refundResult['out_trade_no']."<br>";
  			$msg.= "商户退款单号：".$refundResult['out_refund_no']."<br>";
  			$msg.= "微信退款单号：".$refundResult['refund_idrefund_id']."<br>";
  			$msg.= "退款渠道：".$refundResult['refund_channel']."<br>";
  			$msg.= "退款金额：".($refundResult['refund_fee']/100)."<br>";
  			$msg.= "现金券退款金额：".$refundResult['coupon_refund_fee']."<br>";
  			
  	        $sql = "UPDATE " . DB_PREFIX . "order_refund SET status='PAYING' WHERE order_refund_id='{$order_info['order_refund_id']}';";
  		    $this->db->query($sql);

  		}
  		 $refundResult['message']=$msg;
  		$this->log_payment->info(serialize($refundResult));
  		$this->log_payment->info('refundResult::'.$msg);
  		return $refundResult;
  	}
  	public function reFundQuery($order_info){
  		//加载微信支付基类
  		require_once(DIR_ROOT."service/service/payment/wxpay_class/WxPayHelper.php");
  		$appid = $this->config->get('wxpay_appid');
  		$appsecret = $this->config->get('wxpay_appsecret');
  		$partnerid = $this->config->get('wxpay_partnerid');
  		$apikey = $this->config->get('wxpay_apikey');
  		
  		$out_trade_no = $order_info['order_id'];
  		$out_refund_no=$order_info['order_refund_id'];
  		//使用退款查询接口
  		
  		$refundQuery = new RefundQuery($appid, $appsecret, $partnerid, $apikey);

  		//设置必填参数
  		//appid已填,商户无需重复填写
  		//mch_id已填,商户无需重复填写
  		//noncestr已填,商户无需重复填写
  		//sign已填,商户无需重复填写
  		$refundQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号
  		$refundQuery->setParameter("out_refund_no",$out_refund_no);//商户退款单号
  		// $refundQuery->setParameter("refund_id","XXXX");//微信退款单号
  		// $refundQuery->setParameter("transaction_id","XXXX");//微信退款单号
  		//非必填参数，商户可根据实际情况选填
  		//$refundQuery->setParameter("sub_mch_id","XXXX");//子商户号
  		//$refundQuery->setParameter("device_info","XXXX");//设备号
  	
  		//退款查询接口结果
  		$refundQueryResult = $refundQuery->getResult();

  		$refundQueryResult=array_merge($order_info,$refundQueryResult);
  		
  		//商户根据实际情况设置相应的处理流程,此处仅作举例
  		if ($refundQueryResult["return_code"] == "FAIL") {
  			$msg.= "通信出错：".$refundQueryResult['return_msg']."<br>";
  		}
  		else{
  			$msg.= "业务结果：".$refundQueryResult['result_code']."<br>";
  			$msg.= "错误代码：".$refundQueryResult['err_code']."<br>";
  			$msg.="错误代码描述：".$refundQueryResult['err_code_des']."<br>";
  			$msg.="公众账号ID：".$refundQueryResult['appid']."<br>";
  			$msg.= "商户号：".$refundQueryResult['mch_id']."<br>";
  			$msg.= "子商户号：".$refundQueryResult['sub_mch_id']."<br>";
  			$msg.= "设备号：".$refundQueryResult['device_info']."<br>";
  			$msg.= "签名：".$refundQueryResult['sign']."<br>";
  			$msg.= "微信订单号：".$refundQueryResult['transaction_id']."<br>";
  			$msg.= "商户订单号：".$refundQueryResult['out_trade_no']."<br>";
  			$refundres=array();
  			for($i=0;$i<$refundQueryResult['refund_count'];$i++){
  				
  			$msg.= "退款笔数：".$refundQueryResult['refund_count_'.$i]."<br>";
  			$msg.= "商户退款单号：".$refundQueryResult['out_refund_no_'.$i]."<br>";
  			$msg.= "微信退款单号：".$refundQueryResult['refund_id_'.$i]."<br>";
  			$msg.= "退款渠道：".$refundQueryResult['refund_channel_'.$i]."<br>";
  			$msg.= "退款金额：".($refundQueryResult['refund_fee_'.$i]/100)."<br>";
  			$msg.= "现金券退款金额：".($refundQueryResult['coupon_refund_fee_'.$i]/100)."<br>";
  			$msg.= "退款状态：".$refundQueryResult['refund_status_'.$i]."<br>";
  			$msg.= "入款账户：".$refundQueryResult['refund_recv_accout_'.$i]."<br>";
 
  			$refundres[$refundQueryResult['refund_status_'.$i]][$i]=$refundQueryResult['out_refund_no_'.$i];
 
  			}
  			
  			if(isset($refundres['SUCCESS'])&&count($refundres['SUCCESS'])==$refundQueryResult['refund_count'])
  			{//如果全部成功
  				$sql = "UPDATE " . DB_PREFIX . "order_refund SET status='DONE',comment='{$msg}' WHERE order_refund_id='{$order_info['order_refund_id']}';";
  				$this->db->query($sql);
  				
  				/*此处增加退款订单处理逻辑,也可以定时批量处理*/
  				$this->load->model('checkout/order','service');
  				$this->model_checkout_order->confirmOrderRefund($order_info['order_id']);
  				
  				
  			}elseif(isset($refundres['FAIL'])||$refundQueryResult['result_code']=='FAIL'){
  				//需要重新发起
  				$sql = "UPDATE " . DB_PREFIX . "order_refund SET status='ERROR',comment='{$msg}' WHERE order_refund_id='{$order_info['order_refund_id']}';";
  				$this->db->query($sql);
  				
  			}elseif(isset($refundres['CHANGE'])){
  				//需要重新发起
  				$sql = "UPDATE " . DB_PREFIX . "order_refund SET status='PHASE2_PASSED',comment='{$msg}' WHERE order_refund_id='{$order_info['order_refund_id']}';";
  				$this->db->query($sql);
  				
  			}
  			
  		}
  		$refundQueryResult['message']=$msg;
  		$this->log_payment->info(serialize($refundQueryResult));
  		$this->log_payment->info('reFundQuery::'.$msg);
  		return $refundQueryResult;
  	
  	}
}
?>