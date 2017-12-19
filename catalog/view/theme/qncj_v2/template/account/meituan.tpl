<?php
//require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect();
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content">
		<?php if ($warning) { ?>
		<div class="warning"><?php echo $warning; ?></div>
		<?php } ?>
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?>
                <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <div class="login-content">
            <?php if ($detect->isTablet() || !$detect->isMobile()) { ?>
                <div id="login-ad" class="fl">
                    <?php echo $content_top; ?>
                </div>
            <?php } ?>
            <div id="login-panel" class="fr">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
                    <div class="mt">
                        <?php echo $heading_title; ?>
                        <span><?php echo $text_account_already; ?></span>
                    </div>

                    <div class="content">
                        <p class="title" style="display:none;"><?php echo $text_account_already; ?></p>
                        <table class="form">
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_mobile; ?><br/>
                                    <input type="mobile" name="mobile" value="<?php echo $mobile; ?>" maxlength="50"/>
                                    <span class="error"
                                          id="mobile-error"><?php echo(isset($error_mobile) ? $error_mobile : ''); ?></span>
                            </tr>
														<tr>
                                <td><span class="required">*</span>美团兑换码<br/>
                                    <input type="text" name="mt_code"  maxlength="50"/>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> 请设置密码（已有帐号用户，请输入密码）<br/>
                                    <input type="password" name="password" value="<?php echo $password; ?>"
                                           maxlength="20" autocomplete="off"/>
                                    <?php if ($error_password) { ?>
                                        <span class="error"><?php echo $error_password; ?></span>
                                    <?php } ?></td>
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
                            </tr>
                            <?php }?>
                            <tr>
                                <td>
                                    <span class="required">*</span> <?php echo $entry_mobile_vcode; ?><br/>
                                    <button class="btn btn-default" type="button" id="get-vcode-btn">获取验证码</button>
                                    &nbsp;
                                    <input type="text" name="mobile_vcode" value="<?php echo $mobile_vcode; ?>"
                                           class="input-slim"/>
                                    <br/>
                                    <span class="error"
                                          id="mobile-vcode-error"><?php echo isset($error_mobile_vcode) ? $error_mobile_vcode : ''; ?></span>
                                </td>
                            </tr>
                            
                            <?php if ($text_agree) { ?>
                                <tr>
                                    <td>

                                            <?php if ($agree) { ?>
                                                <input type="checkbox" name="agree" value="1" checked="checked"/>
                                            <?php } else { ?>
                                                <input type="checkbox" name="agree" value="1"/>
                                            <?php } ?>
                                            <?php echo $text_agree; ?>

                                            <?php if ($error_agree) { ?>
                                                <span class="error"><?php echo $error_agree; ?></span>
                                            <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>

                                        <input type="checkbox" name="newsletter"
                                               value="1" <?php if ($newsletter) { ?> checked="checked" <?php } ?> />

                                        <?php echo $text_newsletter; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="left"><a onclick="$('#register').submit();" class="button highlight">
                                            <span><?php echo $button_register; ?></span></a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <input type="hidden" name="invite_code" value="<?php echo $invitecode; ?>"/>
                </form>
            </div>
        </div>
        <?php echo $content_bottom; ?>
    </div>

    <script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css"
          media="screen"/>
    <script type="text/javascript">
        $('.fancybox').fancybox({
            width: 560,
            height: 560,
            autoDimensions: false
        });

        $(document).ready(function () {
            var getBtn$ = $('#get-vcode-btn');
            getBtn$.click(function () { 
                var mobile = $('[name="mobile"]').val();
                if (!mobile) {
                    $('#mobile-error').text('请填手机号');
                    return;
                }
                var url = 'index.php?route=account/meituan/validate_mobile&mobile=' + mobile;
                <?php if($this->config->get('config_customer_lr_captcha')) {?>
                var sysvcode = $('[name="sys_vcode"]').val();
                if (!sysvcode) {
                    $('#sys-vcode-error').text('<?php echo $entry_sys_vcode; ?>');
                    return;
                }
                url+='&sysvcode=' + sysvcode;
                <?php }?>
                getBtn$.attr('disabled', 'disabled');
                $.getJSON(url, function (result) {
                    //console.log(result);
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
                           $('#mobile-vcode-error').text("");
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
                    	$("#sys_vcode").click();
                    	 <?php }?>
                        
                    } else {
                        $('#mobile-vcode-error').html('<span style="color:#0000ff">请查看您的手机获取验证码</span>');
                    }
                })
            });
        });

  
    </script>
<?php echo $footer; ?>