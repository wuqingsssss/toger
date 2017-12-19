<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>

  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> 
          <button onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="information_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($information_group_description[$language['language_id']]) ? $information_group_description[$language['language_id']]['name'] : ''; ?>" />
              
<?php if(COUNT($languages) > 1) {?>
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
<?php } ?>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
              <td><?php echo $entry_code; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>" /></td>
            </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <option value="1" <?php if ($status==1) { ?> selected="selected"<?php } ?> ><?php echo $text_enabled; ?></option>
                <option value="0" <?php if ($status==0) { ?> selected="selected"<?php } ?> ><?php echo $text_disabled; ?></option>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
