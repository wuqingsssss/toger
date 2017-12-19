<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <div class="buttons">
    <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
    <a onclick="location = '<?php echo $cancel; ?>';" class="btn"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_category; ?></td>
            <td class="left"><?php echo $entry_limit; ?></td>
			<td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="left"><?php echo $entry_style; ?></td>
        
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left">
            <select name="single_article_module[<?php echo $module_row; ?>][article_category]">
            <?php foreach ($categories as $category) { ?>
            <?php  if($category['article_category_id'] == $module['article_category']) {?>
            <option  value="<?php echo $category['article_category_id']; ?>" selected="selected" /><?php echo $category['name']; ?></option>
            <?php } else {?>
             <option  value="<?php echo $category['article_category_id']; ?>" /><?php echo $category['name']; ?></option>
            <?php } ?>
            <?php } ?>
            </select>
            </td>
            <td class="left">
            <input type="number" class="input-mini" name="single_article_module[<?php echo $module_row; ?>][limited]" value="<?php echo $module['limited']; ?>" size="3" />
            </td>
         	<td class="left"><select name="single_article_module[<?php echo $module_row; ?>][layout_id]" class="span2">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="single_article_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position']  == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position']  == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position']  == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position']  == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
              <td class="left"><select name="single_article_module[<?php echo $module_row; ?>][status]" class="span2">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
              <td class="left">
              
           	   <select name="single_article_module[<?php echo $module_row; ?>][style]">
           	   <?php foreach($styles as  $style) {?>
           	   	<option value="<?php echo $style; ?>" <?php if($module['style']==$style) {?>selected="selected"<?php }?>><?php echo ${'entry_style_'.$style}; ?></option>
           	   <?php } ?>
               
                </select>
            </td>
            
            <td class="right"><input type="number" class="input-mini" name="single_article_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="7"></td>
            <td class="right"><a onclick="addModule();" class="btn"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
       
    </form>
  </div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left">';
	
	
	html += ' 	<select name="single_article_module[' + module_row + '][article_category]">';
	<?php foreach ($categories as $category) { ?>
	html += '     <option value="<?php echo $category['article_category_id']; ?>"><?php echo $category['name']; ?></option>';
	<?php } ?>
	html += '	</select>';
	html += '    </td>';;
	html += '    <td class="left"><input type="number" class="input-mini" name="single_article_module[' + module_row + '][limited]" value="10" size="3" /> </td>';

	html += '    <td class="left"><select name="single_article_module[' + module_row + '][layout_id]" class="span2">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="single_article_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="single_article_module[' + module_row + '][status]" class="span2">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
    html += '    <td class="left">';
    html += '  	 <select name="single_article_module[' + module_row + '][style]"> ';
    html += '  	  <option value="download"><?php echo $entry_style_download_cate; ?></option> ';   
    html += '  	  <option value="cate"><?php echo $entry_style_cate; ?></option> ';   
    html += '  	  <option value="cate-list"><?php echo $entry_style_cate_list; ?></option> ';   
    html += '  	  <option value="tab-list"><?php echo $entry_style_tab_list; ?></option>';   
    html += '  	  <option value="list"><?php echo $entry_style_list; ?></option> ';      
    html += '  	    </select>';
    html += '    </td>';
	html += '    <td class="right"><input type="number" class="input-mini" name="single_article_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

	module_row++;
}


//--></script>