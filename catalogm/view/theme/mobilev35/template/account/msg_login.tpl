<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->

<?php
echo $this->getChild('module/navtop', array('navtop' => array(
				'left' => '<a class="return" href="javascript:history.back()"></a>',
				'center' => '<a class="locate fz-18">' . 短信验证码登录 . '</a>',
				'right' => '<a href="' . $this->url->link('account/register', '', 'SSL') . '" class="locate fz-13">注册</a>'
		), "wechathidden" => 0, "right_show" => 1));
?>
<div class="module bg-body" id="m-login">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-phone" type="tel" name="mobile" data-validate="phone" placeholder="手机号" value="<?php echo $mobile; ?>"/>
            </div>
        </div>
        <!-- 短信验证码 -->   		
        <div class="form-group" id="form-group-register-captcha">
            <a href="javascript:" class="pull-right fz-15 col-red">获取短信验证码</a>

            <div class="input-group">
                <input id="input-captcha" type="tel" name="code" placeholder="验证码"/>
            </div>
        </div>

		<!--记住帐号-->
        <div class="col-red fz-12 bt-10">
			<span style="color:gray">未注册过的手机将自动创建青年菜君账户</span>
		</div>
		<?php if ($error) { ?>
			<div class="col-red fz-12 bt-10"><?php echo $error; ?></div>
		<?php } ?>

        <div class="form-group">
            <input type="button" value="验证并登录" class="btn btn-block btn-green btn-submit" onclick="$('#login').submit();"/>
        </div>
		
		
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar'); ?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/msg_login.js"></script>
