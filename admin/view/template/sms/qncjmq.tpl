<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
  <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></button></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
         <tr>
          <td><?php echo $entry_sign; ?></td>
          <td><input type="text" name="qncjmq_sign" value="<?php echo $qncjmq_sign; ?>" size="50" />
          </td>
        </tr> 
         <tr>
          <td><?php echo $entry_timeout; ?></td>
          <td><input type="text" name="qncjmq_timeout" value="<?php echo $qncjmq_timeout; ?>" size="50" />
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="qncjmq_status">
              <?php if ($qncjmq_status) { ?>
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
          <td><input type="text" name="qncjmq_sort_order" value="<?php echo $qncjmq_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
