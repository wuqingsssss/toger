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
    <a onclick="$('form').submit();" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'ld.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?>
            </td>
            <td class="left">
            	<?php echo $column_uri; ?>
            </td>
            <td class="left"><?php if ($sort == 'l.sort_order') { ?>
              <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'l.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($links)) { ?>
          <?php foreach ($links as $link) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($link['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $link['link_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $link['link_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $link['name']; ?></td>
            <td class="left"><?php echo $link['uri']; ?></td>
            <td class="left"><?php echo $link['sort_order']; ?></td>
            <td class="left"><?php echo $link['status']; ?></td>
            <td class="right">
            <?php foreach ($link['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?>
           <?php foreach ($link['delete'] as $delete) { ?>
              [ <a href="<?php echo $delete['href']; ?>" onclick="return confirm('Are you sure to delete?');"><?php echo $delete['text']; ?></a> ]
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>