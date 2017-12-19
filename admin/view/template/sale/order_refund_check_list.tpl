<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<style>
.htabs {
    line-height: 16px;
    border-bottom: 1px solid #DDDDDD;
    margin-bottom: 0px;
    float: left;
    margin-left:15px;
}
</style>
<div class="box">
    <div class="heading">
    <h2> <?php echo $heading_title; ?></h2>
     <div class="htabs">
     <a href="index.php?route=sale/order_refund_check&filter_order_refund_status=all" <?php if($filter_order_refund_status=='all'){ echo 'class="selected"';}?>>全部</a>
     <?php if($order_refund_statuses){ foreach($order_refund_statuses as $status){ ?>
     <a href="index.php?route=sale/order_refund_check&filter_order_refund_status=<?php echo $status[value];?>" <?php if($filter_order_refund_status==$status[value]){ echo 'class="selected"';$childstatus=$status[children];}?>><?php echo $status[name];?></a>
     <?php }}?>
     </div>
        <div class="buttons">
        <?php foreach($childstatus as $status){?>
        <a onclick="$('#form').attr('action','index.php?route=sale/order_refund_check/confirm&status=<?php echo $status['value']; ?>');$('#form').submit();" class="btn btn-primary"><span><?php echo $status['name']; ?></span></a> 
        <?php }?>
        </div>
    </div>
    <div class="content">
        <form method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;">
                        <input type="checkbox" name="order_refund_id_page" onclick="$('input[name*=\'order_refund_id\']').attr('checked', this.checked);"/>
                    </td>
                     <td class="left">
                        <?php if ($sort == 'o.order_refund_id') { ?>
                            <a href="<?php echo $sort_order; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_order_refund_id; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_order; ?>"><?php echo $column_order_refund_id; ?></a>
                        <?php } ?></td>
                    <td class="left">
                        <?php if ($sort == 'o.order_id') { ?>
                            <a href="<?php echo $sort_order; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                        <?php } ?>|交易号</td>
                    <td class="left">
                        <a><?php echo $column_customer_phone; ?></a>
                    </td>
                     <td class="right">付款方式</td>
                     <td class="right">付款</td>
                    <td class="left"><?php if ($sort == 'payment_code') { ?>
                            <a href="<?php echo $sort_status; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_payment; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>"><?php echo $column_payment; ?></a>
                        <?php } ?></td>
                   
                    <td class="right"><?php if ($sort == 'o.value') { ?>
                            <a href="<?php echo $sort_order; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_value; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_value; ?>"><?php echo $column_value; ?></a>
                        <?php } ?></td>
                         <td class="left">
                         <?php echo $column_reason; ?>
                         </td>
                          <td class="left">
                         <?php echo $column_status; ?>
                         </td>
                    <td class="left">审核时间</td>
                    <!-- td class="left"><?php echo $column_date_modified; ?></td-->
                    <td>来源</td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td><input type="hidden" name="filter_order_refund_status" value="<?php echo $filter_order_refund_status;?>"></td>
                      <td align="left">
                        <input type="text" name="filter_order_refund_id" value="<?php echo $filter_order_refund_id; ?>" size="4"
                               style="text-align: right;width:120px;"/>
                    </td>
                    <td align="left">
                        <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4"
                               style="text-align: right;width:120px;"/>
                    </td>
                    <td><input type="text" name="filter_customer_phone" value="<?php echo $filter_customer_phone; ?>" size="4"/>
                    </td>
                     <td align="right">
                    </td>
                     <td align="right">
                    </td>
                    <td><select name="filter_payment_code" class="span2">
                    <option value="">所有</option>
                            <?php foreach ($payments as $key=> $payment) { ?>
                                <?php if ($payment[code] == $filter_payment_code) { ?>
                                    <option value="<?php echo $payment[code]; ?>"
                                            selected="selected"><?php echo $payment[title]; ?></option>
                                <?php } else { ?>
                                    <option
                                        value="<?php echo $payment[code]; ?>"><?php echo $payment[title]; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></td>
                   
                     <td align="right">
                    </td> <td align="right">
                    </td> <td align="right">
                    </td>
                    <td></td>
                    <td><select name="filter_partner_code" class="span2">
                            <option value=""></option>

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
                    <td align="right"><a onclick="filter();"
                                         class="button"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($orders) { ?>
                    <?php foreach ($orders as $order) { ?>

                        <tr>
                            <td style="text-align: center;"><?php if ($order['selected']) { ?>
                                    <input type="checkbox" name="order_refund_id[]" value="<?php echo $order['order_refund_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="order_refund_id[]" value="<?php echo $order['order_refund_id']; ?>"/>
                                <?php } ?></td>
                                 <td class="left">
                                <?php echo $order['order_refund_id']; ?>
                            </td>
                            <td class="left">

                                <?php echo $order['order_id'].($order['payment_trade_no']?'|'.$order['payment_trade_no']:''); ?>
                                <?php if ($order['p_order_id'] != '') { ?>
                                    -  (子订单)
                                <?php } ?>
                            </td>
                            <td class="left"><?php echo $order['telephone']; ?></td>
                            <td class="right"><?php echo $order['payment_code1']; ?></td>
                            <td class="right"><?php echo $order['value1']; ?></td>
                            <td class="left"><?php echo $order['payment_code'].($order[payment_account]?'|'.$order[payment_account].'|'.$order[payment_name]:''); ?></td>
                            <td class="right"><?php echo $order['value']; ?></td>
                            <td class="left" style="width:150px;"><?php echo '=>'.$order['reason'].'<br/>'.$order['phase1_user_name'].'=>'.$order['phase1_refused_reason'].'<br/>'.$order['phase2_user_name'].'=>'.$order['phase2_refused_reason'].'<br/>=>'.$order['rq']['message']; ?></td>
                            <td class="left"><a onclick="$('#form').attr('action','index.php?route=sale/order_refund_check&filter_order_refund_status=<?php echo $order['status']; ?>');$('#form').submit();"><?php echo EnumOrderRefundStatus::getOrderRefundStatus($order['status']).($order['comment']?'|'.$order['comment'].$refunderrors[$order['payment_code']][$order['comment']]:''); ?></a></td>
                            <td class="left"><?php echo '=>'.$order['created_at'].'<br/>'.$order['phase1_user_name'].'=>'.$order['phase1_updated_at'].'<br/>'.$order['phase2_user_name'].'=>'.$order['phase2_updated_at']; ?></td>
                            <td class="left"><?php echo $order['partner']; ?></td>
                            <td class="right"><?php foreach ($order['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
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
        <div class="pagination"> <input type="checkbox" name="selectallpage" onclick="$('input[name*=\'order_refund_id\']').attr('checked', this.checked);"/>选中所有页面<?php echo $pagination; ?></div>
    </div>
</div>

<script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=sale/order_refund_check&token=<?php echo $token; ?>';

        var paramGropus=[];
        $('#search_filter').find(':input').not(':button').each(function () {
            var field$=$(this);
            var name=field$.attr('name');
            var val=field$.val();
            if(name && val){
                paramGropus.push(name+'='+encodeURIComponent(val));
            }
        });
        url+='&'+ paramGropus.join('&');
        window.location.href=url;
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