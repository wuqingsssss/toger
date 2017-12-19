<?php foreach ($addresses as $address) { ?>
  <label><input type="radio"  name="address_id" value="<?php echo $address['address_id']; ?>" <?php if ($address['address_id'] == $address_id) { ?>checked="checked"<?php }?> />
  <?php echo $address['firstname']; ?> ，<?php echo $address['lastname']; ?> ， <?php echo $address['zone']; ?> ， <?php echo $address['city']; ?> ， 
  <?php echo $address['address_1']; ?> <?php echo $address['address_2']; ?>  
  <?php if($address['mobile']){ ?>，<?php echo $entry_mobile; ?>  <?php echo $address['mobile']; ?> <?php } ?>
  <?php if($address['phone']){ ?>， <?php echo $entry_phone; ?>  <?php echo $address['phone']; ?><?php } ?>
  </label>
  <a class="fancybox" href="index.php?route=checkout/address/update&address_id=<?php echo $address['address_id']; ?>" style="color: rgb(24, 93, 148); margin-left:10px; display:inline-block;">[修改]</a>
  <br />
<?php } ?>


<script type="text/javascript">
$('.fancybox').fancybox({
	showCloseButton:true,
	autoDimensions: true,
	onClosed:function() {
		$('#shipping-existing').load('index.php?route=checkout/address/lists');
	}
});
</script>