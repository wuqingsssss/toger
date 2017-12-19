<?php echo $header; ?>
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
	  //  var packet = $("#hongbao");
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
         },   { duration:1500,
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
<header id="header">
	<div class="ix-row">
		<div class="ix-u-sm-4">
			<div id="logo" class="tc">
				<a href="<?php echo $this->url->link('common/home'); ?>" title="">
					<img src="catalog/view/theme/<?php echo get_template(); ?>/images/logo-m.png" alt="" />
				</a>
			</div>
		</div>
		<div class="ix-u-sm-8">
			<div id="point-switch" ng-controller="SwitchZTDCtrl">
				<!-- a id="ztd-btn">
					<?php echo $point_text; ?>
				</a-->
			</div>
		</div>
	</div>
</header>
<div id="content" class="content">
<?php //echo $content_top; 
?>
	<div role="banner">
		<?php echo $this->getChild('module/slideshow',array('banner_id' => 9,'width' => 640,'height' => 0));?>
	</div>
	<div>

<div id="period">
		<div class="wrap">
			<div class="htabs2 clearfix">
				<?php foreach ($supply_periods as $key => $supply_period) { ?>
				<a href="<?php echo $supply_period['href']; ?>" title="<?php echo $supply_period['name']; ?>" <?php if($sequence==$key){ ?>class="selected"  <?php }?>>
				<span><?php echo $supply_period['name'];?></span>
				</a>
				<span class="period-date fr"><?php if($sequence==$key){ echo date("m/d",strtotime($supply_period['ps_start_date']));?>-<?php echo date("m/d",strtotime($supply_period['ps_end_date']));}?></span>
				<?php }?>		
				<a href="index.php?route=promotion/promotion&pid=4" title="新人专区">
				<span>新人专区</span>
				</a>
			</div>
		</div>
	</div>
	</div>
	
	<div class="product-grid">
	    <?php if(isset($yiyuangou)){?>
	    <?php echo $this->getChild('module/yiyuangou',array('image_width' => 100,'image_height' => 100,'count' => 12,'cate' => 48,'sequence'=>$sequence));?>
	    <?php }else{?>
		<?php echo $this->getChild('module/cates',array('image_width' => 100,'image_height' => 100,'count' => 12,'cate' => 49,'sequence'=>$sequence));?>
	    <?php }?>
	</div>
	
	<?php //echo $content_bottom; 
	?>
</div>
<?php if($this->config->get('pickupaddr_status')){?>
<?php echo $this->getChild('module/point/lbs',array('show_location_tipbox'=>$show_location_tipbox));?>
<?php }?>
<?php if($show_location_tipbox) {?>
<script type="text/javascript"><!--
$(document).ready(function(){
	//showPickPointsLbs();
});
//--></script>  
<?php } ?>
<?php echo $footer; ?>
<?php if($is_open_diag==1){ ?>
<script>
    var result = confirm('此账号已绑定其他微信账号，是否覆盖该绑定！');
    if(result){
        window.location.href="<?php echo $this->url->link('account/fugaiopenid'); ?>";
    }
</script>
<?php } ?>

<style type="text/css">
#bestseller{ margin-top:170px; }
.allsort{ background: none; }
.allsort .mc{ display:block; }
.allsort .mt .extra { display:none; }
</style>
<?php echo $this->getChild('module/sharebtn');?>