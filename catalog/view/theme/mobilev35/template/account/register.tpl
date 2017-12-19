<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->

<div class="module" id="m-tab">
    <a href="<?php echo $this->url->link('account/login', '', 'SSL');?>" class="login"><span>登录</span></a>
    <a href="javascript:" class="register col-red"><span>注册</span></a>
</div>

<div class="module bg-body" id="m-register">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-new-phone" type="text" name="mobile" data-validate="phone" placeholder="手机号"/>
            </div>
        </div>
				<?php if ($error_mobile) { ?>
						<div class="col-red fz-12 bt-10"><?php echo $error_mobile; ?></div>
				<?php } ?>
        <div class="form-group" id="form-group-register-captcha">
            <a href="javascript:" class="pull-right fz-15 col-red">获取短信验证码</a>

            <div class="input-group">
                <input id="input-captcha" type="text" name="mobile_vcode" placeholder="验证码"/>
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
				<div class="form-group">
            <div class="input-group">
                <div class="input-group-addon password"></div>
                <input id="input-new-password" type="password" name="confirm" data-validate="length:6:确认密码过短" placeholder="确认密码"/>
            </div>
        </div>
				<?php if ($error_confirm) { ?>
						<div class="col-red fz-12 bt-10"><?php echo $error_confirm; ?></div>
				<?php } ?>
				<?php if ($text_agree) { ?>
        <div class="fz-12 bt-10">
						<input type="hidden" name="agree" value=""/>
						<i class="icon icon-accept gray" onclick="agree_value()"></i>
						
						&nbsp;&nbsp;我已阅读并同意<a href="<?php echo $agree_url?>" class="text-underline col-red">用户协议</a>
				</div>
				<?php } ?>
				<div class="form-group">
            <input type="button" value="注册" class="btn btn-block btn-red btn-submit"  />
        </div>
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/login.js"></script>