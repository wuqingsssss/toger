<?php if ($error) { ?>
	<div class="warning"><?php echo $error['error_warning']; ?></div>
<?php } ?>
<div class="box">
    <div class="heading">
		<div class="buttons">
			<a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
		<div id="tabs" class="htabs">
			<a href="#tab-general"><?php echo $tab_general; ?></a>

		</div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="g_id" value="<?php echo $info['g_id'] ?>">
			<div id="tab-general">
				<div id="language">
					<table class="form">
						<tr>
							<td><span class="required">*</span> 菜品<span class="help">请从自动匹配的候补中选择</span></td>
							<td><input type="text" name="p_name" class="span4"  value="<?php echo $info['p_name']; ?>" />
							     <?php if (isset($error['p_name'])) { ?>
									<span class="error"><?php echo $error['p_name']; ?></span>
								 <?php } ?>
						    </td>
						</tr>
						<tr>

							<td><span class="required">*</span> 菜品ID</td>
							<td><input type="text" name="product_id" class="span4"  value="<?php echo $info['product_id']; ?>" readonly=""/></td>
						</tr>
						<tr>
							<td>团购商品图片</td>
							<td valign="top"><input type="hidden" name="image" value="<?php echo $info['image']; ?>" id="image"/>
								<img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_edit('image', 'preview');" style="width:100px;height:100px"/>
								<div>
									<a onclick="image_edit('image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
									<a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>');
                                            $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
								</div>
								<?php if (isset($error['image'])) { ?>
									<span class="error"><?php echo $error['image']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 标题</td>
							<td><input type="text" name="name" size="100" maxlength="255" 
									   value="<?php echo $info['name'] ?>" />
									   <?php if (isset($error['name'])) { ?>
									<span class="error"><?php echo $error['name']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td> 描述</td>
							<td><input type="text" name="desc" size="100" maxlength="255" 
									   value="<?php echo $info['desc'] ?>" />
									   <?php if (isset($error['desc'])) { ?>
									<span class="error"><?php echo $error['desc']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>详细介绍</td>
							<td>
								<textarea rows="5" class="input-xxlarge" name="rich_text" 
										  id="description">
											  <?php echo $info['rich_text'] ?>
								</textarea>
							</td>
						</tr>
							<tr>
							<td>分享标题<span class="help">不设置时，缺省标题为团购标题</span></td>
							<td><input type="text" name="share_title" size="100" maxlength="255" 
									   value="<?php echo $info['share_title'] ?>" />
									   <?php if (isset($error['share_title'])) { ?>
									<span class="error"><?php echo $error['share_title']; ?></span>
								<?php } ?>
							</td>
						</tr>
					    <tr>
							<td>分享图标<span class="help">不设置时，分享图标缺省为团购商品图片</span></td>
						    <td valign="top"><input type="hidden" name="share_image" value="<?php echo $info['share_image']; ?>" id="share_image"/>
								<img src="<?php echo $preview_share; ?>" alt="" id="preview_share" class="image" onclick="image_edit('share_image', 'preview_share');" style="width:100px;height:100px"/>
								<div>
									<a onclick="image_edit('share_image', 'preview_share');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
									<a onclick="$('#preview_share').attr('src', '<?php echo $no_image; ?>');
                                            $('#share_image').attr('value', '');"><?php echo $text_clear; ?></a>
								</div>
								<?php if (isset($error['share_image'])) { ?>
									<span class="error"><?php echo $error['share_image']; ?></span>
								<?php } ?>
							</td>
						</tr>
							<tr>
							<td>分享内容<span class="help">不设置时，缺省内容为团购描述</span></td>
						    <td><input type="text" name="share_desc" size="100" maxlength="255" 
									   value="<?php echo $info['share_desc'] ?>" />
									   <?php if (isset($error['share_desc'])) { ?>
									<span class="error"><?php echo $error['share_desc']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 团购价</td>
							<td><input type="text" name="sell_price" size="100" maxlength="255" 
									   value="<?php echo $info['sell_price'] ?>" />
									   <?php if (isset($error['sell_price'])) { ?>
									<span class="error"><?php echo $error['sell_price']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 商品数量</td>
							<td><input type="text" name="quantity" size="100" maxlength="255" 
									   value="<?php echo $info['quantity'] ?>" />
									   <?php if (isset($error['quantity'])) { ?>
									<span class="error"><?php echo $error['quantity']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 成团人数<span class="help">2-10人</span></td>
							<td><input type="text" name="member_num" size="100" maxlength="255" 
									   value="<?php echo $info['member_num'] ?>" />
									   <?php if (isset($error['member_num'])) { ?>
									<span class="error"><?php echo $error['member_num']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 成团有效天数</td>
							<td><input type="text" name="duration" size="100" maxlength="255" 
									   value="<?php echo $info['duration'] ?>" />
									   <?php if (isset($error['duration'])) { ?>
									<span class="error"><?php echo $error['duration']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 最多可发起团数</td>
							<td><input type="text" name="group_num" size="100" maxlength="255" 
									   value="<?php echo $info['group_num'] ?>" />
									   <?php if (isset($error['group_num'])) { ?>
									<span class="error"><?php echo $error['group_num']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> 可发起团购时间</td>
							<td><input type="text" name="start_time" value="<?php echo $info['start_time']; ?>" size="12"
									   id="date-start"/>
								<input type="text" name="end_time" value="<?php echo $info['end_time']; ?>" size="12"
									   id="date-end"/>
									   <?php if (isset($error['time'])) { ?>
									<span class="error"><?php echo $error['time']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>发货日期<span class="help">为空时，按发团时间＋团购有效天数＋1算出；否则按该日期统一发货</span></td>
							<td><input type="text" name="send_time" value="<?php echo $info['send_time']; ?>" size="12"
									   id="date-send"/>
									   <?php if (isset($error['send_time'])) { ?>
									<span class="error"><?php echo $error['send_time']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
    						<td><?php echo $entry_status; ?></td>
                            <td><select name="status">
                                    <?php if ($info['status'] == '1') { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
					</table>
				</div>
			</div>

		</form>
    </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
                                        CKEDITOR.replace('description', {
                                            filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                                            filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                                            filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                                            filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                                            filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                                            filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
                                        });
</script> 


<script type="text/javascript"><!--
$(document).ready(
            function () {
				var pre = "<?php echo HTTPS_IMAGE ?>";
                $('input[name=\'p_name\']').autocomplete({
                    delay: 0,
                    source: function (request, response) {
                        $.ajax({
                            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                            dataType: 'json',
                            success: function (json) {
                                response($.map(json, function (item) {
                                    return {
                                        label: item.name,
                                        value: item.product_id,
										img: pre + item.image,
										img_url: item.image
                                    };
                                }));
                            }
                        });
                    },
                    select: function (event, ui) {
//                        $('input[name=\'filter_name\']').val(ui.item.label);
//                        $('input[name=\'pid\']').val(ui.item.value);
						$('input[name=\'image\']').val(ui.item.img_url);
						$('#preview').attr('src',ui.item.img);
						$('input[name=\'product_id\']').attr('value',ui.item.value);
						$('input[name=\'p_name\']').attr('value',ui.item.label);
                        return false;
                    }
                });
            }
    );
/*
 * var ImageUpload={ Title: string,Token:string }
 * */
function image_edit(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token='+ImageUpload.Token+'&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: ImageUpload.Title,
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token='+ImageUpload.Token,
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_edit(\'' + field + '\', \'' + preview + '\');" />');
//						$('#' + field).replaceWith("<input type='hidden' name='"+field+"' value='"+ data +"' id = "+field+">");
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript">
    $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
    $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
    $('#date-send').datepicker({dateFormat: 'yy-mm-dd'});
</script>