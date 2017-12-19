<div id="article_category_<?php echo $category_id; ?>" class="box">
  <div class="box-heading">
    <div style="float:right;">
      <a href="<?php echo $more; ?>" title="more" class="more">更多>></a>
    </div>
    <?php echo $heading_title; ?>
  </div>
  <div class="zhuanti-content">
   <?php if($articles) {?>
    <ul>
    <?php if($feature_status) { $article=$articles[0]; array_shift($articles);?>
    	<li class="first">
    		<div class="hot-article">
    		
    			<a href="<?php echo $article['href']; ?>" title=""><h1><?php echo $article['title']; ?></h1></a>
    			<div class="jianjie"><?php echo $article['summary']; ?> ...
    			【<a href="<?php echo $article['href']; ?>" title="" style="color: #0000ff;">查看详情</a>】</div>
    			
    		</div>
    	</li>
    <?php } ?>
      <?php foreach ($articles as $article) { ?>
      <li class="zhuanti_list">
      <span class="date"><?php echo $article['date_added']; ?></span>
      <a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a>
      </li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
</div>