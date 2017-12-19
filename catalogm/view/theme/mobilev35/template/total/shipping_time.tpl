<div id="shipping_time">
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  	<div class="cart-content" id="" style="display:block;">
  		<div style="margin-left:0px;width:648px;padding-left:4px;" class="tsbox">
  		<form id="shipping_time_form">
  		<div>
  		<table cellspacing="0" cellpadding="0">
  			<tbody>
  				<tr>
  					<td valign="top" style="width:70px"><b>送货日期：</b></td>
  					<td>
  						<input type="radio" value="1" id="CODTime1" name="CODTime" <?php if($shipping_time==1) {?>checked="checked"<?php }?>>
  						<label style="margin-left:3px;" for="CODTime1">工作日、双休日与假日均可送货</label><br><br>
  						<input type="radio" value="3" id="CODTime3" name="CODTime" <?php if($shipping_time==3) {?>checked="checked"<?php }?>>
  						<label style="margin-left:3px;" for="CODTime3">只工作日送货(双休日、假日不用送)</label><br><br>
  						<input type="radio" value="2" id="CODTime2" name="CODTime" <?php if($shipping_time==2) {?>checked="checked"<?php }?>>
  						<label style="margin-left:3px;" for="CODTime2">只双休日、假日送货(工作日不用送)</label><br><br>
  					</td>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  	<div style="margin-bottom:15px;">
  		<b>是否送货前电话确认：</b>
  		<input type="radio" value="1" <?php if($shipping_confirm==1) {?>checked="checked"<?php }?> id="idInformRad1" name="isInformRad">
  		<label for="idInformRad1">是</label>
  		<input type="radio" value="0" id="idInformRad0" name="isInformRad" <?php if($shipping_confirm==0) {?>checked="checked"<?php }?>>
  		<label for="idInformRad0">否</label>
  	</div>
  	</form>
  	<div style="margin-bottom:3px;line-height:20px;">
  		<span style="color:red">声明：</span><br>
  		1. 我们会努力按照您指定的时间配送，但因天气、交通等各类因素影响，您的订单有可能会有延误现象！<br>
  		2. 为避免送货延迟，商品要尽快完成支付！以上敬请谅解！
  	</div>
  </div>
  <br />
  <a onclick="shipping_time_click();" class="button"><span>保存</span></a>
</div>
</div>
<script type="text/javascript">
function shipping_time_click(){
	$.ajax({
		url: 'index.php?route=total/shipping_time/click',
		type: 'post',
		data: $('#shipping_time_form').serialize(),
		dataType: 'json',
		success: function(json) {
			if(json['success']){
				$('.success').remove();
				
				$('#shipping_time').prepend('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('#shipping_time .cart-heading').click();

				$('.success').fadeIn('slow').delay('3000').fadeOut('slow');
			}
		}
	});	
}
</script>