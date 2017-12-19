<?php if ($error_warning) { ?>
 <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
             <tr>
              <td><?php echo $entry_category; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($category['category_id'], $product_category)) { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
            </tr>
             <tr>
                <td><span class="required">*</span> <?php echo $entry_number; ?></td>
                <td><input type="text" name="number" size="100" value="<?php echo $number; ?>" />
                  <span class="error"><?php echo $error_number; ?></span>
              </tr>
            
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input type="text" name="name" value="<?php echo $name; ?>"/>
                  <span class="error"><?php echo $error_name; ?></span>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo $entry_trade_type; ?></td>
                <td>
                	<select name="trade_type">
                		<?php foreach ($trade_types as $value){?>
                			<?php if($value==$trade_type){?>
                				<option id="<?php echo $value;?>" selected="selected"><?php echo $value;?></option>
                			<?php }else {?>
                				<option id="<?php echo $value;?>"><?php echo $value;?></option>
                			<?php }?>
                		<?php }?>
                	</select>
                  <span class="error"><?php echo $error_trade_type; ?></span>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo $entry_industry_type; ?></td>
                <td>
                <select name="industry_type">
                		<?php foreach ($industry_types as $industry_type){?>
                			<option id="<?php echo $industry_type;?>"><?php echo $industry_type;?></option>
                		<?php }?>
                	</select>
                
                  <span class="error"><?php echo $error_industry_type; ?></span>
              </tr>
              
               <tr>
                <td><span class="required">*</span> <?php echo $entry_local; ?></td>
                <td>
                 <select name="local_addr_zone" id="local_addr_zone" onchange="changeZone();">
                			<option id="全国" selected="selected">全国</option>
                		<?php foreach ($zones as $zone){?>
                			<?php if($zone['name'] == $local_addr_zone){?>
                				<option id="<?php echo $zone['name'];?>" selected="selected"><?php echo $zone['name'];?></option>
	                		<?php }else{?>
	                			<option id="<?php echo $zone['name'];?>"><?php echo $zone['name'];?></option>
	                		<?php }?>
                		<?php }?>
                </select>
                
                <select name="local_addr_city" id="local_addr_city">
                	<option id="" selected="selected"></option>
                	<?php foreach ($citys as $city){?>
                			<?php if($city['city_name'] == $local_addr_city){?>
                				<option id="<?php echo $city['city_name'];?>" selected="selected"><?php echo $city['city_name'];?></option>
	                		<?php }else{?>
	                			<option id="<?php echo $city['city_name'];?>"><?php echo $city['city_name'];?></option>
	                		<?php }?>
                		<?php }?>
                </select>
                  <span class="error"><?php echo $error_local_addr; ?></span>
              </tr>
              
               <tr>
                <td><span class="required">*</span> <?php echo $entry_period; ?></td>
                <td><input class="date" type="text" name="period" value="<?php echo $period; ?>"/>
                  <span class="error"><?php echo $error_period; ?></span>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo $entry_status; ?></td>
                <td>
                	
                	 <select name="project_status">
                		<?php foreach ($project_statuss as $value){?>
                			<?php  if($value==$project_status){?>
                				<option id="<?php echo $value;?>" selected="selected"><?php echo $value;?></option>
                			<?php }else{?>
                				<option id="<?php echo $value;?>"><?php echo $value;?></option>
                			<?php }?>
                		<?php }?>
                	</select>
                  <span class="error"><?php echo $error_project_status; ?></span>
              </tr>
              
              <tr>
                <td><span class="required"></span> <?php echo '单位'; ?></td>
                <td>
                	
                	 <select name="unit">
                	 	<option id="<?php echo '';?>" ><?php echo '';?></option>
                		<option id="<?php echo '斤';?>" ><?php echo '斤';?></option>
                		<option id="<?php echo '吨';?>" ><?php echo '吨';?></option>
                	</select>
                  <span class="error"><?php echo $error_project_status; ?></span>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo $entry_price; ?></td>
                <td><input type="text" name="price" size="100" value="<?php echo $price; ?>" />
                  <span class="error"><?php echo $error_price; ?></span>
              </tr>
              
              <tr>
              <td><?php echo $entry_supply_demand; ?></td>
              <td><select name="supply_demand">
                  <?php if ($supply_demand == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_demand; ?></option>
                  <option value="1"><?php echo $text_supply; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_demand; ?></option>
                  <option value="1"  selected="selected"><?php echo $text_supply; ?></option>
                  <?php } ?>
                </select></td>
                </tr>
                <tr>
                 <td><?php echo $entry_show; ?></td>
              <td><select name="status">
                  <?php if ($status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <option value="1"  selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                </select></td>
              </tr>
             
              <tr>
                <td><?php echo $entry_condition; ?></td>
                <td>
                
                 <select name="conditions">
                		<?php foreach ($project_conditions as $value){?>
                		<?php if($value==$conditions){?>
                			<option id="<?php echo $value;?>" selected="selected"><?php echo $value;?></option>
                			<?php }else{?>
                			<option id="<?php echo $value;?>"><?php echo $value;?></option>
                			<?php }?>
                		<?php }?>
                </select>
                </td>
              </tr>
               <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="description" id="description<?php echo $language['language_id']; ?>"><?php echo  $description;?></textarea></td>
              </tr>
            </table>
          </div>
           <?php } ?>
          
        </div>
      </form>
    </div>
  </div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script>

<script type="text/javascript"><!--

$('#tabs a').tabs();
$('#languages a').tabs();
$('#vtab-option a').tabs();

//--></script>

<script type="text/javascript"><!--

function changeZone()
{
	var zone = document.getElementById('local_addr_zone');
	var selectCity = '<?php if(isset($local_addr_city)){ echo $local_addr_city;}else {echo "";} ?>';
	var cityZone = zone.value;
	$.ajax({
		url: 'index.php?route=project/product/getCitys&token=<?php echo $token; ?>',
		dataType: 'json',
		type: 'POST',
		data: 'cityZone='+cityZone,
		success: function(json) {
			$("#local_addr_city").empty();
			for(var i =0;i<json.length;i++){
				if(selectCity==json[i]['city_name'])
				{
					$("<option value='"+json[i]['city_name']+"' selected='selected'>"+json[i]['city_name']+"</option>").appendTo($("#local_addr_city"));
				}
				else
				{
					$("<option value='"+json[i]['city_name']+"'>"+json[i]['city_name']+"</option>").appendTo($("#local_addr_city"));
				}
			}
		}
	 });
}

$(document).ready(function(){
	
//	
//	$('#local_addr_zone').bind('change',function(){
//		changeZone();
//	});
		
	
	$('input[name=\'related\']').autocomplete({
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
			$('#product-related' + ui.item.value).remove();

			$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

			$('#product-related div:odd').attr('class', 'odd');
			$('#product-related div:even').attr('class', 'even');

			return false;
		}
	});
});


