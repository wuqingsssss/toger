<?php echo $header; ?>
    <div id="header" class="bar bar-header bar-light">
        <h1 class="title"><?php echo $heading_title; ?>：#<?php echo $order_id; ?></h1>
    </div>

    <div id="content" class="content">
        <div class="">
            <div class="item item-divider">
                <?php echo $text_order_detail; ?>
            </div>
            <div class="item item-text-wrap">



                <table class="list">
                    <tbody>
                    <?php if ($p_order_id != '') { ?>
                        <tr>
                            <td class="left" style="width: 50%;">
                                <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?>(子订单)<br/>
                                <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br/>
                                <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?>

                                <?php if ($payment != '') { ?>
                                    <?php echo $payment; ?>
                                <?php } ?>

                                <?php if ($cancelable) { ?>
                                <div>
                                    <a data-id="<?php echo $order_id; ?>" id="cancel-order-btn"
class="button button-slim button-block button-default"><span>取消</span></a></div>
                                <?php } ?>

                            </td>
                            <td class="left">
                                <?php if ($shipping_point_id>0) { ?>
                                    <b>取菜时间</b> <?php echo $pdate; ?><br/>
                                    <b>取菜地点</b> <?php echo $shipping_method; ?>
                                <?php } ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td class="left">
                                <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br/>
                                <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br/>

                                <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br/>
                                <?php if ($shipping_method && $pdate) { ?>
                                    
                                    <b>取菜时间：</b> <?php echo $pdate; ?> <br />
                                    <b>取菜码：</b> <?php echo $pickup_code; ?> <br />
                                    <b>电话:</b> <?php echo $pointinfo['telephone']; ?>  <br />
                                    <b>取菜地址:</b> <?php echo $pointinfo['name'].$pointinfo['address']; ?> 
                                    
                                <?php }else{ ?>
                                    <b>配送时间:</b><?php echo $shipping_time; ?><br />  
                                    <b>收货人:</b> <?php echo $shipping_firstname; ?>  <br />
                                    <b>电话:</b> <?php echo $shipping_mobile; ?>  <br />
                                    <b>收货地址:</b><?php echo $shipping_address_1.$shipping_address_2; ?>

<?php }?>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <?php if ($payment != '') { ?>
                <?php echo $payment; ?>
                <?php } ?>







                <?php if ($p_order_id != '') { ?>
                    <?php foreach ($groups as $key => $products2) { ?>
                        <?php if ($key) { ?>
                            <div class="checkout-heading"><label>取菜时间：</label><b><?php echo $key; ?></b></div>
                        <?php } ?>
                        <table class="list">
                            <thead>
                            <tr>
                                <td class="tc"><?php echo $column_name; ?></td>
                                <td class="tc"><?php echo $column_model; ?></td>
                                <td class="tc"><?php echo $column_price; ?></td>
                                <td class="tc"><?php echo $column_quantity; ?></td>

                                <td class="tc"><?php echo $column_total; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($products2 as $product) { ?>
                                <tr>
                                    <td class="tc"><?php echo $product['name']; ?>
                                        <?php foreach ($product['option'] as $option) { ?>
                                            <br/>
                                            &nbsp;
                                            <small> - <?php echo $option['name']; ?>
                                                : <?php echo $option['value']; ?></small>
                                        <?php } ?></td>
                                    <td class="tc"><?php echo $product['model']; ?>
                                    </td>
                                    <td class="tc"><?php echo $product['price']; ?></td>
                                    <td class="tc"><?php echo $product['quantity']; ?></td>

                                    <td class="tc"><?php echo $product['total']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                <?php } else { ?>
                    <?php foreach ($groups as $key => $products2) { ?>
                    
                        <table class="list">
                            <thead>
                            <tr>
                                <td class="tc"><?php echo $column_name; ?></td>
                                <!--<td class="tc"><?php echo $column_model; ?></td>-->
                                <td class="tc"><?php echo $column_price; ?></td>
                                <td class="tc"><?php echo $column_quantity; ?></td>

                                <!--<td class="tc"><?php echo $column_total; ?></td>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($products2 as $product) { ?>
                                <tr>
                                    <td class="tc"><?php echo $product['name']; ?>
                                        <?php foreach ($product['option'] as $option) { ?>
                                            <br/>
                                            &nbsp;
                                            <small> - <?php echo $option['name']; ?>
                                                : <?php echo $option['value']; ?></small>
                                        <?php } ?></td>
                                    <!--<td class="tc"><?php echo $product['model']; ?></td>-->
                                    <td class="tc"><?php echo $product['price']; ?></td>
                                    <td class="tc"><?php echo $product['quantity']; ?></td>

                                    <!--<td class="tc"><?php echo $product['total']; ?></td>-->
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>
                    <?php } ?>
                <?php } ?>

                <br/>
                <table class="list">
                    <thead>
                    <tr>
                        <td class="tc" colspan="4"></td>
                        <td class="tc"><?php echo $column_total; ?></td>
                    </tr>
                    </thead>

                    <tfoot>
                    <?php foreach ($totals as $total) { ?>
                        <tr>
                            <td colspan="4" class="tr"><b><?php echo $total['title']; ?>：</b></td>
                            <td class="tl"><?php echo $total['text']; ?></td>
                        </tr>
                    <?php } ?>
                    </tfoot>

                </table>

                <?php if ($comment) { ?>
                    <table class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $text_comment; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="left"><?php echo $comment; ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>

                <?php if ($histories) { ?>
                    <h2><?php echo $text_history; ?></h2>
                    <table class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $column_date_added; ?></td>
                            <td class="left"><?php echo $column_status; ?></td>
                            <td class="left"><?php echo $column_comment; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($histories as $history) { ?>
                            <tr>
                                <td class="left"><?php echo $history['date_added']; ?></td>
                                <td class="left"><?php echo $history['status']; ?></td>
                                <td class="left"><?php echo $history['comment']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>

                <?php if ($cancelable) { ?>
                <div>
                    <a data-id="<?php echo $order_id; ?>" id="cancel-order-btn"
                       class="button button-slim button-block button-default">取消</a>
                </div>
                <?php } ?>
            </div>


        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cancel-order-btn').click(function () {
                var order_id = $(this).data('id');
                if (confirm('确认取消订单#' + order_id + '？')) {
                    $.ajax({
                        url: '<?php echo $link_cancel; ?>',
                        type: 'post',
                        data: 'order_id=' + order_id,
                        dataType: 'json',
                        success: function (json) {
                            if (json['success']) {
                                window.location.reload();
                            }
                        }
                    });
                }
            })
        });

    </script>

<?php echo $footer; ?>