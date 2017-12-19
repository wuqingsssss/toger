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
            <td><input type="text" name="author" value="<?php echo $author; ?>" />
              <?php if ($error_author) { ?>
              <span class="error"><?php echo $error_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_product; ?></td>
            <td>
			<select name="news_id">
		  <?php foreach ($anews as $news ){ ?>
		  <?php if ($news['news_id'] == $news_id) { ?>
		  <option value="<?php echo $news['news_id']; ?>" selected="selected"><?php echo $news['title'] ?></option>
		  <?php } else { ?>
           <option value="<?php echo $news['news_id']; ?>"><?php echo $news['title'] ?></option>
           <?php } ?>
           <?php } ?>
           </select>
			
			
              <?php if ($error_article) { ?>
              <span class="error"><?php echo $error_article; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_text; ?></td>
            <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
              <?php if ($error_text) { ?>
              <span class="error"><?php echo $error_text; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
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
