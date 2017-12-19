<div id="invoice_apply">
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  <div class="cart-content" id="">
  <form id="invoice_apply_form">
  <div id="part_invoice_form">
  	<div id="invoiceContentPanel_1">
  		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="display:" id="tb_invoice_1" class="txt_12">
  			
  			<tbody>
  				<tr>
  					<td valign="top" align="left" colspan="2" height="30">
  						<span style="margin-right:8px;font-weight:bold;">发票类型：</span>
  						<input type="radio" value="1" checked="" id="invoince_InvoiceType_1" name="invoince_type" <?php if($invoice_type==1) {?>checked="checked"<?php } ?>>
  						<label for="invoince_InvoiceType_1">普通发票</label>
  						
  						
  					</td>
  				</tr>
  			</tbody>
  			<tbody style="display:" id="invoice_titleTr_1">
  				<tr>
  					<td valign="top" align="left" colspan="2" height="30">
  						<span style="margin-right:8px;font-weight:bold;">发票抬头：</span>
  						<input type="radio" value="1" id="invoince_pttt_1_4" name="invoince_pttt" onclick="$('#invoice_unitNameTr').hide();" <?php if($invoice_head==1) {?>checked="checked"<?php } ?>>
  						<label for="invoince_pttt_1_4">个人 </label>
  						<input type="radio" value="2" id="invoince_pttt_1_5" name="invoince_pttt" onclick="$('#invoice_unitNameTr').show();" <?php if($invoice_head==2) {?>checked="checked"<?php } ?>>
  						<label for="invoince_pttt_1_5">单位</label>
  					</td>
  				</tr>
  				<tr <?php if($invoice_head!=2) {?>style="display:none;"<?php } ?> id="invoice_unitNameTr" class="txt_color_hui">
  					<td width="70" valign="middle" align="left" height="30"><span style="font-weight:bold;">单位名称：</span></td>
  					<td valign="top" align="left" height="30">
  						<input type="text" value="<?php echo $invoice_name; ?>" style="width:260px; height:30px;" class="txt" name="invoice_Unit_TitName" id="invoice_Unit_TitName"><span style="color:red;"> &nbsp;*</span><br>
  					</td>
  				</tr>
  				<tr>
  					<td colspan="2" height="30">
  						<span style="color:red">温馨提示：您填写的所有内容都将被系统自动打印到发票上，所以请千万别填写和发票抬头无关的信息。</span></td>
  				</tr>
  			</tbody>
  			
  		</table>
  	</div>

  	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="txt_12">
  		<tbody>
  			<tr>
  				<td width="80px" valign="top" align="left" style="font-weight:bold;">发票内容：</td>
  				<td align="left">
  					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="txt_12" style="display:" id="invoicecontentlist_1">
  						<tbody>
  							
  							<tr style="display:">
  								<td>
  									<span id="invoince_contentPanel_1">
  										<input type="radio" value="1" id="invoince_content_1_1" name="invoince_content_1" <?php if($invoice_content==1) {?>checked="checked"<?php } ?>>
  										<label for="invoince_content_1_1">明细</label>
  										<?php if(false) {?>
  										<input type="radio" value="2" id="invoince_content_1_22" name="invoince_content_1" <?php if($invoice_content==2) {?>checked="checked"<?php } ?>>
  										<label for="invoince_content_1_22">办公用品（附购物清单）</label>
  										<input type="radio" value="3" id="invoince_content_1_3" name="invoince_content_1" <?php if($invoice_content==3) {?>checked="checked"<?php } ?>>
  										<label for="invoince_content_1_3">电脑配件</label>
  										<input type="radio" value="4" id="invoince_content_1_19" name="invoince_content_1" <?php if($invoice_content==4) {?>checked="checked"<?php } ?>>
  										<label for="invoince_content_1_19">耗材</label>
  										<?php } ?>
  									</span>
  								</td>
  							</tr>
  							
  							
  						</tbody>
  					</table>
  					
  				</td>
  			</tr>
  		</tbody>
  	</table>
  	
  </div>
	
  <br>
  <a onclick="invoice_apply_click();return false;" class="button"><span>保存</span></a>
	</form>
</div>
</div>
<script type="text/javascript">
function invoice_apply_click(){
	$.ajax({
		url: 'index.php?route=total/invoice/click',
		type: 'post',
		data: $('#invoice_apply_form').serialize(),
		dataType: 'json',
		success: function(json) {
			if(json['success']){
				$('.success').remove();
				
				$('#invoice_apply').prepend('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('#invoice_apply .cart-heading').click();

				$('.success').fadeIn('slow').delay('3000').fadeOut('slow');
			}
		}
	});	
}
</script>