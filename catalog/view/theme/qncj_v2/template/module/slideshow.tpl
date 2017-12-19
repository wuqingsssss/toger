<?php if($banners) {?>
<div id="slideshow_<?php echo $banner_id; ?>_wrap" class="slideshow">
  <div id="slideshow_<?php echo $banner_id; ?>" class="nivoSlider">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<link rel="stylesheet" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/slideshow.css" type="text/css" media="screen" />        
<script type="text/javascript" src="catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js"></script>


<script type="text/javascript">
$(document).ready(function() {
	$('#slideshow_<?php echo $banner_id; ?>').nivoSlider();
});
</script>
<?php } ?>