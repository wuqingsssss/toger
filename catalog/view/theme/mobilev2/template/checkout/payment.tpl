<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<table class="form">
  <?php foreach ($payment_methods as $payment_method) { ?>
  <tr>
    <td style="width: 1px;">
    <?php if ($payment_method['code'] == $payment_code &&  $payment_code!='') { ?>
       <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
      <?php } ?></td>
    <td><label class="icon_<?php echo $payment_method['code']; ?>" for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
  </tr>
  <?php } ?>
</table>
<?php } ?>
<div id="bank" style="display: none;">
<ul class="bank-list">
			<?php foreach ($pay_banks as $key ) { ?>
				 <?php if($pay_bank==$key){ ?>
				<li><input type="radio" name="pay_bank" value="<?php echo $key;?>" checked="checked"/><span
				class="bank-icon <?php echo $key;?>"></span></li>
			<?php } else {?>
				<li><input type="radio" name="pay_bank" value="<?php echo $key;?>"/><span
				class="bank-icon <?php echo $key;?>"></span></li>
			<?php }?>
		
		<?php }?>
	</ul>
	<div class="clear"></div>
	<a id='bank-confirm' onclick="payment_alibank_click();" class="button"><span>保存我的网银选择</span></a>
</div>


