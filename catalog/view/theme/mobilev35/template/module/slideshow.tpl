<?php if($banners) { ?>
<div class="module banner banner-default" id="m-head-banner">
    <ul>
     <?php foreach ($banners as $banner) { ?>
      <li>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>">
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-wrapper" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    </a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-wrapper" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    <?php } ?>
    </li>
    <?php } ?> 
    </ul>
</div>
<?php } ?>