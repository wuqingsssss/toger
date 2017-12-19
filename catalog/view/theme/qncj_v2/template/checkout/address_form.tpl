<form id="address-mod-fancy" action="<?php echo $action; ?>">
<table class="form">
    <tr>
      <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
      <td>
      <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="span2" />
      <?php echo $entry_lastname; ?>
      <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="span2" />
      </td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
      <td><select name="zone_id" class="span2"  onchange="$('select[name=\'city_id\']').load('index.php?route=common/localisation/city&zone_id=' + this.value);$('#city_id').show();">
        </select></td>
    </tr>
    <tr	id="city_id">
      <td><span class="required">*</span> <?php echo $entry_city; ?></td>
      <td><select name="city_id" class="middle-field" onchange="$('input[name=\'city\']').val($(this).find('option:selected').text())"></td>
    </tr>
    <tr style="display:none;">
      <td><span class="required">*</span> <?php echo $entry_city; ?></td>
      <td><input type="text" name="city" value="" class="span2" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
      <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" class="span6" maxlength="128" /></td>
    </tr>
    <input type="hidden" name="address_2" value="" class="span2" />
    <tr>
          <td><span class="required">*</span> <?php echo $entry_mobile; ?></td>
          <td><input class="span2" type="text" name="mobile" value="<?php echo $mobile; ?>" maxlength="20" />
            </td>
   </tr>
   <tr>
          <td> <?php echo $entry_phone; ?></td>
          <td><input class="span2" type="text" name="phone" value="<?php echo $phone; ?>" />
           </td>
    </tr>
   <tr style="display:none;">
      <td><?php echo $entry_postcode; ?></td>
      <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" class="span2" /></td>
   </tr>
   </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
          	<input class="btn" type="button" onclick="address_modify();" value="修改地址" />
          </td>
    </tr>
</table>
</form>
<script type="text/javascript">
$('select[name=\'zone_id\']').load('index.php?route=common/localisation/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
$('select[name=\'city_id\']').load('index.php?route=common/localisation/city&zone_id=<?php echo $zone_id; ?>&city_id=<?php echo $city_id; ?>');

function address_modify(){
	$.ajax({
		url: $('#address-mod-fancy').attr('action'),
		type: 'post',
		data: $('#address-mod-fancy').serialize(),
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				$('span.error').remove();
				
				$.each(json['error'],function(name,msg){
					$('#address-mod-fancy input[name=\''+ name +'\']').after('<span class="error">' + msg + '</span>');
				});
				
				$.each(json['error'],function(name,msg){
					$('#address-mod-fancy select[name=\''+ name +'\']').after('<span class="error">' + msg + '</span>');
				});
			}	 
						
			if (json['success']) {
				$.fancybox.close();
			}	
		}
	});
}
</script>