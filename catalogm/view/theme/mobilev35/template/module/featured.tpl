<div class="index">
<div class="box mt10">
  <div class="box-heading "><span class="fs18"><?php echo $heading_title; ?></span></div>
  <div class="box-content">
  	 <div class="product-grid">
		  	<?php echo $this->common_render_tpl('product/product_list.tpl',array('products' => $products)); ?>
	 </div>
  </div>
</div>
</div>