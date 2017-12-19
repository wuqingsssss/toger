<div class="content">
<h3><span><?php echo $heading_title; ?></span></h3>
<?php if($article) {?>
<ul role="article" class="vlist c1">
<?php foreach ($article as $record) { ?>
	<li>
	<span class="date"><?php  echo $record['date_added']; ?></span>
	<a href="<?php echo $record['href']; ?>" title="<?php echo $record['title']; ?>"><span class="title"><?php echo $record['title']; ?></span></a>
	<div class="summary"><?php echo $record['summary']; ?></div>
	</li>
<?php } ?>
</ul>

<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>
</div>