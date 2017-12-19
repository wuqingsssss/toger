<table class="list order-list">
    <thead>
    <tr class="tc">
        <td style="width:120px;"><?php echo $column_order_id; ?></td>
        <td style="width:240px;"><?php echo $column_products; ?></td>
        <td style="width:60px;"><?php echo $column_customer; ?></td>
        <td style="width:80px;"><?php echo $column_total; ?></td>
        <td style="width:60px;"><?php echo $column_status; ?></td>
        <td style="width:80px;">配送时间</td>
        <td style="width:60px;"><?php echo $text_action; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php $temp = '-1'; ?>
    <?php foreach ($orders as $order) { ?>
        <?php if ($order['p_order_id'] == '' && $order['count'] == 0) { ?>
            <tr class="tc">
                <td class="left">#<?php echo $order['order_id']; ?></td>
                <td class="tl">
                    <?php foreach ($order['products'] as $product) { ?>
                        <?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"
                               target="_blank">
                                <img src="<?php echo $product['thumb']; ?>" alt="" width="40" height="40"
                                     class="img-border"/>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </td>
                <td><?php echo $order['customer']; ?></td>
                <td><?php echo $order['total']; ?></td>
                <td class="tc"><?php echo $order['status']; ?></td>
                <td class="tc">
			<?php if($order[shipping_point_id]) echo $order['pdate']; else echo '约'.date('Y-m-d H:s',strtotime($order['shipping_time'])).'左右';?></td>
                <td class="center">
                    <?php if ($order['cancel'] && !$order['cancelExpired']) { ?>
                        <a class="blue"
                           onclick="cancel_order('<?php echo $order['order_id']; ?>');return false;"><span><?php echo $button_order_cancel; ?></span></a>
                    <?php } ?>

                    <?php if (isset($order['refund']) && $order['refund']) { ?>
                        <a class="blue"
                           onclick="refund_order('<?php echo $order['order_id']; ?>');return false;"><span><?php echo $button_order_refund; ?></span></a>
                    <?php } ?>
                    <?php if (isset($order['view']) && $order['view']) { ?>
                        <a href="<?php echo $order['view']; ?>"><span><?php echo $button_view; ?></span></a>
                    <?php } ?>
                </td>
            </tr>
        <?php } else if ($order['p_order_id'] != '' && $order['count'] == 0) { ?>
            <tr class="tc">
                <td class="left">#<?php echo $order['order_id']; ?><br/>(子订单)</td>
                <td class="tl">
                    <?php foreach ($order['products'] as $product) { ?>
                        <?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"
                               target="_blank">
                                <img src="<?php echo $product['thumb']; ?>" alt="" width="40" height="40"
                                     class="img-border"/>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </td>
                <?php  if ($order['p_order_id'] != '' && $order['p_order_id'] != $temp) {
                    $rowspan = isset($porders[$order['p_order_id']]) ? $porders[$order['p_order_id']] : 1;
                    ?>
                    <td rowspan="<?php echo $rowspan; ?>" class="tc vam"><?php echo $order['customer']; ?></td>
                    <td rowspan="<?php echo $rowspan; ?>" class="tc vam"><?php echo $order['total']; ?></td>
                <?php } ?>
                <td  class="tc vam"><?php echo $order['status']; ?></td>
                <?php $temp = $order['p_order_id']; ?>
                <td class="tc vam"><?php echo $order['pdate']; ?></td>
                <td class="tc vam">
                    <?php if (isset($order['cancel']) && $order['cancel']) { ?>
                        <a class="blue"
                           onclick="cancel_order('<?php echo $order['order_id']; ?>');return false;"><span><?php echo $button_order_cancel; ?></span></a>
                    <?php } ?>

                    <?php if (isset($order['refund']) && $order['refund']) { ?>
                        <a class="blue"
                           onclick="refund_order('<?php echo $order['order_id']; ?>');return false;"><span><?php echo $button_order_refund; ?></span></a>
                    <?php } ?>
                    <?php if (isset($order['view']) && $order['view']) { ?>
                        <a href="<?php echo $order['view']; ?>"><span><?php echo $button_view; ?></span></a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>