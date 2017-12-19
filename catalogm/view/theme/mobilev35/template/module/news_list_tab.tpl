<?php if($articles) {?>
<div class="article-list">
	<div class="grid_8">
		<?php foreach($articles as $result) {?>
		<div id="tab-<?php echo $result['article_id']; ?>">
			<div class="tab_content">
				<h2><?php echo $result['name']; ?></h2>
				<?php if($result['thumb']) {?>
				<div class="image"><img src="<?php echo $result['thumb']; ?>" alt="" /></div>
				<?php } ?>
				<div class="description">
				<?php echo $result['description']; ?>
				<div class="link"><a href="<?php echo $result['href']; ?>" class="button">更多详情</a></div>
				</div>
				
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="grid_4">
		<div id="tabs" class="vtabs">
			<?php foreach($articles as $result) {?>
			<a href="#tab-<?php echo $result['article_id']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<?php } ?>

