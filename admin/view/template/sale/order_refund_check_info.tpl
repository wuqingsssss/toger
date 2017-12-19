<div class="box">
    <div class="heading">
        <h2><img src="view/image/order.png" alt=""/> <?php echo $heading_title; ?></h2>

        <div class="buttons" style="margin-bottom:10px;">
        </div>
    </div>
    <div class="content">
        <div class="vtabs">
            <a href="#tab-refund-history"><?php echo '退款处理'; ?></a>
            <a href="#tab-order"><?php echo $tab_order; ?></a>
            <a href="#tab-product"><?php echo $tab_product; ?></a>
            <a href="#tab-history"><?php echo $tab_order_history; ?></a>
            <a href="#tab-discount"><?php echo $tab_order_discount; ?></a>
        </div>
        <div id="tab-refund-history" class="vtabs-content">
          <table class="form">
                <tr class="highlight">
                    <td><?php echo $text_order_id; ?></td>
                    <td>#<?php echo $order_id; ?>
                    </td>
                </tr>
                <tr class="highlight">
                    <td>退款单号</td>
                    <td><?php echo $order_refund['order_refund_id']; ?></td>
                </tr> 
                 <tr class="highlight">
                    <td>退款单状态</td>
                    <td><h3><?php echo EnumOrderRefundStatus::getOrderRefundStatus($order_refund['status']).($order_refund['comment']?'|'.$order_refund['comment'].$refunderrors[$order_refund['payment_code']][$order_refund['comment']]:'');?></h3>
                    </td>
                </tr>
                 <tr class="highlight">
                    <td>收货人</td>
                    <td><?php echo $telephone; ?></td>
                </tr>
                <tr>
                    <td>支付方式</td>
                    <td><?php echo $order_refund['payment_code1']; ?></td>
                </tr>
                 <tr>
                    <td>支付金额</td>
                    <td><?php echo $order_refund['value1']; ?></td>
                </tr>
                 <tr>
                    <td>退款方式</td>
                    <td><?php echo $order_refund['payment_code'].($order_refund[payment_account]?'|'.$order_refund[payment_account].'|'.$order_refund[payment_name]:''); ?></td>
                </tr>
                 <tr>
                    <td>退款金额</td>
                    <td><?php echo $order_refund['value']; ?></td>
                </tr>
                </table>
        <?php if($refunds){?>
         <table class="list">
                <thead>
                <tr><td class="left"><b>其它退款单</b></td>
                    <td class="left"><b>添加日期</b></td>
                    <td class="left"><b>支付方式</b></td>
                    <td class="left"><b>支付金额</b></td>
                    <td class="left"><b>退款方式</b></td>
                    <td class="left"><b>退款金额</b></td>
                    <td class="left"><b>结果</b></td>
                    <td class="left"><b>原因</b></td>
                    <td class="left"><b>操作</b></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($refunds as $refund){
                $status=EnumOrderRefundStatus::getOrderRefundAllStatus($refund['status']);
                ?>
          <tr>      <td class="left"><?php echo $refund['order_refund_id'];?></td>
                    <td class="left"><?php echo $refund['created_at'];?></td>
                    <td class="left"><?php echo $refund['payment_code1'];?></td>
                    <td class="left"><?php echo $refund['value1'];?></td>
                    <td class="left"><?php echo $refund['payment_code'];?></td>
                    <td class="left"><?php echo $refund['value'];?></td>
                    <td class="left"><?php echo $status['name'];?></td>
                    <td class="left"><?php echo $refund['reason'];?></td>
                    <td class="left"><?php if($order_refund['order_refund_id']!=$refund['order_refund_id']){?>
                <a href="<?php echo $refund['action'];?>">详情</a></td>
                <?php }?>
                </tr> 
                <?php }?>
                </tbody>
                </table>
                <?php }?>
            <table class="list">
                <thead>
                <tr>
                    <td class="left"><b>添加日期</b></td>
                    <td class="left"><b>结果</b></td>
                    <td class="left"><b>原因</b></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php $time = new DateTime($order_refund['created_at']); ?>
                    <td class="left"><?php echo $time->format('Y-m-d H:i') ?></td>
                    <td class="left">客户申请退款</td>
                    <td class="left"><?php echo $order_refund['reason'] ?></td>
                </tr>
                <?php if ($order_refund['status'] == 'PHASE1_REFUSED') { ?>
                    <tr>
                        <?php $time = new DateTime($order_refund['phase1_updated_at']); ?>
                        <td class="left"><?php echo $time->format('Y-m-d H:i') ?></td>
                        <td class="left"><?php echo $order['phase1_user_name'];?>客服审核不通过</td>
                        <td class="left"><?php echo $order_refund['phase1_refused_reason'] ?></td>
                    </tr>
                <?php } ?>
                <?php if ($order_refund['status'] == 'PHASE1_PASSED' || $order_refund['status'] == 'PHASE2_REFUSED' || $order_refund['status'] == 'PHASE2_PASSED'|| $order_refund['status'] == 'DONE'|| $order_refund['status'] == 'ERROR') { ?>
                    <tr>
                        <?php $time = new DateTime($order_refund['phase1_updated_at']); ?>
                        <td class="left"><?php echo $time->format('Y-m-d H:i') ?></td>
                        <td class="left"><?php echo $order['phase1_user_name'];?>客服审核通过</td>
                        <td class="left"></td>
                    </tr>
                <?php } ?>
                <?php if ($order_refund['status'] == 'PHASE2_REFUSED') { ?>
                    <tr>
                        <?php $time = new DateTime($order_refund['phase2_updated_at']); ?>
                        <td class="left"><?php echo $time->format('Y-m-d H:i') ?></td>
                        <td class="left"><?php echo $order['phase2_user_name'];?>主管审核不通过</td>
                        <td class="left"><?php echo $order_refund['phase2_refused_reason'] ?></td>
                    </tr>
                <?php } ?>
                <?php if ($order_refund['status'] == 'PHASE2_PASSED' || $order_refund['status'] == 'DONE'|| $order_refund['status'] == 'ERROR') { ?>
                    <tr>
                        <?php $time = new DateTime($order_refund['phase2_updated_at']); ?>
                        <td class="left"><?php echo $time->format('Y-m-d H:i') ?></td>
                        <td class="left"><?php echo $order['phase2_user_name'];?>主管审核通过</td>
                        <td class="left"></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>

            <table class="form " style="width: 80%">
                <tbody>
                <?php if ($this->user->hasPermission('phase1','sale/order_refund_check') && ($order_refund['status'] == 'PENDING' || $order_refund['status'] == 'PHASE1_REFUSED')) { ?>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary pull-right confirm-btn" data-status="PHASE1_PASSED"
                                    type="button">客服审核通过
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80%">
                            <select id='phase1_fail_reason'>
                                <option value="菜品已生产">菜品已生产</option>
                                <option value="菜品已完成配送">菜品已完成配送</option>
                                <option value="客户原因造成">客户原因造成</option>
                                <option value="">自定义</option>
                            </select>
                            <input type="text" id="phase1_fail_custom_reason" disabled/>
                        </td>
                        <td>
                            <button class="btn btn-danger pull-right confirm-btn" data-status="PHASE1_REFUSED"
                                    data-reason="true" type="button">客服审核不通过
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($this->user->hasPermission('phase2','sale/order_refund_check') && ($order_refund['status'] == 'PHASE1_PASSED' || $order_refund['status'] == 'PHASE2_REFUSED')) { ?>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary pull-right confirm-btn" data-status="PHASE2_PASSED"
                                    type="button">主管审核通过
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%">
                            <select id='phase2_fail_reason'>
                                <option value="客户请求不合理，拒绝退款">客户请求不合理</option>
                                <option value="通过其它方式补偿客户">通过其它方式补偿客户</option>
                                <option value="">自定义</option>
                            </select>
                            <input type="text" id="phase2_fail_custom_reason" disabled/>
                        </td>
                        <td>
                            <button class="btn btn-danger pull-right confirm-btn" data-status="PHASE2_REFUSED"
                                    data-reason="true" type="button">主管审核不通过
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="tab-order" class="vtabs-content">
            <table class="form">
                <?php if ($order_status) { ?>
                    <tr class="highlight">
                        <td><?php echo $text_order_status; ?></td>
                        <td id="order-status"><h3><?php echo $order_status; ?></h3></td>
                    </tr>
                <?php } ?>
                <tr class="highlight">
                    <td><?php echo $text_order_id; ?></td>
                    <td>#<?php echo $order_id; ?>
                        <?php if ($p_order_id) { ?>
                            - (子订单)
                        <?php } ?>
                    </td>
                </tr>
                <tr class="highlight">
                    <td><?php echo $text_invoice_no; ?></td>
                    <td><?php echo $invoice_no; ?></td>
                </tr>
                <tr class="highlight">
                    <td>取菜日期</td>
                    <td><?php echo $pdate; ?></td>
                </tr>
                <?php if ($shipping_method) { ?>
                    <tr class="highlight">
                        <td>取菜地点</td>
                        <td><?php echo $shipping_method; ?></td>
                    </tr>
                    <tr class="highlight">
                        <td>取菜码</td>
                        <td><?php echo $pickup_code; ?></td>
                    </tr>
                <?php } ?>

                <?php if ($customer) { ?>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> </a></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td><?php echo $firstname; ?> </td>
                    </tr>
                <?php } ?>
                <?php if ($customer_group) { ?>
                    <tr>
                        <td><?php echo $text_customer_group; ?></td>
                        <td><?php echo $customer_group; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_email; ?></td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td><?php echo $text_telephone; ?></td>
                    <td><?php echo $telephone; ?></td>
                </tr>
                <?php if ($fax) { ?>
                    <tr>
                        <td><?php echo $text_fax; ?></td>
                        <td><?php echo $fax; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_store_name; ?></td>
                    <td><?php echo $store_name; ?></td>
                </tr>
                <tr>
                    <td><?php echo $text_store_url; ?></td>
                    <td><a onclick="window.open('<?php echo $store_url; ?>');"><u><?php echo $store_url; ?></u></a></td>
                </tr>
                <?php if (false) { ?>
                    <tr>
                        <td><?php echo $text_ip; ?></td>
                        <td><?php echo $ip; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_total; ?></td>
                    <td><?php echo $total; ?>
                        <?php if ($credit && $customer) { ?>
                            <?php if (!$credit_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_credit_add; ?>"
                                     title="<?php echo $text_credit_add; ?>" id="credit_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_credit_remove; ?>"
                                     title="<?php echo $text_credit_remove; ?>" id="credit_remove" class="icon"/>
                            <?php } ?>
                        <?php } ?></td>
                </tr>
                <?php if ($reward && $customer) { ?>
                    <tr>
                        <td><?php echo $text_reward; ?></td>
                        <td><?php echo $reward; ?>
                            <?php if (!$reward_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_reward_add; ?>"
                                     title="<?php echo $text_reward_add; ?>" id="reward_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_reward_remove; ?>"
                                     title="<?php echo $text_reward_remove; ?>" id="reward_remove" class="icon"/>
                            <?php } ?></td>
                    </tr>
                <?php } ?>

                <?php if ($comment) { ?>
                    <tr>
                        <td><?php echo $text_comment; ?></td>
                        <td><?php echo $comment; ?></td>
                    </tr>
                <?php } ?>
                <?php if ($affiliate) { ?>
                    <tr>
                        <td><?php echo $text_affiliate; ?></td>
                        <td>
                            <a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $text_commission; ?></td>
                        <td><?php echo $commission; ?>
                            <?php if (!$commission_total) { ?>
                                <img src="view/image/add.png" alt="<?php echo $text_commission_add; ?>"
                                     title="<?php echo $text_commission_add; ?>" id="commission_add" class="icon"/>
                            <?php } else { ?>
                                <img src="view/image/delete.png" alt="<?php echo $text_commission_remove; ?>"
                                     title="<?php echo $text_commission_remove; ?>" id="commission_remove"
                                     class="icon"/>
                            <?php } ?></td>
                    </tr>
                <?php } ?>
                <?php if ($invoice_detail_status) { ?>
                    <tr>
                        <td><?php echo $text_invoice_type; ?></td>
                        <td><?php echo $invoice_type; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_head; ?></td>
                        <td><?php echo $invoice_head; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_name; ?></td>
                        <td><?php echo $invoice_name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_invoice_content; ?></td>
                        <td><?php echo $invoice_content; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php echo $text_date_added; ?></td>
                    <td><?php echo $date_added; ?></td>
                </tr>
                <tr>
                    <td><?php echo $text_date_modified; ?></td>
                    <td><?php echo $date_modified; ?></td>
                </tr>
            </table>
        </div>


        <div id="tab-product" class="vtabs-content">
            <table class="form">
                <tr class="highlight">
                    <?php if ($order_status) { ?>
                        <td class="left"><?php echo $text_order_status; ?> : <?php echo $order_status; ?></td>
                    <?php } ?>
                    <td class="left"><?php echo $text_order_id; ?>#<?php echo $order_id; ?>
                        <?php if ($p_order_id) { ?>
                            - (子订单)
                        <?php } ?>
                    </td>
                    <td class="left">取菜日期 : <?php echo $pdate; ?></td>
                    <?php if ($shipping_method) { ?>
                        <td class="left">取菜地点 : <?php echo $shipping_method; ?></td>

                    <?php } ?>
                </tr>
            </table>
            <table id="product" class="list">
                <thead>
                <tr>
                    <td class="left"><?php echo $column_product; ?></td>
                    <td class="left"><?php echo $column_model; ?></td>
                    <td class="right"><?php echo $column_quantity; ?></td>
                    <td class="right"><?php echo $column_price; ?></td>
                    <td class="right"><?php echo $column_total; ?></td>
                </tr>
                </thead>
                <?php foreach ($products as $product) { ?>
                    <tbody id="product-row<?php echo $product['order_product_id']; ?>">
                    <tr>
                        <td class="left"><?php if ($product['product_id']) { ?>
                                <a href="<?php echo $product['href']; ?>"
                                   class="popup"><?php echo $product['name']; ?></a>
                            <?php } else { ?>
                                <?php echo $product['name']; ?>
                            <?php } ?>
                            <?php foreach ($product['option'] as $option) { ?>
                                <br/>
                                <?php if ($option['type'] != 'file') { ?>
                                    &nbsp;
                                    <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                <?php } else { ?>
                                    &nbsp;
                                    <small> - <?php echo $option['name']; ?>: <a
                                            href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a>
                                    </small>
                                <?php } ?>
                            <?php } ?></td>
                        <td class="left"><?php echo $product['model']; ?></td>
                        <td class="right"><?php echo $product['quantity']; ?></td>
                        <td class="right"><?php echo $product['price']; ?></td>
                        <td class="right"><?php echo $product['total']; ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>

            <table id="total" class="list">
                <thead>
                <tr>
                    <td colspan="4" class="right">
                        <?php if ($p_order_id) { ?>
                            <b>总单号 : #<?php echo $p_order_id; ?></b>
                        <?php } ?>
                    </td>
                    <td class="right">订单总计</td>
                </tr>
                </thead>
                <?php foreach ($sub_orders as $sub_order) { ?>
                    <tbody>
                    <tr>
                        <td colspan="4" class="right"></td>
                        <td class="right">#<?php echo $sub_order['order_id']; ?> - (子订单)</td>
                    </tr>
                    </tbody>
                <?php } ?>
                <?php foreach ($totals as $totals) { ?>
                    <tbody id="totals">
                    <tr>
                        <td colspan="4" class="right"><?php echo $totals['title']; ?>:</td>
                        <td class="right"><?php echo $totals['text']; ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>
            <?php if ($downloads) { ?>
                <h3><?php echo $text_download; ?></h3>
                <table class="list">
                    <thead>
                    <tr>
                        <td class="left"><b><?php echo $column_download; ?></b></td>
                        <td class="left"><b><?php echo $column_filename; ?></b></td>
                        <td class="right"><b><?php echo $column_remaining; ?></b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($downloads as $download) { ?>
                        <tr>
                            <td class="left"><?php echo $download['name']; ?></td>
                            <td class="left"><?php echo $download['filename']; ?></td>
                            <td class="right"><?php echo $download['remaining']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
        <div id="tab-history" class="vtabs-content">
            <div id="history"></div>
        </div>

        <div id="tab-discount" class="vtabs-content">
            <div id="discount-history"></div>
            <?php if (!$discount_status) { ?>
                <form id="discount_form">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_discount; ?></td>
                            <td><input type="text" name="discount" value=""/></td>
                        </tr>
                        <tr>
                            <td class="top"><?php echo $entry_discount_comment; ?></td>
                            <td>
                                <input type="text" name="comment" class="span6"/>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <a id="button-discount-history"
                                   class="btn"><span><?php echo $button_add_discount_hidtory; ?></span></a>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $('#reward_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#reward_add').fadeOut();

                    $('#reward_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_reward_remove; ?>" id="reward_remove" class="icon" />');

                    $('#reward_remove').fadeIn();
                }
            }
        });
    });

    $('#reward_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#reward_remove').fadeOut();

                    $('#reward_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_reward_add; ?>" id="reward_add" class="icon" />');

                    $('#reward_add').fadeIn();
                }
            }
        });
    });

    $('#commission_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#commission_add').fadeOut();

                    $('#commission_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_commission_remove; ?>" id="commission_remove" class="icon" />');

                    $('#commission_remove').fadeIn();
                }
            }
        });
    });

    $('#commission_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#commission_remove').fadeOut();

                    $('#commission_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_commission_add; ?>" id="commission_add" class="icon" />');

                    $('#commission_add').fadeIn();
                }
            }
        });
    });

    $('#credit_add').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/addcredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#credit_add').fadeOut();

                    $('#credit_add').replaceWith('<img src="view/image/delete.png" alt="<?php echo $text_credit_remove; ?>" id="credit_remove" class="icon" />');

                    $('#credit_remove').fadeIn();
                }
            }
        });
    });

    $('#credit_remove').live('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/removecredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'json',
            success: function (json) {
                if (json.error) {
                    alert(json.error);
                }

                if (json.success) {
                    alert(json.success);

                    $('#credit_remove').fadeOut();

                    $('#credit_remove').replaceWith('<img src="view/image/add.png" alt="<?php echo $text_credit_add; ?>" id="credit_add" class="icon" />');

                    $('#credit_add').fadeIn();
                }
            }
        });
    });

    $('#history .pagination a').live('click', function () {
        $('#history').load(this.href);

        return false;
    });

    $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&readonly=1');


    //新增折扣优惠逻辑
    $('#button-discount-history').bind('click', function () {
        $.ajax({
            type: 'POST',
            url: 'index.php?route=sale/order/discount&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
            dataType: 'html',
            data: $('#discount_form').serialize(),
            beforeSend: function () {
                $('.success, .warning').remove();
                $('#button-discount-history').attr('disabled', true);
                $('#discount-history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function () {
                $('#button-discount-history').attr('disabled', false);
                $('.attention').remove();
            },
            success: function (html) {
                $('#discount-history').html(html);

                $('#button-history').forms[0].reset();
            }
        });
    });

    $('#discount-history .pagination a').live('click', function () {
        $('#discount-history').load(this.href);

        return false;
    });

    $('#discount-history').load('index.php?route=sale/order/discount&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');


    $('#phase1_fail_reason').change(function () {
        if ($(this).val() == '') {
            $('#phase1_fail_custom_reason').removeAttr('disabled');
        } else {
            $('#phase1_fail_custom_reason').attr('disabled', 'disabled');
        }
    });
    $('#phase2_fail_reason').change(function () {
        if ($(this).val() == '') {
            $('#phase2_fail_custom_reason').removeAttr('disabled');
        } else {
            $('#phase2_fail_custom_reason').attr('disabled', 'disabled');
        }
    });

    var confirmUrl = 'index.php?route=sale/order_refund_check/confirm&token=<?php echo $token; ?>&order_refund_id=<?php echo $order_refund['order_refund_id']; ?>';
    $('.confirm-btn').click(function () {
        $this = $(this);
        var status = $this.data('status');
        var needReason = $this.data('reason');
        var reason;
        if (needReason) {
            var $select = $this.parent('td').prev('td').find('select');
            var $custom = $this.parent('td').prev('td').find(':text');
            reason = !!$select.val() ? $select.val() : $custom.val();
            if (!reason) {
                alert('须填写不通过原因');
                return;
            }
        } else {
            if (!confirm('确认通过审核?')) {
                return;
            }
        }
        console.log(confirmUrl);
        $.ajax({
            type: 'POST',
            url: confirmUrl,
            dataType: 'json',
            data: {
                reason: reason,
                status: status
            },
            fail: function () {
                alert('操作失败');
            },
            success: function (html) {
                window.location.reload();
            }
        });

    });


    //--></script>
<script type="text/javascript"><!--
    $('.vtabs a').tabs();
    //--></script>
