<div class="content">
<h3><span><?php echo $heading_title; ?></span></h3>
<?php if($article) {?>
<ul role="article" class="vlist c2">
<?php foreach ($article as $record) { ?>
	<li>
	<a href="<?php echo $record['href']; ?>" title="<?php echo $record['title']; ?>">
	<img src="<?php echo $record['image']; ?>" alt="<?php echo $record['title']; ?>" class="image" />
	</a>
	<span class="date"><?php  echo $record['date_added']; ?></span>
	<a href="<?php echo $record['href']; ?>" title="<?php echo $record['title']; ?>">
	<span class="title"><?php echo $record['title']; ?></span>
	</a>
	</li>
<?php } ?>
</ul>

<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>
</div>