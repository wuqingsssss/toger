 <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>

  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn"><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_author; ?></td>
            <td><input type="text" name="customer_name" value="<?php echo $customer_name; ?>" maxlength="50" />
              <?php if ($error_customer_name) { ?>
              <span class="error"><?php echo $error_customer_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_product; ?></td>
            <td><input type="text" name="product" value="<?php echo $product; ?>" />
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <?php if ($error_product) { ?>
              <span class="error"><?php echo $error_product; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_type; ?></td>
            <td>
              <?php foreach($types as $result) {?>
              <label class="inline radio">
              <input type="radio" name="type" value="<?php echo $result['value']; ?>" <?php if($type==$result['value']) {?>checked="checked"<?php }?> /><?php echo $result['name']; ?></label>
   
              <?php } ?>
              <?php if ($error_product) { ?>
              <span class="error"><?php echo $error_product; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="vt"><span class="required">*</span> <?php echo $entry_content; ?></td>
            <td><textarea name="content" rows="8" class="span6"><?php echo $content; ?></textarea>
              <?php if ($error_content) { ?>
              <span class="error"><?php echo $error_content; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td class="vt"><span class="required">*</span> <?php echo $entry_reply; ?></td>
            <td><textarea name="reply" rows="8" class="span6"><?php echo $reply; ?></textarea>
              <?php if ($error_reply) { ?>
              <span class="error"><?php echo $error_reply; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status" class="span2">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>

<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'json',
			data: 'filter_name=' +  encodeURIComponent(request.term),
			success: function(data) {		
				response($.map(data, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
		
	},
	select: function(event, ui) {
		$('input[name=\'product\']').val(ui.item.label);
		$('input[name=\'product_id\']').val(ui.item.value);
		
		return false;
	}
});
//--></script> 
