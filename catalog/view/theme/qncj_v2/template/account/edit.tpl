<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if (isset($error_warning) && $error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if (isset($error) && $error) { ?>
  <div class="error"><?php echo $error; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
    <div class="content">
      <table class="form">
      	<tr>
          <td>
          <span class="required">*</span> <?php echo $entry_name; ?><br />
          <input type="text" name="name" value="<?php echo $name; ?>" class="span4" maxlength="32" />
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
          <input type="text" name="mobile" value="<?php echo $mobile; ?>" class="span4" maxlength="50" readonly/>
            <?php if ($error_mobile) { ?>
             <br />
            <span class="error"><?php echo $error_mobile; ?></span>
            <?php } ?>
          </td>
        </tr>
      	<tr>
          <td><?php echo $entry_email; ?><br />
          <input type="text" name="email" value="<?php echo $email; ?>" class="span4" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr class="hide">
          <td><?php echo $entry_telephone; ?><br />
          <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="span4" maxlength="50" />
          </td>
        </tr>
        <tr class="hide">
          <td>
          <?php echo $entry_fax; ?><br />
          <input type="text" name="fax" value="<?php echo $fax; ?>" class="span4" maxlength="50" /></td>
        </tr>
        <tr>
        	 <td>
        	 	<div class="left"><a onclick="$('#edit').submit();" class="button"><span><?php echo $button_continue; ?></span></a></div>
        	 </td>
        </tr>
      </table>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>