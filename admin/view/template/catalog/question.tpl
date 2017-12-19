<div class="box">
    <div class="heading">
		<h2><?php echo $heading_title; ?></h2>
		<div class="buttons"><button onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary">新增</button> 
			<button onclick="make_sure()" class="btn btn-danger">删除</button></div>
    </div>
    <div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left">
							问题
						</td>
						<td class="left">
							答案
						</td>
						<td>分类</td>
						<td class="right" width="100">
							编辑
						</td>
					</tr>
				</thead>
				<tbody>
					<?php if ($list) { ?>
						<?php foreach ($list as $li) { ?>
							<tr>
								<td style="text-align: center;"><?php if ($li['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $li['qa_id']; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $li['qa_id']; ?>" />
									<?php } ?>
								</td>

								<td class="left"><?php echo $li['description']; ?></td>
								<td class="left"><?php echo strip_tags(htmlspecialchars_decode($li['answer'])); ?></td>
								<td class="left"><?php echo $cat_data[$li['catagory_id']]; ?></td>

								<td class="right">
									[ <a href="?route=catalog/question/add_edit&id=<?php echo $li['qa_id'] ?>">编辑</a> ]
								</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="center" colspan="5">暂无数据!</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>

<script type="text/javascript">
    function make_sure() {
        if (confirm("确认删除么？\r\n删除后将无法回复!")) {
            $('form').submit();
        }
    }
</script>
