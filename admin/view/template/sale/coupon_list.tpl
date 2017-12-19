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
                <a onclick="location = '<?php echo $insert; ?>'"
                   class="btn  btn-primary"><span><?php echo $button_insert; ?></span></a>
            <?php } ?>
            <?php if ($this->user->permitOr(array('super_admin', 'coupons:modify'))) { ?>
                <a
                    onclick="document.getElementById('form').submit();"
                    class="btn btn-danger"><span><?php echo $button_delete; ?></span></a></div>
            <?php } ?>
    </div>
    <div class="content">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox"
                                                                     onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                    <td class="left"><?php if ($sort == 'cd.name') { ?>
                            <a href="<?php echo $sort_name; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                        <?php } ?></td>
                    <td class="left">拥有人</td>
                    <td class="left"><?php if ($sort == 'c.code') { ?>
                            <a href="<?php echo $sort_code; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                        <?php } ?></td>
                    <td class="right"><?php if ($sort == 'c.discount') { ?>
                            <a href="<?php echo $sort_discount; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'c.date_start') { ?>
                            <a href="<?php echo $sort_date_start; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'c.date_end') { ?>
                            <a href="<?php echo $sort_date_end; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'c.duration') { ?>
                            <a href="<?php echo $sort_duration; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_duration; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_duration; ?>"><?php echo $column_duration; ?></a>
                        <?php } ?></td>
                         <td class="left"><?php if ($sort == 'c.free_get') { ?>
                            <a href="<?php echo $sort_free_get; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_free_get; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_free_get; ?>"><?php echo $column_free_get; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'c.status') { ?>
                            <a href="<?php echo $sort_status; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td></td>
                    <td align="left">
                        <input type="text" style="margin-bottom: 0" size="4" value="<?php echo $name; ?>" name="name">
                    </td>
                    <td align="left">
                        <?php $selOwnerId = (isset($owner_id) ? $owner_id : '') ?>
                        <select name="owner_id" style="width: 100px;margin-bottom: 0" onchange="filter()">
                            <option value="">全部</option>
                            <?php foreach ($users as $u) { ?>
                                <option
                                    value="<?php echo $u['user_id']; ?>" <?php echo($selOwnerId == $u['user_id'] ? 'selected' : ''); ?> ><?php echo $u['username'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                     <td align="left"></td>
                    <td align="right"><a
                            class="btn btn-success" onclick="filter()"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($coupons) { ?>
                    <?php foreach ($coupons as $coupon) { ?>
                        <tr>
                            <td style="text-align: center;"><?php if ($coupon['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $coupon['coupon_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $coupon['name']; ?></td>
                            <td class="left"><?php echo $coupon['ownerName']; ?></td>
                            <td class="left">
                                <?php $userId = $this->user->getId(); ?>
                                <?php if (empty($coupon['owner_id']) || $coupon['owner_id'] == $userId) { ?>
                                    <?php echo $coupon['code']; ?>
                                <?php } else { ?>
                                    <code style="color: #555">无权查看</code>
                                <?php } ?>
                            </td>
                            <td class="right"><?php echo $coupon['discount']; ?></td>
                            <td class="left"><?php echo $coupon['date_start']; ?></td>
                            <td class="left"><?php echo $coupon['date_end']; ?></td>
                            <td class="left"><?php echo $coupon['duration']; ?></td>
                            <td class="left"><?php echo $coupon['free_get']; ?></td>
                            <td class="left"><?php echo $coupon['status']; ?></td>
                            <td class="right"><?php foreach ($coupon['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
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
    	$('#form input').keydown(function(e) {
    		if (e.keyCode == 13) {
    			filter();
    		}
    	});
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
