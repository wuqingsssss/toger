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
                        <input type="hidden" name="country_id" value="<?php echo $country_id; ?>" id="country_id"/>
                        <input type="hidden" name="zone_id" value="<?php echo $zone_id; ?>" id="zone_id"/>
                    </td>
                </tr>
                <tr>
                    <td>城市</td>
                    <td>
                        <select name="city_id">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($cities as $city) { ?>
                                <?php if ($city['city_id'] == $city_id) { ?>
                                    <option value="<?php echo $city['city_id']; ?>"
                                            selected="selected"><?php echo $city['name']; ?></option>
                                <?php } else { ?>
                                    <option
                                        value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="status">
                            <?php if ($status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                </tr>
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"/></td>
                </tr>


            </table>
        </form>
    </div>
</div>
 