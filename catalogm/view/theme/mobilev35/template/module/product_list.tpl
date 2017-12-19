<div class="module" id="m-foods">
    <ul class="foods bg-body">
<?php 
$count=count($products);
foreach ($products as $index => $product) {  ?>
        <li>
        <?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>" class="img-wrapper pull-left"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a>
 <?php } ?>
            <div class="content">
                <div class="title fz-16 text-overflow"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></div>
                 <div class="tag hidden"> 
		      	   <?php if($product['icons']) {?>
                      <?php foreach($product['icons'] as $icon) {?>
                          <span class="icon fz-12  icon-message-tag"><?php echo $icon['tag'];?></span>
                      <?php }  ?>
                  <?php }?>
		      </div>
                <div class="intro col-gray fz-12 text-overflow"><?php echo $product['subtitle']; ?></div>
                <div class="activity">
                <?php
                $code=EnumPromotionTypes::clearCode($product['promotion']['promotion_code']);?>
                 <?php if ( $code==(EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
                    <i class="icon-word miao"></i>
                       <?php }?>
                     <?php if ( $product['combine'] ) { ?>
                    <i class="icon-word tao"></i>
                       <?php }?>
                    <?php if ( $code==(EnumPromotionTypes::PROMOTION_SPECIAL)) { ?>
                    <i class="icon-word te"></i>
                    <?php }?>
                     <?php if ( $code==(EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
                    <i class="icon-word xian"></i>
                       <?php }?>
                     <?php if ( $code==(EnumPromotionTypes::REGISTER_DONATION)) { ?>
                    <i class="icon-word shou"></i>
                       <?php }?>
                </div><?php if ($product['price']) { ?>
                <div class="prices">
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>
                    <span class="price fz-18 col-red"><?php echo $product['promotion']['promotion_price']; ?>&nbsp;&nbsp;</span>
                    <span class="price fz-12 text-delete"><?php echo $product['price'];?></span>
                     <?php } else { ?>
                      <span class="price fz-16 col-red"><?php echo $product['price'];?></span>
                      <?php } ?>
                </div>
                 <?php } ?>
                <div class="add-cart"><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img round btn-add-cart in-list" ></span></div>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>