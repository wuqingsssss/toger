<?php if($products) {?>
<div id="total-donation" class="box">
  <div class="box-heading"><h2><?php echo $heading_title; ?></h2></div>
  <div class="box-content">
  	<div class="product-grid">
		<?php include 'catalog/view/theme/dss/template/product/ilex_product_list.php'; ?>
	 </div>
  </div>
</div>
<div class="clear"></div>
<?php } ?>
