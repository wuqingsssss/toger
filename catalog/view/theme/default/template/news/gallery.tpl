<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  	</div>
  	
	  <div class="divider">
	      <h5><span><?php echo $heading_title; ?></span></h5>
	  </div>
	  
<?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function(){
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5
	});
});
//--></script> 

<?php echo $footer; ?> 