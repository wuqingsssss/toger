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
            <td class="left"><?php echo $entry_hidebtn; ?></td>
            <td class="left"><?php echo $entry_callback; ?></td>
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
            <td class="left"><input type="text" name="sharebtn_module[<?php echo $module_row; ?>][btn_hide]" value="<?php echo $module['btn_hide']; ?>" class="input-mini" />
              <?php if (isset($error_dimension[$module_row])) { ?>
              <span class="error"><?php echo $error_dimension[$module_row]; ?></span>
              <?php } ?></td>
               <td class="left"><input type="text" name="sharebtn_module[<?php echo $module_row; ?>][callback]" value="<?php echo $module['callback']; ?>" class="input-mini" />
              <?php if (isset($error_dimension[$module_row])) { ?>
              <span class="error"><?php echo $error_dimension[$module_row]; ?></span>
              <?php } ?></td>
            <td class="left"><select name="sharebtn_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
               <td class="left"><select name="sharebtn_module[<?php echo $module_row; ?>][template]">
                <?php foreach($templates as $template) {?>
                	<option value="<?php echo $template;?>" <?php if($template==$module['template']) {?> selected="selected" <?php }?>><?php echo $template?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="sharebtn_module[<?php echo $module_row; ?>][position]">
                <?php foreach($positions as $position) {?>
                	<option value="<?php echo $position['position']?>" <?php if($position['position']==$module['position']) {?> selected="selected" <?php }?>><?php echo $position['title']?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="sharebtn_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="sharebtn_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="7"></td>
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
	html += '    <td class="left"><input type="text" name="sharebtn_module[' + module_row + '][btn_hide]" value="" size="3" /> </td>';
	html += '    <td class="left"><input type="text" name="sharebtn_module[' + module_row + '][callback]" value="" size="3" /> </td>';
	html += '    <td class="left"><select name="sharebtn_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '<td class="left"><select name="sharebtn_module[' + module_row + '][template]">';
    <?php foreach($templates as $template) {?>
    html += '	<option value="<?php echo $template;?>" ><?php echo $template?></option>';
    <?php } ?>
    html += ' </select></td>';
	html += '    <td class="left"><select name="sharebtn_module[' + module_row + '][position]">';
	<?php foreach($positions as $position) {?>
	html += '      <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="sharebtn_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="sharebtn_module[' + module_row + '][sort_order]" value="100" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
