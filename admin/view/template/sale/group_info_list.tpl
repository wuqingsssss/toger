<?php if ($error_warning) { ?>
	<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
		<h2><?php echo $heading_title; ?>---<?php echo $g_info['name']?></h2>
    </div>
    <div class="content">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td>序号</td>
						<td>团ID</td>
						<td>创建时间</td>
						<td>结束时间</td>
						<td>成团时间</td>
						<td>创建用户ID</td>
						<td>团购状态</td>
						<td>操作</td>
					</tr>
				</thead>
				<tbody>
					<tr class="filter">
						<td></td>
						<td></td>
						<td><input type="text" name="create_time" 
								   value="<?php echo $this->request->get['create_time']?>" size="12" class="date"></td>
						<td><input type="text" name="end_time" 
								   value="<?php echo $this->request->get['end_time']?>" size="12" class="date"/>
						</td>
						<td><input type="text" name="finish_time" 
								   value="<?php echo $this->request->get['finish_time']?>" size="12" class="date"/>
						</td>
						<td></td>
						<td>
							<select name="status">
								<option value="0"></option>
							<?php foreach($status_arr as $k => $status){?>
								<option value="<?php echo $k?>" <?php if($k == $this->request->get['status']){echo 'selected';}?>><?php echo $status?></option>
							<?php }?>
							</select>
						</td>
						<td align="right"><a onclick="filter();"
                                         class="btn btn-success"><span><?php echo $button_filter; ?></span></a></td>
					</tr>
					<?php if ($c_list) { ?>
						<?php foreach ($c_list as $k => $c_info) { ?>
							<tr>
								<td><?php echo $k + 1 ?></td>
								<td><?php echo $c_info['c_id'] ?></td>
								<td><?php echo $c_info['create_time'] ?></td>
								<td><?php echo $c_info['end_time'] ?></td>
								<td><?php echo $c_info['finish_time'] ?></td>
								<td><?php echo $c_info['customer_id'] ?></td>
								<td>
									<?php
									switch ($c_info['status']) {
										case 1:
											echo '进行中';
											break;
										case 2:
											echo '已成团';
											break;
										case -1:
											echo '未成团 已取消';
											break;
										case -2:
											echo '未付款';
											break;
										default :
											echo '未知';
									}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td colspan="8">
									<table width="80%" align="right" >
										<?php foreach($members[$c_info['c_id']] as $mem){?>
										<tr>
											<td style="color:gray" align="center">
												<a href="<?php echo $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . "&order_id={$mem['order_id']}", 'SSL');?>">
												订单ID: <?php echo $mem['order_id']?>
											</td>
											<td style="color:gray" align="center">用户ID: <?php echo $mem['customer_id']?></td>
											<td style="color:gray" align="center">用户角色: <?php echo ($mem['type']==1)?'创建者':'参与者'?></td>
											<td style="color:gray" align="center">参与时间: <?php echo $mem['join_time']?></td>
<!--											<td style="color:gray" align="center">
												订单状态: <?php 
												switch ($mem['stauts']) {
																case 0:
																	echo '未处理';
																	break;
																case 1:
																	echo '已确认';
																	break;
																case 9:
																	echo '已取消';
																	break;
																default :
																	echo '未知';
															}?>
											</td>-->
										</tr>
										<?php } ?>
									</table>
								</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		});
	
		function filter() {
			url = "<?php echo $url?>";
			var create_time = $('input[name=\'create_time\']').attr('value');
			if (create_time) {
				url += '&create_time=' + encodeURIComponent(create_time);
			}

			var end_time = $('input[name=\'end_time\']').attr('value');
			if (end_time) {
				url += '&end_time=' + encodeURIComponent(end_time);
			}
			
			var finish_time = $('input[name=\'finish_time\']').attr('value');
			if (finish_time) {
				url += '&finish_time=' + encodeURIComponent(finish_time);
			}
			
			var status = $("select[name='status']").val();
			if (status) {
				url += '&status=' + encodeURIComponent(status);
			}
			
			 location = url;
		}
	</script>