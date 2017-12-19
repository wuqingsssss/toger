<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/allsort.css" />
  <div class="w" id="allsort">
  		<?php foreach($categories as $category) {?>
		<div class="fl">
			<!--图书-->
			<div class="m border-box">
				<div class="mt">
					<h2>
						<a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
					</h2>
				</div>
				<div class="mc">
					<?php if($category['children']) { foreach($category['children'] as $index => $child) {?>
					<dl <?php if($index==0) {?>class="fore"<?php } ?>>
						<dt><a href="<?php echo $child['href']; ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name'];?></a></dt>
						<dd> 
						<?php if($child['children']) { foreach($child['children'] as $index => $subchild) {?>
						<em><a href="<?php echo $subchild['href']; ?>" title="<?php echo $subchild['name']; ?>"><?php echo $subchild['name'];?></a></em>  
						<?php }} ?>
						</dd>
					</dl>
					<?php }} ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<span class="clr"></span>
	</div>
  
  
 <?php echo $content_bottom; ?>
 </div>
<?php echo $footer; ?>