<div class="box">
    <div class="heading">
        <h2><img src="view/image/order.png" alt=""/> <?php echo (isset($pre_generate) ? '生成' : '') . $heading_title; ?>
        </h2>


        <div class="buttons noprint" style="margin-bottom:10px;">
            <?php if (isset($pre_generate)) { ?>
                <button type="button" id="btn-submit" class="btn btn-primary"
                        data-url="<?php echo $link_confirm ?>"><?php echo $button_confirm; ?></button>
                <button type="button" onclick="history.go(-1)" class="btn"><?php echo $button_cancel; ?></button>
            <?php } else { ?>
                <button onclick="window.print();" class="btn">打印</button>
                <?php if ($order['status'] == 'PENDING') { ?>
                    <button id="confirm-done-btn" class="btn" data-url="<?php echo $link_confirm_done; ?>">已完成</button>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
    <div class="content">
        <div class="vtabs noprint">
            <a href="#tab-product"><?php echo $tab_order; ?></a>
        </div>


        <div id="tab-product" class="vtabs-content i-main-print-zone i-force-print-full-width">
            <table class="form">
                <tr class="highlight">
                    <?php if (isset($pre_generate)) { ?>
                        <td class="left" style="width: 25%">发注日期 :
                            <?php $now=new DateTime(); ?>
                            <input type="date" class="date" value="<?php echo $now->format('Y-m-d'); ?>"
                                   name="operate_date" style="margin: 0">
                        </td>
                    <?php } else { ?>
                        <td class="left" style="width: 25%">批次 : <?php echo $order['serial_no']; ?></td>
                        <td class="left" style="width: 25%">创建时间 : <?php echo $order['created_at']; ?></td>
                        <td class="left" style="width: 25%">发注日期
                            : <?php echo(date($date_format_short, strtotime($order['operate_date']))); ?>
                        </td>
                        <td class="left" style="width: 25%">状态 : <?php echo $order['status_text']; ?></td>
                    <?php } ?>
                </tr>
            </table>

            <table id="products-tbl" class="list">
                <thead>
                <tr>
                    <td class="left" style="width: 25%"><?php echo $column_product; ?></td>
                    <td class="left" style="width: 5%"><?php echo $column_quantity; ?></td>
                    <!--                    <td class="right" style="width: 15%">-->
                    <?php //echo $column_date_pick; ?><!--</td>-->
                    <td class="left" style="width: 35%"><?php echo $column_comment; ?></td>
                </tr>
                </thead>
                <?php foreach ($products as $product) { ?>
                    <tbody>
                    <tr>
                        <td class="left">
                            <a href="<?php echo $link_site_product_detail_prefix . $product['product_id']; ?>"
                               class="popup"><?php echo $product['name']; ?></a>
                        </td>

                        <td class="left"><?php echo $product['quantity']; ?></td>
                        <td class="left">
                            <?php if (isset($pre_generate)) { ?>
                                <input type="text" style="margin-bottom: 0;width: 95%;" class="p-comment"
                                       data-pid="<?php echo $product['product_id'] ?>"
                                       value="<?php echo isset($product['comment'])?$product['comment']:''; ?>"/>
                            <?php } else {
                                echo isset($product['comment'])?$product['comment']:'';
                            } ?>
                        </td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>

        </div>
    </div>
</div>


<script type="text/javascript">
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});

    $('.vtabs a').tabs();

    var rawProducts =<?php echo json_encode($products); ?>;
    var rawOrderIds =<?php echo json_encode($orderIds); ?>;
    var infoPrefixUrl = '<?php echo $link_info_prefix; ?>'.replace(/&amp;/g, '&');

    $('#btn-submit').click(function () {
        var data = [];
        $('#products-tbl').find('tbody tr ').find('.p-comment').each(function () {
            var pid = $(this).data('pid');
            var val = $(this).val();

            var product = _.filter(rawProducts, function (p) {
                return p.product_id == pid;
            })[0];
            data.push({
                pid: pid,
                num: product.quantity,
                cmt: val
            })
        });
        console.log('size:', data.length);
        var operate_date = $('[name=operate_date]').val();

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            dataType: 'json',
            data: {
                data: data,
                orderIds: rawOrderIds,
                operate_date: operate_date
            },
            success: function (result) {
                console.log(result);
                var purchase_id = result.purchase_id;
                window.location.href = infoPrefixUrl + purchase_id;

            },
            error: function (e) {
                console.error(e);
            }
        });

//        $.post($(this).data('url'),{data:JSON.stringify(rawProducts)})
//            .success(function (result) {
//                console.log(result);
//            });
    });

    $('#confirm-done-btn').click(function () {
        if (!confirm('确认已完成?')) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            dataType: 'json',
            data: {
                status: 'DONE'
            },
            success: function (result) {
                window.location.reload();

            },
            error: function (e) {
                console.error(e);
            }
        });
    });
</script>
