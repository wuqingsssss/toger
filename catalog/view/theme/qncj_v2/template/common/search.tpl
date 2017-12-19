<style>
<!--
#search #key{
    margin-left:135px !important;
    margin-top:-30px !important;
    width: 280px !important;
}

#i-search .search-type{
	width: 120px !important;
}
-->
</style>
<div id="search">
	<div id="i-search">
		<div class="search-type in showmenu">
			<a>全部</a>
			<ul class="nav menu">
				<li id="first" data-id=""><span>全部</span></li>
				<?php foreach ($categories as $category) { ?>
					<li data-id="<?php echo $category['category_id']?>"><span><?php echo $category['name'] ?></span></li>
				<?php }?>
			</ul>
		</div>
		<input type="hidden" name="filter_search_type" id="filter_search_type" value="name" value=""/>
		<?php if(isset($filter_keyword)){?>
				<input id="key" type="text" name="filter_name" value=""<?php echo $filter_keyword; ?>" /> 
		<?php }else{?>
			<input id="key" type="text" name="filter_name" placeholder="<?php echo $text_search; ?>" value="" /> 
		
		<?php }?>
	</div>
	<input type="button" class="button-search" id="btn-search" value="" /><br />
	<?php echo $hotword; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
	var search = '<?php echo $searchFlag;?>';
	if(search=='1')
	{
		$('#search .showmenu a').html('<?php echo $category_name; ?>');
		$('#search #key').val('<?php echo $filter_keyword; ?>');
		$('#filter_search_type').val('<?php echo $category_id; ?>');
	}
	
	// 定义搜索范围
	$('#search .showmenu a').click(function(){
		$('#search .showmenu .menu').show();
		
		$('#search .showmenu .menu').mouseleave(function(){  $('#search .showmenu .menu').hide(); });
	});

	$('#search .showmenu .menu li').click(function(){
		$('#search .showmenu a').html($(this).find('span').html());
		
		$('#filter_search_type').val($(this).attr('data-id'));
	});


	function filter_search_begin(){
		url = 'index.php?route=common/search/dosearch';  // 修改成自己需要使用到的搜索链接
		 
		var filter_keyword = $('input[name=\'filter_name\']').attr('value')
		
		if (filter_keyword) {
			url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
		}else{
			alert('请输入关键字后再进行搜索');
			$('input[name=\'filter_name\']').focus();
			return;
		}
		
		var filter_category_id = $('input[name=\'filter_search_type\']').attr('value')
		
		if (filter_category_id&&filter_category_id!='') {
			url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
		}
		
		location = url;
	}


	$('.button-search').bind('click', function() {
		filter_search_begin();
	});

	$('#search input[name=\'filter_name\']').keydown(function(e) {
		if (e.keyCode == 13) {
			filter_search_begin();
		}
	});

	//绑定默认选项
	<?php if($filter_search_type) {?>
	$('#search .showmenu .menu li[data-id="<?php echo $filter_search_type;?>"]').click();
	<?php } ?>
});
</script>