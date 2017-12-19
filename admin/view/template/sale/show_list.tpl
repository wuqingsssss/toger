<?php if ($warning) { ?>
	<div class="warning"><?php echo $warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
    <div class="heading">
		<h2><?php echo $heading_title; ?></h2>
		
		<div class="buttons">
			<select onchange="get_list($(this).val())">
				<option value="">请选择列表类型</option>
				<option value="0" <?php if ($this->request->get['type'] === '1') echo 'selected'; ?>>进行中</option>
				<option value="1" <?php if ($this->request->get['type'] === '-1') echo 'selected'; ?>>已过期</option>
				<option value="2" <?php if ($this->request->get['type'] === '2') echo 'selected'; ?>>未开始</option>
			</select>
			<button onclick="make_sure(-1)" class="btn btn-primary">停用</button>
			<button onclick="make_sure(1)" class="btn btn-primary">启用</button>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left" width="30%">
								优惠卷名称
							</td>
							<td class="left">
								金额
							</td>
							<td class="left">显示开始时间</td>
							<td class="left">显示结束时间</td>
							<td class="left">状态</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($list) { ?>
							<?php foreach ($list as $li) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($li['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $li['show_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $li['show_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $li['info']['name']; ?></td>
									<td class="left"><?php echo $li['info']['discount']; ?></td>
									<td class="left"><?php echo $li['start_time']; ?></td>
									<td class="left"><?php echo $li['end_time']; ?></td>
									<td class="left"><?php echo ($li['status'] > 0) ? '显示' : '不显示'; ?></td>
<!--									<td class="right">
										[ <a href="?route=sale/show_coupon/time_edit&id=<?php echo $li['show_id'] ?>">修改时间</a> ]
									</td>-->
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="7">暂无数据!</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>

	<script type="text/javascript">
		//回车事件
		$(document).keypress(function(e){
			if(e.which == 13){
				$('#but').click();
			}
		});

		function make_sure(audit) {
			if (confirm("请确认你的选择?!")) {
				var act = $('form').attr('action') + "&act=" + audit;
				$('form').attr('action', act);
				$('form').submit();
			}
		}
		function get_list(type) {
			window.location.href = window.location.href + "&type=" + type;
		}
		function get_search(){
			var str = $('#search').val();
			window.location.href = window.location.href + "&search=" + str;
		}
	</script>
