<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" ><?php echo $content_top; ?>
<?php echo $this->getChild('product/consulation/lists');?>


<?php echo $this->getChild('product/consulation/insert');?>
 <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>