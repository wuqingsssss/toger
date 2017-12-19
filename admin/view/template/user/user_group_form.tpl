<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if($show_right){?>
<div class="box i-user-group">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons"><a onclick="$('#form').submit();"
                                class="btn btn-primary"><span><?php echo $button_save; ?></span></a> 
			<a href="javascript:history.go(-1);"
                class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td>
						<?php if($super){?>
						<select name="parent_group_id" id='group'>
							<option value="">--无--</option>
							<?php foreach ($user_groups as $user_group) { ?>
							<option <?php if($edit_info['parent_group_id'] == $user_group['user_group_id']){echo 'selected';}?>
								value="<?php echo $user_group['user_group_id']?>"><?php echo $user_group['name']?>
							<?php  }?>
						</select>
						<input type="hidden" name="gid" value="<?php echo $this->request->get['user_group_id']?>">
						<input type="text" name="name" value="<?php echo $name?>">
						<?php }else{?>
							<?php echo $group_info['name'];?>--<input type="text" name="name" value="<?php echo $name?>">
							<input type="hidden" name="parent_group_id" value="<?php echo $pid;?>">
						<?php }?>
						<?php if ($error_name) { ?>
						<span class="error"><?php echo $error_name; ?></span>
                        <?php } ?>
					</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;padding-top: 15px;">权限设置</td>
                    <td>
						<?php foreach($show_right as $k => $a){ ?>
						<fieldset class="span6">
								<legend><?php echo $k;?></legend>
								<div class="scrollbox" id="scrollbox_0">
									<?php foreach($a as $route=> $info){ ?>
									<div class="even">
							
											<input type="checkbox" id="permission<?php echo str_replace('/','',$route);?>" onclick='$("input[name*=\"permission[<?php echo $route;?>]\"").attr("checked",this.checked);' value="<?php echo $route;?>"><?php echo $info['title'];?>

											<?php if (!empty($info['action'])) { ?>
											<?php foreach($info['action'] as $v){?>

											<input type="checkbox" onclick='$("#permission<?php echo str_replace('/','',$route);?>").attr("checked",$("input[name*=\"permission[<?php echo $route;?>]\"]:checked").length>0);' name="permission[<?php echo $route;?>][<?php echo $v;?>]" <?php echo(isset($userPerms[$route][$v])?' checked':'');?> value="1" ><?php echo (isset($entry_action[$v])?$entry_action[$v]:$v);?>

											<?php }}?>
										
									</div>
									<?php } ?>
								</div>
								
							</fieldset>
						<?php } ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php } ?>