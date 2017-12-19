<?php echo $header; ?>
<div class="bar bar-header bar-light">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content"><?php echo $content_top; ?>
  <h1><?php echo $heading_title; ?></h1>
 <h2><?php echo $text_search; ?></h2>
  <?php if ($products) { ?>
  <?php echo $this->getChild('product/product/filter');?>
<!--  <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare_total"><?php echo $text_compare; ?></a></div>-->
 <div id="filter_contents" >
  <div class="product-grid">
    <?php include 'catalog/view/theme/dss/template/product/ilex_product_list.php'; ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
</div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
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
//--></script> 
<?php echo $footer; ?>