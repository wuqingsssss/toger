<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/login.css" rel="stylesheet"/>
<!-- 公共头结束 -->

<?php
echo $this->getChild('module/navtop', array('navtop' => array(
				'left' => '<a class="return" href="javascript:history.back()"></a>',
				'center' => '<a class="locate fz-18">' . 登录 . '</a>',
				'right' => '<a href="' . $this->url->link('account/register', '', 'SSL') . '" class="locate fz-13">注册</a>'
		), "wechathidden" => 0, "right_show" => 1));
?>

<?php if(isset($header_type)&&$header_type=='weixin'){ ?>
    <a class="pull-right locate fz-13 col-red" href="<?php echo $this->url->link('account/register', '', 'SSL'); ?>" style="margin:10px 20px 0px 0px;">注册</a>
<?php }?>
<div class="module bg-body" id="m-login">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon user"></div>
                <input id="input-phone" type="tel" name="email" data-validate="phone" placeholder="手机号" value="<?php echo $email; ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon password"></div>
                <input id="input-password" type="password" name="password" placeholder="密码"/>
            </div>
        </div>
				
				
        <div class="form-group">
            <input type="button" value="登录" class="btn btn-block btn-green btn-submit" onclick="$('#login').submit();"/>
        </div>
				<!--记住帐号-->
				
        <div class="col-red fz-12 bt-10">
			<a href="<?php echo $forgotten; ?>" class="fr login_form_forgets"><?php echo $text_forgotten; ?></a>
			<a href="<?php echo $this->url->link('account/login/msg_login', '', 'SSL')?>" class="pull-right" >短信验证码登录</a>
		</div>
				<?php if ($error_warning) { ?>
						<div class="col-red fz-12 bt-10"><?php echo $error_warning; ?></div>
				<?php } ?>
    </form>
</div>

<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/login.js"></script>