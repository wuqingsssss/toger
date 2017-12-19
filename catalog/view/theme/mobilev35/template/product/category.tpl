<?php echo $header35; ?>
<link type="text/css" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/general.css"/>
<link rel="stylesheet" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/home.css"/>
<link rel="stylesheet" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/cart.css"/>
<link rel="stylesheet" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/classify.css"/>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/uc.css" rel="stylesheet"/>
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
<?php echo $this->getChild('module/navtop');?>
<?php echo $this->getChild('module/product_list_cat');?>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
<script type="text/javascript">
	$(function(){	
		$('.classify_l_nav a').click(function(){
			$(this).addClass('on').siblings().removeClass('on');
			$('.classify_con_rdiv>div').eq($(this).index()).show().siblings().hide();	
		})
	})
</script>
