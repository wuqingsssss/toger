<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
   <?php echo $this->getChild('common/breadcrumb'); ?>
    <div class="section">
  	 <div class="top"><h1><?php echo $heading_title; ?></h1></div>
<div class="content">
  <?php if (!$products) { ?>
  <?php echo $text_empty; ?>
  <?php } ?>
  
  <?php if ($products) { ?>
  <?php echo $this->getChild('product/product/filter');?>

<div id="filter_contents" >
    <div class="wrap">
        <div class="product-grid">
            <?php include 'catalog/view/theme/'.$this->config->get('config_template').'/template/product/ilex_product_list.php'; ?>
        </div>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
  <?php } ?>
  </div>
  </div>
 <?php echo $content_bottom; ?>
 </div>
<?php echo $footer; ?>