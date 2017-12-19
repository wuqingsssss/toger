
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
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons">
    
    </div>
  </div>
  <div class="content">
   <form action="<?php echo $export_customer; ?>" method="post" enctype="multipart/form-data" id="form2">
      <table class="form">
          <tr>
          	<td><?php echo $text_export_customer; ?></td>
          	<td><a onclick="$('#form2').submit();" class="btn btn-primary"><span><?php echo $button_export_customer; ?></span></a></td>
          </tr>
      </table>
    </form>
  </div>
</div>
