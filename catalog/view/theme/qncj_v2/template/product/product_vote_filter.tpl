<form id="search-filter-form" action="<?php echo $filter; ?>" method="post">
<div class="product-filter">
    <dl class="order">
	    <dd>
	    	<a id="voteQuantity" data-value="voted_good_num" rel="nofollow" class="icon asc">
	    		<b>查看最受欢迎的下周菜品</b>
	    	</a>
	   </dd>
	   
    </dl>
  </div>
	<input type="hidden" id="sort" name="sort" />
	<input type="hidden" id="order" name="order" />
 </form>
<script type="text/javascript">
$(document).ready(function(){
	var order = '<?php echo $order; ?>';
	$('#sort').val('voted_good_num');
	if(order!=null&&order!=''){
		if(order=='DESC')
		{
				$('#order').val('ASC');
				$('#voteQuantity').removeClass('asc')
				$('#voteQuantity').addClass('desc')
		}else{
				$('#order').val('DESC');
				$('#voteQuantity').removeClass('desc')
				$('#voteQuantity').addClass('asc')
		}
	}
	$('#voteQuantity').attr('href',"<?php echo $filter."&sort=";?>"+$('#sort').val());
});
</script>
 