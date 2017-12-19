<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
  <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></button></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        
	  	  <tr>
          <td><span class="required">*</span> <?php echo $entry_appid; ?></td>
          <td><input type="text" name="bdpay_appid" value="<?php echo $bdpay_appid; ?>" size="50" />
	 		    <?php if ($error_appid) { ?>
            <span class="error"><?php echo $error_appid; ?></span>
          <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_apikey; ?></td>
          <td><input type="text" name="bdpay_apikey" value="<?php echo $bdpay_apikey; ?>" size="50" />
          <?php if ($error_apikey) { ?>
            <span class="error"><?php echo $error_apikey; ?></span>
          <?php } ?></td>
        </tr>
	  
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="bdpay_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $bdpay_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="bdpay_status">
              <?php if ($bdpay_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="bdpay_sort_order" value="<?php echo $bdpay_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>