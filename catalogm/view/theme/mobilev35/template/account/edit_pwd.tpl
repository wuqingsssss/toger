<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->

<?php
echo $this->getChild('module/navtop', array('navtop' => array(
				'left' => '<a class="return" href="javascript:history.back()"></a>',
				'center' => '<a class="locate fz-18">' . 修改密码 . '</a>',
				'right' => '<a href="' . $this->url->link('account/register', '', 'SSL') . '" class="locate fz-13">注册</a>'
		), "wechathidden" => 0));
?>
<div class="module bg-body" id="m-login">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
		<!-- 短信验证码 -->   		
        <div class="form-group" id="form-group-register-captcha">
            <a href="javascript:" class="pull-right fz-15 col-red">获取短信验证码</a>

            <div class="input-group">
                <input id="input-captcha" type="tel" name="code" placeholder="验证码"/>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon password"></div>
                <input id="input-new-password" type="password" name="password" data-validate="length:6:密码过短" placeholder="密码"/>
            </div>
        </div>
        

		<?php if ($error) { ?>
			<div class="col-red fz-12 bt-10"><?php echo $error; ?></div>
		<?php } ?>

        <div class="form-group">
            <input type="button" value="修改" class="btn btn-block btn-green btn-submit" onclick="$('#login').submit();"/>
        </div>
		
		
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar'); ?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/edit_pwd.js"></script>
