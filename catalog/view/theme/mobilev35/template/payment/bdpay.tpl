<div class="buttons">
  <div class="right"><a id="button-confirm" class="button button-block button-positive"><span><?php echo $button_confirm; ?></span></a>
  <input type="hidden" id="paymentaction" name="paymentaction" value="<?php echo $action; ?>"/>
  </div>
</div>
<script type="text/javascript"><!--
<?php if(isset($reorder)){?>
$('#button-confirm').bind('click', function() {
	window.location ='<?php echo $action; ?>';
});
<?php }else {?>
$('#button-confirm').bind('click', function() { 
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
					alert(json['error']['warning']);
				}
			}else{
				if(json['payment'])
					$("#payment").html(json['payment']);
				window.location =$("#paymentaction").val(); 
			}
		}		
	});
});

<?php }?>
//--></script> 