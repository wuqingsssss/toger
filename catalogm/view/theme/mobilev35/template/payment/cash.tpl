<div class="buttons cash">
  <div class="form" style="font-size:0.2rem;">
     <div>
         <span>促销员账号: </span>                          
         <input type="text" id="username" value="" class="span4" autocomplete="off"/><br/>
         <span>密码:</span> 
         <input type="password" id="password" value="" class="span4" autocomplete="off"/><br/>
     </div>
  </div>
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
    
	$.ajax({ 
				type: 'POST',
				url: 'index.php?route=payment/cash/checkSalesPerson',
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
		  });
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
					url: 'index.php?route=payment/cash/confirm&salesman='+json['salesman']+'&order_id='+json['order_id'],
					success: function() {
						location = '<?php echo $continue; ?>';
					}		
				});
			}
		}		
	});
}
	
//--></script> 
