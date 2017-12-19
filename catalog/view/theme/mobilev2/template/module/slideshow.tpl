<?php if($banners) { ?>
<link rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/NerveSlider9.2/nerveSlider.css" />
  <div id="slideshow_<?php echo $banner_id; ?>">
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
$("#slideshow_<?php echo $banner_id; ?>").nerveSlider({
	slideTransitionSpeed: 1000,
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