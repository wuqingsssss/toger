<?php echo $header; ?>
<div id="header" class="bar bar-header bar-positive">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content ilex-success">
	<div class="card">
		 <div class="tishi">
	  		<div class="tishi-title"><?php echo $heading_title; ?></div>
	   		<div class="tishi-content"><?php echo $text_message; ?></div>
	 	</div>
	</div>
</div>

<?php if($packet) {?>  
    <div class="cover">&nbsp;</div>
    <div class="red">
        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/baoa.png" class="bao">
        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/bg.png" class="bg">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b1.png" class="b1">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b2.png" class="b2">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b3.png" class="b3">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b4.png" class="b4">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b5.png" class="b5">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b6.png" class="b6">
    	<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/b7.png" class="b7">
    	
    </div>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/packet.css" />
    <script type="text/javascript" src="catalog/view/javascript/yxMobileSlider.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-ui.min.js"></script>
    
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
	  $(".bao").attr("src","catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/packet/baob.png");
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
    	  var person = $("#person_icon");
    
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

<?php echo $footer; ?>