<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2> <?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'purchase_orders:source_order:confirm'))) { ?>
                <a class="btn btn-primary"
                   href="<?php echo $link_source_order_confirm; ?>">订单审核</a>
            <?php } ?>
            <?php if ($this->user->permitOr(array('super_admin', 'purchase_orders:add'))) { ?>
                <a class="btn btn-primary" id="gen_purchse_btn"
                   data-confirm-url="<?php echo $link_confirm_gen_purchase_order; ?>"
                   data-gen-url="<?php echo $link_gen_purchase_order; ?>">生成发注</a>
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <form action="index.php" method="get" enctype="multipart/form-data" id="form">
            <input type="hidden" name="route" value="sale/order_purchase"/>
            <input type="hidden" name="token" value="<?php echo $token; ?>"/>
            <table class="list">
                <thead>
                <tr>
                    <!--                    <td width="1" style="text-align: center;">-->
                    <!--                        <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>-->
                    <!--                    </td>-->
                    <td class="left">
                        <?php if ($sort == 'serial_no') { ?>
                            <a href="<?php echo $link_sort_serial_no; ?>"
                               class="<?php echo strtolower($order); ?>">批次</a>
                        <?php } else { ?>
                            <a href="<?php echo $link_sort_serial_no; ?>">批次</a>
                        <?php } ?></td>
                    <td class="left">状态</td>
                    <td class="left"><?php if ($sort == 'operate_date') { ?>
                            <a href="<?php echo $link_sort_operate_date; ?>"
                               class="<?php echo strtolower($order); ?>">发注时间</a>
                        <?php } else { ?>
                            <a href="<?php echo $link_sort_operate_date; ?>">发注时间</a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'created_at') { ?>
                            <a href="<?php echo $link_sort_created_at; ?>"
                               class="<?php echo strtolower($order); ?>">创建时间</a>
                        <?php } else { ?>
                            <a href="<?php echo $link_sort_created_at; ?>">创建时间</a>
                        <?php } ?></td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td align="left">
                        <input type="text" name="filter_order_serial_no" value="<?php echo $filter_order_serial_no; ?>"
                               style="width:95%;"/>
                    </td>
                    <td>
                        <select name="filter_order_status" class="span2">
                            <option value="">全部</option>
                            <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['id'] == $filter_order_status) { ?>
                                    <option value="<?php echo $order_status['id']; ?>"
                                            selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                    <option
                                        value="<?php echo $order_status['id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                    <td></td>
                    <td></td>
                    <td align="right"><a id="search-btn"
                                         class="button"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($orders) { ?>
                    <?php foreach ($orders as $order) { ?>

                        <tr>
                            <td class="left"><?php echo $order['serial_no']; ?></td>
                            <td class="left"><?php echo $order['status_text']; ?></td>
                            <td class="left">
                                <?php echo date('Y-m-d', strtotime($order['operate_date'])); ?>
                            <td class="left">
                                <?php echo date('Y-m-d H:i:s', strtotime($order['created_at'])); ?>
                            </td>
                            <td class="right">
                                <?php if ($this->user->permitOr(array('super_admin', 'purchase_orders:detail'))) { ?>
                                    [ <a href="<?php echo $link_detail_page_prefix . $order['id']; ?>">查看</a> ]
                                <?php } ?>
                            </td>
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

<script type="text/javascript">

    $(document).ready(function () {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});

        $('#search-btn').click(function () {
            $('#form').submit();
        });

        $('#gen_purchse_btn').click(function () {
            var confirmUrl = $(this).data('confirmUrl');
            var genUrl = $(this).data('genUrl');
            $.getJSON(confirmUrl)
                .success(function (result) {
                    if (!result || !result.need_to_generate) {
                        alert('没有订单需要生成发注单');
                        return;
                    }

                    window.location.href = genUrl;
                });

        });
    });
</script>