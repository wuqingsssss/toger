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
     <?php if($payments){foreach($payments as $key=>$payment){ ?>
     <a href="<?php echo $payment['listurl'];?>" <?php if($filter_payment_code==$key){ echo 'class="selected"';$payaction=$payment['payurl'];}?>><?php echo $payment['title'];?></a>
     <?php }}?>
     </div>
        <div class="buttons"><?php if($payaction){?>
        <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_refund; ?></span></a> 
         <?php }?>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $payaction;?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;">
                    <input type="hidden" name="filter_payment_code" value="<?php echo $filter_payment_code;?>">
                        <input type="checkbox" name="selected_page" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
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
                        <?php } ?></td>
                    <td class="left">
                        <a><?php echo $column_customer_phone; ?></a>
                    </td>
                    <td class="left"><?php echo $column_status; ?></td>
                    <td class="right"><?php if ($sort == 'o.total') { ?>
                            <a href="<?php echo $sort_total; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                        <?php } ?></td>
                         <td class="right">付款方式</td>
                     <td class="right">付款</td>
                    <td class="right"><?php if ($sort == 'o.value') { ?>
                            <a href="<?php echo $sort_order; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_value; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_value; ?>"><?php echo $column_value; ?></a>
                        <?php } ?></td>
                          <td class="left">
                         <?php echo $column_payment; ?>
                         </td>
                         <td class="left">
                         <?php echo $column_reason; ?>
                         </td>
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
                    <td></td>
                      <td align="left">
                        <input type="text" name="filter_order_refund_id" value="<?php echo $filter_order_refund_id; ?>" size="4"
                               style="text-align: right;width:120px;"/>
                    </td>
                    <td align="left">
                        <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4"
                               style="text-align: right;width:120px;"/>
                    </td>
                    <td><input type="text" name="filter_customer_phone" value="<?php echo $filter_customer_phone; ?>" size="4"/>
                    </td><td></td><td></td>
                    <td></td>
                    <td align="right">
                    </td>
                     <td align="right">
                    </td> 
                     <td align="right">
                    </td> <td align="right">
                    </td>
                    <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12"
                               class="date"/></td>
                    <td><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>"
                               size="12" class="date"/></td>
                    <td><select name="filter_partner_code" class="span2">
                            <option value=""></option>

                            <?php if ($filter_partner_code == '0') { ?>
                                <option value="0" selected="selected">内站</option>
                            <?php } else { ?>
                                <option value="0">内站</option>
                            <?php } ?>

                            <?php foreach ($partners as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php if ($key == $filter_partner_code) { ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
                            <?php } ?>

                        </select></td>
                    <td align="right"><a onclick="filter();"
                                         class="button"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($orders) { ?>
                    <?php foreach ($orders as $order) { ?>

                        <tr>
                            <td style="text-align: center;"><?php if ($order['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_refund_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_refund_id']; ?>"/>
                                <?php } ?></td>
                                 <td class="left">
                                <?php echo $order['order_refund_id']; ?>
                            </td>
                            <td class="left">

                                <?php echo $order['order_id']; ?>
                                <?php if ($order['p_order_id'] != '') { ?>
                                    -  (子订单)
                                <?php } ?>
                            </td>
                            <td class="left"><?php echo $order['telephone']; ?></td>
                            <td class="left"><?php echo EnumOrderRefundStatus::getOrderRefundStatus($order['status']); ?></td>
                            <td class="left"><?php echo $order['total']; ?></td>
                            <td class="left"><?php echo $order['payment_code1']; ?></td>
                            <td class="right"><?php echo $order['value1']; ?></td>
                            <td class="right"><?php echo $order['value']; ?></td>
                            <td class="left"><?php echo $order['payment_code'].($order[payment_account]?'|'.$order[payment_account].'|'.$order[payment_name]:''); ?></td>
                            <td class="left"><?php echo $order['reason'].'=>'.$order['phase1_refused_reason'].'=>'.$order['phase2_refused_reason']; ?></td>
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
                        <td class="center" colspan="15"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
          <div class="pagination"> <input type="checkbox" name="selectallpage" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>选中所有页面<?php echo $pagination; ?></div>
        </form>
       </div>
</div>

<script type="text/javascript"><!--

    function filter() {
        url = 'index.php?route=sale/order_refund&token=<?php echo $token; ?>';

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