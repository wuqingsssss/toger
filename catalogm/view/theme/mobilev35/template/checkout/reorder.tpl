<?php echo $headersimple; ?>

<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/order.css?v=<?php echo STATIC_VERSION; ?>" />
<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>

<div class="pg-page payment">
    <?php echo $order_pay?>
    <div class="fz-16"><?php echo $checkout_token; ?></div>
</div>

<script type="text/javascript">
<!--

	// 支付方法
	$("input[name='balance']").bind('click', function() {
		changePayment();
	});
	
	$("input[name='payment_method']").bind('click', function() {
		changePayment();
	});
	

	// 更换支付方式
	function changePayment(){
		var balance = '0'; 
    	var ba = document.getElementsByName("payment_method"); 
    	if( $("input[name='balance']").attr('checked')){
    		balance = '1';
    	}
        var paymethods =document.getElementsByName("payment_method");
        var payment_method;
        for(var i = 0; i < paymethods.length; i++)
        {
            if(paymethods[i].checked){
    	        payment_method=paymethods[i].value;
            }
        };
    	
    	$.ajax({ 
    		url: 'index.php?route=checkout/payment/changemethod&token=<?php echo $token; ?>',
    		type: 'post',
    		data: 'payment_method='+payment_method+'&balance='+balance+'&order_id=<?php echo $order_id; ?>', 		
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>');
      		},	
    		complete: function() {
    			$('.waiting').remove();
    		},	
    		success: function(json) {
    			$('.waiting').remove();
    			if (json['error']) {
    				if (json['redirect']) {
    					 window.location = json['redirect'];
    					}
    				if (json['error']['warning']) {
                        _.toast(json['error']['warning'], 3000);
    				}
    			}else{
        			
        			if(json['payinfo']['balance']['valid']){
            			$('#balance-check').removeClass('blk-disabled');
            			
            			if(json['payinfo']['balance']['selected']){
            				$('#balance-pay').show();
             			}
            			else{
            				$('#balance-pay').hide();
            			}
        			}
        			else{
            			$('#balance-check').addClass('blk-disabled');
            			$('#balance-pay').hide();
        			}
   	
        			if(json['payinfo']['otherpay']['valid']){
            			$('#pay-select').removeClass('blk-disabled');
        			}
        			else{
            			$('#pay-select').addClass('blk-disabled');
        			}
        			$('#balance-value').html(json['payinfo']['balance']['value']);
       				$('#balancepay-value').html(json['payinfo']['balance']['pay']);
        			$('#pay-value').html(json['payinfo']['otherpay']['pay']);
    			}
    		}		
    	});
	}
	
	// 立即支付
    $('#submit-pay').bind('click', function () {
    	var paytype = $("input[name='payment_method']:checked").val();
    	
    	$('#submit-pay').attr({"disabled":"true"});
     	$.ajax({
    		url: 'index.php?route=checkout/reorder/paysubmit&token=<?php echo $token; ?>',
    		type: 'post',
    		data: 'order_id=<?php echo $order_id; ?>',
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>'); 			
    		},
    		complete: function() {
    			$('.waiting').remove(); 

    		},
    		success: function(json) {
    			$('.waiting').remove(); 
    			console.log(json);

				if (json['error']) {					
					if (json['error']['warning']) {
						
						$('#m-payment .btn-submit').removeAttr('disabled');
						_.toast(json['error']['warning'], 3000);
					}
					if (json['redirect']) {			
						window.location = json['redirect'];
					}
				} else {
					_.toast('提交成功跳转中...');
					if (json['redirect']) {									
						window.location = json['redirect'];
					}
					if(json['payment']){
						$("#submit-pay").html(json['payment']);	
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError);
			}		
		});
    });
	
	//更新支付信息
	function checkoutComfirm(){
		$.ajax({
			url: 'index.php?route=checkout/payment/update',
			dataType: 'json',
			success: function(json) {
				if (json['redirect']) {
					window.location = json['redirect'];
				}
	
				if (json['output']) {
					$('.checkout-block').html(json['output']);
	
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError);
			}
		});
	
	}


	
//-->
</script>

<script type="text/javascript" src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<?php echo $footer35; ?>