
<!-- 订单小计 START -->
<div class="checkout-total" style="padding:0; background-color:#fff;">
	<?php foreach ($totals as $total) { ?>
	<!-- 列出应付金额以外结算科目 -->
	<?php if($total['code'] != 'total') { ?>
	<div class="order_total">
		<span class="price"><?php echo $total['text']; ?></span>
		<span class="text"><?php echo $total['title']; ?><?php if($total['text']){?>:<?php }?></span>		
	</div>
	<?php } else{ ?>
	<!-- 应付金额 -->
    <?php $amount = $total; } ?>
	<?php } ?>
</div>
<!-- 订单小计 END -->
<!-- 支付 START -->
<div class="checkout-order">
	<?php if(isset($amount)) { ?>
    <div class="o_amount_payable"><?php echo $amount['title']; ?>: 
    <span> <?php echo $amount['text']; ?> </span>
    </div>
    <?php } ?>
	<div id="payment" class="order-submit">
		 <div class="right"><input id="button-confirm" value="<?php echo $button_submit; ?>" type="button"></div>
	</div>
</div>
<!-- 支付 END -->
