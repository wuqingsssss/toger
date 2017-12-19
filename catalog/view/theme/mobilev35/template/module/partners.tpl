<div id="partners" class="box">
  <div class="top"><?php echo $heading_title; ?></div>
  <div id="links" class="middle">
   <ul style="list-style-type:none;padding-left: 0px;margin:0px;">
		<?php foreach ($banners as $result) { ?>
		<li style="margin-bottom:3px;"><a href="<?php echo $result['link']; ?>" target="_blank"><img src="<?php echo $result['thumb']; ?>" alt="<?php echo $result['title']; ?>" title="<?php echo $partners_image['title']; ?>"/></a></li>
		<?php }?>
	</ul>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
