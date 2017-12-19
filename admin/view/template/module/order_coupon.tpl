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
        <div class="vtabs">
          <?php $module_row = 1; ?>
          <?php foreach ($modules as $module) { ?>
          <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $layouts[$module['layout_id']]['name'].'.'. $module['template']. '.' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
          <?php $module_row++; ?>
          <?php } ?>
          <span id="module-add"><?php echo $button_add_module; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> </div>
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) {?>
        <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_layout; ?></td>
              <td><select name="order_coupon_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
             <tr>
              <td><?php echo $entry_template; ?></td>
              <td>
            <select name="order_coupon_module[<?php echo $module_row; ?>][template]">
                <?php foreach($templates as $template) {?>
                	<option value="<?php echo $template;?>" <?php if($template==$module['template']) {?> selected="selected" <?php }?>><?php echo $template?></option>
                <?php } ?>
              </select>
            </td>
            </tr>
            <tr>
              <td><?php echo $entry_position; ?></td>
              <td><select name="order_coupon_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select>
                <input type="hidden" name="order_coupon_module[<?php echo $module_row; ?>][layout_module_id]" value="<?php echo $module['layout_module_id']; ?>"/>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="order_coupon_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
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
              <td><input type="text" name="order_coupon_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            </tr>
               <tr>
              <td><?php echo $entry_order; ?></td>
              <td>
              <div><ul id="rule_<?php echo $module_row; ?>" class='form'>
              <?php
                $rule_row = 1; 
               foreach($module['rule'] as $rule){ ?>
              <li>
                满<input type="text" name="order_coupon_module[<?php echo $module_row; ?>][rule][<?php echo $rule_row;?>][order_total]" placeholder="请填写满足金额" value="<?php echo $rule['order_total']; ?>" size="3" />
                赠<select name="order_coupon_module[<?php echo $module_row; ?>][rule][<?php echo $rule_row;?>][code_type]">
                <option value='1'<?php if($rule['code_type']=='1') echo ' selected';?>>优惠劵</option>
                <option value='2'<?php if($rule['code_type']=='2') echo ' selected';?>>储值</option>
                </select>
                <input type="text" name="order_coupon_module[<?php echo $module_row; ?>][rule][<?php echo $rule_row;?>][coupon_code]" placeholder="请填写优惠劵或储值code" value="<?php echo $rule['coupon_code']; ?>" size="3" />
              </li>
              <?php
                 $rule_row++;
                }?>
          </ul></div>
         <span>

         <img src="view/image/add.png" onclick="addRule(<?php echo $module_row; ?>,<?php echo $rule_row; ?>);" style="cursor: pointer;"/>
         
         </span>
         
        </td>
            </tr>
          </table>
        </div>
        <?php $module_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';

	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $entry_layout; ?></td>';
	html += '      <td><select name="order_coupon_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '      </select><input type="hidden" name="order_coupon_module[' + module_row + '][layout_module_id]" value=""/></td>';
	html += '    </tr>';
	html += '  <tr>\
     <td><?php echo $entry_template; ?></td>\
     <td>\
       <select name="order_coupon_module[' + module_row + '][template]">\
       <?php foreach($templates as $template) {?>\
       	<option value="<?php echo $template;?>" ><?php echo $template?></option>\
       <?php } ?>\
     </select>\
   </td>\
   </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_position; ?></td>';
	html += '      <td><select name="order_coupon_module[' + module_row + '][position]">';
	html += '        <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '        <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '        <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '        <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_status; ?></td>';
	html += '      <td><select name="order_coupon_module[' + module_row + '][status]">';
	html += '        <option value="1"><?php echo $text_enabled; ?></option>';
	html += '        <option value="0"><?php echo $text_disabled; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort_order; ?></td>';
	html += '      <td><input type="text" name="order_coupon_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += ' <tr>\
     <td><?php echo $entry_order; ?></td>\
         <td><ul id="rule_' + module_row + '" class="form">\
         <li>\
         满<input type="text" name="order_coupon_module[' + module_row + '][rule][1][order_total]" placeholder="请填写满足金额" value="" size="3" />\
         赠<select name="order_coupon_module[' + module_row + '][rule][1][code_type]">\
         <option value="1">优惠劵</option>\
         <option value="2">储值</option>\
         </select>\
         <input type="text" name="order_coupon_module[' + module_row + '][rule][1][coupon_code]" placeholder="请填写优惠劵的code" value="" size="3" />\
       </li>\
         </ul><span>\
            <img src="view/image/add.png" onclick="addRule(' + module_row + ',2);" style="cursor: pointer;"/>\
            </span>\
         </td>\
    </tr>';
	html += '  </table>'; 
	html += '</div>';
	
	$('#form').append(html);
	
	$('#language-' + module_row + ' a').tabs();
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $text_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.vtabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}

function addRule(module_row,rule_row) {	
	var html;
	html='<li>\
    满<input type="text" name="order_coupon_module[' + module_row + '][rule]['+rule_row+'][order_total]" placeholder="请填写满足金额" value="" size="3" />\
    赠<select name="order_coupon_module[' + module_row + '][rule]['+rule_row+'][code_type]">\
    <option value="1">优惠劵</option>\
    <option value="2">储值</option>\
    </select>\
    <input type="text" name="order_coupon_module[' + module_row + '][rule]['+rule_row+'][coupon_code]" placeholder="请填写优惠劵的code" value="" size="3" />\
        </li>';
        $('#form #rule_'+module_row).append(html);
        
console.log($('#form #rule_'+module_row).html(),html);
	rule_row++;
}

//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 
