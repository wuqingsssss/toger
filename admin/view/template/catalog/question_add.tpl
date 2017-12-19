<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
    <div class="heading">
		<div class="buttons">
			<a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
			<a onclick="history.go(-1)" class="btn"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
		<div id="tabs" class="htabs">
			<a href="#tab-general"><?php echo $tab_general; ?></a>

		</div>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="qid" value="<?php echo $info['qa_id'] ?>">
			<div id="tab-general">
				<div id="language">
					<table class="form">
						<tr>
							<td><span class="required">*</span> 问题描述</td>
							<td><input type="text" name="ques" size="100" maxlength="255" 
									   value="<?php echo $info['description'] ?>" />
									   <?php if (isset($error_ques)) { ?>
									<span class="error"><?php echo $error_ques; ?></span>
								<?php } ?></td>
						</tr>

						<tr>
							<td>问题答案</td>
							<td>
								<textarea rows="5" class="input-xxlarge" name="answ" 
										  id="description">
											  <?php echo $info['answer'] ?>
								</textarea>
							</td>
						</tr>
						<tr>
							<td>分类</td>
							<td>
								<select name="cat" id = 'cat' onchange="change_val($(this))">
									<option value="">请选择</option>
									<?php foreach ($cat_list as $cat) { ?>
										<option value="<?php echo $cat['qa_catagory_id'] ?>"><?php echo $cat['catagory_name'] ?></option>
									<?php } ?>
								</select> 
								<?php if (isset($error_cat)) { ?>
									<span class="error"><?php echo $error_cat; ?></span>
								<?php } ?>
							</td>
						<input type="hidden" name="pid" id = 'pid' value="<?php echo $info['catagory_id'] ?>">
						</tr>
					</table>
				</div>
			</div>

		</form>
    </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
			CKEDITOR.replace('description', {
        filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
    });
//--></script> 
<script type="text/javascript"><!--
    $('#tabs a').tabs();
    $('#languages a').tabs();
    var token = "<?php echo $this->request->get['token'] ?>";
    function change_val(this_obj) {
        remove_nodes = this_obj.nextAll();
        remove_nodes.remove();
        var cid = this_obj.val();
        var obj = $('#pid').prev();
        $('#pid').val(cid);
//		alert(cid);
        $.ajax({
            url: '/admin/index.php?route=catalog/question/get_cat_list&token=' + token + '&cid=' + cid, // 跳转到 action  
            type: 'get',
            dataType: 'json',
            success: function (data) {
                if (data.code == "1") {//有子集
                    var str = "&nbsp;&nbsp;<select name='cat" + cid + "' id = 'cat" + cid + "' onchange='change_val($(this))'>\n\
								<option value=''>请选择</option>";
                    $.each(data.data, function (n, item) {
                        str = str + "<option value='" + item['qa_catagory_id'] + "'>" + item['catagory_name'] + "</option>";
                    });

                    str = str + "</select>";
//					alert(str);
                    obj.append(str);
                }
            },
            error: function () {
                alert("请求错误");
            }
        });
    }
//--></script> 