<div class="list_carousel responsive">
	<ul id="boss_carousel<?php echo $module; ?>">
	<?php foreach ($banners as $banner) { ?>
		<li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
	<?php } ?>
	<?php foreach ($banners as $banner) { ?>
		<li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
	<?php } ?>
	</ul>
	<a id="prev_carousel<?php echo $module; ?>" class="prev" href="#" title="prev">&lt;</a>
	<a id="next_carousel<?php echo $module; ?>" class="next" href="#" title="next">&gt;</a>
</div>

<script type="text/javascript" src="catalog/view/javascript/bossthemes/jquery.touchSwipe.min.js"></script>
<script type="text/javascript" language="javascript">
$(window).load(function(){
	$('#boss_carousel<?php echo $module; ?>').carouFredSel({
		auto: false,
		responsive: true,
		width: '100%',
		prev: '#prev_carousel<?php echo $module; ?>',
		next: '#next_carousel<?php echo $module; ?>',
		swipe: {
		onTouch : true
		},
		items: {
			//width: 'auto',
			height: 33,
			visible: {
			min: 1,
			max: 8
			}
		},
		scroll: {
			direction : 'left',    //  The direction of the transition.
			duration  : 500   //  The duration of the transition.
		}
	});
});
</script>