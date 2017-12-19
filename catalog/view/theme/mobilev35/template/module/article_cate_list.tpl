<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
   <?php if($categories) {?>
   <ul style="padding-left: 3px;list-style: none outside none;">
      <?php foreach ($categories as $category) { ?>
     	 <li style="margin-bottom:10px;"><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>(<?php echo  $category['count'];?>)
     	 	 <?php if($category['article']) {?>
     	 	<ul style="padding-left: 10px;list-style: none outside none;">
     	 		<?php foreach ($category['article'] as $article) { ?>
     	 			 <li style="margin-bottom:2px;"><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a>
     	 			 </li>
     			 <?php } ?>
     	 	</ul>
     	 	<?php } ?>
     	 </li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
</div>