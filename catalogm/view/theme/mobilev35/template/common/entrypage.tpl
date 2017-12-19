<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/entrypage/';
?><?php echo $header; ?>

<?php if(empty($homepage)){?>
<meta http-equiv="refresh" content="5;url=index.php?route=common/home">
<?php }else {?>
<meta http-equiv="refresh" content="5;url=index.php?route=<?php echo $homepage; ?>">
<?php }?>

<style type="text/css">
body{ margin:0; padding:0;}
#layer_bg{position:absolute; width:100%; height:100%; z-index:-1;}
#layer_bg img{width:100%; height:100%;}
#layer_front{width:100%; height:100%; z-index:0;}
.skip{ float:right; 
       height:30px; 
       width :80px;
       margin-top:20px ;
       margin-right:20px;  
       cursor:pointer; 
       border-radius: 15px;
       background-color:#000;
       opacity:0.5;
       text-align:center;
}
</style>
<div id="wrap">

       <?php if($banners) { ?>
<link rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/NerveSlider9.2/nerveSlider.css" />
    <div id="layer_bg" > 
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>">
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    </a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    <?php } ?>
    <?php } ?>
   </div>
<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/NerveSlider9.2/jquery.nerveSlider.js"></script>
<script type="text/javascript">
$("#layer_bg").nerveSlider({
	slideTransitionSpeed: 1000,
	sliderWidth: "100%",
	sliderHeight: "100%",
	sliderHeightAdaptable: true,
	slidesDraggable: true,
	sliderResizable: true,
	showPause: false,
	showArrows: false,
	slideTransitionSpeed: 1000,
	slideTransitionEasing: "easeOutExpo"
	});
$(document).ready(function() {
	
});
</script>
<?php } ?>  
    <div id="layer_front">
    	<div class="skip">
    	<?php if(empty($homepage)){?>
        <a href="index.php?route=common/home">
        <?php }else {?>
        <a href="index.php?route=<?php echo $homepage; ?>">
        <?php }?> 
    	<img src="<?php echo $imgPath; ?>skip_icon.png" style="width:60px;padding:8px 3px;"> </a> </div>
    </div>
</div>
</div>
</body>
</html>

