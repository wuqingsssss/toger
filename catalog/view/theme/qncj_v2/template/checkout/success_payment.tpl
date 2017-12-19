<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>



  <div class="content ">
  	<div class="mainbody">
  		<div class="mc">
    	<s class="icon-succ04"></s>
    	<h1 class="orderinfo">订单提交成功，请您尽快付款！</h3>
    	<div class="list-orderinfo">
			<p>订单号： <b><?php echo $order_id; ?></b></p>
			<p>应付金额： <b><?php echo $total; ?></b> 元</p>
			<p class="mb-tip">
        	请您在提交订单后<span class="ftx-04">30分钟</span>内完成支付，否则订单会自动取消。
			</p>
        </div>
		<div id="payment-method" class="pay-info">
		    <?php echo $payment; ?><b class="icon_<?php echo $payment_code; ?>">在线支付</b>
		</div>
		    
		</div>
		    
		    
	</div><!-- END .mainbody -->
	
	
		    
</div>
 
  <?php echo $content_bottom; ?></div>
<div style="display:none;">
	<a id="fancy_dopayment" href="index.php?route=checkout/success/do_payment&order_id=<?php echo $order_id; ?>" class="fancybox">立即支付</a>
</div>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript"><!--
$(document).ready(function(){
	$('#fancy_dopayment').fancybox({
		width: 860,
		height: 500,
		autoDimensions: true,
		scrolling: 'no',
		onClosed:function() {
			location.href =location.href;
		}
	});
});

//--></script> 
<?php echo $footer; ?>