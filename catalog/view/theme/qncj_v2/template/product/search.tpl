<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($products) { ?>
 <div id="filter_contents" >
     <div class="wrap">
         <div class="product-grid">
             <?php include 'catalog/view/theme/'.$this->config->get('config_template').'/template/product/ilex_product_list.php'; ?>
         </div>
         <div class="pagination"><?php echo $pagination; ?></div>
     </div>

</div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>