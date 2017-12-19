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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
    <div class="content">
      <table class="form">
      	<tr>
          <td>
          <span class="required">*</span> <?php echo $entry_name; ?><br />
          <input class="login_form_user" type="text" name="name" value="<?php echo $name; ?>" maxlength="32" />
            <?php if ($error_name) { ?>
             <br />
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
            
            </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_salution; ?><br />
          	<label><input type="radio" name="salution" value="M" checked="checked" /> 男</label>&nbsp;&nbsp;
          	<label><input type="radio" name="salution" value="F" /> 女</label>
          </td>
        </tr>
	    <tr>
          <td><span class="required">*</span> <?php echo $entry_mobile; ?><br />
          <input class="login_form_mobile" type="text" name="mobile" value="<?php echo $mobile; ?>" maxlength="50" readonly/>
            <?php if ($error_mobile) { ?>
             <br />
            <span class="error"><?php echo $error_mobile; ?></span>
            <?php } ?></td>
        </tr>
      	<tr>
          <td> <?php echo $entry_email; ?><br />
          <input class="login_form_email" type="text" name="email" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr class="hide">
          <td><?php echo $entry_telephone; ?><br />
          <input type="text" name="telephone" value="<?php echo $telephone; ?>" maxlength="50" />
          </td>
        </tr>
        <tr class="hide">
          <td>
          <?php echo $entry_fax; ?><br />
          <input type="text" name="fax" value="<?php echo $fax; ?>" maxlength="50" /></td>
        </tr>
        <tr>
        	 <td>
        		<button onclick="$('#edit').submit();" class="button button-block button-positive"><?php echo $button_continue; ?></button>
        	 </td>
        </tr>
      </table>
    </div>
  </form>
</div>

</div>

<?php echo $this->getChild('mobile/account/menu') ?>
<?php echo $footer; ?>