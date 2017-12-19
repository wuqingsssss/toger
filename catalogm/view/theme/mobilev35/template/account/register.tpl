<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->
<?php
echo $this->getChild('module/navtop', array('navtop' => array(
				'left' => '<a class="return" href="javascript:history.back()"></a>',
				'center' => '<a class="locate fz-18">' . 注册 . '</a>',
				'right' => '<a href="' . $this->url->link('account/login', '', 'SSL') . '" class="locate fz-13">登录</a>'
		), "wechathidden" => 0, "right_show" => 1));
?>

<div class="module bg-body" id="m-register">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
        <!-- 手机号 -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-new-phone" type="tel" name="mobile" data-validate="phone" placeholder="手机号"/>
            </div>
        </div>
        <?php if ($error_mobile) { ?>
        <div class="col-red fz-12 bt-10"><?php echo $error_mobile; ?></div>
        <?php } ?>
        
        <!-- 图形验证码 -->           
        <?php if($this->config->get('config_customer_lr_captcha')) {?>
        <div class="form-group" id="form-group-register-captcha">
        	<img id="sys_vcode" class="pull-right fz-15"
                 onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));"
                 title="<?php /*echo $text_refresh_mobile_vcode; */ ?>"
                 src="index.php?route=information/contact/captcha" alt=""/>
            <div class="input-group">
                <input type="tel" name="sys_vcode" placeholder="<?php echo $entry_sys_vcode; ?>"/>
            </div>
        </div>
		<?php if ($error_sys_vcode) { ?>
		<div class="col-red fz-12 bt-10"><?php echo $error_sys_vcode; ?></div>
		<?php } ?>
	   
		<?php }?>
										
		<!-- 短信验证码 -->   		
        <div class="form-group" id="form-group-register-captcha">
            <a href="javascript:" class="pull-right fz-15 col-red">获取短信验证码</a>

            <div class="input-group">
                <input id="input-captcha" type="tel" name="mobile_vcode" placeholder="验证码"/>
            </div>
        </div>
        <?php if ($error_mobile_vcode) { ?>
        		<div class="col-red fz-12 bt-10"><?php echo $error_mobile_vcode; ?></div>
        <?php } ?>

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon password"></div>
                <input id="input-new-password" type="password" name="password" data-validate="length:6:密码过短" placeholder="密码"/>
            </div>
        </div>
				<?php if ($error_password) { ?>
						<div class="col-red fz-12 bt-10"><?php echo $error_password; ?></div>
				<?php } ?>		
				<?php if ($text_agree) { ?>
        <div class="fz-12 bt-10" align="center">
				<input type="hidden" name="agree" value="1"/>
				
				注册代表同意<a href="<?php echo $agree_url?>" class="text-underline col-red">用户协议</a>
		</div>
				<?php } ?>
		<div class="form-group">
            <input type="button" value="完成注册" class="btn btn-block btn-green btn-submit"  />
        </div>
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/login.js"></script>