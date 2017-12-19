<?php if($products){?><div class="module with-bottom" id="m-today">
    <div class="text-center fz-18">今日推荐</div>
    <div class="banner banner-default with-dot-border static" id="today-banner">
        <ul>
        <?php
        
         foreach ($products as  $product) {  ?>
            <li>
                <div class="clearfix">
                    <a href="<?php echo $product['href']; ?>" class="img-wrapper"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a>
                    <div class="content inline-block">
                        <div class="title fz-19"><?php echo $product['name']; ?></div>                       
                  <?php if ($product['price']) { ?>     
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>
                 <div class="price col-red"><span class="fz-19"><?php echo $product['promotion']['promotion_price']; ?></span><span class="fz-14">元/份</span></div>
                     <?php } else { ?>
                     <div class="price col-red"><span class="fz-19"><?php echo $product['price'];?></span><span class="fz-14">元/份</span></div>
                      <?php } ?>
                 <?php } ?><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img btn-add-cart in-banner"></span>
                    </div>
                </div>
            </li>
             <?php } ?>          
        </ul>
    </div>
</div><?php  } ?>