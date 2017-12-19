<style>
	.hide{display:none;}
	.leftbox{float:left;border: 1px solid #ccc; width: 500px; height: 800px; overflow-y: auto;}
	.right{float:left;border: 1px solid #ccc; width: 500px;  height: 800px; overflow-y: auto; }
	.btn a{cursor: pointer;}
	.leftbox span,.right span{ display: block; padding:  0px 6px; height: 40px; line-height: 40px;}
	.btn{ float: left; margin: 100px 20px 0;}
	.btn a{display: block; margin-top: 20px;}
	.leftbox span input.text{ width:100px;}
</style> 
<?php if ($warning) { ?>
	<div class="warning"><?php echo $warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>

<div class="box">
    <div class="heading">
		<div class="buttons">
			<button onclick="$('#form').submit()"  class="btn btn-primary">保存</button>
		</div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<div class="vtabs">
					<?php $module_row = 0; ?>
					<?php foreach ($left_menu as $name => $menu) { ?>
						
						<a href="#left-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>" tag_num="<?php echo $module_row; ?>" onclick="get_num(<?php echo $module_row; ?>)">
						<input type="text" id = "tag_name-<?php echo $module_row; ?>" style="width:80%;" name="tag_name[<?php echo $module_row; ?>]" value="<?php echo $name ?>">
							<img src="view/image/delete.png" style="top: 50%;margin-top: -8px;" alt="" 
								 onclick="$('.vtabs a:first').trigger('click');
	                                     $('#module-<?php echo $module_row; ?>').remove();
										 $('#tag_name-<?php echo $module_row; ?>').remove();
	                                     $('#tab-module-<?php echo $module_row; ?>').remove();
	                                     return false;">
						</a>
						<?php $module_row++; ?>
					<?php } ?>

					<span id="module-add"><?php echo $button_add_module; ?>&nbsp;
						<img src="view/image/add.png" alt="" onclick="addModule();" />
					</span> 
				</div>
				<div id="left_add">
				<?php
				$i = 0;
				foreach ($left_menu as $name => $menu) {
					?>
					<div id="left-<?php echo $i; ?>" class="leftbox"> 
						<?php foreach ($menu as $k => $m) { ?>
							<span>
								<input  type="checkbox" name="left[<?php echo $i; ?>][<?php echo $k; ?>]" value="<?php echo $k; ?>"><?php echo $k; ?>
								<input class="text" type="text" name="title[<?php echo $i; ?>][<?php echo $k; ?>]" value="<?php echo $m['title']; ?>">
								<input class="text" type="text" name="action[<?php echo $i; ?>][<?php echo $k; ?>]" value="<?php echo $m['action']; ?>">
								<i style="float:right;"><input type="checkbox" name="menu[<?php echo $i; ?>][<?php echo $k; ?>]" value="1" <?php if($m['show_menu']){echo 'checked';}?>>菜单</i>
							</span>
						<?php } ?>
					</div> 
					
					
					<?php
					$i++;
				}
				?>
				</div>
				<a onclick="r_to_l();">
					<div class="btn">
						《《====向左
					</div>
				</a>
				<a onclick="l_to_r();">
					<div class="btn">
						向右====》》
					</div> 
				</a>
			</form>
			<div id="right-box" class="right"> 
				<?php foreach ($menu_list as $menu) { ?>
					<span><input type="checkbox" name="right[]" value="<?php echo $menu ?>" /><?php echo $menu ?></span> 
				<?php } ?>
			</div> 
		</div>
	</div>
<script type="text/javascript">
   
    function r_to_l() {
    	var i=parseInt($('.vtabs a.selected').attr('tag_num'));
        $('input[name="right[]"]:checked').each(function () {
            var item = '<span><input type="checkbox" name="left[' + i + ']['+$(this).val()+']" value="' + $(this).val() + '" >' + $(this).val() + '<input type="text" class="text" name="title[' + i + ']['+$(this).val()+']" value="' + $(this).val() + '"/><input type="text" class="text" name="action[' + i + ']['+$(this).val()+']" value=<?php echo $default_action;?> /><i style="float:right;"><input type="checkbox" name="menu[' + i + ']['+$(this).val()+']" value="1" checked />菜单</i></span>';
            $("#left-" + i).append(item);
            $(this).parent().remove();
        });
    }
    function l_to_r() {
    	var i=parseInt($('.vtabs a.selected').attr('tag_num'));
    	 console.log(i, $('input[name*="left[' + i + ']"]:checked'));
    	
        $('input[name*="left[' + i + ']"]:checked').each(function () {
            var item = '<span><input type="checkbox" name="right[]" value="' + $(this).val() + '">' + $(this).val() + '</span>';
            $("#right-box").append(item);
            $(this).parent().remove();
        });
    }

</script>
<script type="text/javascript">
    $(function () {
        $('.vtabs a').tabs();
    });
 
    function get_num(n) {
        num = n;
    }
    var module_row = <?php echo $module_row ; ?>;
    function addModule() {
        html = '<div id="left-' + module_row + '" class="leftbox"></div> ';
//        $('#left-' + (module_row - 1)).after(html);
		$('#left_add').append(html);
        $('#module-add').before('<a href="#left-' + module_row + '" id="module-' + module_row + '" tag_num="' + module_row + '" onclick="get_num(' + module_row + ')">\n\
			<input type="text" name="tag_name['+module_row+']" style="width:80%;" value = "<?php echo $tab_module; ?>' + module_row + '">\n\
			<img src="view/image/delete.png" style="top: 50%;margin-top: -8px;" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#left-' + module_row + '\').remove(); return false;" /></a>');

        $('.vtabs a').tabs();

        $('#module-' + module_row).trigger('click');

        module_row++;
    }
</script> 