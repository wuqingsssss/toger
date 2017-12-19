<div id="search">
	<div id="i-search">
		<input type="hidden" name="filter_search_type" id="filter_search_type" value="name" value=""/>
		<?php if(isset($filter_keyword) && $filter_keyword){?>
				<input id="key" type="text" name="filter_name" value=""<?php echo $filter_keyword; ?>" /> 
		<?php }else{?>
			<input id="key" type="text" name="filter_name" placeholder="产品搜索..." value="" /> 
		
		<?php }?>
	</div>
	<a id="btn-search"><span>搜索</span></a>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	function filter_search_begin(){
		url = 'index.php?route=common/home&sequence=<?php echo $sequence;?>';  // 修改成自己需要使用到的搜索链接
		 
		var filter_keyword = $('input[name=\'filter_name\']').attr('value')
		
		if (filter_keyword) {
				
		

		}else{
			alert('请输入关键字后再进行搜索');
			$('input[name=\'filter_name\']').focus();
			return;
		}
		get_product_home(<?php echo $sequence;?>,0,encodeURIComponent(filter_keyword));
		
		
	}


	$('#btn-search').bind('click', function() {
		filter_search_begin();
	});

	$('#search input[name=\'filter_name\']').keydown(function(e) {
		if (e.keyCode == 13) {
			filter_search_begin();
		}
	});
});
--></script>