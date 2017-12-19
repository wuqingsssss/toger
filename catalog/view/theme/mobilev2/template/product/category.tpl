<?php echo $header; ?>
<div id="header" class="bar bar-header bar-default">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>

	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>  
<div id="content" class="content product-grid">
<?php include dirname(dirname(__FILE__)).'/product/ilex_product_list.php'; ?>

<?php if ($products) { ?>
<!--  <div class="pagination"><?php echo $pagination; ?></div>-->	
<?php } ?>
</div>

<nav id="menu">
	<?php echo $this->getChild('mobile/category/list_aside') ?>
</nav>
</div>

<link type="text/css" rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/jquery.mmenu.css" />
<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/jquery.mmenu.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('nav#menu').mmenu();
});
</script>
<?php echo $footer; ?>

