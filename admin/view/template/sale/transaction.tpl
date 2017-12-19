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
        </div>
    <div class="content">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox"
                                                                     onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                 
                    <td class="left"><?php if ($sort == 'trans_code') { ?>
                            <a href="<?php echo $sort_code; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                        <?php } ?></td>
                    <td class="right"><?php if ($sort == 'value') { ?>
                            <a href="<?php echo $sort_value; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_value; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_value; ?>"><?php echo $column_value; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'date_start') { ?>
                            <a href="<?php echo $sort_date_start; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'date_end') { ?>
                            <a href="<?php echo $sort_date_end; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'date_added') { ?>
                            <a href="<?php echo $sort_date_added; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'date_modified') { ?>
                            <a href="<?php echo $sort_date_added; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                        <?php } ?></td>
                        <td class="left"><?php if ($sort == 'is_tpl') { ?>
                            <a href="<?php echo $sort_is_tpl; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_is_tpl; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_is_tpl; ?>"><?php echo $column_is_tpl; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'used') { ?>
                            <a href="<?php echo $sort_used; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_used; ?></a>
                        <?php } else { ?>
                            <a href="<?php echo $sort_used; ?>"><?php echo $column_used; ?></a>
                        <?php } ?></td>
                    <td class="left"><?php if ($sort == 'operator') { ?>
                        <a href="<?php echo $sort_operator; ?>"
                           class="<?php echo strtolower($order); ?>"><?php echo $column_operator; ?></a>
                           <?php } else { ?>
                        <a href="<?php echo $sort_operator; ?>"><?php echo $column_operator; ?></a>
                    <?php } ?></td>
                    <td class="left"><?php if ($sort == 'customer_id') { ?>
                        <a href="<?php echo $sort_operator; ?>"
                           class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                        <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                   <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td></td>
                    <td align="left">
                        <input type="text" style="margin-bottom: 0" size="4" value="<?php echo $trans_code; ?>" name="trans_code">
                    </td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">
                        <select name="is_tpl" style="width: 100px;margin-bottom: 0" onchange="filter()">
                            <?php if (!isset($is_tpl)) {?>
                            <option value="" selected>  全部    </option>
                            <option value="1"> 模板</option>
                            <option value="0"> 普通</option>
                            <?php }else{?>
                            <option value="">  全部    </option>
                            <?php if ($is_tpl) {?>
                            <option value="1" selected> 模板 </option>
                            <option value="0"> 普通</option>
                            <?php }else{?>
                            <option value="1"> 模板</option>
                            <option value="0" selected> 普通</option>
                            <?php }?>
                            <?php }?>
                        </select>
                    </td>
                    <td align="left">
                        <select name="used" style="width: 100px;margin-bottom: 0" onchange="filter()">
                            <?php if (!isset($used)) {?>
                            <option value="" selected>  全部    </option>
                            <option value="1"> 已使用 X</option>
                            <option value="0"> 未使用 O</option>
                            <?php }else{?>
                            <option value="">  全部    </option>
                            <?php if ($used) {?>
                            <option value="1" selected> 已使用 X</option>
                            <option value="0"> 未使用 O</option>
                            <?php }else{?>
                            <option value="1"> 已使用 X</option>
                            <option value="0" selected> 未使用 O</option>
                            <?php }?>
                            <?php }?>
                        </select>
                    </td>
                    
                   
                    <td align="left">
                        <input type="text" style="margin-bottom: 0" size="4" value="<?php echo $operator; ?>" name="operator">
                    </td>                  
                    <td align="left">
                        <input type="text" style="margin-bottom: 0" size="4" value="<?php echo $customer_id; ?>" name="customer_id">
                    </td>
                    <td align="right"><a
                            class="btn btn-success" onclick="filter()"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($trans_codes) { ?>
                    <?php foreach ($trans_codes as $trans_code) { ?>
                        <tr>
                            <td style="text-align: center;"><?php if ($trans_code['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $trans_code['trans_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $trans_code['trans_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $trans_code['trans_code']; ?></td>
                            <td class="right"><?php echo $trans_code['value']; ?></td>
                            <td class="left"><?php echo $trans_code['date_start']; ?></td>
                            <td class="left"><?php echo $trans_code['date_end']; ?></td>
                            <td class="left"><?php echo $trans_code['date_added']; ?></td>
                            <td class="left"><?php echo $trans_code['date_modified']; ?></td>
                            <td class="left"><?php echo empty($trans_code['is_tpl'])?'O':'X'; ?></td>
                            <td class="left"><?php echo empty($trans_code['used'])?'O':'X'; ?></td>
                            <td class="left"><?php echo $trans_code['operator']; ?></td>
                            <td class="left"><?php echo $trans_code['customer_id']; ?></td>
                            <td class="right">
                                  <?php if(empty($trans_code['used'])){?>
                                  <?php foreach ($trans_code['action'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                  <?php } ?>
                                  <?php }?>
                                  </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
        <br/>
        <br/>
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
        url = 'index.php?route=sale/transaction&token=<?php echo $token; ?>';

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
