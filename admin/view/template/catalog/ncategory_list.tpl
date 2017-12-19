<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
     <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><?php echo $button_insert; ?></button> <button onclick="$('form').submit();" class="btn"><?php echo $button_delete; ?></button></div>
    </div>
  
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="right"><?php echo $column_sort_order; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($ncategories) { ?>
            <?php foreach ($ncategories as $ncategory) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($ncategory['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $ncategory['ncategory_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $ncategory['ncategory_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $ncategory['name']; ?></td>
              <td class="right"><?php echo $ncategory['sort_order']; ?></td>
              <td class="right"><?php foreach ($ncategory['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
