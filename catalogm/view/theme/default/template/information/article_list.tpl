<div class="article-list">
<h1><span><?php echo $heading_title; ?></span></h1>
<?php if($article) {?>
<ul class="vlist">
<?php foreach ($article as $record) { ?>
	<li>
	<span class="date"><?php  echo $record['date_added']; ?></span>
	<a href="<?php echo $record['href']; ?>" title="<?php echo $record['title']; ?>"><span class="title"><?php echo $record['title']; ?></span></a>
	</li>
<?php } ?>
</ul>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>
</div>
