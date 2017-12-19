<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/help.css" />
<div class="helpMenu">
	<h2>平台资讯</h2>
	<ul>
		<li><a href="index.php?route=information/article/category&article_category_id=4">平台快讯</a></li>
		<li><a href="index.php?route=information/article/category&article_category_id=2">行业资讯</a></li>
		<li><a href="index.php?route=information/article/category&article_category_id=1">促销新闻</a></li>
		<li><a href="index.php?route=information/article/category&article_category_id=3">常见问题</a></li>
	</ul>
<?php foreach($section as $code) {?>
	<h2><?php echo get_information_group_name($code); ?></h2>
	<?php $informations=getGroupInformationsByCode($code); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
<?php } ?>
	
	
	
	
</div>
