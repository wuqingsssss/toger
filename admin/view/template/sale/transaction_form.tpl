<?php if ($error_warnings) { ?>
    <?php foreach ($error_warnings as $error) {?>
    <div class="alert alert-error"><?php echo $error; ?><a class="close" data-dismiss="alert">×</a></div>
    <?php }?>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="tab-general">
                <table class="form">
                <?php if($operation == EnumOperation::INSERT) { ?>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_prefix; ?></td>
                        <td>
                            <input type="text" name="prefix" value="<?php echo $prefix; ?>"/>                           
                            <?php if ($error_prefix) { ?>
                                <span class="error"><?php echo $error_prefix; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_length; ?></td>
                        <td>
                            <input type="number" step="1" min="10" name="length" style="width: 50px"
                                   value="<?php echo isset($length) ? $length : 10; ?>"/>
                            <?php if ($error_length) { ?>
                                <span class="error"><?php echo $error_length; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
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
               <?php }else{?>
                    <tr>
                        <td><?php echo $entry_code; ?></td>
                        <td><span><?php echo $trans_code; ?></span></td>
                    </tr>
               <?php }?>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_value; ?></td>
                        <td><input type="text" name="value" value="<?php echo $value; ?>"/></td>
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
                        <td><?php echo $entry_is_tpl; ?></td>
                        <td><?php if ($is_tpl) { ?>
                                <input type="radio" name="is_tpl" value="1" checked="checked"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="is_tpl" value="0"/>
                                <?php echo $text_no; ?>
                            <?php } else { ?>
                                <input type="radio" name="is_tpl" value="1"/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="is_tpl" value="0" checked="checked"/>
                                <?php echo $text_no; ?>
                            <?php } ?></td>
                    </tr>
                    
                </table>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript"><!--
    $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
    $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
 
    //--></script>
