<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" ><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<div class="article">
  
  <h1><?php echo $title; ?></h1>
<!--  <div class="release"><?php echo $date_added;?></div>-->
  <div class="content">
  	<?php if(isset($image) && $image) {?>
  	<img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="img" />
  	<?php } ?>
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
 <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
