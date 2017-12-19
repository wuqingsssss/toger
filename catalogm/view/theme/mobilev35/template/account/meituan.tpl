<?php echo $header; ?>
    <div id="header" class="bar bar-header bar-positive">
        <h1 class="title"><?php echo $heading_title; ?></h1>
    </div>
    <div id="content" class="content">
        <div id="login-panel" class="card">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
                <table class="form">
                    <tr>
                        <td>
                            <input type="text" name="mobile" placeholder="<?php echo $entry_mobile; ?>" value="<?php echo $mobile; ?>" maxlength="50" class="login_form_mobile" />
                            <span class="error"
                                  id="mobile-error"><?php echo(isset($error_mobile) ? $error_mobile : ''); ?></span>
                            <br/>
                            <br/>
                        </td>
                    </tr>
										<tr>
                        <td>
                            <input type="text" name="mt_code" placeholder="美团兑换码" maxlength="50" class="input-slim" />
                            <br/>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="password" name="password"  placeholder="请设置密码" value="<?php echo $password; ?>" maxlength="20"
                                   class="login_form_password" autocomplete="off"  />
														<span><?php echo $entry_password ?></span>
                            <?php if ($error_password) { ?>
                                <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?>
                            <br/>
                            <br/>
                        </td>
                    </tr>
                     <?php if($this->config->get('config_customer_lr_captcha')) {?>
     <tr>
                        <td>
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
                    <?php }?>
                    <tr>
                        <td>
                            <div class="ix-row ix-row-collapse">
                                <div class="ix-u-sm-8">
                                    <input type="text" name="mobile_vcode" placeholder="<?php echo $entry_mobile_vcode; ?>"  value="<?php echo $mobile_vcode; ?>"
                                           class="input-slim"/>
                                </div>
                                <div class="ix-u-sm-4">
                                    <button class="button button-slim button-block button-positive" type="button" id="get-vcode-btn">获取验证码</button>
                                </div>
                            </div>
                            <br/>
                                    <span class="error"
                                          id="mobile-vcode-error"><?php echo isset($error_mobile_vcode) ? $error_mobile_vcode : ''; ?></span>
                        </td>
                    </tr>
  
                    <?php if ($text_agree) { ?>
                        <tr>
                            <td>
                                <label>
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
                            <div class="left">
                                <a onclick="$('#register').submit();" class="button button-block button-positive">
                                    <?php echo $button_register; ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- #END #login-panel -->
    </div>
    <script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css"
          media="screen"/>
    <script type="text/javascript">
        $('.fancybox').fancybox({
            width: 560,
            height: 560,
            autoDimensions: true
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
                           $('#mobile-vcode-error').text("");
                       }
                       else {
                           curCount--;
                           getBtn$.text("重新发送(" + curCount +")" );
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