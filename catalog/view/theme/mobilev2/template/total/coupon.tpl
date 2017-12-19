<div>
    <div class="cart-heading"><?php echo $heading_title; ?></div>
    <div class="cart-content" id="coupon">
        <?php echo $entry_coupon; ?>&nbsp;
        <?php if($coupon_info){ ?>
        <select name="coupon">
            <?php foreach($coupon_info as $value) { ?>
            <?php if($value['used']=='0'){?><option value="<?php echo $value['coupon_customer_id']?>"><?php echo $value['name'];?></option><?php }?>
            <?php }?>
        </select>
        <?php }?>
    
        <a id="button-coupon" class="button button-block button-positive button-slim"><?php echo $button_coupon; ?></a>
        <br/>
        <strong><?php echo $entry_coupon_info; ?></strong>
    </div>
</div>
<script type="text/javascript"><!--
$('#button-coupon').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/coupon/calculate',
		data: $('#coupon :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-coupon').attr('disabled', true);
			$('#button-coupon').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-coupon').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			if (json['error']) {
				$('#basket').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
			}
			
			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script> 