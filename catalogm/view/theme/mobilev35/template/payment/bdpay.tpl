<div class="buttons bdpay">
  <div class="right"><button id="button-confirm" class="button" onclick="javascript:bdpay();"><?php echo $button_confirm; ?></button>
  <input type="hidden" id="paymentaction" name="paymentaction" value="<?php echo $action; ?>"/>
  </div>
</div>
<script type="text/javascript"><!--
function bdpay(){
<?php if(isset($reorder)){?>
//$('#button-confirm').bind('click', function() {
	window.location ='<?php echo $action; ?>';
//});
<?php }else {?>
//$('#button-confirm').bind('click', function() { 
	var overlay=_.toast("百度支付,提交中请稍后...");
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
				if(json['payment'])
					$("#payment").html(json['payment']);
				overlay.content('提交成功跳转中...');
				window.location =$("#paymentaction").val(); 
			}
		}		
	});
//});

<?php }?>
}
//--></script> 