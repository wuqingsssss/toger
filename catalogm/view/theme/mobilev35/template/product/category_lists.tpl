<?php echo $header; ?>
<div id="header" class="bar bar-header bar-default">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">

<ul class="list">
	<?php foreach($categories as $item) {?>
	<a href="<?php echo $item['href']; ?>" title="<?php echo $item['name']; ?>" class="item"><?php echo $item['name']; ?></a>
	<?php } ?>
</ul>

</div>
<?php echo $footer; ?>