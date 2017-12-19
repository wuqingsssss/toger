<h1>正在加载微信支付...</h1>

<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
//				WeixinJSBridge.log(res.err_msg);
				if (res.err_msg == 'get_brand_wcpay_request:ok') {
					location.href = '<?php echo $return_url; ?>';
				} else {
					location.href = '<?php echo $fail_url; ?>';
				}
			}
		);
	}
	
	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	callpay();
</script>
