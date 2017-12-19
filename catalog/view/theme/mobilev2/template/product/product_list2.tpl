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
      <?php if(! $product['donation']) {?>
       <div class="quantity"><?php echo $text_qty; ?>
       <input class="down" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','down','1','')" value="-">
       <input type="text" id="product_<?php echo $product['product_id']; ?>_quantity" value="1" class="addcart">
       <input class="up" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','up','1','')" value="+">
       </div>
      <?php } ?>
      <div class="cart">
        <a rel="nofollow" onclick="shopcart_addToCart('<?php echo $product['product_id']; ?>');" class="btn">
          <span><?php echo $button_cart; ?></span>
        </a>
      </div>
      <div class="wishlist">
        <a rel="nofollow" onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="btn">
          <span><?php echo $button_wishlist; ?></span>
        </a>
      </div>
    </div>
<?php } ?>