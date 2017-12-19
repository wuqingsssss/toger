<?php 
class ServicePaymentWxpay extends Service {
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
  		include_once("wxpay_class/WxPayHelper.php");
  		 
  		$appid = $this->config->get('wxpay_appid');
  		$appsecret = $this->config->get('wxpay_appsecret');
  		$partnerid = $this->config->get('wxpay_partnerid');
  		$apikey = $this->config->get('wxpay_apikey');
  		 
  		$out_trade_no = $order_info['order_id'];
  		 
  		$refund_fee = intval($order_info["total"]*100+0.5);//退款金额
  		//商户退款单号，商户自定义，此处仅作举例
  		$out_refund_no = $out_trade_no;//.date('Ymdhis', time())
  		//总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
  		$total_fee = $refund_fee;//订单金额
  	    $this->log_payment->info(print_r($order_info,1));
  		//使用退款接口
  		$refund = new Refund($appid, $appsecret, $partnerid, $apikey);

  		//设置必填参数
  		//appid已填,商户无需重复填写
  		//mch_id已填,商户无需重复填写
  		//noncestr已填,商户无需重复填写
  		//sign已填,商户无需重复填写
  		$refund->setParameter("out_trade_no","$out_trade_no");//商户订单号
  		$refund->setParameter("out_refund_no","$out_refund_no");//商户退款单号
  		$refund->setParameter("total_fee","$total_fee");//总金额
  		$refund->setParameter("refund_fee","$refund_fee");//退款金额
  		$refund->setParameter("op_user_id",$partnerid);//操作员/商户id
  		//非必填参数，商户可根据实际情况选填
  		//$refund->setParameter("sub_mch_id","XXXX");//子商户号
  		//$refund->setParameter("device_info","XXXX");//设备号
  		//$refund->setParameter("transaction_id","XXXX");//微信订单号
  		 
  		//调用结果
  		$refundResult = $refund->getResult();
  	
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
  			$msg.= "退款金额：".$refundResult['refund_fee']."<br>";
  			$msg.= "现金券退款金额：".$refundResult['coupon_refund_fee']."<br>";
  		}
  		$this->log_payment->info($refundResult);
  		$this->log_payment->info('refundResult::'.$msg);
  		return $refundResult;
  	}
  	public function reFundQuery($order_id){
  		//加载微信支付基类
  		include_once("wxpay_class/WxPayHelper.php");
  	
  		$appid = $this->config->get('wxpay_appid');
  		$appsecret = $this->config->get('wxpay_appsecret');
  		$partnerid = $this->config->get('wxpay_partnerid');
  		$apikey = $this->config->get('wxpay_apikey');
  	
  		$out_trade_no = $order_id;
  	
  		//使用退款查询接口
  		$refundQuery = new RefundQuery($appid, $appsecret, $partnerid, $apikey);
  		//设置必填参数
  		//appid已填,商户无需重复填写
  		//mch_id已填,商户无需重复填写
  		//noncestr已填,商户无需重复填写
  		//sign已填,商户无需重复填写
  		$refundQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号
  		// $refundQuery->setParameter("out_refund_no","XXXX");//商户退款单号
  		// $refundQuery->setParameter("refund_id","XXXX");//微信退款单号
  		// $refundQuery->setParameter("transaction_id","XXXX");//微信退款单号
  		//非必填参数，商户可根据实际情况选填
  		//$refundQuery->setParameter("sub_mch_id","XXXX");//子商户号
  		//$refundQuery->setParameter("device_info","XXXX");//设备号
  	
  		//退款查询接口结果
  		$refundQueryResult = $refundQuery->getResult();
  	
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
  			$msg.= "退款笔数：".$refundQueryResult['refund_count']."<br>";
  			$msg.= "商户退款单号：".$refundQueryResult['out_refund_no']."<br>";
  			$msg.= "微信退款单号：".$refundQueryResult['refund_idrefund_id']."<br>";
  			$msg.= "退款渠道：".$refundQueryResult['refund_channel']."<br>";
  			$msg.= "退款金额：".$refundQueryResult['refund_fee']."<br>";
  			$msg.= "现金券退款金额：".$refundQueryResult['coupon_refund_fee']."<br>";
  			$msg.= "退款状态：".$refundQueryResult['refund_status']."<br>";
  		}
  		$this->log_payment->info($refundQueryResult);
  		$this->log_payment->info('reFundQuery::'.$msg);
  		return $refundQueryResult;
  	
  	}
}
?>