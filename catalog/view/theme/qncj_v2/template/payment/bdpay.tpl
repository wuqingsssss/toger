<a id="button-confirm" class="button"><span>立即付款</span></a>

<script type="text/javascript"><!--
<?php if(isset($reorder)){?>
$('#button-confirm').bind('click', function() {
	do_payment('<?php echo $action; ?>');
});

function do_payment(url){
	//打开fancybox
	$('#fancy_dopayment').click();
	
	window.open(url,'newwindow');
}
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
				window.location ='<?php echo $this->url->link('checkout/success/payment'); ?>&order_id='+json['order_id'];
			}
		}		
	});
});

<?php }?>


//--></script> 