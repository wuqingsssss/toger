<div class="buttons balance">
  <div class="right"><button id="button-confirm" class="button button-block button-positive" onclick="javascript:balance();" ><?php echo $button_confirm; ?></button></div>
</div>
<script type="text/javascript"><!--

function balance() {
	/*
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
    
	$.ajax({ 
				type: 'POST',
				url: 'index.php?route=payment/balance/checkSalesPerson',
				data: {username:username, password:password},
				dataType: 'json',
				cache: false,
				error: function(){
					return false;
					},
				success: function(json) {
					if (json['error']) {
						alert('账号或密码错误！');
					}
					else{
						validate();
					}
				}
		  });*/
	validate();
	  
};
			
function validate(){
	var overlay=_.toast("储值支付,提交中请稍后...");
	$.ajax({ 
		url: 'index.php?route=checkout/checkout/validate&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#checkout-comment textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
		},	
		complete: function() {

		},	
		success: function(json) {
			if (json['error']) {
				if (json['error']['warning']) {
					overlay.destroy();
					$('#button-confirm').removeAttr('disabled');
					_.toast(json['error']['warning'], 3000);
				}
			}else{
					
				$.ajax({ 
					type: 'GET',
                    dataType: 'json',
                    url: 'index.php?route=payment/balance/confirm&order_id='+json['order_id'],
					success: function(data) {
                        if(data.error=='true'){
                        	overlay.destroy();
                        	$('#button-confirm').removeAttr('disabled');
                            _.toast(data.msg, 3000);
                            return false;
                        }else{
                        	overlay.content('提交成功跳转中...');
                            location = '<?php echo $continue; ?>';
                        }
					}		
				});
			}
		}		
	});
}
	
//--></script> 
