<div class="buttons alipay">
  <div class="right"><?php echo $action;?></div>
</div>

<script type="text/javascript"><!--
<?php if(isset($reorder)){?>
$('#button-confirm').bind('click', function() {
	document.forms['alipaysubmit'].submit();
});
<?php }else {?>
$('#button-confirm').bind('click', function() {
	var overlay=_.toast("支付宝,提交中请稍后...");
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
			
			console.log(json);
			
			if (json['error']) {
				 if (json['redirect']) {
					 window.location = json['redirect'];
					}
				if (json['error']['warning']) {
					overlay.destroy();
					$('#button-confirm').removeAttr('disabled');
                    _.toast(json['error']['warning'], 3000);
				}
			}else{
				overlay.content('提交成功跳转中...');
				if(json['payment'])
				$("#payment").html(json['payment']);			
				document.forms['alipaysubmit'].submit();
			}
		}		
	});
});

<?php }?>
//--></script> 