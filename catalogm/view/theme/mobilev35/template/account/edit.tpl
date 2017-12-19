<?php echo $header35 ;?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/profile.css" rel="stylesheet"/>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/us.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>"<a class='return' href='{$this->url->link('account/account')}'></a>",
       'center'=>'<a class="locate fz-18" >个人资料</a>',
       'right'=>''
)));?>
<!-- 公共头结束 -->
<div class="module" id="m-head">
    <div class="img-wrapper head-bg"><img src="<?php echo HTTP_CATALOG.$tplpath;?>images/header-bg.jpg"></div>
    <div class="content">
        <div class="text-center avatar"><img src="<?php echo HTTP_CATALOG.$tplpath;?>images/touxiang.png" class="round"></div>
        <div class="text-center phone col-white fz-15"><?php echo $mobile; ?></div>
    </div>
</div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
<div class="module" id="m-profile">
    <div class="nickname">
        <div>昵称：</div>
        <div><input type="text" name="name" value="<?php echo $name; ?>" maxlength="32"></div>
    </div>
		<?php if ($error_name) { ?>
				<div class="col-red fz-12 bt-10"><?php echo $error_name; ?></div>
		<?php } ?>
		<div class="nickname">
        <div>邮箱：</div>
        <div><input type="text" name="email" value="<?php echo $email; ?>"></div>
    </div>
		<?php if ($error_email) { ?>
				<div class="col-red fz-12 bt-10"><?php echo $error_email; ?></div>
		<?php } ?>
    <div class="gender">
        <div>性别：</div>
        <div class="content">
            <div class="radio">
                <i class="icon icon-food-checkbox gray" value="M" id="man"></i>
                <span>先生</span>
            </div>
            <div class="radio">
                <i class="icon icon-food-checkbox gray" value="F" id="female"></i>
                <span>女士</span>
            </div>
						<input type="hidden" name="salution" value="<?php echo $salution ? $salution: 'M';?>">
						<input type="hidden" name="mobile" value="<?php echo $mobile; ?>">
        </div>
    </div>
		
<!--    <div class="age">
        <div class="pull-right"><i class="icon icon-forward"></i></div>
        <div>年龄：</div>
        <div class="col-gray">可不填</div>
    </div>-->
</div>
<div class="but-submit" onclick="$('#edit').submit();">
	<button class="btn btn-green">提交</button>
</div>
</form>
<!-- 公共底部开始 -->
<div id="footer">
</div>
<!-- 公共导航 -->
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35;?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/profile.js"></script>