<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content"><?php echo $content_top; ?>
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <h1 class="mgb10"><?php echo $heading_title; ?></h1>
<div><a href="<?php echo $this->url->link('account/order');?>">全部订单(<span class="count"><?php echo $this->getTotalOrderCount(); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::UnPayment);?>">未付款(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::UnPayment); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_not_order_status_ids='.EnumOrderStatus::UnPayment.','.EnumOrderStatus::Complete.','.EnumOrderStatus::Cancel);?>">进行中(<span class="count"><?php echo $this->model_account_order->getTotalOrders(array('filter_not_order_status_ids'=>array(EnumOrderStatus::UnPayment,EnumOrderStatus::Complete,EnumOrderStatus::Cancel))); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Complete);?>">已完成(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::Complete); ?></span>)</a>
<a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Cancel);?>">已取消(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::Cancel); ?></span>)</a>
</div>
        <?php if ($orders) { ?>
        			
        
            <?php include DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/ilex_order_list.php'; ?>
            <div class="pagination"><?php echo $pagination; ?></div>

        <?php } else { ?>
            <div class="content"><?php echo $text_empty; ?></div>
        <?php } ?>
        <script type="text/javascript">
            function cancel_order(order_id) {
                if (confirm('确认取消订单#' + order_id + '？')) {
                    $.ajax({
                        url: '<?php echo $cancel; ?>',
                        type: 'post',
                        data: 'order_id=' + order_id,
                        dataType: 'json',
                        success: function (json) {
                            if (json['success']) {
                                window.location.href = window.location.href;
                            }
                        }
                    });
                }
            }


            function refund_order(order_id) {
                var reason = prompt('请填写退款原因:');
                if (!!reason) {
                    $.ajax({
                        url: '<?php echo $refund; ?>',
                        type: 'post',
                        data: {order_id: order_id, reason: reason},
                        dataType: 'json',
                        success: function (result) {
                            if (!!result.success) {
                                window.location.reload();
                            }
                        }
                    });
                }
            }
        </script>
        <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>