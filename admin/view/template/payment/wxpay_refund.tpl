<!-- <?php echo $htmldata;?> -->
<div class="box">
    <div class="heading">
    <h2> 退款结果</h2>
    </div>
    <div class="content">
              <table class="list">
                <thead>
                <tr><td class="left">
                    退款序号
                    </td>
                    <td class="left">
                    订单id
                    </td>
                    <td class="left">
                       支付方式</td>
                    <td class="left">
                      支付金额</td>
                    <td class="left">
                       退款方式
                    </td>
                    <td class="left">退款金额</td>
                    <td class="right">退款状态</td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($resdata) {  ?>
                    <?php foreach ($resdata as $refund_info) { ?>

                        <tr>
                             <td class="left">
                                <?php echo $refund_info['order_refund_id']; ?>
                            </td>
                            <td class="left">
                                <?php echo $refund_info['order_id']; ?>
                            </td>
                            <td class="left"><?php echo $refund_info['payment_code1']; ?></td>
                            <td class="right"><?php echo $refund_info['value1']; ?></td>
                            <td class="left"><?php echo $refund_info['payment_code']; ?></td>
                            <td class="right"><?php echo $refund_info['value']; ?></td>
                            <td class="left"><?php echo $refund_info['status'].'['.$refund_info['message'].']'; ?></td>
                            <td class="right"><?php foreach ($refund_info['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?></td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
       </div>
</div>