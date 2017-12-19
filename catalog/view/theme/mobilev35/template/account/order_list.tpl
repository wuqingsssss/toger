<?php echo $header; ?>
<div  id="header" class="bar bar-header bar-positive">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>


<div id="content" class="content">
<div class="card">
<a href="<?php echo $this->url->link('account/order');?>">全部订单(<span class="count"><?php echo $this->getTotalOrderCount(); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::UnPayment);?>">未付款(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::UnPayment); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_not_order_status_ids='.EnumOrderStatus::UnPayment.','.EnumOrderStatus::Complete.','.EnumOrderStatus::Cancel);?>">进行中(<span class="count"><?php echo $this->model_account_order->getTotalOrders(array('filter_not_order_status_ids'=>array(EnumOrderStatus::UnPayment,EnumOrderStatus::Complete,EnumOrderStatus::Cancel))); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Complete);?>">已完成(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::Complete); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Cancel);?>">已取消(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::Cancel); ?></span>)</a>
</div>

 <div class="card">
<?php if ($orders) { ?>
  	<?php include 'catalog/view/theme/mobilev2/template/account/ilex_order_list.php'; ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  
  <?php } else { ?>
	  <?php echo $text_empty; ?>
  <?php } ?>
  </div>
</div>

<?php echo $this->getChild('mobile/account/menu') ?>


<script type="text/javascript">
function cancel_order(order_id){
	if(confirm('确认取消订单#'+order_id+'？')){
		$.ajax({
			url: '<?php echo $cancel; ?>',
			type: 'post',
			data: 'order_id='+order_id,
			dataType: 'json',
			success: function(json) {
				if(json['success']){
					 window.location.href=window.location.href;
				}
			}
		});	
	}
}


function refund_order(order_id){
	if(confirm('确认申请退款订单#'+order_id+'？')){
		$.ajax({
			url: '<?php echo $refund; ?>',
			type: 'post',
			data: 'order_id='+order_id,
			dataType: 'json',
			success: function(json) {
				if(json['success']){
					window.location.href=window.location.href;
				}
			}
		});	
	}
}
</script>
<?php echo $footer; ?>


