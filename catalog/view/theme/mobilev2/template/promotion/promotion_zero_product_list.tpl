<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <?php echo $this->getChild('common/breadcrumb'); ?>
  
  <div class="section">
  	<div class="top"><h1><?php echo $heading_title; ?></h1></div>
  	<div class="content">
  <?php if (!$products) { ?>
  <?php // echo $text_empty; ?>
  <?php } ?>
  
<?php if ($products) { ?>
<div id="promotion_zerobuy" >
	  <div class="product-grid">
	  	<?php include 'catalog/view/theme/dss/template/product/ilex_product_list.php'; ?>
	  </div>
	  <div class="pagination"><?php echo $pagination; ?></div>
</div>
<?php } ?>
 </div> 
  
 <?php echo $content_bottom; ?>
 </div>
 
 </div>
<script type="text/javascript">
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