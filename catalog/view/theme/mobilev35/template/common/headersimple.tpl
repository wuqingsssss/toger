<?php 
require("hm.php");
$_hmt = new _HMT("61f1331aa9214144042cf468daaf9caf");
$_hmtPixel = $_hmt->trackPageView();
?>
<!DOCTYPE html>
<html size=50px>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1"/>
    <title>青年菜君</title>
    <!-- 公共样式库 -->
    <link href="<?php echo HTTP_CATALOG.$tplpath;?>css/common.css" rel="stylesheet"/>
    <script src="<?php echo HTTP_CATALOG;?>assets/js/zepto/zepto-1.1.6.js"></script>
    
    <style id="J_style"></style>
    <script type="text/javascript">
	! function() {
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
</script>
</head>
<body style="background: #EDEDED;">
<!-- 公共头开始 -->
<div id="header">
    <div class="pull-left">
        <a class="return" href="javascript:_.go();"></a>
    </div>
    <div class="text-center">
         <a class="locate fz-18" href="javascript:location.reload(true);"><?php echo $heading_title;?></a>
    </div>
</div>
<!-- 公共头结束 -->