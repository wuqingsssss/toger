<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="order-status"><img src="image/page/status3.jpg"></div>
  <div class="content">
  	<div class="tishi">
  		<div class="tishi-title"><?php echo $heading_title; ?></div>

   		<div class="tishi-content"><?php echo $text_message; ?></div>
	</div>
</div>
 
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>