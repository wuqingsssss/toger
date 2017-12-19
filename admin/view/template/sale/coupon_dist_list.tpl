<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'coupons:add'))) { ?>
            <a onclick="location = '<?php echo $insertCoupon; ?>'"
               class="btn  btn-primary"><span><?php echo $btn_dist_coupon; ?></span></a>
            <?php } ?>
            <?php if ($this->user->permitOr(array('super_admin', 'coupons:add'))) { ?>
            <a onclick="location = '<?php echo $insertPacket; ?>'"
               class="btn  btn-primary"><span><?php echo $btn_dist_packet; ?></span></a>
            <?php } ?>
            <?php if ($this->user->permitOr(array('super_admin', 'coupons:modify'))) { ?>
            <a
                    onclick="document.getElementById('form').submit();"
                    class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
            <?php } ?> 
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox"
                                                                     onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                    <td class="left"><?php echo $column_name; ?></td>
                    <td class="left">名称</td>
                    <td class="left"><?php echo $column_code; ?></td>
                    <td class="right"><?php echo $column_discount; ?></td>
                    <td class="left"><?php echo $column_date_start; ?></td>
                    <td class="left"><?php echo $column_date_end; ?></td>
                    <td class="left"><?php echo $column_status; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td></td>
                    <td align="left">
                        <input type="text" style="margin-bottom: 0" size="4" value="<?php echo $name; ?>" name="name">
                    </td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                </tr>
                <?php if ($result) { ?>
                <?php foreach ($result as $coupon) { ?>

                <tr>
                    <td style="text-align: center;"><?php if ($coupon['selected']) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_customer_id']; ?>"
                               checked="checked"/>
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]"
                               value="<?php echo $coupon['coupon_customer_id']; ?>"/>
                        <?php } ?></td>
                    <td class="left"><?php echo $coupon['userinfo']['mobile']; ?></td>
                    <td class="left"><?php echo $coupon['couponinfo']['name']; ?></td>
                    <td class="left"><?php echo $coupon['couponinfo']['code']; ?></td>
                    <td class="left"><?php echo $coupon['couponinfo']['discount']; ?></td>

                    <td class="left"><?php echo $coupon['couponinfo']['date_start']; ?></td>
                    <td class="left"><?php echo $coupon['couponinfo']['date_end']; ?></td>
                    <td class="left"><?php echo $coupon['couponinfo']['status']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
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
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
    function filter() {
        url = 'index.php?route=sale/coupon&token=<?php echo $token; ?>';

        var paramGropus = [];
        $('#search_filter').find(':input').not(':button').each(function () {
            var field$ = $(this);
            var name = field$.attr('name');
            var val = field$.val();
            if (name && val) {
                paramGropus.push(name + '=' + encodeURIComponent(val));
            }
        });
        url += '&' + paramGropus.join('&');
        window.location.href = url;
    }



</script>