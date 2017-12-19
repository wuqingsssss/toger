  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <script type="text/javascript">
	$(function(){
		var id = $("#group").val();
		get_sub(id);
		$("#group").change(function() {
			get_sub($("#group").val());
		});

          function get_sub(parentid) {
              $.ajax({
                  url: 'index.php?route=user/user/get_sub_group&g_id=' + parentid,
                  type: 'GET',
                  dataType: 'JSON',
                  timeout: 5000,
                  error: function () {
                      alert('数据错误!');
                  },
                  success: function (msg) {
                      if (msg.code < 0) {
                          $('#sub_id').empty().hide();
                      } else {
                          $('#sub_id').empty();
                          html = "<option value=''></option>";
						  select_id = "<?php echo $user_group_id?>";
                          $.each(eval(msg.list), function (i, item) {
							  if(item.user_group_id == select_id){
								  html += "<option value='" + item.user_group_id + "' selected> " + item.name + "</option>";
							  }else{
								  html += "<option value='" + item.user_group_id + "' > " + item.name + "</option>";
							  }
//								$("<option value='" + item.list.user_group_id + "'>" + item.list.name + "</option>").appendTo($("#group"));
                          });
                          $('#sub_id').append(html).show();
                      }

                  }
              });
          }
      })
  </script>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_username; ?></td>
            <td><input type="text" name="username" value="<?php echo $username; ?>" />
              <?php if ($error_username) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
              <?php if ($error_firstname) { ?>
              <span class="error"><?php echo $error_firstname; ?></span>
              <?php } ?></td>
          </tr>
         
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_user_group; ?></td>
            <td><select name="user_group_id" id = "group" >
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $parent_id) { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
			<select name="sub_id" id="sub_id" class="hide">
			</select>
			</td>
          </tr>
          <tr>
            <td><?php echo $entry_password; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"  />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_confirm; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php if ($error_confirm) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
		  
		  <tr>
            <td>是否管理员:</td>
            <td>
				<select name="is_admin">
				<option value="1" <?php if($is_admin > 0){ echo 'selected';}?>>是</option>
                <option value="0" <?php if($is_admin <= 0){ echo 'selected';}?>>否</option>
              </select>
			</td>
          </tr>
        </table>
      </form>
    </div>
  </div>
