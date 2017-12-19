<div id="comment">
    <div class="module-heading">
        <span class="order_t_title fl fm-18"><?php echo $module_title; ?></span>
        <div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
        <!--  <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right" style="margin-top: 0.3rem;"/>-->
    </div>
    <div class="module-content" id="comment">
        <textarea name="comment" rows="4"  class="order_comment"><?php echo $comment; ?></textarea>
        <a id="button-comment" class="button button-slim button-positive button-block"><?php echo $button_comment; ?></a>
    </div>
</div>

<script type="text/javascript"><!--
$('#button-comment').bind('click', function() {
	var $comment = $("textarea[name='comment']").attr('value');
	if($comment){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=total/comment/add',
			data: $('#comment :input'),
			dataType: 'json',		
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-comment').attr('disabled', true);
				$('#button-comment').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('#button-comment').attr('disabled', false);
				$('.wait').remove();
			},		
			success: function(json) {
				
				if(json['success']){
					$('#comment .module-content').prepend('<div class="success" style="display: none;">' + json['success'] + '</div>');
					$('#comment .module-heading').click();
				}
					
			}
		});
	}
});
//--></script> 