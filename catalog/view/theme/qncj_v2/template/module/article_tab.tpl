

<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
   <?php if($articles) {?>
    <ul>
      <?php foreach ($articles as $article) { ?>
      <li><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
</div>