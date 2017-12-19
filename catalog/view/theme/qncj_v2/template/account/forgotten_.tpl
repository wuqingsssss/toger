<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div> 
    <div id="forgotten" class="content">
     <div class="pass_title">
     	<h1><?php echo $heading_title; ?></h1>
     </div>
     
     <div class="pass_left lt">
     	<?php echo $this->getChild('account/forgotten/step',1);?>
     	
     
     <p class="pass_tsyy"><?php echo $text_email; ?></p>
     
     <?php if(isset($success) && $success) {?>
     <div class="success"><?php echo $success; ?></div>
     <?php } else { ?>
     <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?><br />
          <input type="text" name="email" value="<?php $email; ?>" class="span4" />
          <?php if ($error_email) { ?>
          <br />
          <span class="error"><?php echo $error_email; ?></span>
          <?php } ?>
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_captcha; ?><br />
          <input type="text" name="captcha" value="" class="input-slim" />
   			<img id="captcha" class="captcha" onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));" title="<?php echo $text_refresh_captcha; ?>" src="index.php?route=information/contact/captcha" alt="" />
            <?php if ($error_captcha) { ?>
            <br />
		    <span class="error"><?php echo $error_captcha; ?></span>
		    <?php } ?></td>
        </tr>
        <tr>
        <td><input type="submit" class="button" value="<?php echo $button_forgot_password; ?>" /></td>
        </tr>
      </table>
      </form>
      <?php }?>
      </div>
      <?php echo $this->getChild('account/forgotten/service');?>
    </div>
  <?php echo $content_bottom; ?>
  </div>
<?php echo $footer; ?>