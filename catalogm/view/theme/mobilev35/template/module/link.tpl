<?php if(isset($links)){ ?>
<div id="link" class="module hlist content no_border">
  <div id="link_title"><?php echo $heading_title; ?></div>
  
  <div class="link_content">
  <?php foreach($links as $link) { ?>
  <a class="item" href="<?php echo $link['uri'] ?>" title="<?php echo $link['description'] ?>" target="_blank">
	<?php if($link['thumb']) { ?>
		<img src="<?php echo $link['thumb']; ?>" alt="<?php echo $link['name'] ?>" /><br />
	<?php } else { ?>
		<?php echo $link['name'] ?>
	<?php } ?>
  </a>
  <?php } ?>
  </div>
</div>
<?php } ?>