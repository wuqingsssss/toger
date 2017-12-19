<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="btn-toolbar" >
      <div class="buttons">
      	<button onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><?php echo $button_insert; ?></button>
      </div>
      <div class="btn-group">
          <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><?php echo $button_batch;?> <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').submit();"  ><?php echo $button_delete; ?></a></li>
            <li><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" ><?php echo $button_copy; ?></a></li>
            <li><a onclick="$('#form').attr('action', '<?php echo $disabled; ?>'); $('#form').submit();" ><?php echo $button_disable;?></a></li>
            <li><a onclick="$('#form').attr('action', '<?php echo $enabled; ?>'); $('#form').submit();" ><?php echo $button_enable;?></a></li>
  <?php if (isset($linkpost_wordpress)) { ?>
            <li><a data-toggle="modal" onclick="$('#form').attr('action', '<?php echo $linkpost_wordpress; ?>'); linkpostWordpress();" ><?php echo $button_linkpost_wordpress;?></a></li>
  <?php } ?>
          </ul>
	  </div>
 	 </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.number') { ?>
                <a href="<?php echo $sort_number; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_number; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_number; ?>"><?php echo $column_number; ?></a>
                <?php } ?></td>
             <td class="left"><?php if ($sort == 'p.trade_type') { ?>
                <a href="<?php echo $sort_trade_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_trade_type; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_trade_type; ?>"><?php echo $column_trade_type; ?></a>
              <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
                
                
                 <td class="left"><?php if ($sort == 'p.supply_demand') { ?>
                <a href="<?php echo $sort_supply_demand; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_supply_demand; ?>"><?php echo $column_type; ?></a>
                <?php } ?></td>
                
               <td class="left">审核状态</td>
                
              <td class="left"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter" >
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_number" class="span2" value="<?php echo $filter_number; ?>" /></td>
              <td><input type="text" name="filter_trade_type" class="span2" value="<?php echo $filter_trade_type; ?>" /></td>
              <td align="left"><input type="text" class="span1" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
              <td><select name="filter_status" class="span2">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
                
                <td><select name="filter_supply_demand" class="span2">
                  <option value="*"></option>
                  <?php if ($filter_supply_demand=='') { ?>
                  <option value="0" ><?php echo $text_demand; ?></option>
                  <option value="1"><?php echo $text_supply; ?></option>
                  <?php } else if($filter_supply_demand==0){ ?>
                  <option value="0" selected="selected"><?php echo $text_demand; ?></option>
                  <option value="1"><?php echo $text_supply; ?></option>
                  <?php } else if($filter_supply_demand==1){?>
                  <option value="0"><?php echo $text_demand; ?></option>
                  <option value="1"  selected="selected"><?php echo $text_supply; ?></option>
                  <?php }?>
                </select></td>
                
                <td>
                <select name="filter_verified" class="span2">
                  <option value="*"></option>
                  <?php if ($filter_verified=='') { ?>
                  <option value="0" ><?php echo $text_pass; ?></option>
                  <option value="1"><?php echo $text_no_pass; ?></option>
                  <?php } else if($filter_verified=='0'){ ?>
                  <option value="0" selected="selected"><?php echo $text_pass; ?></option>
                  <option value="1"><?php echo $text_no_pass; ?></option>
                  <?php } else if($filter_verified=='1'){?>
                  <option value="0"><?php echo $text_pass; ?></option>
                  <option value="1"  selected="selected"><?php echo $text_no_pass; ?></option>
                  <?php }?>
                </select>
                
                </td>
                
              	<td align="left"><button onclick="filter();return false;" class="btn"><?php echo $button_filter; ?></button></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['number']; ?></td>
              <td class="left"><?php echo $product['trade_type']; ?></td>
              <td class="left">
			    <?php echo $product['price']; ?>
              </td>
              <td class="left"><?php echo $product['status']; ?></td>
              
              
              <td class="left"><?php echo $product['supply_demand']; ?></td>
              
              
              <td class="left"><?php echo $product['verified']; ?></td>
              
              <td class="left">
              <?php if(isset($product['verifyAction'])) { $result=$product['verifyAction']; ?>
              [ <a href="<?php echo $result['href'] ?>"><?php echo $result['text'] ?></a> ]
              <?php } ?>
                <?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
               
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
<!-- wordpress form start -->
<?php if (isset($linkpost_wordpress)) { ?>
<section>
		<div id="wpModal" class="modal hide fade form-horizontal" style="display:none">
			<div class="modal-header">
				<h2><?php echo $button_linkpost_wordpress;?></h2>
			</div>
			<div class="modal-body">
				<fieldset>
					<span id="wp_cats">loading...</span>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="button" onclick="$('#form').submit();" class="btn btn-primary" value="Go">
				<input type="button" class="btn" value="Close" data-dismiss="modal">
			</div>
		</div>
</section>
<?php } ?>
<!-- wordpress form end -->
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=project/product&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_number = $('input[name=\'filter_number\']').attr('value');
	
	if (filter_number) {
		url += '&filter_number=' + encodeURIComponent(filter_number);
	}

	var filter_trade_type = $('input[name=\'filter_trade_type\']').attr('value');
	
	if (filter_trade_type) {
		url += '&filter_trade_type=' + encodeURIComponent(filter_trade_type);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	var filter_supply_demand = $('select[name=\'filter_supply_demand\']').attr('value');
	
	if (filter_supply_demand != '*') {
		url += '&filter_supply_demand=' + encodeURIComponent(filter_supply_demand);
	}

	var filter_verified = $('select[name=\'filter_verified\']').attr('value');
	
	if (filter_verified != '*') {
		url += '&filter_verified=' + encodeURIComponent(filter_verified);
	}
	
	
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
<script type="text/javascript"><!--
$(document).ready(
		function(){
			$('input[name=\'filter_name\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=project/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
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
									
					return false;
				}
			});

			$('input[name=\'filter_number\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_number=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.model,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_model\']').val(ui.item.label);
									
					return false;
				}
			});


			$('input[name=\'filter_sku\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_sku=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.sku,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_sku\']').val(ui.item.label);
									
					return false;
				}
			});
    }
);

