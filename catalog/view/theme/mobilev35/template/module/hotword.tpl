<div id="hotwords">
	<strong><?php echo $heading_title; ?></strong>
	<?php foreach($hotwords as $word) {?>
	<a target="_blank" title="<?php echo $word['keyword']; ?>" href="<?php echo $word['href']; ?>"><?php echo $word['keyword']; ?></a>
	<?php } ?>
</div>