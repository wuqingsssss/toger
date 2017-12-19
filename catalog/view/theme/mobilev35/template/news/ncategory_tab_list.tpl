<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <?php if ($ncategories) { ?>
  <div id="tabs" class="menu-tab">
  		<a href="#all">显示全部</a>
  		<?php foreach($ncategories as $result) {?>
  		<a href="#<?php echo $result['ncategory_id']; ?>"><?php echo $result['name']; ?></a>
  		<?php } ?>
  </div>
  <div id="all">
   <div class="gallery">
  <?php foreach($ncategories as $result) { if($result['articles']) { foreach($result['articles'] as $article) {?>
  	<div class="item">
  	<a href="<?php echo $article['image']; ?>" class="colorbox" rel="colorbox">
  	<img src="<?php echo $article['image']; ?>" alt="<?php echo $article['name']; ?>" />
  	<br />
  	<h6><?php echo $article['name']; ?></h6>
  	</a>
  	</div>
  <?php }}} ?>
  </div>
  </div>
  <?php foreach($ncategories as $result) {?>
  <div id="<?php echo $result['ncategory_id']; ?>">
  <div class="gallery">
  <?php foreach ($result['articles'] as $index =>  $article) { ?>

  	<div class="item i<?php echo  $index;  ?>">
  	<a href="<?php echo $article['image']; ?>" class="colorbox" rel="colorbox<?php echo $result['ncategory_id']; ?>">
  	<img src="<?php echo $article['image']; ?>" alt="<?php echo $article['name']; ?>" />
  	<br />
  	<h6><?php echo $article['name']; ?></h6>
  	</a>
  	</div>
  
  <?php } ?>
	</div>
  </div>
  <?php } ?>
<script type="text/javascript">
$(function() {
  $('#tabs a').tabs();
});
</script>

<script type="text/javascript"><!--
$(document).ready(function(){
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5
	});

	
});
//--></script> 
<?php } ?>
  
  <?php if (!$ncategories) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?>