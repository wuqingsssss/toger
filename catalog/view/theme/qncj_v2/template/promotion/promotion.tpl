<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <?php echo $this->getChild('common/breadcrumb'); ?>
<?php if ($promotion&&$promotion['page_header']){
echo $promotion['page_header'];
}?>
  <div class="section">
  	<div class="top">
  	<h1><?php echo $heading_title; ?></h1>
  	</div>
  	<div class="content">
  <?php if (!$products) { ?>
  <?php echo $text_empty; ?>
  <?php } ?>
  
<?php if ($products) {?>
<div id="promotion_zerobuy" >
	  <div class="product-grid">
	  	<?php include 'catalog/view/theme/'.$this->config->get("config_template").'/template/product/ilex_product_list.php'; ?>
	  </div>
</div>
<?php } ?>
 </div> 
 <?php echo $content_bottom; ?>
 </div>
<?php if ($promotion&&$promotion['page_footer']){
echo $promotion['page_footer'];
}?>
 </div>
<script type="text/javascript">

$('#header').css('height','105px');

 $(document).ready(function(){
	$('.display .grid').click(function(){ display('grid');});

	$('.display .list').click(function(){ display('list'); });
	
	if($('.product-grid').length > 0){
		view = $.cookie('display');
		
		if (view=='list') {
			display(view);
		}
	}
});
</script>
<?php echo $footer; ?>