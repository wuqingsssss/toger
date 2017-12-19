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
<div class="article-summary-lists">
<?php foreach($articles as $result) {?>
<div class="item" style="overflow:hidden; padding-bottom:20px; border-bottom:1px dotted #ccc;">
	<div class="post-image pull-left">
		<a href="<?php echo $result['href']; ?>" title="<?php echo $result['title']; ?>">
		<img src="<?php echo $result['image']; ?>" alt="<?php echo $result['title']; ?>" class="image" />
		</a>
	</div>
	<div class="summary">
		<h2 class="post-title">
			<a href="<?php echo $result['href']; ?>" title="<?php echo $result['title']; ?>">
			<span class="title"><?php echo $result['title']; ?></span>
			</a>
		</h2>
		<div class="post-description">
			<?php echo $result['summary']; ?>
		</div>
	</div>
</div>
<?php } ?>
</div>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } ?>
</div>
 <?php echo $content_bottom; ?></div>
 <?php echo $footer; ?>