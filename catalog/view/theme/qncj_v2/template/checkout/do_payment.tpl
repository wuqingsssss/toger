<div class="section ilex-fancy">
	<div class="ilex-fancy-title">支付</div>
	<div  class="content">
	<div class="title mgb10">请您在新打开的页面进行支付，支付前请不要关闭该窗口。</div>
	<div class="mgb10 tc"><b>订单号：</b><?php echo $order_id; ?></div>
	
	<div class="buttons tc">
		<a href="<?php echo $this->url->link('account/order/info','order_id='.$order_id); ?>" class="btn">已完成支付</a>
		&nbsp;&nbsp;&nbsp;
		<a href="<?php echo $this->url->link('information/information&information_id=28'); ?>" class="btn">支付遇到问题</a>
	</div>
	</div>
</div>