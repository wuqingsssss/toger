<?php if($products) {?>
<div class="product-grid">
	<?php include 'catalog/view/theme/dss/template/product/ilex_product_list.php'; ?>
</div>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else {?>
<div class="information">暂无满足条件的搜索结果</div>
<?php } ?>