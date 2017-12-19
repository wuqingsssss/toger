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
                    <td><span class="required">*</span> <?php echo $column_name;?></td>
                    <td>
                        <input type="text" name="name" value="<?php echo $name; ?>" maxlength="100"
                               class="span6"/>
                    </td>
                </tr>
                 <tr>
                    <td><span class="required">*</span> <?php echo $column_code;?></td>
                    <td>
                        <input type="text" name="name" value="<?php echo $refer_code; ?>" maxlength="100"
                               class="span6"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_name; ?></td>
                    <td>
                        <input type="text" name="point_code" value="<?php echo $point_code; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_name) { ?>
                            <span class="error"><?php echo $error_name; ?></span>
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo $entry_type; ?></td>
                    <td><select name="type">
                         <?php foreach($text_column_type as $key=>$value){ ?>
                            <option value="<?php echo $key;?>"<?php if ($type==$key) { ?> selected="selected"<?php } ?>><?php echo $value; ?></option>          
                            <?php }?>
                        </select></td>
                </tr>
               
                <tr>
                    <td><span class="required">*</span> <?php echo $column_valid_time;?></td>
                    <td>
                        <input type="datetime" name="s_valid_time" class="datetime" value="<?php echo $s_valid_time; ?>" maxlength="30"
                               class="span2"/>
                        <input type="datetime" name="e_valid_time" class="datetime" value="<?php echo $e_valid_time; ?>" maxlength="30"
                               class="span2"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript">
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.vtabs a').tabs();
</script>
