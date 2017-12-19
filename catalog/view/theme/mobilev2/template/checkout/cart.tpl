<?php echo $header; ?>
<div id="header" class="bar bar-header bar-positive">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
<?php if ($attention) { ?>
    <div class="attention"><?php echo $attention; ?></div>
<?php } ?>    
<?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
    
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
      <div class="cart-info">

		  <div class="list">
			  <div class="item item-title">
				  <div class="ix-row">
					  <div class="ix-u-sm-6">
						 <?php echo $column_name; ?>
					  </div>
					  <div class="ix-u-sm-3">
						 <?php echo $column_quantity; ?>
					  </div>
					  <div class="ix-u-sm-2">
						 <?php echo $column_remove; ?>
					  </div>
				  </div>
			  </div>
			  <?php foreach($groups as $key => $products) {?>
			  <?php foreach ($products as $product) { ?>
			  <div class="item f12" data-id="<?php echo $product['product_id']; ?>">
				  <div class="ix-row">
					  <div class="ix-u-sm-6">
						  <div class="image fl mr10">
							  <?php if ($product['thumb']) { ?>
							  <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
								  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
							  </a>
							  <?php } ?>
						  </div>
						  <div class="item-product-detail ">
							  <?php echo $product['name']; ?>
							  <br />

							  <?php if (!$product['stock']) { ?>
    							  <span class="stock">***</span><br />
    							  <?php } ?>
    							  <?php if(!empty($product['promotion']['promotion_code'])){?>
                                  <span class="label"><?php echo EnumPromotionTypes::getPromotionType($product['promotion']['promotion_code']);?></span>
							  <?php } ?>
							  <div>
								  <?php foreach ($product['option'] as $option) { ?>
								  - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
								  <?php } ?>
							  </div>
							  <?php if ($this->config->get('config_reward_status') &&  $product['points']) { ?>
							  <small><?php echo $product['points']; ?></small>
							  <?php } ?>
    							  <?php if(empty($product['promotion']['promotion_price'])){?>
    							      <span class="f14 mt10 price-new"><?php echo $product['price']; ?></span>
                                  <?php }else {?>
                                      <span class="f14 mt10 price-new"><?php echo $product['promotion']['promotion_price'];?></span>
                                       <br />
                                      <span class="f14 mt10 price-old">原价<?php echo $product['price']; ?></span>
                                                                       
                                  <?php }?>
							  <br />
						  </div>
					  </div>
    					  <div class="ix-u-sm-3 quantity tc">
    					  <?php  if($product['promotion']['promotion_code'] == EnumPromotionTypes::TOTAL_DONATION ||
                                    $product['promotion']['promotion_code'] == EnumPromotionTypes::REGISTER_DONATION || 
                                    $product['promotion']['promotion_code'] == EnumPromotionTypes::ZERO_BUY ||
                                    $product['promotion']['promotion_code'] == EnumPromotionTypes::EXCHANGE_BUY
                                   ) { ?>
					  1
                 <?php }else { ?>
                  <input class='btn' style="width:36px;" type="button" value=" - " <?php if($product['quantity'] <= 1) {?>disabled<?php }?> onclick="minus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
	              <input style="width:25px;padding:0 2px 0 2px;height:28px;line-height:18px;" type="text" onchange="$('#basket').submit();" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="2" />
	              <input class='btn' style="width:36px;" type="button" value=" + " onclick="plus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_plus; ?>" title="<?php echo $text_plus; ?>">
              <?php } ?>
              </div>
					  <div class="ix-u-sm-2 remove tc">
						  <a class="fr" href="<?php echo $product['remove']; ?>" title="<?php echo $text_remove; ?>">
							  <img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
						  </a>
					  </div>
				  </div>

			  </div>
			  <?php }  ?>
			  <?php }  ?>
			  <?php if($exchange_buy){?>
              <div class="exchange_buy">
                  &nbsp;&nbsp;<b><?php echo $exchange_buy['text']?></b>
                  &nbsp;
                  <a id="exchange_buy" href="<?php echo $exchange_buy['href']; ?>">
                      <br/>
                      <span class="link"><?php echo $exchange_buy['exchange_buy_btn'];?></span>
                  </a>
              </div>
	          <?php }?>
		  </div>


		  <!-- div class="pick-date-select clearfix mb10">
			  <div class="ix-row">
				  <div class="ix-u-sm-5"> 配送时间选择：</div>
				  <div class="ix-u-sm-7">
					  <select style="width:100%;" name="date" thevalue="<?php echo $select_date; ?>" data-init-val="<?php echo $select_date; ?>" onchange="$('#basket').submit();">
						  <?php foreach($dates as $index => $result) {?>
						  <option value="<?php echo $result; ?>"<?php if($select_date==$result) echo " selected"; ?> ><?php echo $result; ?></option>
						  <?php } ?>
					  </select>
				  </div>
			  </div>
		  </div-->
      </div>
   
    <div class="cart_submit card">
    	
    	<div class="cart-total tr">
    		<ul>
    			<?php foreach ($totals as $total) { ?>
                    <li class="ilex total">
						<span class="zebiaoti"><?php echo $total['title']; ?></span>
						<span class="zonge"><?php echo $total['text']; ?></span>
					</li>
                <?php } ?>
             </ul>
    	</div>
    	
    	<div class="submit">
    		<a href="<?php echo $checkout; ?>" title="<?php echo $button_checkout; ?>" class="button button-block button-positive"><label><?php echo $button_checkout; ?></label></a>
    	</div>
<!--    	<a class="clear_cart" onclick="$('#basket').attr('action', '<?php echo $remove; ?>');$('#basket').submit();"><img src="catalog/view/theme/default/image/remove.png" alt="删除选中的商品" title="删除选中的商品">删除选中的商品</a>-->
    </div>
     </form>
</div>


<?php echo $footer; ?>
<script type="text/javascript"><!--
$('.cart-module .cart-heading').bind('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}
		
	$(this).parent().find('.cart-content').slideToggle('slow');
});

function plus(name){
	var number=parseInt($('.cart-info input[name=\''+name+'\']').val())+ 1
	$('.cart-info input[name=\''+name+'\']').val(number);

	$('#basket').submit();
}

function minus(name){
	var number=parseInt($('.cart-info input[name=\''+name+'\']').val())- 1
	$('.cart-info input[name=\''+name+'\']').val(number);

	$('#basket').submit();
}
//--></script> 
<?php echo $footer; ?>