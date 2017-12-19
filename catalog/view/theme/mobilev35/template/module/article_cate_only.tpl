<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
   <?php if($categories) {?>
   <ul style="padding-left: 3px;list-style: none outside none;">
      <?php foreach ($categories as $category) { ?>
     	 <li style="margin-bottom:5px;"><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>(<?php echo  $category['count'];?>)</li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
</div>