<div id="product-filter-section" class="clearfix wrap">
	<div class="w700 fl">
		<div class="kouwei fl">
			口味
		</div>

		<div class="product-category fl ml30">
		    <?php if(isset($yiyuangou)){ ?>
			    <a class="selected" href="javascript:get_product_home(<?php echo $sequence;?>,<?php echo 54;?>,0)" title="<?php echo '1元专区'; ?>"><?php echo '1元专区'; ?></a>
			<?php }else{ ?>
			<?php foreach ($categories as $category) { ?>
			    <a class="<?php if($filter_category_id==$category['category_id']){?> selected  <?php }?>" href="javascript:get_product_home(<?php echo $sequence;?>,<?php echo $category['category_id'];?>,0)" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
		
			<?php }  ?>
			<?php if(isset($freepromotion)){ ?>
			    <a class="<?php if($filter_category_id==1){ ?> selected <?php }?>" href="javascript:get_product_home(<?php echo $sequence;?>,<?php echo 1;?>,0)" title="<?php echo '菜票专栏'; ?>"><?php echo '菜票专栏'; ?></a>
			<?php } ?>
			<?php }?>
		</div>
	</div>
	<div class="w300 fr">
		<?php echo $this->getchild('module/search',array('filter_keyword'=>$filter_keyword)); ?>
	</div>
</div>

