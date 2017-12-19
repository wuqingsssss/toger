<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>

<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:modify'))) { ?>
                <button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button>
            <?php } ?>
            <button onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></button>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td>
                        <input type="text" name="name" value="<?php echo $name; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_name) { ?>
                            <span class="error"><?php echo $error_name; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> 门店编号</td>
                    <td>
                        <input type="text" name="point_code" value="<?php echo $point_code; ?>" maxlength="100"
                               class="span6"/>
                                <?php if ($error_point_code) { ?>
                            <span class="error"><?php echo $error_point_code; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> 新门店编号</td>
                    <td>
                        <input type="text" name="point_code_new" value="<?php echo $point_code_new; ?>" maxlength="100"
                               class="span6"/>
                                <?php if ($error_point_code_new) { ?>
                            <span class="error"><?php echo $error_point_code_new; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> 设备编号<span class="help">多个设备，请使用英文逗号分隔符</span></td>
                    <td>
                        <input type="text" name="device_code" value="<?php echo $device_code; ?>" maxlength="100"
                               class="span6"/>
                                     <?php if ($error_device_code) { ?>
                            <span class="error"><?php echo $error_device_code; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                  <tr>
                <td><?php echo $entry_customer_group; ?></td>
                <td><select name="customer_group_id">
                  <option value="0">全部</option>
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
                <tr>
                    <td>所属商圈</td>
                    <td>
                        <select name="cbd_id">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($cbds as $cbd) { ?>
                                <?php if ($cbd['id'] == $cbd_id) { ?>
                                    <option value="<?php echo $cbd['id']; ?>"
                                            selected="selected"><?php echo $cbd['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $cbd['id']; ?>"><?php echo $cbd['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_address; ?></td>
                    <td>
                        <input type="text" name="address" value="<?php echo $address; ?>" maxlength="200"
                               class="span6"/>
                    </td>
                </tr>
                <tr>
                    <td>地图标注</td>
                    <td>
                        <input type="text" name="coordinate" value="<?php echo $coordinate; ?>" maxlength="100"
                               class="span6"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_business_hour; ?></td>
                    <td>
                        <input type="text" name="business_hour" value="<?php echo $business_hour; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_telephone; ?></td>
                    <td>
                        <input type="text" name="telephone" value="<?php echo $telephone; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>门店图片</td>
                    <td valign="top"><input type="hidden" name="image" value="<?php echo $image; ?>" id="image"/>
                        <img src="<?php echo $preview; ?>" alt="" id="preview" class="image"
                             onclick="image_upload('image', 'preview');"/>

                        <div>
                            <a onclick="image_upload('image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>');
                                $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><?php foreach (EnumPointStatus::getOptions() as $key => $value) { ?>
                           <input type="radio" name="status" value="<?php echo $value['value'];?>" <?php if($status==$value['value']) echo' checked="checked"';?> /><?php echo $value['name']; ?>&nbsp&nbsp&nbsp&nbsp
                          <?php } ?>
                        <input type="checkbox" name="updatebaidu" checked="checked" value='1' />
                                                 将修改同步到百度平台
                        </td>
                </tr>
                
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"/></td>
                </tr>
                <tr>
                    <td><?php echo $entry_description; ?></td>
                    <td>
                        <textarea name="description" cols="80" rows="5"
                                  class="span6"><?php echo $description; ?></textarea>
                    </td>
                </tr>

            </table>
        </form>
    </div>
</div>
