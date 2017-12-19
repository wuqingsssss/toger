<div id="mostviewed" class="box">
  <div class="box-heading"><h2><?php echo $heading_title; ?></h2></div>
  <div class="box-content">
  	<div class="product-grid">
		  	<?php echo $this->common_render_tpl('product/product_list2.tpl',array('products' => $products)); ?>
	 </div>
  </div>
</div>
