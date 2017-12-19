<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2> <?php echo $heading_title; ?></h2>
      <div class="buttons">
      <button onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="btn"><?php echo $button_delete; ?></button>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left">询价人</td>
              <td class="left">联系电话</td>
              <td class="left">询价时间</td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($enquiries) { ?>
            <?php foreach ($enquiries as $result) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($result['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $result['enquiry_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $result['enquiry_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $result['name']; ?></td>
              <td class="left"><?php echo $result['telephone']; ?></td>
              <td class="left"><?php echo $result['date_added']; ?></td>
              <td class="right"><?php foreach ($result['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <?php if($enquiries) {?>
      <div class="pagination"><?php echo $pagination; ?></div>
      <?php } ?>
    </div>
  </div>