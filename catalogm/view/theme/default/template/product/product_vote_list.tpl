<?php echo $header;?>
<style>
<!--

#vote_bad{
	margin-left:10px;
}
-->
</style>

<div id="mostviewed" class="box">
  <div class="box-heading"><h2><?php echo $heading_title; ?></h2></div>
  <div class="box-content">
  	<div class="product-grid">
<?php foreach ($products as $product) { ?>
    <div class="product">
      <?php if ($product['thumb']) { ?>
      <div class="image">
      <a href="<?php echo $product['href']; ?>" class="poshytip" title="<?php echo $product['description']; ?>">
      
      <img src="<?php echo $product['thumb']; ?>"  alt="<?php echo $product['name']; ?>" />
      <?php if($product['icons']) {?>
      <div class="lables clearfix">
      <?php foreach($product['icons'] as $icon) {?>
      	 <span class="icon icon_<?php echo $this->config->get('config_language_id')?>_<?php echo $icon; ?>"></span>
      	 <?php } ?>
      </div>
	  <?php }  ?>
	  
      </a></div>
      <?php } ?>
      <div class="name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></div>
      <div class="description"><?php echo $product['description']; ?></div>
      <div class="subtitle"><?php echo $product['subtitle']; ?></div>
      <div class="unit"><?php echo $text_unit; ?><?php echo $product['unit']; ?></div>
      <div class="origin"><?php echo $text_origin; ?><?php echo $product['origin']; ?></div>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
        <?php } else { ?>
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
        <?php } ?>
        <?php if ($product['tax']) { ?>
        <br />
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <?php } ?>
      </div>
      <?php } ?>
       <div class="quantity">
       <span id="vote_good"><?php echo $button_vote_good; ?>:<span id="vote_good_num<?php echo $product['product_id']?>"><?php echo $product['voted_good_num']; ?></span></span>
       </div>
      <div class="cart">
        <a rel="nofollow" onclick="voteResult('<?php echo $product['product_id']; ?>','good');" class="btn">
          <span><?php echo $button_vote_good; ?></span>
        </a>
      </div>
    </div>
    	
<?php } ?>
 <div class="pagination"><?php echo $pagination; ?></div>
 </div>
  </div>
</div>

<script type="text/javascript">
<!--
function voteResult(product_id,result)
{
	$.ajax({
		url: 'index.php?route=vote/vote/dovote',
		type: 'POST',
		dataType: 'json',
		data:'product_id=' + product_id+'&voteResult='+result,
		success: function(data) {
			if(data.success=='1')
			{
				$('#vote_good_num'+data.productId).text(data.voteGoodNum);
			}else{
				alert(data.error);
			}
		},
		error:function (XMLHttpRequest, textStatus, errorThrown){ 
	        alert(errorThrown);
	    },
	});
}

//--></script>
