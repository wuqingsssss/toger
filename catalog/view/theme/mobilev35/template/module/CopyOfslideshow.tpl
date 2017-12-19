<link rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/nivoslider/themes/default/default.css" type="text/css" media="screen" />        
<link rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/nivoslider/nivo-slider.css" type="text/css" media="screen" />
<?php if($banners) {?>
<div id="slideshow_<?php echo $banner_id; ?>_wrap" class="slider-wrapper theme-default">
  <div id="slideshow_<?php echo $banner_id; ?>" class="nivoSlider">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" data-thumb="<?php echo $banner['image']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" data-thumb="<?php echo $banner['image']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<?php } ?>

<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/NerveSlider9.2/jquery.nerveSlider.js"></script>
<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/NerveSlider9.2/nerveSlider.css"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#slideshow_<?php echo $banner_id; ?>').nivoSlider();
});
</script>
