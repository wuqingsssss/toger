<?php if(isset($allResults)&&!is_null($allResults)){?>
<ul class="article-list">
	<?php foreach ($allResults as $supply){?>
	<li><a href="<?php echo $supply['href']?>" title="<?php echo $supply['name']?>"><?php echo $supply['name']?></a></li>
	<?php  }?>
</ul>
<?php }else{?>
	没有记录
<?php }?>
