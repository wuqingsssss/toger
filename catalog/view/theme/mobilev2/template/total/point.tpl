<div id="point">
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  	<div class="cart-content" id="" style="display:block;">
  		<div style="margin-left:0px;width:648px;padding-left:4px;" class="tsbox">
  		<form id="point_form">
  		<div>
  		<table cellspacing="0" cellpadding="0">
  			<tbody>
  				<?php foreach($points as $result) {?>
  				<tr>
  					<td>
  						<input type="radio" value="1" id="point1" name="point" <?php if($selected_point==1) {?>checked="checked"<?php }?>>
  						<label style="margin-left:3px;" for="point1"><?php echo $result['name']; ?></label>
            		</td>
            		<td width="50px">&nbsp;</td>
		            <td style="color:#999;">
		              	地址：<?php echo $result['address']; ?>
		<!--              	（<a href="">详细地址</a>）-->
						<span style="margin-left:10px;">联系电话：<?php echo $result['telephone']; ?></span>
		            </td>			
	  			</tr>
  				<?php } ?>
  			</tbody>
  		</table>
  	</div>
  	
  	</form>
  	<div style="margin-top:10px;margin-bottom:3px;line-height:20px;">
  		<span style="color:red">声明：</span>
  		来店提货前请电话确认是否已经备好货！<br>
  	
  	</div>
  </div>
  <br />
  <a onclick="point_click();" class="button"><span>保存</span></a>
</div>
</div>
<script type="text/javascript">
function point_click(){
	$.ajax({
		url: 'index.php?route=total/point/click',
		type: 'post',
		data: $('#point_form').serialize(),
		dataType: 'json',
		success: function(json) {
			if(json['success']){
				$('.success').remove();
				
				$('#point').prepend('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('#point .cart-heading').click();

				$('.success').fadeIn('slow').delay('3000').fadeOut('slow');
			}
		}
	});	
}
</script>