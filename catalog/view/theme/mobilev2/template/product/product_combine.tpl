<?php if(isset($groups) && $groups) {?>
<div id="product-combination">
<div class="htabs">
  <a style="display: inline;" class="selected"><?php echo $heading_title_combine; ?></a>
</div>
<div class="m recommend hide" id="recommend" style="display: inline-block; width:100%;">
<div class="mc tabcon hide" id="group" style="display: block;">
<div class="master">
<div class="p-img"><a href="<?php echo $self; ?>" title="<?php echo $heading_title; ?>" target="_blank"> <img
	src="<?php echo $thumb; ?>" width="140" height="140" alt="" /></a></div>
<div class="p-name"><?php echo $heading_title; ?></div>
<div class="icon-add"></div>
</div>
<div class="suits">
<div class="container">
<ul class="list">
<?php foreach($groups as $result) {?>
	<li>
	<div class="p-img">
	<a href="<?php echo $result['href']; ?>"title="<?php echo $result['name']; ?>" target="_blank">
	<img width="140" height="140"
		src="<?php echo $result['thumb']; ?>"
		alt="<?php echo $result['name']; ?>"></a></div>
	<div class="p-name intercept">
	<a href="<?php echo $result['href']; ?>"
		title="<?php echo $result['name']; ?>" target="_blank"><?php echo $result['name']; ?></a></div>
	<div class="choose"><input type="checkbox" price="<?php echo $result['price_amount']; ?>" value="<?php echo $result['product_id']; ?>"
		onclick="buyGCombineBuy(this)"><span class="p-price"><strong><?php echo $result['price']; ?></strong></span></div>
	</li>
<?php } ?>
</ul>
</div>
</div>

<form action="<?php echo $group_action; ?>" method="post"  id="group_add_cart">
<input type="hidden" value="<?php echo $product_id; ?>" id="product_<?php echo $product_id; ?>" name="product_ids[]" />
<?php foreach($groups as $result) {?>
<input type="hidden" value="" id="product_<?php echo $result['product_id']; ?>" name="product_ids[]" />
<?php } ?>
<div class="infos">
<!--
<div class="p-name">
	<a href="#" onclick="$('#group_add_cart').submit();return false;"><?php echo $text_combine_product; ?></a>
</div>
-->
<div class="p-title"><?php echo $text_combine_title; ?></div>
<div class="p-price" id="buy-wmeprice" value="<?php echo $group_total_number; ?>"><?php echo $text_combine_product_total; ?><strong><?php echo $group_total; ?></strong></div>

<div class="left"><a href="#" onclick="$('#group_add_cart').submit();return false;" class="button"><span><?php echo $button_combine_product_buy; ?></span></a></div>
</form>
</div>
<div class="clear"></div>
</div>
<!--group end-->
</div>
</div>
<script type="text/javascript">
function buyGCombineBuy(node){
	var product_id=node.value;
	
	if(node.checked){
		$('#product_'+product_id+'').val(product_id);

		current=parseFloat(document.getElementById('buy-wmeprice').attributes.getNamedItem("value").nodeValue)+parseFloat(node.attributes.getNamedItem('price').nodeValue);
		document.getElementById('buy-wmeprice').attributes.getNamedItem("value").nodeValue=current;
		$('#buy-wmeprice strong').load('index.php?route=product/product/currency&num='+current);
	}else{
		$('#product_'+product_id+'').val('');
		
		current=parseFloat(document.getElementById('buy-wmeprice').attributes.getNamedItem("value").nodeValue)-parseFloat(node.attributes.getNamedItem('price').nodeValue);
		document.getElementById('buy-wmeprice').attributes.getNamedItem("value").nodeValue=current;
		$('#buy-wmeprice strong').load('index.php?route=product/product/currency&num='+current);
	}
}

$(document).ready(function(){
	$('.suits .container').css('width',$('.suits .container ul').outerWidth());
});
</script>
<?php } ?>