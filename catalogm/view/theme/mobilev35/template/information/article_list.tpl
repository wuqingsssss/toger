<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" ><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<div class="article-list">
<h1><span><?php echo $heading_title; ?></span></h1>
<?php if($articles) {?>
<ul class="vlist">
<?php foreach ($articles as $result) { ?>
	<li>
	<span class="date"><?php  echo $result['date_added']; ?></span>
	<a href="<?php echo $result['href']; ?>" title="<?php echo $result['title']; ?>">
		<span class="title"><?php echo $result['title']; ?></span>
	</a>
	</li>
<?php } ?>
</ul>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>
</div>
 <?php echo $content_bottom; ?></div>
 <?php echo $footer; ?>