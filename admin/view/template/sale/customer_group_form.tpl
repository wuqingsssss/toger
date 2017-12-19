<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> 最低消费金额(元整)</td>
            <td><input type="text" name="level" value="<?php echo $level; ?>" />
             </td>
          </tr>
          <tr>
            <td>会员等级说明</td>
            <td><textarea name="level_des" rows="5" cols="60"><?php echo $level_des; ?></textarea></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
