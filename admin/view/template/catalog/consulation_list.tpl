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
              <td class="left span2"><?php if ($sort == 'r.author') { ?>
                <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                <?php } ?></td>
              <td class="left span2"><?php echo $column_type; ?></td>  
              <td class="left span4"><?php echo $column_content; ?></td>  
              <td class="left span4"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                <?php } ?>
              </td>
              <td class="left span1"><?php if ($sort == 'r.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left span2"><?php if ($sort == 'r.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="right span2"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
          	<tr class="filter">
            <td></td>
            <td><input class="span2" type="text" name="filter_customer_name" value="<?php echo $filter_customer_name; ?>" /></td>

			<td>
				<select name="filter_type">
					<option value="*"></option>
					<?php foreach($types as $result) {?>
					<option value="<?php echo $result['value']; ?>" <?php if($filter_type==$result['value']) {?> selected="selected"<?php }?>><?php echo $result['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			
			<td><input type="text" name="filter_content" value="<?php echo $filter_content; ?>" /></td>
			<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
				<input type="hidden" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
			</td>
			
            <td><select name="filter_status" class="span2">
                <option value="*"></option>
                <option value="1" <?php if($filter_status==1) {?> selected="selected" <?php } ?>><?php echo $text_enabled; ?></option>
                <option value="0" <?php if($filter_status==='0') {?> selected="selected" <?php } ?>><?php echo $text_disabled; ?></option>
              </select>
            </td>
            <td><input type="text" class="date" name="filter_date_added" value="<?php echo $filter_date_added; ?>" /></td>
            <td align="right"><a onclick="filter();" class="btn"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
            <?php if ($consulations) { ?>
            <?php foreach ($consulations as $result) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($result['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $result['consulation_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $result['consulation_id']; ?>" />
                <?php } ?>
              </td>
              
              <td class="left"><?php echo $result['customer_name']; ?></td>
              <td><?php echo $result['type']; ?></td>
              <td><?php echo $result['content']; ?></td>
              <td><?php echo $result['name']; ?></td>
              <td><?php echo $result['status']; ?></td>
              <td><?php echo $result['date_added']; ?></td>
              <td class="right"><?php foreach ($result['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <?php if($consulations) {?>
      <div class="pagination"><?php echo $pagination; ?></div>
      <?php } ?>
    </div>
  </div>
<script type="text/javascript"><!--
$(document).ready(
		function(){
			$('input[name=\'filter_name\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.name,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_name\']').val(ui.item.label);
					$('input[name=\'filter_product_id\']').val(ui.item.value);
									
					return false;
				}
			});		
    }
);

//--></script> 
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/consulation';
//  var str=$("form").serialize();

	var filter_customer_name = $.trim($('input[name=\'filter_customer_name\']').attr('value'));

	if (filter_customer_name) {
		url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
	}

	var filter_type = $('select[name=\'filter_type\']').attr('value');

	if (filter_type != '*') {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}

	var filter_content = $.trim($('input[name=\'filter_content\']').attr('value'));

	if (filter_content) {
		url += '&filter_content=' + encodeURIComponent(filter_content);
	}
	
	var filter_name = $.trim($('input[name=\'filter_name\']').attr('value'));

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_product_id = $.trim($('input[name=\'filter_product_id\']').attr('value'));

	if (filter_name) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	var filter_date_added = $.trim($('input[name=\'filter_date_added\']').attr('value'));

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	url+='&token=<?php echo $token; ?>';

	location = url;
}
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>

