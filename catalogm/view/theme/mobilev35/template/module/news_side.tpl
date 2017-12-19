<div id="aside-news">
<div class="box">
   <div class="box-heading"><span><?php echo $heading_title; ?></span></div>
   <ul class="box-content">
       <?php foreach ($news as $news_story) { ?>
       <li><a href="<?php echo $news_story['href']; ?>"><?php echo $news_story['title']; ?></a></li>
       <?php } ?>
	</ul>
</div>
</div>

