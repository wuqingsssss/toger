<?php if ($error_warning) { ?>
	<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
				<h2><?php echo $heading_title; ?></h2>
				<div class="buttons">
						<button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button>
						<button onclick="window.history.back(-1);" class="btn btn-default"><?php echo $button_cancel; ?></button>
				</div>
    </div>
		<div class="content">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
						<input type="hidden" name="id" value="<?php echo $info['id']?>">
						<table class="form">
								<tr>
										<td><span class="required">*</span> 平台名称:</td>
										<td><input type="text" name="name" value="<?php echo $info['name']; ?>" size="50" />
										<?php if ($error_name) { ?>
											<span class="error"><?php echo $error_name; ?></span>
										<?php } ?></td>
								</tr>
								<tr>
										<td><span class="required">*</span> 代号</td>
										<td>
												<?php if(isset($this->request->get['partner_id'])){?>
												<?php echo $info['code']; ?>
												<?php }else{?>
												<input type="text" name="code" value="<?php echo $info['code']; ?>" size="50" />
												<?php } ?>
												<?php if ($error_code) { ?>
													<span class="error"><?php echo $error_code; ?></span>
												<?php } ?>
										</td>
								</tr>
								<tr>
										<td>key值:</td>
										<td><input type="text" name="key" value="<?php echo $info['key']; ?>" size="50" />
								</tr>

								<tr>
										<td>point_code值:</td>
										<td><input type="text" name="point_code" value="<?php echo $info['point_code']; ?>" size="50" />
										</td>
								</tr>
								<tr>
										<td>状态:</td>
										<td>
												<select name="status">
														<option value="1" <?php if(!isset($info['status']) || $info['status'] == 1){echo 'selected' ;}?>>启用</option>
													<option value="0" <?php if(isset($info['status']) && $info['status'] === '0'){echo 'selected' ;}?>>停用</option>
												</select>
										</td>
								</tr>
								<tr>
										<td><?php echo $entry_sort_order; ?></td>
										<td><input type="text" name="sort_order" value="<?php echo ($info['sort_order'] == 0) ? 100 : $info['sort_order']; ?>" size="1" /></td>
								</tr>
						</table>
				</form>
		</div>
</div>
