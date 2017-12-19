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
            <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:modify'))) { ?>
                <button onclick="location = '<?php echo $insert; ?>';"
                        class="btn btn-primary"><?php echo $button_insert; ?></button>
                <button onclick="$('form').submit();" class="btn btn-danger"><?php echo $button_delete; ?></button>
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
                    <td class="left"><?php echo $column_type; ?></td>
                    <td class="left"><?php echo $column_name; ?></td>
                    <td class="left"><?php echo $column_code; ?></td>
                    <td class="left"><?php echo $column_point_code; ?></td>
                    <td class="left"><?php echo $column_s_valid_time; ?></td>
                    <td class="left"><?php echo $column_e_valid_time; ?></td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($points) { ?>
                    <?php foreach ($points as $result) { ?>
                        <tr>
                            <td style="text-align: center;"><?php if ($result['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $result['refer_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $result['refer_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $text_column_type[$result['type']] ; ?></td>
                            <td class="left"><?php echo $result['name']; ?></td>
                            <td class="left"><?php echo $result['refer_code']; ?></td>
                            <td class="left"><?php echo $result['point_code']; ?></td>
                            <td class="left"><?php echo $result['s_valid_time']; ?></td>
                            <td class="left"><?php echo $result['e_valid_time']; ?></td>
                            <td class="right"><?php foreach ($result['action'] as $action) { ?>
                                    <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:detail'))) { ?>
                                        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                    <?php } ?>
                                <?php } ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>
