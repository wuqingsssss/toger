<?php 
$count=count($products);
foreach ($products as $index => $product) {  ?>
<div class="row">
	<div class="col">
		<div class="product">
		      <?php if ($product['thumb']) { ?>
		      <div class="image">
		      <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
		      <img src="<?php echo $product['thumb']; ?>" id="pdt_img<?php echo $product['product_id']; ?>"  alt="<?php echo $product['name']; ?>" />
		      </a></div>
		      <?php } ?>
		      <div class="name">
	          <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
		      </div>
		      <div class="tag"> 
		      	   <?php if($product['icons']) {?>
                      <?php foreach($product['icons'] as $icon) {?>
                          <span class="icon icon-message-tag"><?php echo $icon['tag'];?></span>
                      <?php }  ?>
                  <?php }?>
		      </div>
		      <div class="subtitle"><?php echo $product['subtitle']; ?></div>

			<?php if ($product['price']) { ?>
		      <div class="price fl">
		          <?php if (empty($product['promotion']['promotion_code'])) { ?>
		              <span class="price-new"><?php echo $product['price']; ?></span>
		        <?php } else { ?>
		              <span class="price-new"><?php echo $product['promotion']['promotion_price']; ?></span>
		              <span class="price-old"><?php echo "原价".$product['price']; ?></span>		              
		        <?php } ?>
		        <?php if ($product['tax']) { ?>
		        <br />
		        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
		        <?php } ?>
		      </div>
		      <?php } ?>
				<div class="cart-info fr">
				    <div class="cart">
				 
                        <a rel="nofollow" onclick="addToCart('<?php echo $product['product_id']; ?>'<?php if($filter_category_id==1) { ?>,'<?php echo EnumPromotionTypes::ZERO_BUY; ?>'<?php }?>);" class="btn">
                        <span><?php echo $button_cart; ?></span>
                        </a>
					</div>
				</div>

		    </div>


		</div>
</div>
<?php } ?>