
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <div class="buttons">
    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><span><?php echo $button_insert; ?></span></a>
    <a onclick="$('#form').submit();" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="left"><?php echo $column_code; ?></td>
            <td class="left"><?php echo $column_status; ?></td>
            <td class="left"><?php echo $column_articles; ?></td>
            <td class="right"><?php echo $column_sort_order; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($categories) { ?>
          <?php foreach ($categories as $category) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($category['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $category['article_category_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $category['article_category_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $category['name']; ?></td>
            <td class="left"><?php echo $category['code']; ?></td>
            <td class="left"><?php echo $category['status']; ?></td>
            <td class="left"><?php echo $category['articles']; ?></td>
            <td class="right"><?php echo $category['sort_order']; ?></td>
            <td class="right"><?php foreach ($category['action'] as $class => $action) { ?>
              [ <a href="<?php echo $action['href']; ?>" class="<?php echo $class; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
