<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->

<div class="module" id="m-tab">
    <a href="javascript:" class="login col-red"><span>登录</span></a>
    <a href="<?php echo $this->url->link('account/register', '', 'SSL');?>" class="register"><span>注册</span></a>
</div>

<div class="module bg-body" id="m-login">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-phone" type="text" name="email" data-validate="phone" placeholder="手机号" value="<?php echo $email; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon password"></div>
                <input id="input-password" type="password" name="password" placeholder="密码"/>
            </div>
        </div>
        <div class="col-red fz-12 bt-10"><a href="">忘记密码?</a></div>
				<?php if ($error_warning) { ?>
						<div class="col-red fz-12 bt-10"><?php echo $error_warning; ?></div>
				<?php } ?>
				
        <div class="form-group">
            <input type="button" value="登录" class="btn btn-block btn-red btn-submit" onclick="$('#login').submit();"/>
        </div>
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/login.js"></script>