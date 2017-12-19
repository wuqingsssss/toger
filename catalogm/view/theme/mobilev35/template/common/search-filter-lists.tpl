<?php if($products) {?>
<div class="product-grid">
	<?php echo $this->getChild('product/product/lists',$products);?>
</div>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else {?>
<div class="information">暂无满足条件的搜索结果</div>
<?php } ?>