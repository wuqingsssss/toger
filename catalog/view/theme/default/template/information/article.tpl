<div class="article">
  <div class="release">
  	<?php echo $date_added;?>
  </div>
  <h1><?php echo $title; ?></h1>

  <div class="content">
  	<?php echo $content; ?>
  	
  	<div class="download">
  	 <?php if(isset($downloads)&&$downloads) { ?>
  	 <dl>
  	 <?php foreach ($downloads as $download) { ?>
  	 	<dd>
  	 	<a href="<?php echo $download['href']; ?>" title="<?php echo $download['name']; ?>">
  	 	<?php echo $download['name']; ?> - <?php echo $download['size']; ?>
  	 	</a>
  	 	</dd>
  	 <?php }?>
  	 </dl>
  	 <?php } ?>
  	</div>
  	
  	<?php if(isset($article)) { ?>
  	<div class="related article-list">
  		<b><?php echo $text_relate_article; ?></b>
  		<ul>
  			<?php foreach ($article as $record) { ?>
			<li>
			<span class="date"><?php  echo $record['date_added']; ?></span>
			<a href="<?php echo $record['href']; ?>" title="<?php echo $record['title']; ?>"><span class="title"><?php echo $record['title']; ?></span></a>
			</li>
  			<?php } ?>
  		</ul>
  	</div>
  	<?php } ?>
  </div>
</div>
