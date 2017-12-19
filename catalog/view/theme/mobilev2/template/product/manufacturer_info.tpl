<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  
  <?php if ($products) { ?>
  <?php echo $this->getChild('product/product/filter');?>
  
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