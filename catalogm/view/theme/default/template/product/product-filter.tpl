<div class="product-filter">
    <div class="display"><b>显示方式：</b> 
	    <span class="icon grid active"><b></b>图片</span> 
	    <span class="icon list"><b></b>列表</span>
    </div>
    
    <dl class="order">
    	<dt>排序：</dt>
	    <dd><a data-value="p.price" rel="nofollow" class="icon desc"><b></b>价格</a></dd>
	    <dd class="curr"><a data-value="p.sale_num" class="icon asc"><b></b>销量</a></dd>
    </dl>
    
    
    <div class="limit"><b>显示个数</b>
      <select onchange="$('#limit').val(this.value);do_search();">
                        <option value="5">5</option>
                                <option value="16" selected="selected">16</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                      </select>
    </div>
  </div>
  
  <script type="text/javascript">
function do_search(){
	$.ajax({
		url: 'index.php?route=product/category/filter',
		type: 'post',
		data: $('#search-filter-form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#results').append('<div class="loading"><span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span></div>');
			$('#results .product-grid').hide();
		},	
		complete: function() {
			$('#results .loading').remove();
		},			
		success: function(json) {
			if (json['success']) {
				$('#results').html(json['output']);
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

</script>