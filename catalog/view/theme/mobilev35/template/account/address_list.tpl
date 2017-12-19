<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

  <div class="buttons" style=" margin-bottom:10px;">
  	<a href="<?php echo $insert; ?>" class="btn"><span><?php echo $button_new_address; ?></span></a>
 </div>
 
 <div id="address-lists">
 	<?php foreach ($addresses as $result) { ?>
 	<div class="item">
 		<div class="top">
 			<div class="extra">
 				<a href="<?php echo $result['update']; ?>"><span><?php echo $button_edit; ?></span></a>
 				<a href="<?php echo $result['delete']; ?>"><span><?php echo $button_delete; ?></span></a>
 			</div>
 			<h3><?php echo $result['shipping_name']; ?>-<?php echo $result['address_1']; ?></h3>
 		</div>
 		<div class="content">
 			<div class="detail">
	 			<dl>
	 				<dt>收货人：</dt>
	 				<dd><?php echo $result['shipping_name']; ?></dd>
	 			</dl>
	 			<dl>
	 				<dt>地址：</dt>
	 				<dd><?php echo $result['address']; ?></dd>
	 			</dl>
	 			<dl>
	 				<dt>手机：</dt>
	 				<dd><?php echo $result['mobile']; ?></dd>
	 			</dl>
	 			<dl>
	 				<dt>固定电话：</dt>
	 				<dd><?php echo $result['phone']; ?></dd>
	 			</dl>
 			</div>
 		</div>
 	</div>
 	<?php } ?>
 </div>
 
 <div class="buttons" style=" margin-bottom:10px;">
  	<a href="<?php echo $insert; ?>" class="btn"><span><?php echo $button_new_address; ?></span></a>
 </div>
 
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>