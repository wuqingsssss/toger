  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
    	<h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><?php echo $button_insert; ?></button> 
      <button onclick="$('#form').submit();" class="btn btn-default"><?php echo $button_delete; ?></button></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
              <th class="left"><?php echo $column_name; ?></th>
              <th class="left"><?php echo $text_start_date; ?></th>
              <th class="left"><?php echo $text_end_date; ?></th>
              <th class="right"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($promotions)) { ?>
            <?php foreach ($promotions as $promotion) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($promotion['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $promotion['pb_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $promotion['pb_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $promotion['pb_name']; ?></td>
               <td class="left"><?php echo $promotion['start_time']; ?></td>
                <td class="left"><?php echo $promotion['end_time']; ?></td>
              <td class="right span2"><?php foreach ($promotion['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"<?php if($action['target']) echo ' target="$action[target]""'; ?>><?php echo $action['text']; ?></a> ]
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
    </div>
     <div class="pagination"><?php echo $pagination; ?></div>
  </div>
