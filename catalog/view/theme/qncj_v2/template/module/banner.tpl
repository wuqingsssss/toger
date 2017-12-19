<div id="banner_<?php echo $banner_id; ?>" class="banner">
  <?php foreach ($banners as $banner) { ?>
  <?php if ($banner['image']) { ?>
  <?php if ($banner['link']) { ?>
  <div class="item"><a href="<?php echo $banner['link']; ?>">
  
  <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />

  </a></div>
  <?php } else { ?>
  <div class="item">
  <a>
  <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />
  </a>
  </div>
    <?php }?>
  <?php } ?>
  <?php } ?>
</div>
