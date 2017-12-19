<?php echo $header; ?>
<style>
    .error{
        display: block !important;
    }
</style>
<div id="header" class="bar bar-header bar-positive">
    <h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
    <div id="login-panel" class="card">
        <?php if (isset($success) && $success) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } else { ?>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_mobile; ?><br/>
                            <input type="text" name="mobile" value="<?php echo $mobile; ?>" class="span4"/>
                            <span class="error " id="mobile-error"><?php echo isset($error_mobile)?$error_mobile:''; ?></span>
                        </td>
                    </tr>  
                    <?php if($this->config->get('config_customer_lr_captcha')) {?>
                             <tr>               <td>
                            <div class="ix-row ix-row-collapse">
                                <div class="ix-u-sm-8">
                                    <input type="text" name="sys_vcode" value="" class="input-slim" placeholder="<?php echo $entry_sys_vcode; ?>"/>     
                                </div>
                                <div class="ix-u-sm-4">
                                   <img id="sys_vcode" class="mobile_vcode"
                                         onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));"
                                         title="<?php /*echo $text_refresh_mobile_vcode; */ ?>"
                                         src="index.php?route=information/contact/captcha" alt=""/>
                                </div>
                            </div>
                            <br/>
                                    <span class="error"
                                          id="sys-vcode-error"><?php echo isset($error_sys_vcode) ? $error_sys_vcode : ''; ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_captcha; ?><br/>

                            <div class="ix-row ix-row-collapse">
                                <div class="ix-u-sm-8">
                                    <input type="text" name="mobile_vcode" value=""  placeholder="<?php echo $entry_mobile_vcode; ?>" 
                                           class="input-slim"/>
                                </div>

                                <div class="ix-u-sm-4">
                                    <button class="button button-slim button-block button-positive" type="button" id="get-vcode-btn">获取验证码</button>
                                </div>
                            </div>

                            <span class="error" id="mobile-vcode-error"><?php echo isset($error_mobile_vcode)?$error_mobile_vcode:''; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" class="button button-block button-positive"
                                   value="<?php echo $button_forgot_password; ?>"/></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
    </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
    $(document).ready(function () {
    	var curCount = 0;
        var getBtn$ = $('#get-vcode-btn');
        getBtn$.click(function () {
            var mobile = $('[name="mobile"]').val();
            if (!mobile) {
            	 $('#mobile-error').text('请填写手机号码');
                return;
            } else {
                $('#mobile-error').text('');
            }
            var url = 'index.php?route=account/forgotten/validate_mobile&mobile=' + mobile;
            <?php if($this->config->get('config_customer_lr_captcha')) {?>
            var sysvcode = $('[name="sys_vcode"]').val();
            if (!sysvcode) {
                $('#sys-vcode-error').text('<?php echo $entry_sys_vcode; ?>');
                return;
            }
            url+='&sysvcode=' + sysvcode;
            <?php } ?>
          
            getBtn$.attr('disabled', 'disabled');
            $.getJSON(url, function (result) {
                console.log(result);
                getBtn$.removeAttr('disabled');
                if (!result.success) {
                	 $('.error').html('');
                 	for(id in result.msg)
                         $('#'+id).text(result.msg[id]);
                 	<?php if($this->config->get('config_customer_lr_captcha')) {?>
                	if(result.msg['sys-vcode-error'])
                	        $("#sys_vcode").click();
                	 <?php }?>
                } else {
                    $('#mobile-vcode-error').html('<span style="color:#0000ff">请查看您的手机获取验证码</span>');
                }
            })
        });
    });
</script>