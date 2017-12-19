<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/coupon2/';
?>
<link href="<?php echo $cssPath; ?>couponpickup2.css" rel="stylesheet">
<script>
$('#header').css('height','105px');
</script>
<style>
<?php if($coupon_info['share_bg']){ ?>
.hb{background: url(<?php echo HTTP_IMAGE.$coupon_info['share_bg'];?>) no-repeat 0px 0px /100% 100% ;}
<?php }?>
</style>
<div class="hb">
   <div class="hb_top"><img src="<?php if($coupon_info['share_image3']) {echo HTTP_IMAGE.$coupon_info['share_image3'] ;}else{ echo $imgPath.'cjsfl.png'; }?>"/></div>  
    <div id="hb_body" class="hb_body">
     <div id="hb_tt" class="hb_tt<?php if($success!='-2')echo ' hide' ;?>"><img src="<?php  echo $imgPath.'title2.png'; ?>"/></div>
        <div class="hb_title">
         <?php echo $this->getChild('module/sharebtn');?>
        <img src="<?php if($coupon_info['share_image1']) {echo HTTP_IMAGE.$coupon_info['share_image1'] ;}else{ echo $imgPath.'title1.png'; }?>"/>
        </div>
    </div>
</div>
<?php echo $footer; ?>