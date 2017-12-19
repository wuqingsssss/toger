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
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="pickupaddr_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $pickupaddr_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="pickupaddr_status">
                <?php if ($pickupaddr_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_description; ?></td>
            <td><textarea name="pickupaddr_description" cols="40" rows="5"><?php echo $pickupaddr_description; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="pickupaddr_sort_order" value="<?php echo $pickupaddr_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
         <!-- table id="module" class="list">
        <thead>
          <tr>
            <td class="left">自提点编号</td>
            <td class="left">自提点名称</td>
            <td class="left">自提点地址</td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left">
                <input type="text" class="input-mini" name="addrs[<?php echo $module_row; ?>][no]" value="<?php echo $module['no']; ?>" size="10" />
              </td>
            <td class="left">
              <input type="text" class="input-medium" name="addrs[<?php echo $module_row; ?>][title]" value="<?php echo $module['title']; ?>" size="40"/>
             </td>
            <td class="left">
              <input type="text" class="input-medium" name="addrs[<?php echo $module_row; ?>][addr]" value="<?php echo $module['addr']; ?>" size="100"/>
             </td>
            <td class="left"><select class="input-mini" name="addrs[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="number" class="input-mini" name="addrs[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="5"></td>
            <td class="left"><a onclick="addModule();" class="btn "><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table-->
      </form>
    </div>
  </div>
  
  <script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="number" class="input-mini" name="addrs[<?php echo $module_row; ?>][no]" value="" size="10" /></td>';
	html += '    <td class="left"><input type="text"  class="input-medium" name="addrs[' + module_row + '][title]" value="" size="40" /> </td>';	
	html += '    <td class="left"><input type="text"   class="input-medium" name="addrs[' + module_row + '][addr]" value="" size="100"/> </td>';
	html += '    <td class="left"><select class="input-mini" name="addrs[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="number" class="input-mini" name="addrs[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-danger"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>