<div class="checkout-content">
  <div class="columns">
  <div class="information">
   		<div class="pay-info">
			<?php echo $text_checkout_payment_method;?>：
			<?php if($alipaybank!='') { ?>
			<b> <?php echo $alipaybank;?></b>  
			<?php } else{?>
			<b><?php echo $payment_method;?></b>  
			<?php } ?>
		</div>

		<div class="ship-info">
			配送方式：<b><?php echo $shipping_method;?></b>
		</div>

  	</div>
  	<div class="checkout-total">
  		<?php foreach ($totals as $total) { ?>
	      <div class="<?php echo $total['code']; ?>">
	      	<span class="price"><?php echo $total['title']; ?>:</span>
	      	<span class="total number"><b><?php echo $total['text']; ?></b></span>
	      </div>
	    <?php } ?>
  	</div>
  	<div class="clear"></div>
  	<div class="payment">
		<?php echo $payment; ?>
	</div>
  	</div>
</div>