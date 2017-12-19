<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
 
  <?php if ($thumb || $description) { ?>
  <div class="category-info">
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <?php } ?>
  
  <?php if ($article) { ?>
  <div id="tabs" class="tabs">
  <?php foreach($article as $result) {?>
  <a href="#tab-<?php echo $result['article_id']; ?>"><?php echo $result['name']; ?></a>
  <?php } ?>
  </div>
  <?php foreach($article as $result) {?>
  <div id="tab-<?php echo $result['article_id']; ?>" class="tab_container">
  	<div class="tab_content"><?php echo $result['description']; ?></div>
  </div>
  <?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
  <?php } ?>
 
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?>