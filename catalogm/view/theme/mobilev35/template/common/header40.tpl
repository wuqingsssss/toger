<?php 
require_once(DIR_ROOT."hm.php");
$_hmt = new _HMT("61f1331aa9214144042cf468daaf9caf");
$_hmtPixel = $_hmt->trackPageView();
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
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
    <link href="<?php echo HTTP_CATALOG.$tplpath;?>css/base.css" rel="stylesheet"/>
    <script src="<?php echo HTTP_CATALOG;?>assets/js/jquery/jquery-1.7.2.min.js"></script>
    
    <script type="text/javascript" src="catalogm/view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
    <link rel="stylesheet" type="text/css"
	   href="catalogm/view/javascript/jquery/ui/themes/flick/jquery-ui-1.8.16.custom.css" />
    <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo HTTP_CATALOG;?>assets/js/jquery/jquery.qncj.utils.js"></script>
    <script src="<?php echo HTTP_CATALOG;?>assets/js/jquery/jquery.task.js"></script>
    <link href="assets/fontello/css/fontello.css" rel="stylesheet" type="text/css" /> 
    <link href="assets/fontello/css/animation.css" rel="stylesheet" type="text/css" /> 
 <?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
    <style id="J_style"></style>
    <script type="text/javascript">
	! function() {
		$.task.monitor();//开启js任务控制
		<?php if(!DEBUG){echo "console.log=function(){};";}?>
	}();
//	window._idx = 10;
	$(function(){
		 console.log('header1');
		_.waiting=$('.waiting');
		_.waiting.hide();  
	});
</script>
</head>
<body>