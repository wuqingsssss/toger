<?php
class ServicePaymentAlipayAlipay extends Service {
	private $alipay_config;
	// ↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	public function __construct($registry) {
		// header("Access-Control-Allow-Origin: *");//跨域问题
		parent::__construct ( $registry, dirname ( __FILE__ ) );
		
		$this->alipay_config ['security_code'] = $this->config->get ( 'alipay_security_code' );
		// 合作身份者id，以2088开头的16位纯数字
		$this->alipay_config ['partner'] = $this->config->get ( 'alipay_partner' );
		// 安全检验码，以数字和字母组成的32位字符
		// 如果签名方式设置为“MD5”时，请设置该参数
		$this->alipay_config ['key'] = $this->config->get ( 'alipay_security_code' );
		
		$this->alipay_config ['seller_email'] = $this->config->get ( 'alipay_seller_email' );
		
		$this->alipay_config ['account_name'] = '才俊青年（北京）科技有限公司';
		
		$this->alipay_config ['gateway'] = ALIPAY_GATEWAY;
		// 商户的私钥（后缀是.pen）文件相对路径
		// 如果签名方式设置为“0001”时，请设置该参数
		$this->alipay_config ['private_key_path'] = ALIPAY_PRIVATE_KEY_PATH;
		
		// 支付宝公钥（后缀是.pen）文件相对路径
		// 如果签名方式设置为“0001”时，请设置该参数
		$this->alipay_config ['ali_public_key_path'] = ALIPAY_ALI_PUBLIC_KEY_PATH;
		$this->alipay_config ['notify_url'] = ALIPAY_NOTIFY_URL;
		// ↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		// ca证书路径地址，用于curl中ssl校验
		// 请保证cacert.pem文件在当前文件夹目录中
		$this->alipay_config ['cacert'] = ALIPAY_CACERT;
		
		// 访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$this->alipay_config ['transport'] = ALIPAY_TACNSPORT;
		
		// 签名方式 不需修改 0001
		$this->alipay_config ['sign_type'] = 'MD5';
		
		// 字符编码格式 目前支持 gbk 或 utf-8
		$this->alipay_config ['input_charset'] = 'utf-8';
		
