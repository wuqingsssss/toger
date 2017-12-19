<div class="buttons alipay">
  <div class="right"><a id="button-confirm" class="button button-block button-positive"><span><?php echo $button_confirm; ?></span></a></div>
</div>

<script type="text/javascript"><!--
<?php if(isset($reorder)){?>
$('#button-confirm').bind('click', function() {
	window.location ='<?php echo $action; ?>';
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
		
		},	
		success: function(json) {
			if (json['error']) {
				if (json['error']['warning']) {
					overlay.destroy();
					$('#button-confirm').removeAttr('disabled');
                    _.toast(json['error']['warning'], 3000);
				}
			}else{
				overlay.content('提交成功跳转中...');
				 window.location ='<?php echo $action; ?>';
			}
		}		
	});
});

<?php }?>
//--></script> 