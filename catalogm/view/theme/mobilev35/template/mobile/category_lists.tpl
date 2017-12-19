<header id="header" class="bar bar-header bar-default rel">
	<h1 class="title aside-left"><?php echo $heading_title; ?></h1>
</header>
<div id="content" class="content">
	<ul class="list">
		<?php foreach($categories as $item) {?>
			<a href="<?php echo $item['href']; ?>" title="<?php echo $item['name']; ?>" class="item"><?php echo $item['name']; ?></a>
		<?php } ?>
	</ul>
</div>