<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h2><?php echo $heading_title; ?></h2>
    <div class="buttons">
    <!--<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
    --></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="content">
        <table class="form">
          <tr>
            <td width="150"><span class="required">*</span> <?php echo $entry_pld_password; ?></td>
            <td><input type="password" id="old_password" name="old_password" value="" />
              <?php if ($error_old_password) { ?>
              <span class="error"><?php echo $error_old_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td width="150"><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="password" id="password" name="password" value="" />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
            <td><input type="password" id="confirm" name="confirm" value="" />
              <?php if ($error_confirm) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
          	<td>&nbsp;</td>
            <td align="left"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>