//--></script>
<!-- wordpress js start -->
<?php if (isset($linkpost_wordpress)) { ?>
<script type="text/javascript"><!--
// wordpress init
$('#wpModal').on('show', function () {

});
function linkpostWordpress(){
	$.ajax({
		url: 'index.php?route=linkpost/wordpress/getActivedWp&token=<?php echo $token; ?>',
		dataType: 'json',
		success: function(json) {
			var wpblogHtml = [];
			var inputs = '';
			for(var i=0; i<json.length; i++){
				var cats = json[i].cats;
				var catsHtml = [];
				for(var j=0; j<cats.length; j++){
					catsHtml[j] = '&nbsp;&nbsp;|-<input type="checkbox" name="wpblogs[' + i + '][cats][]" value="' + cats[j].categoryName + '" />&nbsp;' + cats[j].categoryName;
				}
				wpblogHtml[i] = '<div class="controls"><b>' + json[i].host + ':' +  + json[i].port + json[i].path + '&nbsp;|&nbsp;blogid=' +  + json[i].blogid + '</b></div><div class="controls">' + catsHtml.join('</div><div class="controls">') + '</div>' +
				'<input type="hidden" name="wpblogs[' + i + '][path]" value="' + json[i].path + '">' +
				'<input type="hidden" name="wpblogs[' + i + '][host]" value="' + json[i].host + '">' +
				'<input type="hidden" name="wpblogs[' + i + '][port]" value="' + json[i].port + '">' +
				'<input type="hidden" name="wpblogs[' + i + '][blogid]" value="' + json[i].blogid + '">' +
				'<input type="hidden" name="wpblogs[' + i + '][username]" value="' + json[i].username + '">' +
				'<input type="hidden" name="wpblogs[' + i + '][password]" value="' + json[i].password + '">';
			}
			$('#wp_cats').html('<div class="control-group">' + wpblogHtml.join('</div><div class="control-group">') + '</div>');
		}
	});
	$('#wpModal').modal('show');
/*
					<div class="control-group">
						<label class="control-label" for="category"><span class="required">*</span>&nbsp;<?php echo $entry_category?><br /><?php echo $text_tip_split ?></label>
						<div class="controls">
							<input id="tmp_category" type="text" />
						</div>
					</div>
*/
}

//--></script>
<?php } ?>
<!-- wordpress js end -->