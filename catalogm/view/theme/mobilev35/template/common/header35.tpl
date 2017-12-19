<?php 
require_once(DIR_ROOT."hm.php");
$_hmt = new _HMT("61f1331aa9214144042cf468daaf9caf");
$_hmtPixel = $_hmt->trackPageView();
?>
<!DOCTYPE html>
<html style="height:100%">
<head lang="en">
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
     <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1"/>
    <title><?php echo $title; ?></title>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
    <!-- 公共样式库 -->
    <link href="<?php echo HTTP_ASSETS.DIR_DIR;?>view/theme/mobilev35/css/common.css" rel="stylesheet"/>
    <!--script src="<?php echo HHTTP_ASSETS;?>assets/js/zepto/zepto-1.1.6.js"></script-->
    <script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery-1.7.2.min.js"></script>
    <script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.qncj.utils.js"></script>
    <script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.task.js"></script>
    <!-- script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.tabs.ant-1.0.js"></script-->
    <script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.event.drag-1.5.min.js"></script>
    <script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.touchSlider.js"></script>
    <script src="<?php echo HTTP_ASSETS.DIR_DIR;?>view/theme/mobilev35/js35/lyz.delayLoading.min.js" type="text/javascript"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="assets/fontello/css/fontello.css" rel="stylesheet" type="text/css" /> 
    <link href="assets/fontello/css/animation.css" rel="stylesheet" type="text/css" /> 
  
  <?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
    <style id="J_style"></style>
    <script type="text/javascript">
	! function() {
		$.task.monitor({'ajax':false});//开启js任务控制
		<?php if(!DEBUG){echo "console.log=function(){};";}?>
		function b() {
			var e, b = document.getElementById("J_style"),
				c = document.documentElement.clientWidth || document.body.clientWidth,  //可视区域的宽高和body的宽高一样
				d = 1; 
			d = c / 640, e = 100 * d,
			b.innerHTML = "html{font-size:" + e + "px;}", a = d;
			window._z = d;
		}
		var a = 0;
		b(), window.addEventListener("resize", b);
	}();
	window._idx = 10;
	/*
	$(function(){
		
		$('img').each(function(){
				$(this).attr('src',$(this).attr('src').replace('<?php echo OSS_SERVER;?>','<?php echo OSS_HTTPS_SERVER;?>'));
		});
		
	});*/
	$(function(){
		 console.log('header1');
		_.waiting=$('.waiting');
		_.waiting.hide();  
	});
    </script>
 
</head>
<body style="height:100%"><img src="<?php echo $_hmtPixel; ?>" width="0" height="0" style="display:none" />
<span class="waiting"><img class="icon-spin animate-spin" src="<?php echo HTTP_CATALOG.$tplpath;?>images/waiting.png"/></span>