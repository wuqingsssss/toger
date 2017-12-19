<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/home.css" />
<div class="home">
	<?php echo $column_left; ?>
	<div id="content">
	<?php echo $content_top; ?>

		<div class="clear"></div>
		<div class="home-advs">
			<div class="wrap">
				<?php echo $content_bottom; ?>
			</div>
		</div>
		<div class="clear"></div>
		<div id="shopping"></div>
	<div id="productbox">
	    <?php if(isset($yiyuangou)) {?>
	    <?php echo $this->getChild('product/yiyuangou',array('sequence'=>$sequence,'filter_category_id'=>$filter_category_id,'filter_keyword'=>$filter_keyword)); ?>
		<?php }else{?>
		<?php echo $this->getChild('product/home',array('sequence'=>$sequence,'filter_category_id'=>$filter_category_id,'filter_keyword'=>$filter_keyword)); ?>
	    <?php }?>
	</div>
	</div>

</div>

<?php if($show_location_tipbox) {?>
<script type="text/javascript"><!--
$(document).ready(function(){
	//showPickPointsLbs_pc();
});
//--></script>  
<?php } ?>
<script type="text/javascript">
<!--
var sequence=<?php echo $sequence;?>;
function get_product_home(s,c,p){
	var ci=false;
	if(s!=sequence){
		if(confirm('切换菜品周期，会清空您的购物车！'))
		{sequence=s;
		 ci=true;
		 
			location.href="<?php echo $homelink;?>&sequence="+sequence;
			return;
		}
		else
	    {
		return;
	    }
	}

	
	$.ajax({
		url: '<?php echo $homelink;?>/get_product_home',
		type: 'get',
		data: 'sequence='+s+'&filter_category_id='+c+ '&filter_keyword=' + $('#key').val(),
		dataType: 'text',
		success: function(json) {
		  if(ci){$('#cart_total').html('0');}
	      $('#productbox').html(json);	
		}
});
}
//-->
</script>

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
    	  //var person = $("#cart_total");
    	  packet.css({
                  'position': 'absolute'
          }).animate({
                 'top': -230,
                 'left': 400,
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

<?php echo $footer; ?>