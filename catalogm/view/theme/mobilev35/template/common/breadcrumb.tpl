<?php if($breadcrumbs) {?>
<div id="breadcrumb" class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	    <?php echo $breadcrumb['separator']; ?>
	    <?php if($breadcrumb['href']) {?>
	    <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>"><?php echo $breadcrumb['text']; ?></a>
	    <?php } else {?>
	    <?php echo $breadcrumb['text']; ?>
	    <?php } ?>
	<?php } ?>
	
</div>
<?php } ?>