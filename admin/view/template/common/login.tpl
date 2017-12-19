 <div class="box" id="login" >
  <div class="shopilex">
      <a href="http://shopilex.com" target="_blank"><img class="logo" src="view/image/qncj_logo.png"></a>
      
    </div>
    <div class="login-content">
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($success) { ?>
      <div class="success"><?php echo $success; ?></div>
      <?php } ?>
      <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table style="width: 100%;">
          <tr>
            <td>
              <input type="text" name="username" value="<?php echo $username; ?>" class="span3" placeholder="<?php echo $entry_username; ?>" />
            </td>
          </tr>
          <tr>
            <td>
              <input type="password" name="password" value="<?php echo $password; ?>" class="span3" placeholder="<?php echo $entry_password; ?>" />
            </td>
          </tr>
          <tr>
            <td>
              <?php if($language_status) {?>

              <?php echo $entry_language; ?><br />
              <select name="language_code" class="span2">
                  <?php foreach ($languages as $language) { ?>
                  	<?php if($code==$language['code']) {?>
                  	<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  	<?php } else {?>
                  	<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  	<?php } ?>
                  <?php } ?>
              </select>
              <?php } ?>
              </td>
          </tr>
          
          <tr>
            <td><input type="button" onclick="$('#form').submit();" class="btn btn-primary"  value="<?php echo $button_login; ?>"></td>
          </tr>
        </table>
        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
      </form>
     </div>
  </div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
