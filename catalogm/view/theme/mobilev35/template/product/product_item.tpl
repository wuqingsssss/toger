<?php foreach($data as $da){?>
<div class="comment">
	<div class="info clearfix">
		<div class="img-wrapper pull-left"><img src="images/avatar.jpg"></div>
		<div class="username pull-left"><?php echo $da['uinfo']['username']?></div>
		<div class="pull-right"><?php echo $da['comment_time']?></div>
	</div>
	<div class="content"><?php echo $da['content']['content']?></div>
	<?php if(!empty($da['reply'])){?>
	<div class="content" style="color: gray">客服回复:<?php echo $da['reply']['content']?></div>
	<?php } ?>
</div>
<?php } ?>