<form id="shipping-address-form">
<div id="<?php echo $type; ?>-new" >
  <table class="form">
    <tr>
      <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
      <td>
      <input type="text" name="firstname" value="" class="span2" />
      <?php echo $entry_lastname; ?>
      <input type="text" name="lastname" value="" class="span2" />
      </td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_location; ?></td>
      <td>
      <select name="zone_id" class="span2" onchange="$('#<?php echo $type; ?>-address select[name=\'city_id\']').load('index.php?route=common/localisation/city&zone_id=' + this.value);">
      </select>
      <select name="city_id" class="span2">
      </select>
      </td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_address; ?></td>
      <td><input type="text" name="address_1" value="" class="span6" /></td>
    </tr>
    <tr>
          <td><span class="required">*</span> <?php echo $entry_mobile; ?></td>
          <td>
          	<input type="text" name="mobile" value="" /> 
          	<em> </em>
          	<?php echo $entry_phone; ?>
          	<input type="text" name="phone" value="" />
          </td>
    </tr>
  </table>
  <div class="left">
  	<!-- 移除附加地址输入框和邮编  -->
  	<input type="hidden" name="address_2" value=""  />
  	<input type="hidden" name="postcode" value="" />
  	
  	<input type="hidden" name="address_id" value="0"  />
    <a id="button-address" class="button highlight"><span><?php echo $button_save; ?></span></a></div>
</div>
</form>
<script type="text/javascript">
	$('#<?php echo $type; ?>-address select[name=\'zone_id\']').load('index.php?route=common/localisation/zone&country_id=<?php echo $country_id; ?>');
	$('#<?php echo $type; ?>-address select[name=\'city_id\']').load('index.php?route=common/localisation/city&country_id=<?php echo $country_id; ?>');
</script> 