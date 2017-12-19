<div id="category">
	<div class="allsort">
			<div class="mt">
				<div class="extra"></div>
				<strong><a href="<?php echo $this->url->link('product/category/allsort');?>"><?php echo $heading_title;?></a></strong>
			</div>
			<div id="_JD_ALLSORT" class="mc" load="2">
			<?php foreach ($categories as $category) { ?>
			<div class="item item-<?php echo $category['category_id']; ?>">
				<?php if ($category['category_id'] == $category_id) { ?>
				<span><h3><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h3><s></s></span>
				<?php } else { ?>
				<span><h3><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h3><s></s></span>
				<?php } ?>
				<?php if($category['children']) {?>
				<div class="i-mc">
					<div class="close" onclick="$(this).parent().parent().removeClass('hover')"></div>
					<div class="subitem">
					<?php foreach($category['children'] as $child) {?>
					<dl class="">
						<dt>
							<a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
						</dt>
						<?php if($child['children']) { ?>
						<dd> 
						<?php foreach($child['children'] as $subchild) {?>
						<em><a href="<?php echo $subchild['href']; ?>"><?php echo $subchild['name']; ?></a></em> 
						<?php } ?>
						</dd>
						<?php } ?>
					</dl>
					<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="extra"></div>
		</div>
	</div>		
</div>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/ilexnet/nav/category-nav.css"    />
<script src="catalog/view/javascript/ilexnet/nav/360buy.js" type="text/javascript"></script>
<script type=text/javascript> 
$(".allsort").hoverForIE6({current:"allsorthover",delay:200});
$(".allsort .item").hoverForIE6({delay:150});
</script>
