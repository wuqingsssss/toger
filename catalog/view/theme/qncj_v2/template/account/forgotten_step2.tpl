<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?> 
    <div id="forgotten" class="content">
     <div class="pass_title">
     	<h1><?php echo $heading_title; ?></h1>
     </div>
     <div class="pass_left lt">
     	<?php echo $this->getChild('account/forgotten/step',2);?>
     
     <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
     <input type="hidden" name="mobile" value="<?php echo $mobile; ?>" class="span4" />
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?><br />
          	  <input type="password" name="password" value="<?php echo $password; ?>" class="span4" />
          	  <?php if ($error_password) { ?>
            <br />
		    <span class="error"><?php echo $error_password; ?></span>
		    <?php } ?>
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?><br />
          	  <input type="password" name="confirm" value="<?php echo $confirm; ?>" class="span4" />
          	  <?php if ($error_confirm) { ?>
            <br />
		    <span class="error"><?php echo $error_confirm; ?></span>
		    <?php } ?>
          </td>
        </tr>
        <tr>
        <td><input type="submit" class="button" value="<?php echo $button_save; ?>" /></td>
        </tr>
      </table>
      </form>
      </div>
      <?php echo $this->getChild('account/forgotten/service');?>
    </div>
  <?php echo $content_bottom; ?>
  </div>
<?php echo $footer; ?>