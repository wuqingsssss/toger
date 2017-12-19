<?php 
class ServicePaymentAlipay extends Service {
  	public function getMethod($address,$total=0) {
		$this->load->language('payment/alipay');
		
		if ($this->config->get('alipay_status') && $total > EPSILON) {
      		$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'         => 'alipay',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('alipay_sort_order')
      		);
    	}
	
    	return $method_data;
  	}
  	public function reFund($order_info){
  	
  		error_reporting(E_ALL);
  	
  		require_once("alipay_wap/alipay.config.php");
  		require_once("alipay_wap/lib/alipay_submit.class.php");
  		$cacert_url= DIR_ROOT ."service/model/payment/alipay_wap/cacert.pem";
  		 
  		/**************************请求参数**************************/
  		 
  		//服务器异步通知页面路径
  		$notify_url = HTTP_SERVER.DIR_API."controller/payment/notify_url.php";
  		$notify_url = HTTP_SERVER.DIR_API."index.php?route/payment/notify";
  		//需http://格式的完整路径，不允许加?id=123这类自定义参数
  	
  		$security_code = $this->config->get('alipay_security_code');
  		$partner = $this->config->get('alipay_partner');
  	
  		//卖家支付宝帐户
  		$seller_email = $this->config->get('alipay_seller_email');
  		//必填
  	
  		//退款当天日期
  		$refund_date = date('YmdHis',time());
  		//必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
  	
  		//批次号
  		$batch_no = date('Ymd',time()).$order_info['order_id'];
  		//必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
  	
  		//退款笔数
  		$batch_num = 1;
  		//必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
  	
  		//退款详细数据
  		$detail_data =($order_info['order_id'].'^'.sprintf("%.2f", $order_info["total"]).'^全额退款');
  		//必填，具体格式请参见接口技术文档
  	
  	
  		/************************************************************/
  	
  		//构造要请求的参数数组，无需改动
  		$parameter = array(
  				"service" => "refund_fastpay_by_platform_pwd",//refund_fastpay_by_platform_nopwd
  				"partner" => $partner,
  				"notify_url"	=> $notify_url,
  				"seller_email"	=> $seller_email,
  				"refund_date"	=> $refund_date,
  				"batch_no"	=> $batch_no,
  				"batch_num"	=> $batch_num,
  				"detail_data"	=> $detail_data,
  				'_input_charset'=>'utf-8'
  		);
  	
  		//建立请求
  		$alipaySubmit = new AlipaySubmit($alipay_config);
  		$html_text = $alipaySubmit->buildRequestHttp($parameter);//buildRequestHttp/buildRequestForm
  		echo $html_text;
  			
  		return $html_text;
  	}
  	public function reFundQuery($order_id){
  		//加载微信支付基类
  		require_once("alipay_wap/alipay.config.php");
  	
  		require_once("alipay_wap/lib/lib/alipay_notify.class.php");
  	
  		$alipay_config['partner']		= $this->config->get('alipay_partner');
  		//安全检验码，以数字和字母组成的32位字符
  		//如果签名方式设置为“MD5”时，请设置该参数
  		$alipay_config['key']			= $this->config->get('alipay_key');
  	
  		$alipayNotify = new AlipayNotify($alipay_config);
  		$verify_result = $alipayNotify->verifyNotify();
  		 
  		if($verify_result) {//验证成功
  			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  			//请在这里加上商户的业务逻辑程序代
  	
  	
  			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
  	
  			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
  	
  			//批次号
  	
  	
  	
  			$refundQueryResult['batch_no'] = $_POST['batch_no'];
  	
  			//批量退款数据中转账成功的笔数
  	
  			$refundQueryResult['success_num'] = $_POST['success_num'];
  	
  			//批量退款数据中的详细信息
  			$refundQueryResult['result_details'] = $_POST['result_details'];
  	
  	
  			$refundQueryResult['return_code']='success';
  	
  	
  			//判断是否在商户网站中已经做过了这次通知返回的处理
  			//如果没有做过处理，那么执行商户的业务程序
  			foreach($batch_no as $order_id){
  	
  				echo $order_id;
  			}
  	
  			//如果有做过处理，那么不执行商户的业务程序
  	
  			//调试用，写文本函数记录程序运行情况是否正常
  			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
  	
  			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
  	
  			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		}
  		else {
  	
  			$refundQueryResult['return_code']='fail';
  			//验证失败
  			//调试用，写文本函数记录程序运行情况是否正常
  			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
  		}
  		return $refundQueryResult;
  		 
  	}
  	
  	
  	public function singleTradeQuery($order_info){
  		 
  		error_reporting(E_ALL);

  		require_once("alipay_wap/alipay.config.php");
  		require_once("alipay_wap/lib/alipay_submit.class.php");
  		
  		$cacert_url= DIR_ROOT ."service/service/payment/alipay_wap/cacert.pem";
  			
  		/**************************请求参数**************************/

  		$url="https://mapi.alipay.com/gateway.do";
  		//需http://格式的完整路径，不允许加?id=123这类自定义参数
  		 
  		$security_code = $this->config->get('alipay_security_code');
  		$partner = $this->config->get('alipay_partner');
  		 
  		 print_r($partner);
  		  print_r($security_code);
  		//批次号
  		$out_trade_no = $order_info['order_id'];
  		//必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
  		//卖家支付宝帐户
  		$seller_email = $this->config->get('alipay_seller_email');
  		   
  		/************************************************************/
  		 
  		//构造要请求的参数数组，无需改动
  		$parameter = array(
  				"service" => "single_trade_query",//refund_fastpay_by_platform_nopwd
  				"seller_email"	=> $seller_email,
  				"partner" => $partner,
  				"out_trade_no"	=> $out_trade_no,
  				"_input_charset"=>'utf-8'
  		);
  		
  		$alipaySubmit = new AlipaySubmit($alipay_config);
  		$parameter = $alipaySubmit->buildRequestPara($parameter);//buildRequestHttp/buildRequestForm

  		 
  		//建立请求
 $html_text=HTTP::getSSLGET($url,$cacert_url,$parameter);
  			print_r($html_text);
  		return $html_text;
  	}
}
?>