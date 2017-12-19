<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?> - <a style="text-decoration: none;" href="<?php echo $newslink; ?>"><?php echo $text_headlines; ?></a></div>
   <div class="box-content">
       <?php foreach ($news as $news_story) { ?>
      <div style="display: inline-block; width: 30%; padding: 5px; vertical-align: top;">
	  <a style="font-weight: bold; text-decoration: none;" href="<?php echo $news_story['href']; ?>"><?php echo $news_story['title']; ?></a><br />
      <span style="color: #444; font-size: 12px;"><?php echo $news_story['short_description2']; ?>...</span> <a class="button" href="<?php echo $news_story['href']; ?>"><span><?php echo $text_read_more; ?></span></a> <br />
	  <?php if ($news_story['acom']) { ?>
	  <span style="font-style: italic; color: #777;"><?php echo $news_story['total_comments']; ?> <?php echo $text_comments; ?></span>
      <?php } ?></div>
    <?php } ?>
	
</div>
</div>

