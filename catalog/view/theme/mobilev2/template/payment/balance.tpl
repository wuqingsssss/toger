<div class="buttons">
  <div class="form">
<!-- <span class="required">*</span> 会员账号:                            <br/>
     <input type="text" id="username" value="" class="span4" autocomplete="off"/>
     <br/>
     <span class="required">*</span> 会员密码:<br/>
     <input type="password" id="password" value="" class="span4" autocomplete="off"/><br/>
     <br/> -->     
     <span> 您的储值余额是：<strong><?php echo $transaction_total; ?></strong></span>
  </div>
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
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
	  
});
			
function validate(){
	$.ajax({ 
		url: 'index.php?route=checkout/checkout/validate&token=<?php echo $token; ?>',
		type: 'post',
		data: $('#checkout-comment textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
		},	
		complete: function() {
			$('#button-confirm').attr('disabled', false);
		},	
		success: function(json) {
			if (json['error']) {
				if (json['error']['warning']) {
					alert(json['error']['warning']);
				}
			}else{
					
				$.ajax({ 
					type: 'GET',
                    dataType: 'json',
                    url: 'index.php?route=payment/balance/confirm&order_id='+json['order_id'],
					success: function(data) {
                        if(data.error=='true'){
                            alert(data.msg);
                            return false;
                        }else{
                            location = '<?php echo $continue; ?>';
                        }
					}		
				});
			}
		}		
	});
}
	
//--></script> 
