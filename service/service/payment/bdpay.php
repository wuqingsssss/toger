<?php 
class ServicePaymentBdpay extends Service {
  	public function getMethod($address,$total=0) {
		$this->load->language('payment/bdpay');
		
		if ($this->config->get('bdpay_status')&& $total > EPSILON) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'bdpay',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('bdpay_sort_order')
      		);
    	}
	
    	return $method_data;
  	}
  	public function create_pay_url($order_id,$type='pc')
  	{
  		include_once("bdpayphp/bfb_sdk.php");
  		include_once("bdpayphp/bfb_pay.cfg.php");
  		$this->load->model('checkout/order');
  		
  		$order_id = $this->session->data['order_id'];
  		$order_info = $this->model_checkout_order->getOrder($order_id);
  		$currency_code ='CNY';
  		
  		$total = $order_info['total'];
  		
  		$currency_value = $this->currency->getValue($currency_code);
  		$amount = $total * $currency_value;
  		$amount = number_format($amount, 2, '.', '');
  		
  		//支付参数设置
  		$appid = $this->config->get('bdpay_appid');
  		
  		/*构造百度数据包,字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档*/
  		$bfb_sdk = new bfb_sdk();
  		$bfb_sdk->sp_key=$this->config->get('bdpay_apikey');
  		
  		//print_r($bfb_sdk->sp_key);
  		$order_create_time = date("YmdHis",strtotime($order_info['date_added']));//转换时间为百度支持模式
  			
  		$expire_time = date('YmdHis', $order_info['expire_time']);//交易过期时间修改时间20150323
  		$order_no = $order_info['order_id'];
  		$goods_category = '';/*与优惠券相关。在创建优惠券推广计划的时候，可以指定优惠券使用的商品类型。若订单的类型与优惠券的类型一致时，就会出相应的优惠券。没有指定时，出用户可用的全部优惠券。取值由钱包系统分配*/
  		$good_name = iconv("UTF-8", "GBK", urldecode('青年菜君－订单:'.$order_no));
  		$good_desc = iconv("UTF-8", "GBK", urldecode('请您在提交订单后30分钟内完成支付，否则订单会自动取消'));
  		$goods_url = HTTPS_SERVER;//商品在商户网站上的URL
  		$unit_amount = $amount*100;//商品单价以分为单位
  		$unit_count = 1;//商品数量
  		$transport_amount = 0;//运费
  		$total_amount = $amount*100;//总价分为单位
  		$buyer_sp_username =iconv("UTF-8", "GBK", urldecode($order_info['firstname'].$order_info['lastname']));  ;
  		$return_url = HTTPS_SERVER . 'catalog/controller/payment/bdpay_notify.php';//后台通知地址
  		$page_url = HTTPS_SERVER . 'index.php?route=checkout/success';//前台通知地址
  		$pay_type = 1;
  		$bank_no = 201;/*默认银行 201招行 11银联 101中国工商银行 201中国招商银行 301中国建设银行 401中国农业银行 501中信银行 601浦东发展银行 701中国光大银行 801深圳发展银行 1101交通银行 1201中国银行 13	银联在线UPOP
  		1901广发银行 1902中国邮政储蓄银行 1903中国民生银行 1904华夏银行 1905兴业银行 1906上海银行 1907上海农商银行 1908中国银行大额 1909北京银行 1910北京农商银行*/
  		$sp_uno = $order_info['customer_id'];//用户在本站的唯一id
  		$extra = '';//自定义数据
  		
  		
  		
  		
  		// 构造商户请求支付接口的表单参数
  		$params = array (
  				'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
  				'sp_no' => $appid,
  				'order_create_time' => $order_create_time,
  				'order_no' => $order_no,
  				'goods_category' => $goods_category,
  				'goods_name' => $good_name,
  				'goods_desc' => $good_desc,
  				'goods_url' => $goods_url,
  				'unit_amount' => $unit_amount,
  				'unit_count' => $unit_count,
  				'transport_amount' => $transport_amount,
  				'total_amount' => $total_amount,
  				'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
  				'buyer_sp_username' => $buyer_sp_username,
  				'return_url' => $return_url,
  				'page_url' => $page_url,
  				'pay_type' => $pay_type,
  				'bank_no' => $bank_no,
  				'expire_time' => $expire_time,
  				'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
  				'version' => sp_conf::BFB_INTERFACE_VERSION,
  				'sign_method' => sp_conf::SIGN_METHOD_MD5,
  				'extra' =>$extra
  		);
  		
  		if($type=='m')
  		$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_WAP_DIRECT_URL);//BFB_PAY_WAP_DIRECT_URL移动支付
  		else 
  		$action = $bfb_sdk->create_baifubao_pay_order_url($params,sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL);
		
  		return $action;
  		
  		
  	}
  	
  	public function reFund($order_info){

  		require_once('bdpayphp/bdpay_sdk.php');

  		error_reporting(0);
  		$bdpay_sdk = new bdpay_sdk();
  		$bdpay_sdk->sp_key=$this->config->get('bdpay_apikey');
  		$output_type = 1;
  		$output_charset = 1;
  		$return_url =  HTTPS_SERVER . 'catalog/controller/payment/bdpay_notify.php';//后台通知地址;
  		$sp_refund_no =$order_info['order_id'];//退款流水号date("YmdHis"). sprintf ( '%06d', rand(0, 999999))
  		$order_no = $order_info['order_id'];
  		$return_method= 2;
  		$cashback_amount = intval($order_info["total"]*100+0.5);
  		$cashback_time= date("YmdHis");

  		$params = array (
  				'service_code' => sp_conf::BFB_REFUND_INTERFACE_SERVICE_ID,
  				'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
  				'sign_method' => sp_conf::SIGN_METHOD_MD5,
  				'output_type' => $output_type,
  				'output_charset' => $output_charset,
  				'return_url' => $return_url,
  				'return_method' => $return_method,
  				'version' =>  sp_conf::BFB_INTERFACE_VERSION,
  				'sp_no' => $this->config->get('bdpay_appid'),
  				'order_no'=>$order_no,
  				'cashback_amount' => $cashback_amount,
  				'cashback_time' => $cashback_time,
  				'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
  				'sp_refund_no' => $sp_refund_no
  		);

  		$refundResult = $bdpay_sdk->create_baifubao_Refund_url($params, sp_conf::BFB_REFUND_URL);

  		if($refundResult['ret_code']!='1'){
  			$this->log('create the url for baifubao pay interface failed','bdpay.log');
  			$this->log_payment->info('create the url for baifubao pay interface failed'.serialize($refundResult));
  		}
  		else {
  			$this->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', serialize($refundResult)),'bdpay.log');
  			$this->log_payment->info(sprintf('create the url for baifubao pay interface success, [URL: %s]', serialize($refundResult)));
  		}
  
  		return $refundResult;
  	}
  	public function reFundQuery($order_info){
  	
  		//加载微信支付基类
  		include_once("bdpayphp/bfb_sdk.php");
  		include_once("bdpayphp/bfb_pay.cfg.php");
  		$this->log_payment->info($refundResult);
  		$this->log_payment->info('refundResult::'.$msg);
  		return $refundResult;
  	}
  	public function reFundReturn($order_info){
  		 
  		//加载微信支付基类
  		include_once("bdpayphp/bfb_sdk.php");
  		include_once("bdpayphp/bfb_pay.cfg.php");
  		$this->log_payment->info($refundResult);
  		$this->log_payment->info('refundResult::'.$msg);
  		return $refundResult;
  	}
  	
  	
  	public function queryOrderPay($order_id){
  		//加载微信支付基类
  		include_once("bdpayphp/bfb_sdk.php");
  		include_once("bdpayphp/bfb_pay.cfg.php");
  		
  		
  		//支付参数设置
  		$appid = $this->config->get('bdpay_appid');
  		
  		/*构造百度数据包,字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档*/
  		$bfb_sdk = new bfb_sdk();
  		$bfb_sdk->sp_key=$this->config->get('bdpay_apikey');
  		
  		
  		$order_no =$order_id;
  		
  		/*
  		 * 字符编码转换，百付宝默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档
  		 * 步骤：
  		 * 1. URL转码
  		 * 2. 字符编码转码，转成GBK
  		 *
  		 * $good_name = iconv("UTF-8", "GBK", urldecode($good_name));
  		 * $good_desc = iconv("UTF-8", "GBK", urldecode($good_desc));
  		 *
  		*/
  		
  		// 用于测试的商户请求支付接口的表单参数，具体的表单参数各项的定义和取值参见接口文档
  		
  		$content = $bfb_sdk->query_baifubao_pay_result_by_order_no($order_no);
  		
  		if(false === $content){
  			$bfb_sdk->log('create the url for baifubao query interface failed');
  		}
  		else {
  			$bfb_sdk->log('create the url for baifubao query interface success');
  			echo "查询成功\n";
  			echo $content;
  		}
  		return $refundQueryResult;
  		 
  	}
}
?>