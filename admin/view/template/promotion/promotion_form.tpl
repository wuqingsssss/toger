  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title_rule; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn"><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
      <a href="#tab-general"><?php echo $tab_general; ?></a>
      
     <a href="#tab-group">活动分组</a>
     
      <a href="#tab-store">页面设置</a>
      
      </div>
      <form action="<?php echo $action; ?>" enctype="multipart/form-data" method="post"  id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_pb_name; ?></td>
              <td><input type="text" name="pb_name" value="<?php echo $promotionInfo['pb_name']; ?>" size="40" />
            </tr>
             <tr>
              <td><span class="required">*</span> <?php echo $entry_pb_key; ?></td>
              <td><input type="text" name="pb_key" value="<?php echo $promotionInfo['pb_key']; ?>" size="40" />
            </tr>
             <tr>
              <td><span class="required">*</span> <?php echo $entry_pr_code; ?><?php echo $promotionInfo['pr_code'];?></td>
              <td>
              <select name="pr_code">
              	<option value="">--</option>
                  <?php foreach (EnumPromotionTypes::getPromotionTypes() as $result) { ?>
		                  <?php if (mb_strtolower($promotionInfo['pr_code']) == mb_strtolower($result['value'])) { ?>
		                  <option value="<?php echo $result['value']; ?>" selected="selected"><?php echo $result['name']; ?></option>
		                  <?php } else { ?>
		                  <option value="<?php echo $result['value']; ?>"><?php echo $result['name']; ?></option>
		                  <?php } ?>
                  <?php } ?>
                </select>
            </tr>
            <tr>
              <td><?php echo $entry_start_time; ?></td>
              <td><input type="text" name="start_time" value="<?php echo $promotionInfo['start_time']; ?>" size="40" class="datetime"/>
            </tr>
            <tr>
              <td><?php echo $entry_end_time; ?></td>
              <td><input type="text" name="end_time" value="<?php echo $promotionInfo['end_time']; ?>" size="40" class="datetime"/>
            </tr>

          </table>
        </div>
        <div id="tab-group">
            <table id="discount" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_pr_group; ?></td>
                <td class="right"><?php echo $entry_pr_code; ?></td>
                <td class="right"><?php echo $entry_pr_rule; ?></td>
                <td class="right"><?php echo $entry_pr_banner; ?></td>
                <td class="right"><?php echo $entry_pr_sort_order; ?></td>
                <td class="right"><?php echo $entry_action; ?></td>
              </tr>
            </thead>
            <?php $discount_row = 0; ?>
            <?php foreach ($promotion_prs as $pr) { ?>
            <tbody id="discount-row<?php echo $discount_row; ?>">
              <tr>
                <td class="left"><input type="text" class="span1" name="pr[<?php echo $discount_row; ?>][group]" value="<?php echo $pr['pr_group']; ?>" />
                <input type="hidden" name="pr[<?php echo $discount_row; ?>][id]" value="<?php echo $pr['pr_id']; ?>"/>
                </td>
                <td class="right">
                 <select name="pr[<?php echo $discount_row; ?>][code]">
                    	<option value="">--</option>
                  <?php foreach (EnumPromotionTypes::getPromotionTypes() as $result) { ?>
		                  <?php if (mb_strtolower($pr['pr_code']) == mb_strtolower($result['value'])) { ?>
		                  <option value="<?php echo $result['value']; ?>" selected="selected"><?php echo $result['name']; ?></option>
		                  <?php } else { ?>
		                  <option value="<?php echo $result['value']; ?>"><?php echo $result['name']; ?></option>
		                  <?php } ?>
                  <?php } ?>                   
                  </select>
                </td>     
                <td class="right"><input type="text" class="span1" name="pr[<?php echo $discount_row; ?>][rule]" value="<?php echo $pr['pr_rule']; ?>" size="2" /></td>           
                <td class="right">
                     <input type="hidden" name="pr[<?php echo $discount_row; ?>][banner]" value="<?php echo $pr['pr_banner']; ?>" id="pr_banner_<?php echo $discount_row; ?>" />
                     <img src="<?php echo HTTP_IMAGE.$pr['pr_banner']; ?>" id="preview_<?php echo $discount_row; ?>"  width="100px" class="image" onclick="image_upload('pr_banner_<?php echo $discount_row; ?>', 'preview_<?php echo $pr[pr_id]; ?>');" />
                    <div>
	                <a onclick="image_upload('pr_banner_<?php echo $discount_row; ?>', 'preview_<?php echo $discount_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview_<?php echo $discount_row; ?>').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#pr_banner_<?php echo $discount_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
                 <td class="right"><input type="text" class="span1" name="pr[<?php echo $discount_row; ?>][sort_order]" value="<?php echo $pr['sort_order']; ?>" size="2" /></td>       
                <td class="left">
                <a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a>|
                <a href="index.php?route=promotion/zerobuy&token=<?php echo $token; ?>6&pr_id=<?php echo $pr['pr_id']; ?>" class="button"><span><?php echo $btn_edit_product; ?></span></a>
                </td>
              </tr>
            </tbody>
            <?php $discount_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td class="left"><a onclick="addGroup();" class="button"><span><?php echo $btn_add_group; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-store">
          <table class="form">
            <tr>
                        <td>分享标题</td>
                        <td><input name="share_title" value="<?php echo $promotionInfo['share_title']; ?>"/>
                          </td>
                    </tr>
                     <tr>
                        <td>分享描述</td>
                        <td><input name="share_desc" value="<?php echo $promotionInfo['share_desc']; ?>"/>
                       </td>
                    </tr>
                       <tr>
                        <td>原始分享链接<br/>系统将自动生成短链接</td>
                        <td><input name="share_link" value="<?php echo $promotionInfo['share_link']; ?>"/><?php echo $promotionInfo['share_short_link']; ?>
                       </td>
                    </tr>
                     <tr>
                     <td>分享图标</td>
                     <td valign="top"><input type="hidden" name="share_image" value="<?php echo $promotionInfo['share_image']; ?>" id="share_image" />
                     <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('share_image', 'preview');" />
                    <div>
	                <a onclick="image_upload('share_image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>     
            <tr>
                        <td>展示模版</td>
                        <td><input name="template" value="<?php echo $promotionInfo['template']; ?>"/>
                       </td>
                    </tr>
            <tr>
                        <td>页头</td>
                        <td><textarea name="page_header" id="page_header" style="width: 655px; height: 339px;"><?php echo $promotionInfo['page_header']; ?></textarea></td>
            </tr>
            <tr>
                        <td>页尾</td>
                        <td><textarea name="page_footer" id="page_footer" style="width: 655px; height: 339px;"><?php echo $promotionInfo['page_footer']; ?></textarea></td>
           </tr>
          </table>
        </div>
       <input type="hidden" name="pb_id" value="<?php  echo $promotionInfo['pb_id'];?>" />
       <input type="hidden" name="pr_id" value="<?php  echo $promotionInfo['pr_id'];?>" />
      </form>
    </div>
  </div>
 <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--

CKEDITOR.replace('page_header', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('page_footer', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

//--></script>

<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addGroup() {
	html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><input type="text" class="span1" name="pr[' + discount_row + '][group]" value=""/>\
                  <input type="hidden" name="pr[' + discount_row + '][id]" value="-1"/></td>';
    html += ' <td class="right"> <select name="pr[' + discount_row + '][code]">\
    	<option value="" selected="selected">--</option>\
        <?php foreach (EnumPromotionTypes::getPromotionTypes() as $result) { ?>\
                <option value="<?php echo $result['value']; ?>"><?php echo $result['name']; ?></option>\
        <?php } ?>\
</select></td>';
    html += '    <td class="right"><input type="text" class="span1" name="pr[' + discount_row + '][rule]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="hidden" name="pr[' + discount_row + '][banner]" value="" id="pr_banner_' + discount_row + '" />\
    <img src="" id="preview_' + discount_row + '" class="image" onclick="image_upload(\'pr_banner_' + discount_row + '\', \'preview_' + discount_row + '\');" />\
    <div>\
    <a onclick="image_upload(\'pr_banner_' + discount_row + '\', \'preview_' + discount_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;\
      <a onclick="$(\'#preview_' + discount_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); \
      $(\'#pr_banner_' + discount_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a>\
    </div></td>';
    
    html += '<td class="right"><input type="text" class="span1" name="pr[' + discount_row + '][sort_order]" value="100" size="2" /></td>';  
	html += '<td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
    html += '</tbody>';

	$('#discount tfoot').before(html);

	$('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 

 <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript"><!--
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
//--></script> 
