<?php if ($warning) { ?>
	<div class="warning"><?php echo $warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
    <div class="heading">
		<h2><?php echo $heading_title; ?></h2>
		<div align="center">
			关键字搜索:<input type="text" id="search"  name="search" size="200" maxlength="255" 
						 style="width:400px;height: 24px" value="<?php echo $this->request->get['search']?>"/>
			<button class="btn btn-primary" onclick="get_search()" id="but">确定</button>
		</div>
		<div class="buttons">
			<button onclick="make_sure()" class="btn btn-primary">确定提交</button> 
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								优惠卷名称
							</td>
							<td class="left">
								金额
							</td>
							<td class="left">
								开始时间
							</td>
							<td>结束时间</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($list) { ?>
							<?php foreach ($list as $li) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($li['selected']) { ?>
											<input type="checkbox" name="selected[<?php echo $li['coupon_id']; ?>]" value="<?php echo $li['coupon_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[<?php echo $li['coupon_id']; ?>]" value="<?php echo $li['coupon_id']; ?>" />
										<?php } ?>
									</td>
					<input type="hidden" name="code[<?php echo $li['coupon_id']; ?>]" value="<?php echo $li['code']; ?>">
					<input type="hidden" name="start_time[<?php echo $li['coupon_id']; ?>]" value="<?php echo $li['date_start']; ?>">
					<input type="hidden" name="end_time[<?php echo $li['coupon_id']; ?>]" value="<?php echo $li['date_end']; ?>">
									<td class="left"><?php echo $li['name']; ?></td>
									<td class="left"><?php echo $li['discount']; ?></td>
									<td class="left"><?php echo $li['date_start']; ?></td>
									<td class="left"><?php echo $li['date_end']; ?></td>
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

		function make_sure() {
			if (confirm("请确认你的选择?!")) {
//				var act = $('form').attr('action') + "&act=" + audit;
//				$('form').attr('action', act);
				$('form').submit();
			}
		}
		function get_search(){
			var str = $('#search').val();
			window.location.href = window.location.href + "&search=" + str;
		}
	</script>
