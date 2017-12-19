<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2> 分拣单 - 订单列表</h2>

        <div class="buttons">
            <?php if ( $total > 0) { ?>
                <a class="btn"
                   href="index.php?route=sale/pick_stock/print_dishes&point_id=<?php echo $filter_point_id; ?>"><span>菜品打印</span></a>
                <a class="btn"
                   href="index.php?route=sale/pick_stock/print_orders&point_id=<?php echo $filter_point_id; ?>"><span>订单菜品打印</span></a>
            <?php } ?>
            <a class="btn" href="index.php?route=sale/pick_stock"><span>返回</span></a>
        </div>
    </div>
    <div class="content">
        <form action="" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;">
                        <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                    <td class="left">
                        <?php if ($sort == 'o.order_id') { ?>
                            <a href="<?php echo $sort_order; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'customer') { ?>
                            <a href="<?php echo $sort_customer; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                        <?php } ?></td>
                    <td class="left">
                        <a><?php echo $column_customer_phone; ?></a>
                    </td>
                    <td class="left"><?php if ($sort == 'status') { ?>
                            <a href="<?php echo $sort_status; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></td>
                    <!--<td class="right"><?php /*if ($sort == 'o.total') { */ ?>
                            <a href="<?php /*echo $sort_total; */ ?>"
                               class="<?php /*echo strtolower($order); */ ?>"><?php /*echo $column_total; */ ?></a>
                        <?php /*} else { */ ?>
                            <a href="<?php /*echo $sort_total; */ ?>"><?php /*echo $column_total; */ ?></a>
                        <?php /*} */ ?></td>-->
                    <td class="left"><?php if ($sort == 'o.date_added') { ?>
                            <a href="<?php echo $sort_date_added; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'o.date_modified') { ?>
                            <a href="<?php echo $sort_date_modified; ?>"
                               class="<?php echo strtolower($order); ?>">取菜时间</a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_modified; ?>">取菜时间</a>
                        <?php } ?></td>
                    <td>来源</td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <input type="hidden" value="<?php echo $filter_point_id; ?>" name="filter_point_id"/>
                    <td></td>
                    <td align="left">
                        <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4"
                               style="text-align: right;width:120px;"/>
                    </td>
                    <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>"/></td>
                    <td><input type="tel" name="filter_customer_phone" value="<?php echo $filter_customer_phone; ?>"/>
                    </td>
                    <td>
                    </td>
                    <!-- <td align="right"><input type="text" name="filter_total" class="span2"
                                             value="<?php /*echo $filter_total; */ ?>" size="4" style="text-align: right;"/>
                    </td>-->
                    <td>
                    </td>
                    <td><input type="text" class="date" size="12" value="<?php echo $filter_pdate; ?>" name="filter_pdate" style="margin-bottom: 0" ></td>

                    <td>
                    </td>
                    <td align="right"><a onclick="filter();"
                                         class="button"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if (isset($orders)) { ?>
                    <?php foreach ($orders as $order) { ?>

                        <tr>
                            <td style="text-align: center;"><?php if ($order['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left">

                                <?php echo $order['order_id']; ?>
                                <?php if ($order['p_order_id'] != '') { ?>
                                    -  (子订单)
                                <?php } ?>
                            </td>
                            <td class="left"><a
                                    href="mailto:<?php echo $order['customer']; ?>"><?php echo $order['customer']; ?></a>
                            </td>
                            <td class="left"><?php echo $order['telephone']; ?></td>
                            <td class="left"><?php echo $order['status']; ?></td>
                            <!--                            <td class="right">-->
                            <?php //echo $order['total']; ?><!--</td>-->
                            <td class="left"><?php echo $order['date_added']; ?></td>
                            <td class="left"><?php echo $order['pdate']; ?></td>
                            <td class="left"><?php echo $order['partner']; ?></td>
                            <td class="right"><?php foreach ($order['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?></td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>

<script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=sale/pick_stock/orders';

        var paramGropus = [];
        $('#search_filter').find(':input').not(':button').each(function () {
            var field$ = $(this);
            var name = field$.attr('name');
            var val = field$.val();
            if (val || name=='filter_pdate') {
                paramGropus.push(name + '=' + encodeURIComponent(val));
            }
        });
        url += '&' + paramGropus.join('&');
        window.location.href = url;
    }
    //--></script>
<script type="text/javascript"><!--
    $(document).ready(function () {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    });
    //--></script>
<script type="text/javascript"><!--
    $('#form input').keydown(function (e) {
        if (e.keyCode == 13) {
            filter();
        }
    });

    $('#form input[type="checkbox"]').each(function () {
        $(this).click(function () {
            var checkNum = $('#form input[name*="selected"]:checked').length;

            if (checkNum > 0) {
                $('#btn_shiporder').attr('disabled', false);
            } else {
                $('#btn_shiporder').attr('disabled', true);
            }
        });
    });
    //--></script>