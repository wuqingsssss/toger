<?php echo $header;?>

<div id="mostviewed" class="box">
  <div class="box-heading"><h2><?php echo $heading_title; ?></h2></div>
  <div class="box-content">
  <?php echo $this->getChild('vote/product/filter');?>
  <div class="pagination cart">
    <a href="<?php echo $url;?>" class="btn"><?php echo $text_change_page;?></a>
  </div>
  <div class="clear"></div>


  	<div class="product-grid">
    <?php foreach ($products as $product) { ?>
    <div class="product">
      <?php if ($product['thumb']) { ?>
      <div class="image">
	      <img src="<?php echo $product['thumb']; ?>"  alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
	      <!-- BEGIN Product Icons -->
	      <?php if($product['icons']) {?>
	      <div class="lables clearfix">
	      <?php foreach($product['icons'] as $icon) {?>
	      	 <span class="icon icon_<?php echo $this->config->get('config_language_id')?>_<?php echo $icon; ?>"></span>
	      	 <?php } ?>
	      </div>
	      <?php } ?>
	      <!-- END Product Icons -->
      </div>
      <?php } ?>
      <div class="name">
      	<strong><?php echo $product['name']; ?></strong>
      </div>
      <div class="description"><?php echo $product['description']; ?></div>
      <div class="subtitle"><?php echo $product['subtitle']; ?></div>
      <div class="unit"><?php echo $text_unit; ?><?php echo $product['unit']; ?></div>
      <div class="origin"><?php echo $text_origin; ?><?php echo $product['origin']; ?></div>
      <div class="vote">
	      <span class="voteButton <?php if($product['voted']) {?>selected<?php } ?>">
	        <a rel="nofollow" onclick="voteResult('<?php echo $product['product_id']; ?>','good');$(this).parent('.voteButton').addClass('selected');" class="btn_heart" title="">
	          <span id="vote_good"><span id="vote_good_num<?php echo $product['product_id']?>"><?php echo $product['voted_good_num']; ?></span></span>
	        </a>
	      </span>
      </div>
    </div>
    	
<?php } ?>
</div>
</div>
</div>
<?php echo $footer;?>
<script type="text/javascript">
function voteResult(product_id,result){
	$.ajax({
		url: 'index.php?route=vote/product/dovote',
		type: 'POST',
		dataType: 'json',
		data:'product_id=' + product_id+'&voteResult='+result,
		success: function(data) {
			if(data.success=='1'){
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
</script>
