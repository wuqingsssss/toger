<div id="bestseller">
<div class="box">
  <div class="box-heading"><span><?php echo $heading_title; ?></span></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $index => $product) { ?>
      <div class="product">
        <div class="rate"><span><?php echo $index+1; ?></span></div>
        <?php if ($product['thumb']) { ?>
        <div class="image">
          <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
          </a>
        </div>
        <?php } ?>
        <div class="name">
          <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
            <?php echo $product['name']; ?>
          </a>
        </div>
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
        </div>
        <?php } ?>
        
        <div class="cart-info">
         <div class="quantity"><?php echo $text_qty; ?>
         <input class="down" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','down','1','')" value="-">
         <input type="text" id="product_<?php echo $product['product_id']; ?>_quantity" value="1" class="addcart">
         <input class="up" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','up','1','')" value="+">
         </div>
        <div class="cart">
          <a rel="nofollow" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn">
            <span><?php echo $button_cart; ?></span>
          </a>
        </div>
      </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
</div>