		$this->alipay_config['log']= $this->log_payment;
	}
	public function getMethod($address, $total = 0) {
		$this->load->language ( 'payment/alipay' );
		
		if ($this->config->get ( 'alipay_status' ) && $total > EPSILON) {
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		
		$method_data = array ();
		
		if ($status) {
			$method_data = array (
					'code' => 'alipay',
					'title' => $this->language->get ( 'text_title' ),
					'sort_order' => $this->config->get ( 'alipay_sort_order' ) 
			);
		}
		
		return $method_data;
	}
	
	
	public function get_batch_no($batch_no){
		
		$batch_no=$batch_no. str_pad(mt_rand(1, 99999), 11, '0', STR_PAD_LEFT);
		return $batch_no;
	}
	
	public function reFund($refund_list,$submit) {
		
		/**
		 * ************************请求参数*************************
		 */
		
		$notify_url = $this->alipay_config ( 'notify_url' );
		// 需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		$security_code = $this->alipay_config ( 'security_code' );
		
		// 卖家支付宝帐户
		$seller_email = $this->alipay_config ( 'seller_email' );
		// 必填
	
		// 退款当天日期
		$refund_date = date ( 'Y-m-d H:i:s', time () );
		// 必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
		
		// 批次号
		$batch_no =$this->get_batch_no( date ( 'Ymd', time () ));
		// 必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
		
		
		// 必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）

		foreach ( $refund_list as $refund_info ) {
			// 退款详细数据
			if($refund_info['payment_trade_no']){
			$detail_data [] = ($refund_info['payment_trade_no'] . '^' . sprintf ( "%.2f", $refund_info ["value"] ) . '^菜君退款'); // $order_info['order_id']
			
			
			$sql = "UPDATE " . DB_PREFIX . "order_refund SET batch_no='{$batch_no}' WHERE order_refund_id='{$refund_info['order_refund_id']}';";
			$this->db->query($sql);
			
			}                                                                                                          //  //退款详细数据，必填，格式（支付宝交易号^退款金额^备注），多笔请用#隔开
		}
		// 退款笔数
		$batch_num = count ( $detail_data );
		$detail_data = implode ( '#', $detail_data );

		/**
		 * *********************************************************
		 */
		
		// 构造要请求的参数数组，无需改动
		$parameter = array (
				"service" => "refund_fastpay_by_platform_pwd", // refund_fastpay_by_platform_nopwd||refund_fastpay_by_platform_pwd
				"partner" => $this->alipay_config ['partner'],
				"notify_url" => $this->alipay_config ['notify_url'].'/refundnotify.php',
				//"seller_user_id"	=> $this->alipay_config ['partner'],
				"seller_email" => $this->alipay_config ['seller_email'],
				"refund_date" => $refund_date,
				"batch_no" => $batch_no,
				"batch_num" => $batch_num,
				"detail_data" => $detail_data,
				'_input_charset' => $this->alipay_config ['input_charset'] 
		);
		
		$this->log_payment->info (serialize( $parameter) );
		//print_r($parameter);
		/*直接构造get请求* 
	
		 $parameter ['sign'] = Http::make_sign ( $parameter, $this->alipay_config ['key'], 1, 2 );
	     $parameter ['sign_type'] = $this->alipay_config ['sign_type'];
		  
		// $html_text=HTTP::getSSLGET($this->alipay_config['gateway'],$this->alipay_config['cacert'], $parameter);
		// $html_text=HTTP::getGET($this->alipay_config['gateway'], $parameter);
		
		$html_text = HTTP::buildURL ( $this->alipay_config ['gateway'], $parameter );
		$this->log_sys->info ( $html_text );
		return $html_text;
		
		*/
		/*
		 * //建立请求*/
		
        require_once("lib/alipay_submit.class.php");
        $alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,'get',"批量退款到支付宝[{$batch_num}]",$submit);//buildRequestHttp/buildRequestForm
		
		
		
		
		
		return $html_text;
		
		 
	}
	
	
	public function batchTrans($refund_list,$submit) {
	
		/**
		 * ************************请求参数*************************
		 */
	
		$notify_url = $this->alipay_config ( 'notify_url' );
		// 需http://格式的完整路径，不允许加?id=123这类自定义参数
	
		$security_code = $this->alipay_config ( 'security_code' );
	
		// 卖家支付宝帐户
		$seller_email = $this->alipay_config ( 'seller_email' );
		// 必填
	
		// 付款当天日期
		$pay_date = date ( 'Y-m-d H:i:s', time () );
		// 必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
	
		// 批次号
		$batch_no =$this->get_batch_no( date ( 'Ymd', time () ));
		// 必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
	
		//付款总金额
		$batch_fee = 0;
		//必填，即参数detail_data的值中所有金额的总和

		// 必填，参数detail_data的值中，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....
		
	
		foreach ( $refund_list as $refund_info ) {
			// 退款详细数据
			if($refund_info['payment_account']&&$refund_info['payment_name']){
				$detail_data [] = ($refund_info['order_refund_id'] . '^'.$refund_info['payment_account']. '^'.$refund_info['payment_name'] . '^' . sprintf ( "%.2f", $refund_info ["value"] ) . '^菜君退款['.$refund_info ["order_id"].']'); 
					
				$batch_fee+=$refund_info ["value"];
				
				$sql = "UPDATE " . DB_PREFIX . "order_refund SET batch_no='{$batch_no}' WHERE order_refund_id='{$refund_info['order_refund_id']}';";
				$this->db->query($sql);
			}                                                                                                
		}
		
		
		// 退款笔数
		$batch_num = count ( $detail_data );

		$detail_data = implode ( '|', $detail_data );
		
		$batch_fee=sprintf ( "%.2f", $batch_fee);
		/**
		 * *********************************************************
		*/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "batch_trans_notify",
				"partner" => trim($this->alipay_config ['partner']),
				"notify_url"	=> $this->alipay_config ['notify_url'].'/batchtransnotify.php',
				"email"	=> $this->alipay_config ['seller_email'],
				"account_name"	=> $this->alipay_config ['account_name'],
				"pay_date"	=> $pay_date,
				"batch_no"	=> $batch_no,
				"batch_fee"	=> $batch_fee,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim($this->alipay_config ['input_charset'])
		);
		
		
		$this->log_payment->info ( serialize($parameter) );
		//print_r($parameter);
		/*直接构造get请求*
	
		$parameter ['sign'] = Http::make_sign ( $parameter, $this->alipay_config ['key'], 1, 2 );
		$parameter ['sign_type'] = $this->alipay_config ['sign_type'];
	
		// $html_text=HTTP::getSSLGET($this->alipay_config['gateway'],$this->alipay_config['cacert'], $parameter);
		// $html_text=HTTP::getGET($this->alipay_config['gateway'], $parameter);
	
		$html_text = HTTP::buildURL ( $this->alipay_config ['gateway'], $parameter );
		$this->log_sys->info ( $html_text );
		return $html_text;
	
		*/
		/*
		* //建立请求*/

		require_once("lib/alipay_submit.class.php");
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "批量付款到支付宝[{$batch_num}]",$submit);

		return $html_text;
	
			
	}
	
	
	public function alipayAppNotify(){
	
		require_once("lib/alipay_notify.class.php");

		$this->alipay_config['private_key_path']   = DIR_ROOT.'service/service/payment/alipay/key/rsa_private_key_qncj.pem';
        $this->alipay_config['ali_public_key_path']= DIR_ROOT.'service/service/payment/alipay/key/alipay_public_key_app.pem';
        $this->alipay_config['sign_type']='0001';
        $this->alipay_config['transport']= 'http';
		$alipayNotify = new AlipayNotify ( $this->alipay_config );
		$verify_result = $alipayNotify->verifyNotify ();
	
		$this->log_payment->info ('alipayAppNotify:$_POST'.serialize( $_POST));
		if ($verify_result) { // 验证成功
			$refundQueryResult=$_POST;

			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
	
			$refundQueryResult=false;
			// 验证失败
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	
		$this->log_payment->info ('alipayAppNotify:refundQueryResult'.serialize( $refundQueryResult));
		return $refundQueryResult;
	}
	
	public function alipayWapNotify(){
	
		require_once("lib/alipay_notify.class.php");
	
		$this->alipay_config['private_key_path']   = DIR_ROOT.'service/service/payment/alipay/key/rsa_private_key_qncj.pem';
		$this->alipay_config['ali_public_key_path']= DIR_ROOT.'service/service/payment/alipay/key/alipay_public_key_wap.pem';
		$this->alipay_config['sign_type']='0001';
		$this->alipay_config['transport']= 'http';
		
		$this->log_payment->info(serialize($this->alipay_config));
		
		$alipayNotify = new AlipayNotify ($this->alipay_config);
		$verify_result = $alipayNotify->verifyNotifyWap();
	
		$this->log_payment->info ('alipayWapNotify:$_POST'.serialize( $_POST));
		
	    if($verify_result) {
			$notify_data="".$_POST['notify_data'];
			if($this->alipay_config['sign_type']=='0001'){
			  $notify_data = $alipayNotify->decrypt($_POST['notify_data']);
			}
			//urldecode
			$notify_data=str_replace('&gt;','>',str_replace('&lt;','<',$notify_data));
			$this->log_payment->info ($notify_data);
			$refundQueryResult=json_decode(json_encode(simplexml_load_string($notify_data)), true);
			$this->log_payment->info ($refundQueryResult);
			
		} else {
	
			$refundQueryResult =false;
			// 验证失败
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	
		$this->log_payment->info ('alipayWapNotify:refundQueryResult'.serialize( $refundQueryResult));
		return $refundQueryResult;
	}
	public function reFundNotify() {
		// 加载微信支付基类
		require_once("lib/alipay_notify.class.php");

		$alipayNotify = new AlipayNotify ( $this->alipay_config );
		$verify_result = $alipayNotify->verifyNotify ();	
		$this->log_payment->info ('reFundNotify:$_POST:'.serialize( $_POST));
		if ($verify_result) { // 验证成功
		                     // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		                     // 请在这里加上商户的业务逻辑程序代
		                     
			// ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		                     
			// 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
		                     
			// 批次号
			
			$refundQueryResult ['batch_no'] = $_POST ['batch_no'];
			
			// 批量退款数据中转账成功的笔数
			
			$refundQueryResult ['success_num'] = $_POST ['success_num'];
			
			// 批量退款数据中的详细信息
			$result_details= explode('#', $_POST ['result_details']);
			$results=array();
			foreach($result_details as $row){
				
				$refarray=explode('$', $row);
				$refarray1=explode('^', $refarray[0]);
				$refinfo['order_trade_no']=$refarray1[0];
				$refinfo['value']=$refarray1[1];
				$refinfo['status']=$refarray1[2];
				
				if($refarray[1]){
				$refarray2=explode('^', $refarray[1]);
				$refinfo['order_fee_no']=$refarray2[0];
				$refinfo['order_fee_acount']=$refarray2[1];
				$refinfo['order_fee']=$refarray2[2];
				}
				$results[]=$refinfo;
			}
			
			
			$refundQueryResult ['result_details']=$results;
			
			$refundQueryResult ['return_code'] = 'success';
			
			
			// 如果有做过处理，那么不执行商户的业务程序
			
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			
			// ——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			
			$refundQueryResult ['return_code'] = 'fail';
			// 验证失败
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
		$this->log_payment->info ('reFundNotify:refundQueryResult'.serialize( $refundQueryResult));
		return $refundQueryResult;
	}
	
	public function batchTransNotify(){
		
		// 加载微信支付基类
		require_once("lib/alipay_notify.class.php");
		
		$alipayNotify = new AlipayNotify ( $this->alipay_config );
		$verify_result = $alipayNotify->verifyNotify ();
		
		$this->log_payment->info ('batchTransNotify:$_POST'.serialize( $_POST));
		if ($verify_result) { // 验证成功
			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 请在这里加上商户的业务逻辑程序代
			 
			// ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			 
			// 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			 
			// 批次号
				
			$refundQueryResult ['batch_no'] = $_POST ['batch_no'];
				

				
			// 批量退款数据中的详细信息
			$success_details= explode('|', $_POST ['success_details']);
			$results=array();
			foreach($success_details as $row){
		
				$refarray=explode('^', $row);
				$refinfo['order_refund_id']=$refarray[0];
				$refinfo['payment_account']=$refarray[1];
				$refinfo['payment_name']=$refarray[2];
				$refinfo['value']=$refarray[3];
				$refinfo['status']=$refarray[4];
				$refinfo['message']=$refarray[5];
				$refinfo['time']=$refarray[6];

				$results[]=$refinfo;
			}
			$fail_details= explode('|', $_POST ['fail_details']);

			foreach($fail_details as $row){
			
				$refarray=explode('^', $row);
				$refinfo['order_refund_id']=$refarray[0];
				$refinfo['payment_account']=$refarray[1];
				$refinfo['payment_name']=$refarray[2];
				$refinfo['value']=$refarray[3];
				$refinfo['status']=$refarray[4];
				$refinfo['message']=$refarray[5];
				$refinfo['time']=$refarray[6];
			
				$results[]=$refinfo;
			}
				
				
			$refundQueryResult ['result_details']=$results;
				
			$refundQueryResult ['return_code'] = 'success';
				
				
			// 如果有做过处理，那么不执行商户的业务程序
				
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				
			// ——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
				
			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
				
			$refundQueryResult ['return_code'] = 'fail';
			// 验证失败
			// 调试用，写文本函数记录程序运行情况是否正常
			// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
		
		$this->log_payment->info ('batchTransNotify:refundQueryResult'.serialize( $refundQueryResult));
		return $refundQueryResult;
		
		
		
	}
	
	public function singleTradeQuery($order_info) {
		error_reporting ( E_ALL );
		
		require_once ("alipay_wap/alipay.config.php");
		require_once ("alipay_wap/lib/alipay_submit.class.php");
		
		$cacert_url = DIR_ROOT . "service/service/payment/alipay_wap/cacert.pem";
		
		/**
		 * ************************请求参数*************************
		 */
		
		$url = "https://mapi.alipay.com/gateway.do";
		// 需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		$security_code = $this->config->get ( 'alipay_security_code' );
		$partner = $this->config->get ( 'alipay_partner' );
		
		print_r ( $partner );
		print_r ( $security_code );
		// 批次号
		$out_trade_no = $order_info ['order_id'];
		// 必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
		// 卖家支付宝帐户
		$seller_email = $this->config->get ( 'alipay_seller_email' );
		
		/**
		 * *********************************************************
		 */
		
		// 构造要请求的参数数组，无需改动
		$parameter = array (
				"service" => "single_trade_query", // refund_fastpay_by_platform_nopwd
				"seller_email" => $seller_email,
				"partner" => $partner,
				"out_trade_no" => $out_trade_no,
				"_input_charset" => 'utf-8' 
		);
		
		$alipaySubmit = new AlipaySubmit ( $alipay_config );
		$parameter = $alipaySubmit->buildRequestPara ( $parameter ); // buildRequestHttp/buildRequestForm
		                                                          
		// 建立请求
		$html_text = HTTP::getSSLGET ( $url, $cacert_url, $parameter );
		print_r ( $html_text );
		return $html_text;
	}
}
?>