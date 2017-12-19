<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/metro.css" rel="stylesheet"/>
<!-- 公共头结束 -->
 <img class="bg" src="<?php echo HTTP_CATALOG.$tplpath;?>/images/campaign/bg_metro.jpg" />
<div class="" id="m-metro">  
    <div>
        <img class="logo" src="<?php echo HTTP_CATALOG.$tplpath;?>/images/campaign/logo.png" />
        <img class="title" src="<?php echo HTTP_CATALOG.$tplpath;?>/images/campaign/title.png" />
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="metro">
        <!-- 手机号 -->
        <div class="form-group">
            <div class="input-group-addon phone"></div>
            <div class="input-group">             
                <input id="input-new-phone" type="tel" name="mobile" data-validate="phone" placeholder="输入手机号"/>
            </div>
        </div>
        <?php if ($error_mobile) { ?>
        <div id="error_mobile" class="error"><?php echo $error_mobile; ?></div>
        <?php } ?>
        
        <!-- 图形验证码 -->           
        <?php if($this->config->get('config_customer_lr_captcha')) {?>
        <div class="form-group" id="form-group-metro-captcha">
        	<img id="sys_vcode" class="pull-right"
                 onclick="refreshCaptcha($(this).attr('id'),$(this).attr('src'));"
                 title="<?php /*echo $text_refresh_mobile_vcode; */ ?>"
                 src="index.php?route=information/contact/captcha" alt=""/>
            <div class="input-group">
                <input type="tel" name="sys_vcode" class="captcha" placeholder="<?php echo $entry_sys_vcode; ?>"/>
            </div>
        </div>
		<?php if ($error_sys_vcode) { ?>
		<div class="col-red fz-12 bt-10"><?php echo $error_sys_vcode; ?></div>
		<?php } ?>
	   
		<?php }?>
										
		<!-- 短信验证码 -->   		
        <div class="form-group" id="form-group-metro-captcha">
            <a href="javascript:" class="pull-right fz-15 btn btn-block btn-orange">获取短信验证码</a>

            <div class="input-group">
                <input id="input-captcha" type="tel" name="mobile_vcode" placeholder="输入验证码"/>
            </div>
        </div>
        <?php if ($error_mobile_vcode) { ?>
        		<div id="error_mobile_vcode" class="error"><?php echo $error_mobile_vcode; ?></div>
        <?php } ?>

		<div class="form-group">
            <input type="button" value="参加活动" class="btn btn-block btn-brown btn-submit"  />
        </div>
    </form>
</div>
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/login.js"></script>