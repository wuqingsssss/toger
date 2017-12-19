<?php echo $header; ?>
<style>
    .error{
        display: block !important;
    }
</style><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div id="forgotten" class="content">
        <div class="pass_title">
            <h1><?php echo $heading_title; ?></h1>
        </div>

        <div class="pass_left lt">
            <?php echo $this->getChild('account/forgotten/step', 1); ?>


            <p class="pass_tsyy"><?php echo $text_mobile; ?></p>

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
                          <tr>
                                <td>
                                    <span class="required">*</span><?php echo $entry_sys_vcode; ?> <br/>
                                   <img id="sys_vcode" class="mobile_vcode"
                                         onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));"
                                         title="<?php /*echo $text_refresh_mobile_vcode; */ ?>"
                                         src="index.php?route=information/contact/captcha" alt=""/> <input type="text" name="sys_vcode" value=""
                                           class="input-slim"/>     
                                    <br/>
                                    <span class="error"
                                          id="sys-vcode-error"><?php echo isset($error_sys_vcode) ? $error_sys_vcode : ''; ?></span>
                                </td>
                            </tr> <?php } ?>
                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_captcha; ?><br/>
                                <button id="get-vcode-btn" type="button" class="btn btn-default">获取验证码</button>
                                <input type="text" name="mobile_vcode" value="" class="input-slim"/>
                                <span class="error" id="mobile-vcode-error"><?php echo isset($error_mobile_vcode)?$error_mobile_vcode:''; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" class="button" value="<?php echo $button_forgot_password; ?>"/>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php } ?>
        </div>
        <?php echo $this->getChild('account/forgotten/service'); ?>
    </div>
    <?php echo $content_bottom; ?>
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
                getBtn$.attr("disabled", "true");
                curCount = 60;
                InterValObj= window.setInterval( function(){
                    /**
                    /* 重发短信倒计时
                    */
              	   if (curCount == 0) {                
                       window.clearInterval(InterValObj);//停止计时器
                       getBtn$.removeAttr("disabled");//启用按钮
                       getBtn$.text("获取验证码");
                       $('.error').html('');
                   }
                   else {
                       curCount--;
                       getBtn$.text("重新发送(" + curCount +"秒)" );
                   }
                }, 1000); //启动计时器，1秒执行一次
                
                if (!result.success) {
                	curCount=1;
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