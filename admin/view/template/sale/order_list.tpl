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
            <?php if ($this->user->permitOr(array('super_admin', 'sale_orders:add'))) { ?>
                <button class="btn btn-primary" onclick="insert()";>添加</button>
            <?php } ?>
<script>
function insert(){
    var obj=$("input[name='selected[]']");
    var s='';
    for(var i=0; i<obj.length; i++){
        if(obj[i].checked){ s=obj[i].value; //如果选中，将value添加到变量s中
        break;}
        } 
    
    
    window.location.href="<?php echo $link_insert_order ?>&order_id="+s;
    
}
</script>
            <button
                onclick="$('#form').attr('action', '<?php echo $export_select; ?>'); $('#form').attr('target', '_self'); $('#form').submit();"
                class="btn btn-success"><?php echo $button_export; ?></button>

            <button id="btn_shiporder"
                    onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();"
                    disabled="disabled" class="btn btn-primary"><?php echo $button_invoice; ?></button>

            <?php if ($this->user->permitOr(array('super_admin', 'sale_orders:update'))) { ?>
                <button
                    onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();"
                    class="btn btn-danger"><?php echo $button_delete; ?></button>
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="form1">
            <table class="form">
                <tr>
                    <td colspan="2">上传对应的订单excel</td>
                </tr>
                <tr>
                    <td>订单导入：</td>
                    <td><input type="file" name="upload"/>
                        <a onclick="$('#form1').submit();" class="btn btn-success"><?php echo $button_import; ?></a>
                    </td>
                </tr>
            </table>

        </form>

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
                    <td class="left" >
                        <a><?php echo $column_customer_phone; ?></a>
                    </td>
                    <td class="left"><?php if ($sort == 'status') { ?>
                            <a href="<?php echo $sort_status; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></td>
					<td class="left"><?php if ($sort == 'customer') { ?>
                            <a href="<?php echo $sort_customer; ?>"
                               class="<?php echo strtolower($order); ?>">支付方式</a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_customer; ?>">支付方式</a>
                        <?php } ?></td>
                    <td class="right"><?php if ($sort == 'o.total') { ?>
                            <a href="<?php echo $sort_total; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'o.date_added') { ?>
                            <a href="<?php echo $sort_date_added; ?>"
                               class="<?php echo strtolower($date_added); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'o.pdate') { ?>
                            <a href="<?php echo $sort_date_pick; ?>"
                               class="<?php echo strtolower($pdate); ?>">预定时间</a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_pick; ?>">预定时间</a>
                        <?php } ?></td>
                    <td>来源</td>
                    <td>设备</td>
                    <td>配送方式</td>
					<td>订单类型</td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter">
                    <td></td>
                    <td align="left">
                        <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4"
                               style="text-align: right;width:100px;"/>
                    </td>
                    <td><input type="text" class="span2" name="filter_customer_phone" value="<?php echo $filter_customer_phone; ?>"/>
                    </td>
                    <td><select name="filter_order_status_id" class="span2">
                            <option value="*"><?php echo $text_all_orders; ?></option>
                            <?php if ($filter_order_status_id == '0') { ?>
                                <option value="0" selected="selected"><?php echo $text_abandoned_orders; ?></option>
                            <?php } else { ?>
                                <option value="0"><?php echo $text_abandoned_orders; ?></option>
                            <?php } ?>
                            <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"
                                            selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                    <option
                                        value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></td>
					<td>
						<select name="payment_code" class="span2">
							<option value="*"></option>
							<?php foreach($method_arr as $k => $m){?>
							<option value="<?php echo $k?>" <?php if($k == $payment_code){echo 'selected';}?>><?php echo $m?></option>
							<?php } ?>
						</select>
					</td>
                    <td align="right"><input type="text" name="filter_total" class="span2"
                                             value="<?php echo $filter_total; ?>" size="4" style="text-align: right; width:80px"/>
                    </td>
                    <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12"
                               class="date"/></td>
                    <td><input type="text" name="filter_date_pick" value="<?php echo $filter_date_pick; ?>"
                               size="12" class="date"/></td>
                    <td><select name="filter_partner_code" class="span2">
                            <option value="*"></option>

                            <?php if ($filter_partner_code == '0') { ?>
                                <option value="0" selected="selected">内站</option>
                            <?php } else { ?>
                                <option value="0">内站</option>
                            <?php } ?>

                            <?php foreach ($partners as $key => $value) { ?>
                                <option
                                    value="<?php echo $key; ?>" <?php if ($key == $filter_partner_code) { ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
                            <?php } ?>

                        </select></td>
                    <td><select name="filter_source_from" class="span2">
                            <option value="*"></option>
                            <?php foreach($source_from_options as $option) {?>
                                <option value="<?php echo $option['value']; ?>" <?php if(isset($filter_source_from)&&$option['value']==$filter_source_from) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></option>
                            <?php }  ?>
                        </select>
                    </td>
                    <td><input type="text" name="filter_point_name" value="<?php echo $filter_point_name; ?>" placeholder="自提点或者配送站名称" />
                    <select name="filter_point_id" class="span2">
                            <option value="*"></option>
                            <option value="0"<?php if($filter_point_id==='0') {?> selected="selected"<?php } ?>>宅配</option>
                            <?php  foreach($shipping_point_options as $key=> $groups) {?>
                            <optgroup label="<?php echo $key;?>"><?php echo $key;?></optgroup>
                             <?php foreach($groups as $option) {?>
                                <option value="<?php echo $option['value']; ?>" <?php if($option['value']==$filter_point_id) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></option>
                            <?php }  ?>
                            <?php }  ?>
                        </select>
                        </td>
					<td>
						<select name="order_type" class="span2">
							<option value="*"></option>
							<?php foreach($order_type_arr as $k => $o){?>
							<option value="<?php echo $k?>" <?php if($k === $order_type){echo 'selected';}?>><?php echo $o?></option>
							<?php } ?>
						</select>
					</td>
                    <td align="right"><a onclick="filter();"
                                         class="btn btn-success"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($orders) { ?>
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
                            <td class="left"><?php echo $order['telephone']; ?></td>
                            <td class="left"><?php echo $order['status']; ?></td>
                            <td class="left"><?php echo $order['payment_method']; ?></td>
							<td class="right"><?php echo $order['total']; ?></td>
                            <td class="left"><?php echo $order['date_added']; ?></td>
                            <td class="left"><?php if($order['shipping_point_id']){echo $order['pdate'];}else{ echo $order['shipping_time'];} ?></td>
                            <td class="left"><?php echo $order['partner']; ?></td>
                            <td class="left"><?php echo $order['source_from']; ?></td>
                            <td class="left"><?php echo $order['shipping_method']; ?></td>
							<td class="left"><?php echo $order_type_arr[$order['order_type']]; ?></td>
                            <td class="right"><?php foreach ($order['action'] as $action) { ?>

                                    <?php if ($this->user->permitOr(array('super_admin', 'sale_orders:detail'))) { ?>
                                        [ <a href="<?php echo $action['href']; ?>" <?php if(empty($action['href'])) {echo $sty;} ?> ><?php echo $action['text']; ?></a> ]
                                    <?php } ?>
                                <?php } ?></td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="13"><?php echo $text_no_results; ?></td>
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
        url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

        var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_customer = $('input[name=\'filter_customer\']').attr('value');

        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }

        var filter_customer_phone = $('input[name=\'filter_customer_phone\']').attr('value');

        if (filter_customer_phone) {
            url += '&filter_customer_phone=' + encodeURIComponent(filter_customer_phone);
        }
        var filter_point_name = $('input[name=\'filter_point_name\']').attr('value');

        if (filter_point_name) {
            url += '&filter_point_name=' + encodeURIComponent(filter_point_name);
        }

        var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

        if (filter_order_status_id != '*') {
            url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
        }

        var filter_partner_code = $('select[name=\'filter_partner_code\']').attr('value');

        if (filter_partner_code != '*') {
            url += '&filter_partner_code=' + encodeURIComponent(filter_partner_code);
        }

        var filter_source_from = $('select[name=\'filter_source_from\']').attr('value');

        if (filter_source_from != '*') {
            url += '&filter_source_from=' + encodeURIComponent(filter_source_from);
        }

        var filter_point_id = $('select[name=\'filter_point_id\']').attr('value');

        if (filter_point_id != '*') {
            url += '&filter_point_id=' + encodeURIComponent(filter_point_id);
        }

        var filter_total = $('input[name=\'filter_total\']').attr('value');

        if (filter_total) {
            url += '&filter_total=' + encodeURIComponent(filter_total);
        }

        var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

        if (filter_date_added) {
            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
        }

        var filter_date_pick = $('input[name=\'filter_date_pick\']').attr('value');

        if (filter_date_pick) {
            url += '&filter_date_pick=' + encodeURIComponent(filter_date_pick);
        }
		
		var payment_code = $('select[name=\'payment_code\']').attr('value');

        if (payment_code != '*') {
            url += '&payment_code=' + encodeURIComponent(payment_code);
        }
		
		var order_type = $('select[name=\'order_type\']').attr('value');
        if (order_type != '*') {
            url += '&order_type=' + encodeURIComponent(order_type);
        }

        location = url;
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