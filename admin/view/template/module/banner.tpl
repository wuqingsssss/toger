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
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_banner; ?></td>
            <td class="left"><?php echo $entry_dimension; ?></td>
            <td class="left"><?php echo $entry_class; ?></td>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_template; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td class="span2"></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><select name="banner_module[<?php echo $module_row; ?>][banner_id]">
                <?php foreach ($banners as $banner) { ?>
                <?php if ($banner['banner_id'] == $module['banner_id']) { ?>
                <option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <input type="hidden" name="banner_module[<?php echo $module_row; ?>][layout_module_id]" value="<?php echo $module['layout_module_id']; ?>"/>
              </td>
            <td class="left"><input type="text" name="banner_module[<?php echo $module_row; ?>][width]" value="<?php echo $module['width']; ?>" class="input-mini" />
              <input type="text" name="banner_module[<?php echo $module_row; ?>][height]" value="<?php echo $module['height']; ?>" class="input-mini" />
              <?php if (isset($error_dimension[$module_row])) { ?>
              <span class="error"><?php echo $error_dimension[$module_row]; ?></span>
              <?php } ?></td>
               <td class="left"><input type="text" name="banner_module[<?php echo $module_row; ?>][class]" value="<?php echo $module['class']; ?>" class="input-mini" />
             </td>
            <td class="left"><select name="banner_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
              <td class="left"><select name="banner_module[<?php echo $module_row; ?>][template]">
                <?php foreach($templates as $template) {?>
                	<option value="<?php echo $template;?>" <?php if($template==$module['template']) {?> selected="selected" <?php }?>><?php echo $template?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="banner_module[<?php echo $module_row; ?>][position]">
                <?php foreach($positions as $position) {?>
                	<option value="<?php echo $position['position']?>" <?php if($position['position']==$module['position']) {?> selected="selected" <?php }?>><?php echo $position['title']?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="banner_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="banner_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="8"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
 </div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="banner_module[' + module_row + '][banner_id]">';
	<?php foreach ($banners as $banner) { ?>
	html += '      <option value="<?php echo $banner['banner_id']; ?>"><?php echo addslashes($banner['name']); ?></option>';
	<?php } ?>
	html += '    </select>\
		 <input type="hidden" name="banner_module[' + module_row + '][layout_module_id]" value=""/></td>';
	html += '    <td class="left"><input type="text" name="banner_module[' + module_row + '][width]" value="" size="3" /> <input type="text" name="banner_module[' + module_row + '][height]" value="" size="3" /></td>';
	html += '  <td class="left"><input type="text" name="banner_module[' + module_row + '][class]" value="" class="input-mini" />';
	html += '     </td>';
	html += '    <td class="left"><select name="banner_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '<td class="left"><select name="banner_module[' + module_row + '][template]">';
     <?php foreach($templates as $template) {?>
     html += '	<option value="<?php echo $template;?>" ><?php echo $template?></option>';
     <?php } ?>
     html += ' </select></td>';
 	html += '    <td class="left"><select name="banner_module[' + module_row + '][position]">';
	<?php foreach($positions as $position) {?>
	html += '      <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="banner_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="banner_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
