<div id="reference">
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  <div class="cart-content" id="reference">&nbsp;
      <input type="text" name="reference" value="" maxlength="50"/>
    &nbsp;<a id="button_reference" class="button"><span><?php echo $button_reference;?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button_reference').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=campaign/reference/calculate',
		data: $('#reference :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button_reference').attr('disabled', true);
			$('#button_reference').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button_reference').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			if (json['error']) {
				$('#reference').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
			}
			
			if (json['success']) {
                $('#reference').before('<div class="success">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                $('#reference .cart-heading').click();
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script> 