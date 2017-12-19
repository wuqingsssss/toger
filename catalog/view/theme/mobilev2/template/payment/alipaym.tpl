<div class="buttons">
  <div class="right"><?php echo $action;?></div>
</div>

<script type="text/javascript"><!--
<?php if(isset($reorder)){?>
$('#button-confirm').bind('click', function() {
	document.forms['alipaysubmit'].submit();
});
<?php }else {?>
$('#button-confirm').bind('click', function() {
	
	console.log($('#button-confirm'));
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
				
				document.forms['alipaysubmit'].submit();
			}
		}		
	});
});

<?php }?>
//--></script> 