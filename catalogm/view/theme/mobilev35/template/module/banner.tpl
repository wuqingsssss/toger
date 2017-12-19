<?php if(!$ajax){?>
<style>
/* common banner news */
#banner_<?php echo $banner_id; ?> .item{ 
  margin: 0 10px;
  border-bottom:1px solid #ccc;
}
#banner_<?php echo $banner_id; ?> .item:last-child{
  
  border-bottom:none;
}
</style>
<div id="banner_<?php echo $banner_id; ?>" class="banner module with-bottom<?php if($setting['class'])echo ' '.$setting['class'];?>">
<?php }?>
<?php if($banners) { ?>
  <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['image']) { ?>
  <?php if ($banner['link']) { ?>
  <div class="item"><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></div>
  <?php } else { ?>
  <div class="item"><a><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></div>
  <?php } ?>
  <?php } ?>
    <?php } ?>
    <?php } ?>
<?php if(!$ajax){?>
</div>
<script language="javascript">
$(function(){ 

	$.ajax({
		url: 'index.php?route=module/banner'
		<?php foreach($setting as $key=>$item){ ?>		
		+'&<?php echo "$key=$item";?>'<?php }?>
		,
		dataType: 'json',
		success: function(data) {
			//console.log(data);	    
            $("#banner_<?php echo $banner_id; ?>").html(data.output);
 
		}
	});	
	
	});
	   </script>
<?php }?>