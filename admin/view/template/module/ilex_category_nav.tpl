<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h2>
      <div class="buttons">
      <a onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="btn"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_title; ?></td>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><?php foreach ($languages as $language) { ?>
        				<input name="ilex_category_nav_module[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>" />
        				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
        			<?php } ?></td>	
              <td class="left"><select name="ilex_category_nav_module[<?php echo $module_row; ?>][layout_id]">
                  <?php if ($module['layout_id'] == 0) { ?>
                  <option value="0" selected="selected"><?php echo $text_alllayout; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_alllayout; ?></option>
                  <?php } ?>
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="ilex_category_nav_module[<?php echo $module_row; ?>][position]">
				  <?php if ($module['position'] == 'header_top') { ?>
                  <option value="header_top" selected="selected"><?php echo $text_header_top; ?></option>
                  <?php } else { ?>
                  <option value="header_top"><?php echo $text_header_top; ?></option>
                  <?php } ?>
				  <?php if ($module['position'] == 'header_bottom') { ?>
                  <option value="header_bottom" selected="selected"><?php echo $text_header_bottom; ?></option>
                  <?php } else { ?>
                  <option value="header_bottom"><?php echo $text_header_bottom; ?></option>
                  <?php } ?>
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
                </select></td>
              <td class="left"><select name="ilex_category_nav_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="ilex_category_nav_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
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
      html += '    <td class="left">';
      <?php foreach ($languages as $language) { ?>
      html += '      <input name="ilex_category_nav_module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" />';
      html += '      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
      <?php } ?>
      html += '    </td>';
      html += '    <td class="left"><select name="ilex_category_nav_module[' + module_row + '][layout_id]">';
      html += '      		<option value="0"><?php echo $text_alllayout; ?></option>';
      <?php foreach ($layouts as $layout) { ?>
	  html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	  <?php } ?>
      html += '    </select></td>';
      html += '    <td class="left"><select name="ilex_category_nav_module[' + module_row + '][position]">';
	  html += '      <option value="header_top"><?php echo $text_header_top; ?></option>';
	  html += '      <option value="header_bottom"><?php echo $text_header_bottom; ?></option>';
      html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
      html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
      html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	  html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
      html += '    </select></td>';
      html += '    <td class="left"><select name="ilex_category_nav_module[' + module_row + '][status]">';
      html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
      html += '      <option value="0"><?php echo $text_disabled; ?></option>';
      html += '    </select></td>';
      html += '    <td class="right"><input type="text" name="ilex_category_nav_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
      html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
      html += '  </tr>';
      html += '</tbody>';
	
      $('#module tfoot').before(html);
	
      module_row++;
    }
    //--></script> 
