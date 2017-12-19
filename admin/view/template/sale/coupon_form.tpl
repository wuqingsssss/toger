<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'coupons:modify'))) { ?>
                <a onclick="$('#form').submit();"
                   class="btn  btn-primary"><span><?php echo $button_save; ?></span></a>
            <?php } ?>
            <a
                onclick="location = '<?php echo $cancel; ?>';"
                class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
        <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
            <?php if ($coupon_id) { ?>
                <a href="#tab-history"><?php echo $tab_coupon_history; ?></a>
            <?php } ?>
        </div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="tab-general">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                        <td><input name="name" value="<?php echo $name; ?>"/>
                            <?php if ($error_name) { ?>
                                <span class="error"><?php echo $error_name; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_code; ?></td>
                        <td>

                            <?php if ($coupon_id) { ?>
                                    <input type="text" name="code" value="<?php echo $code; ?>"/>
                            <?php } else { ?>
                                <input type="text" name="code" value="<?php echo $code; ?>"/>
                            <?php } ?>

                            <?php if ($error_code) { ?>
                                <span class="error"><?php echo $error_code; ?></span>
                            <?php } ?></td>
                    </tr>
                    <?php if (!$coupon_id) { ?>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_batch; ?></td>
                            <td>
                                <input type="number" step="1" min="1" name="batch" style="width: 50px"
                                       value="<?php echo isset($batch) ? $batch : 1; ?>"/>
                                <?php if ($error_batch) { ?>
                                    <span class="error"><?php echo $error_batch; ?></span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?php echo $entry_owner; ?></td>
                        <td>
                            <?php $selOwnerId = (isset($owner_id) ? $owner_id : $this->user->getId()) ?>
                            <select name="owner_id">
                              <option value="0" <?php echo(empty($selOwnerId) ? 'selected' : ''); ?> >系统</option>
                                <?php foreach ($users as $u) { ?>
                                    <option
                                        value="<?php echo $u['user_id']; ?>" <?php echo($selOwnerId == $u['user_id'] ? 'selected' : ''); ?> ><?php echo $u['username'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_type; ?></td>
                        <td><select name="type">
                                <?php if ($type == 'P') { ?>
                                    <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                                <?php } else { ?>
                                    <option value="P"><?php echo $text_percent; ?></option>
                                <?php } ?>
                                <?php if ($type == "Q") {?>
                                    <option value="Q" selected="selected"><?php echo $text_product; ?></option>
                                <?php } else { ?> 
                                    <option value="Q"><?php echo $text_product; ?></option>
                                <?php } ?>
                                <?php if ($type == 'F') { ?>
                                    <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                                <?php } else { ?>
                                    <option value="F"><?php echo $text_amount; ?></option>
                                <?php } ?>
                                <?php if ($type == "R") {?>
                                    <option value="R" selected="selected"><?php echo $text_caipiao; ?></option>
                                <?php } else { ?> 
                                    <option value="R"><?php echo $text_caipiao; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_discount; ?></td>
                        <td><input type="text" name="discount" value="<?php echo $discount; ?>"/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_total; ?></td>
                        <td><input type="text" name="total" value="<?php echo $total; ?>"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_logged; ?></td>
                        <td><?php if ($logged) { ?>
                                <input type="radio" name="logged" value="1" checked="checked"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="logged" value="0"/>
                                <?php echo $text_no; ?>
                            <?php } else { ?>
                                <input type="radio" name="logged" value="1"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="logged" value="0" checked="checked"/>
                                <?php echo $text_no; ?>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_shipping; ?></td>
                        <td><?php if ($shipping) { ?>
                                <input type="radio" name="shipping" value="1" checked="checked"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="shipping" value="0"/>
                                <?php echo $text_no; ?>
                            <?php } else { ?>
                                <input type="radio" name="shipping" value="1"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="shipping" value="0" checked="checked"/>
                                <?php echo $text_no; ?>
                            <?php } ?></td>
                    </tr>
                    
                       <tr>
                        <td><?php echo $entry_free_get; ?></td>
                        <td>
                        <input type="radio" name="free_get" value="1"<?php if ($free_get) { echo ' checked="checked"';}?>/><?php echo $text_yes; ?> &nbsp; &nbsp;
                        <input type="radio" name="free_get" value="0"<?php if (!$free_get) { echo ' checked="checked"';}?>/><?php echo $text_no; ?> &nbsp; &nbsp;
                                </td>
                    </tr>
                      <tr>
                        <td><?php echo $entry_mutual_prom; ?></td>
                        <td>
                        <input type="radio" name="mutual_prom" value="0"<?php if (!$mutual_prom) { echo ' checked="checked"';}?>/>不互斥 &nbsp; &nbsp;
                        <input type="radio" name="mutual_prom" value="1"<?php if ($mutual_prom=='1') { echo ' checked="checked"';}?>/>互斥（与特价互斥但非促销品可用） &nbsp; &nbsp;
                        <input type="radio" name="mutual_prom" value="2"<?php if ($mutual_prom=='2') { echo ' checked="checked"';}?>/>绝对互斥（不能与其他促销同时存在） &nbsp; &nbsp;
                        
                                </td>
                    </tr>
                    <tr >
                        <td><?php echo $entry_product; ?></td>
                        <td><input type="text" name="product" value=""/></td>
                    </tr>
                    <tr >
                        <td>&nbsp;</td>
                        <td>
                            <div class="scrollbox_auto" id="coupon-product">
                                <?php $class = 'odd'; ?>
                                <?php foreach ($coupon_product as $coupon_product) { ?>
                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div id="coupon-product<?php echo $coupon_product['product_id']; ?>"
                                         class="<?php echo $class; ?>"> <?php echo $coupon_product['name']; ?><img
                                            src="view/image/delete.png"/>
                                        <input type="hidden" name="coupon_product[]"
                                               value="<?php echo $coupon_product['product_id']; ?>"/>
                                    </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_date_start; ?></td>
                        <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12"
                                   id="date-start"/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_date_end; ?></td>
                        <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12"
                                   id="date-end"/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_duration; ?></td>
                        <td><input type="text" name="duration" value="<?php echo $duration; ?>" size="12"
                                   id="duration"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_uses_total; ?></td>
                        <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_uses_customer; ?></td>
                        <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>"/></td>
                    </tr>
                      <tr>
                        <td><?php echo $entry_usage; ?></td>
                        <td> <?php if ($error_usage) { ?>
                                <span class="error"><?php echo $error_usage; ?></span>
                            <?php } ?>
                        <textarea name="usage" rows="3" width=350px><?php echo $usage; ?></textarea>
                        </td>
                    </tr>
                    
                     <tr>
                        <td>分享标题</td>
                        <td><input name="share_title" value="<?php echo $share_title; ?>"/>
                          </td>
                    </tr>
                     <tr>
                        <td>分享描述</td>
                        <td><input name="share_desc" value="<?php echo $share_desc; ?>"/>
                       </td>
                    </tr>
                     <tr>
                        <td>原始分享链接<br/>系统将自动生成短链接</td>
                        <td><input name="share_link" value="<?php echo $share_link; ?>"/><?php echo $share_short_link; ?>
                       </td>
                    </tr>
                     <tr>
                     <td>分享图标</td>
                     <td valign="top"><input type="hidden" name="share_image" value="<?php echo $share_image; ?>" id="share_image" />
                     <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('share_image', 'preview');" />
                    <div>
	                <a onclick="image_upload('share_image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
            <tr>
                     <td>领取顶部文字<br/>建议png或gif背景透明</td>
                     <td valign="top"><input type="hidden" name="share_image3" value="<?php echo $share_image3; ?>" id="share_image3" />
                     <img src="<?php echo $preview_3; ?>" alt="" id="preview_3" class="image" onclick="image_upload('share_image3', 'preview_3');" />
                    <div>
	                <a onclick="image_upload('share_image3', 'preview_3');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_3').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image3').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
              <tr>
                     <td>领取前提示图<br/>建议png或gif背景透明</td>
                     <td valign="top"><input type="hidden" name="share_image1" value="<?php echo $share_image1; ?>" id="share_image1" />
                     <img src="<?php echo $preview_1; ?>" alt="" id="preview_1" class="image" onclick="image_upload('share_image1', 'preview_1');" />
                    <div>
	                <a onclick="image_upload('share_image1', 'preview_1');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_1').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image1').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
              <tr>
                     <td>领取成功提示图<br/>建议png或gif背景透明</td>
                     <td valign="top"><input type="hidden" name="share_image2" value="<?php echo $share_image2; ?>" id="share_image2" />
                     <img src="<?php echo $preview_2; ?>" alt="" id="preview_2" class="image" onclick="image_upload('share_image2', 'preview_2');" />
                    <div>
	                <a onclick="image_upload('share_image2', 'preview_2');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_2').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image2').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
              <tr>
                     <td>领取按钮</td>
                     <td valign="top"><input type="hidden" name="share_btn" value="<?php echo $share_btn; ?>" id="share_btn" />
                     <img src="<?php echo $preview_btn; ?>" alt="" id="preview_btn" class="image" onclick="image_upload('share_btn', 'preview_btn');" />
                    <div>
	                <a onclick="image_upload('share_btn', 'preview_btn');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_btn').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_btn').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
              <tr>
                     <td>红包大背景</td>
                     <td valign="top"><input type="hidden" name="share_bg" value="<?php echo $share_bg; ?>" id="share_bg" />
                     <img src="<?php echo $preview_bg; ?>" alt="" id="preview_bg" class="image" onclick="image_upload('share_bg', 'preview_bg');" />
                    <div>
	                <a onclick="image_upload('share_bg', 'preview_bg');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_bg').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_bg').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
                  
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td>
                           <input type="radio" name="status" value="1"<?php if ($status) { echo ' checked="checked"';}?>/><?php echo $text_enabled; ?> &nbsp; &nbsp;
                           <input type="radio" name="status" value="0"<?php if (!$status) { echo ' checked="checked"';}?>/><?php echo $text_disabled; ?> &nbsp; &nbsp;
                            </td>
                    </tr>
                </table>
            </div>
            <?php if ($coupon_id) { ?>
                <div id="tab-history">
                    <div id="history"></div>
                </div>
            <?php } ?>
        </form>
    </div>
</div>

<script type="text/javascript"><!--
    $('input[name=\'product\']').autocomplete({
        delay: 0,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
                type: 'POST',
                dataType: 'json',
                data: 'filter_name=' + encodeURIComponent(request.term),
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.name,
                            value: item.product_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('#coupon-product' + ui.item.value).remove();

            $('#coupon-product').append('<div id="coupon-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="coupon_product[]" value="' + ui.item.value + '" /></div>');

            $('#coupon-product div:odd').attr('class', 'odd');
            $('#coupon-product div:even').attr('class', 'even');

            $('input[name=\'product\']').val('');

            return false;
        }
    });

    $('#coupon-product div img').live('click', function () {
        $(this).parent().remove();

        $('#coupon-product div:odd').attr('class', 'odd');
        $('#coupon-product div:even').attr('class', 'even');
    });
    //--></script>
<script type="text/javascript"><!--
    $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
    $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
    //--></script>
<?php if ($coupon_id) { ?>
    <script type="text/javascript"><!--
        $('#history .pagination a').live('click', function () {
            $('#history').load(this.href);

            return false;
        });

        $('#history').load('index.php?route=sale/coupon/history&token=<?php echo $token; ?>&coupon_id=<?php echo $coupon_id; ?>');
        //--></script>
<?php } ?>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
    //--></script>
