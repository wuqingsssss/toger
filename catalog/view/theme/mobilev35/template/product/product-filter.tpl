<form id="search-filter-form">
<div class="product-filter">
<!--  
    <div class="display"><b>显示方式：</b> 
	    <span class="icon grid active"><b></b>图片</span> 
	    <span class="icon list"><b></b>列表</span>
    </div>
-->   
    <dl class="order">
    	<dt>排序：</dt>
	    <dd><a data-value="p.price" rel="nofollow" class="icon asc"><b></b>价格</a></dd>
	    <dd><a data-value="p.sale_num" class="icon asc"><b></b>销量</a></dd>
    </dl>
  </div>
  	<input type="hidden" id="path" name="path" value="<?php echo $path; ?>" />
	<input type="hidden" id="filter_manufacturer_id" name="filter_manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
	<input type="hidden" id="filter_search_type" name="filter_search_type" value="<?php echo $filter_search_type; ?>" />
	<input type="hidden" id="filter_keyword" name="filter_keyword" value="<?php echo $filter_keyword; ?>" />
	<input type="hidden" id="sort" name="sort" value="<?php echo $sort; ?>" />
	<input type="hidden" id="order" name="order" value="<?php echo $order; ?>" />
 </form>
<script type="text/javascript"><!--
function do_search(){
	$.ajax({
		url: '<?php echo $filter; ?>',
		type: 'post',
		data: $('#search-filter-form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#filter_contents').append('<div class="loading"><span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span></div>');
			$('#filter_contents .product-grid').hide();
		},	
		complete: function() {
			$('#filter_contents .loading').remove();
		},			
		success: function(json) {
			if (json['success']) {
				$('#filter_contents').html(json['output']);
			}	
		}
	});	
}

$('.product-filter .order a').bind('click',function(event){
	event.preventDefault();
	
	$('#sort').val($(this).attr('data-value'));

	if($(this).hasClass('desc')){
		$('#order').val('ASC');
		
		$(this).removeClass('desc')
		$(this).addClass('asc')
    }else{
    	$('#order').val('DESC');
    	$(this).removeClass('asc')
		$(this).addClass('desc')
   }
	$('.order dd').removeClass('curr');
	$(this).parent('dd').addClass('curr')
    
	do_search();
 });

--></script>
 