$('#product-related div img').live('click', function() {
	$(this).parent().remove();

	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');
});
//--></script>
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input   type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea class="span4"  name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '  </tr>';
    html += '</tbody>';

	$('#attribute tfoot').before(html);

	attributeautocomplete(attribute_row);

	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent(request.term),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);

			return false;
		}
	});
}

$(document).ready(function(){
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';

			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

					currentCategory = item.category;
				}

				self._renderItem(ul, item);
			});
		}
	});

	

	$('#attribute tbody').each(function(index, element) {
		attributeautocomplete(index);
	});
});

//--></script>
<script type="text/javascript"><!--
$(document).ready(function(){
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'json',
			data: 'filter_name=' +  encodeURIComponent(request.term),
			success: function(data) {
				response($.map(data, function(item) {
					return {
						category: item.category,
						label: item.name,
						value: item.option_id,
						type: item.type
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
		if (ui.item.type != 'virtual_product' ) {
			html += '	<table class="form">';
			html += '	  <tr>';
			html += '		<td><?php echo $entry_required; ?></td>';
			html += '       <td><select name="product_option[' + option_row + '][required]">';
			html += '	      <option value="1"><?php echo $text_yes; ?></option>';
			html += '	      <option value="0"><?php echo $text_no; ?></option>';
			html += '	    </select></td>';
			html += '     </tr>';
		}else{
			html += '	<input type="hidden" name="product_option[' + option_row + '][required]" value="0" />';
		}
		
		if (ui.item.type == 'text') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'textarea') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><textarea  class="span6" name="product_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'file') {
			html += '     <tr style="display: none;">';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'date') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="date" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'datetime') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'time') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="time" /></td>';
			html += '     </tr>';
		}


		html += '  </table>';

		if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}

		if (ui.item.type == 'color' ) {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="left"><?php echo $entry_option_color; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="2"></td>';
			html += '        <td class="left"><a onclick="addOptionColorValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}

		if (ui.item.type == 'virtual_product' ) {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_virtual; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionVirtualValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}
		
		$('#tab-option').append(html);
		
		$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-option a:first\').trigger(\'click\'); $(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); return false;" /></a>');

		$('#vtab-option a').tabs();

		$('#option-' + option_row).trigger('click');

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});

		$('.time').timepicker({timeFormat: 'h:m'});

		option_row++;

		return false;
	}
});
});
//--></script>
<script type="text/javascript"><!--

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select class="span2" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input class="span1" type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="left"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';
	html += '    <td class="right"><select  class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_value_row++;
}

//--></script>

<script type="text/javascript"><!--
var option_color_value_row = <?php echo $option_value_row; ?>;

function addOptionColorValue(option_row) {
	html  = '<tbody id="option-value-row' + option_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="left">';
	html += '    <input type="text" value="" name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][product_value]" id="product_option_lable'+ option_row + option_color_value_row+'"  class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"> ';
	html += '    <input type="hidden" value="" name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][color_product_id]" id="product_option_value'+ option_row  + option_color_value_row+'"  > </td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_color_value_row_temp=option_color_value_row;
	
	$('input[name=\'product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][product_value]\']').autocomplete({
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
			$('#product_option_lable' + option_row + option_color_value_row_temp ).val(ui.item.label);
			$('#product_option_value' + option_row  + option_color_value_row_temp).val(ui.item.value);
			return false;
		}
	});

	option_color_value_row++;
}
//--></script>

<script type="text/javascript"><!--
var option_virtual_value_row = <?php echo $option_value_row; ?>;

function addOptionVirtualValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select class="span2" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input class="span1" type="text" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="right"><input class="span2" type="text" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][product_value]" value="" size="20" /></td>';
	html += '    <td class="left"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1"  name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][points]" value="" size="5" /></td>';
	
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_virtual_value_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_value_row++;
}
//--></script>


<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text"   name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text"   name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#discount tfoot').before(html);

	$('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" class="span1"  name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" class="span1"  name="product_special[' + special_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text"  name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#special tfoot').before(html);

	$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	special_row++;
}
//--></script>

<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});


//--></script>

<script type="text/javascript"><!--



//--></script>
