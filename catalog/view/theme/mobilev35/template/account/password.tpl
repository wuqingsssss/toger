<?php echo $header; ?>
<div id="header" class="bar bar-header bar-positive">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>

<div id="content" class="content">
<?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
<div class="card">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="password">
    <div class="content">
      <table class="form">
       <tr>
          <td><span class="required">*</span> <?php echo $entry_old_password; ?><br />
          <input class="login_form_password" type="password" name="old_password" value="<?php echo $old_password; ?>" />
            <?php if ($error_old_password) { ?>
            <span class="error"><?php echo $error_old_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?><br />
          <input class="login_form_password" type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?><br />
          <input class="login_form_password" type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
         <tr>
        	 <td>
        	 	<button onclick="$('#password').submit();" class="button button-block button-positive"><?php echo $button_confirm; ?></button>
        	 </td>
          </tr>
      </table>
    </div>
  </form>
  </div>
</div>
<?php echo $this->getChild('mobile/account/menu') ?>
<?php echo $footer; ?>