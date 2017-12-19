<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <h1><?php echo $heading_title; ?></h1>
    <div class="content" style="padding:30px;">
    	<?php echo $text_error; ?>
    	<a href="index.php" class="btn">继续购物</a>
    </div>
    <?php echo $content_bottom; ?></div>
</div>
<?php echo $footer; ?>