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
			<a href = "<?php echo $url?>"><button   class="btn btn-primary">新增</button></a> 
			<button onclick="make_sure()" class="btn btn-danger">确定删除</button> 
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								图片
							</td>
							<td class="left">
								标题
							</td>
							<td class="left">
								团购价
							</td>
							<td class="left">
								商品数量
							</td>
							<td class="left">
								成团人数
							</td>
						    <td class="left">
								成团天数限制
							</td>
							<td class="left">
								最多发起团数
							</td>
							<td class="left">
								可发起团购时间
							</td>
							<td class="left">
								状态
							</td>
							<td class="left">
								管理
							</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($list) { ?>
							<?php foreach ($list as $li) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($li['selected']) { ?>
											<input type="checkbox" name="selected[<?php echo $li['g_id']; ?>]" value="<?php echo $li['g_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[<?php echo $li['g_id']; ?>]" value="<?php echo $li['g_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left">
										<img src="<?php echo $li['preview']; ?>"  style="width:100px;height:100px"/>
									</td>
									<td class="left"><?php echo $li['name']; ?></td>
									<td class="left"><?php echo $this->currency->format($li['sell_price']); ?></td>
									<td class="left"><?php echo $li['quantity']; ?></td>
									<td class="left"><?php echo $li['member_num']; ?></td>
									<td class="left"><?php echo $li['duration']; ?></td>
									<td class="left"><?php echo $li['group_num']; ?></td>
									<td class="left">
										<?php echo $li['start_time']; ?></br>
										<?php echo $li['end_time']; ?>
									</td>
									<td class="left"><?php echo $li['status'] == 1 ? '<span style="color:green; font-size:20px;">○</span>' : '<span style="color:red;font-size:20px;">×</span>'; ?></td>
									<td class="center">
										<a href = "<?php echo $this->url->link('sale/group/group_info_list', 'token=' . $this->session->data['token'] . "&gid={$li['g_id']}", 'SSL');?>">[建团详细]</a> | 
										<a href = "<?php echo $url.'&g_id='.$li['g_id']?>">[编辑]</a>
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="10">暂无数据!</td>
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
			if (confirm("请确认你的选择?!")) {
				$('form').submit();
			}
		}
	</script>
