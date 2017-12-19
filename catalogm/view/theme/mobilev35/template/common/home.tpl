<?php echo $header35; ?>

<!-- 页面自定义样式 -->
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/home.css" rel="stylesheet"/>
<!-- 页面内容开始 -->
<?php echo $content_top;?>
<?php //echo $this->getChild('module/navtop');?>
<?php //echo $this->getChild('module/slideshow',array('banner_id' => 9,'width' => 640,'height' => 0));?>
<?php //echo $this->getChild('module/navmain');?>
<?php //echo $this->getChild('module/product_featured',array('skus' => 'C337,C338,C339'));?>
<?php //echo $this->getChild('module/promotion_featured',array('pid' => 9));?>
<?php //echo $this->getChild('module/cates',array('cate'=>49,'title'=>'本周菜品'));?>

<?php //echo $this->getChild('module/navbar');?>
<?php //echo $this->getChild('module/sharebtn');?>
<?php echo $content_bottom;?>

<!-- 公共底部开始 -->

<?php if($packet) {?>  
    <div class="cover">&nbsp;</div> 
    <div class="red">
        <img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/baoa.png" class="bao">
        <img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/bg.png" class="bg">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b1.png" class="b1">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b2.png" class="b2">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b3.png" class="b3">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b4.png" class="b4">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b5.png" class="b5">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b6.png" class="b6">
    	<img src="<?php echo HTTP_CATALOG.$tplpath;?>image/packet/b7.png" class="b7">
    	
    </div>
    <link rel="stylesheet" type="text/css" href="<?php echo HTTP_CATALOG.$tplpath;?>css/packet.css" />
    <script type="text/javascript" src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery-ui.min.js"></script>
 <!--    <script type="text/javascript" src="<?php echo HTTP_CATALOG.$tplpath;?>js35/yxMobileSlider.js"></script> --> 
    
    <!--  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
    
<script>  
window.onload= hongbao;

function hongbao(){
	  var packet = $(".bao");
	  
	  packet.effect("shake", {
          times: 2,
          distance: 15
      }, 100);
    
	  setTimeout(function shan(){
	  $(".bao").addClass("baobao")
	  $(".bao").addClass("baobao1")
	  $(".bg").addClass("red-bg")
	  },500);

	  setTimeout(function fei(){
	  $(".bao").attr("src","<?php echo HTTP_CATALOG.$tplpath;?>image/packet/baob.png");
	  $(".b1").addClass("b11");
	  $(".b2").addClass("b22");
	  $(".b3").addClass("b33");
	  $(".b4").addClass("b44");
	  $(".b5").addClass("b55");
	  $(".b6").addClass("b66");
	  $(".b7").addClass("b77");

	  },1300);
	  
	 setTimeout(function touming(){
	  $(".b1,.b2,.b3,.b4,.b5,.b6,.b7, .bg").addClass("touming");
	 },1500);
	 setTimeout(function touming(){
	  $(".b1,.b2,.b3,.b4,.b5,.b6,.b7, .bg").addClass("touming2");
	 },1700);
	 setTimeout(function touming(){
	  $(".b1,.b2,.b3,.b4,.b5,.b6,.b7, .bg").addClass("touming3");
	 },2000);
	 setTimeout(function touming(){
    	  $(".b1,.b2,.b3,.b4,.b5,.b6,.b7, .bg").addClass("touming4");
    	  var person = $(".icon-user");
    
          packet
          .css({
                  'position': 'absolute'
          })
             .animate({
                 'top': person.offset().top,
                 'left': person.offset().left,
                 'width': '10px',
                 'height': '10px'
         },   { duration:500,
         	   easing: 'easeInOutQuad',
         	   complete:  function (){
                  $(this).detach();
                  $(".cover").css({'display':'none'});
         	   }       
    	       
         });
    },2300);
};
    
</script>
    
<?php }?>





<script>function sharetest(){
	
	var scope = "snsapi_userinfo";
	Wechat.auth(scope, function (response) {
		// you may use response.code to get the access token.
		alert("微信登录返回值：" + JSON.stringify(response));
	}, function (reason) {
		alert("Failed: " + reason);
	});
	
	var params = {
			partnerid: '10000100', // merchant id
			prepayid: 'wx201411101639507cbf6ffd8b0779950874', // prepay id
			noncestr: '1add1a30ac87aa2db72f57a2375d8fec', // nonce
			timestamp: '1439531364', // timestamp
			sign: '0CB01533B8C1EF103065174F50BCA001', // signed string
		};
		 
		Wechat.sendPaymentRequest(params, function () {
			alert("Success");
		}, function (reason) {
			alert("Failed: " + reason);
		});
	
Wechat.share({
         message: {
           title: "青年菜君",
            description: "吃好再来谈梦想",
            mediaTagName: "青年菜君tag",
            thumb: "http://YOUR_THUMBNAIL_IMAGE",
            media: {
                type: Wechat.Type.WEBPAGE,   // webpage
                webpageUrl: "http://www.qingniancaijun.com.cn"    // webpage
            }
        },
        scene: Wechat.Scene.TIMELINE   // share to Timeline
     }, function () {
         alert("Success");
     }, function (reason) {
         alert("Failed: " + reason);
     });
}
</script>

<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/home.js"></script>
<?php echo $footer35; ?>
<!-- 页面内容结束 -->