<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/login.css" rel="stylesheet"/>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/us.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<div id="header">
    <div class="pull-left">
        <a class="return" href="<?php echo $this->url->link('account/login'); ?>">"></a>
    </div>
    <div class="pull-right">
        <a class="setting" href="javascript:"></a>
    </div>
    <div class="text-center">
        <a class="fz-18" href="javascript:">忘记密码</a>
    </div>
</div>
<div class="module bg-body" id="m-register">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-new-phone" type="tel" name="mobile" data-validate="phone" placeholder="注册手机号"/>
            </div>
        </div>
		<?php if ($error_mobile) { ?>
		<div class="col-red fz-12 bt-10"><?php echo $error_mobile; ?></div>
		<?php } ?>
		
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
				
        <div class="form-group" id="form-group-register-captcha">
            <a href="javascript:" class="pull-right fz-15 col-red">获取验证码</a>
            <div class="input-group">
                <input id="input-captcha" type="tel" name="mobile_vcode" placeholder="<?php echo $entry_mobile_vcode; ?>"/>
            </div>
        </div>
		<?php if ($error_mobile_vcode) { ?>
				<div class="col-red fz-12 bt-10"><?php echo $error_mobile_vcode; ?></div>
		<?php } ?>
	
						
				<div class="form-group">
            <input type="button" value="找回密码" class="btn btn-block btn-green btn-submit"  />
        </div>
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/forgotten.js"></script>