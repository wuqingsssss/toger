<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/cart.css" rel="stylesheet"/>
<!-- 公共头结束 -->
<div class="module" id="m-foods">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
        <ul class="foods bg-body">
						<?php foreach($groups as $key => $products) {?>
							<?php foreach ($products as $product) { ?>
            <li data-id="<?php echo $product['product_id']; ?>">
                <a class="checkbox pull-left"><i class="icon icon-food-checkbox"></i></a>
								<?php if ($product['thumb']) { ?>
                <a href="<?php echo $product['href']; ?>" class="img-wrapper pull-left">
										<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/>
								</a>
								<?php } ?>
                <a class="delete pull-right" href="<?php echo $product['remove']; ?>"><i class="icon icon-food-delete"></i></a>

                <div class="content">
                    <div class="title fz-16 text-overflow"><?php echo $product['name']; ?></div>
                    <div class="activity">
                        <i class="icon-word miao"></i>
                        <i class="icon-word tao"></i>
                        <i class="icon-word te"></i>
                        <i class="icon-word xian"></i>
                        <i class="icon-word shou"></i>
                    </div>
                    <div class="prices">
												<?php if(empty($product['promotion']['promotion_price'])){?>
												<span class="price fz-18 col-red"><?php echo $product['price']; ?>&nbsp;&nbsp;</span>
												<?php }else {?>
														<span class="price fz-18 col-red"><?php echo $product['promotion']['promotion_price'];?>&nbsp;&nbsp;</span>
														<span class="price fz-12 text-delete"><?php echo $product['price']; ?></span>
												<?php }?>
                    </div>
                </div>
                <div class="food-num text-center">
										<?php  
										if($product['promotion']['promotion_code'] == EnumPromotionTypes::TOTAL_DONATION ||
												$product['promotion']['promotion_code'] == EnumPromotionTypes::REGISTER_DONATION || 
												$product['promotion']['promotion_code'] == EnumPromotionTypes::ZERO_BUY ||
												$product['promotion']['promotion_code'] == EnumPromotionTypes::EXCHANGE_BUY
											 ) { 
											echo 1;
										}else { ?>
										<i class="icon icon-subtract pull-left"></i>
											<input class="input-checked" type="hidden" name="{food_id}[checked]"/>
											<?php if(empty($product['promotion']['promotion_price'])){?>
													<input class="input-unit-price" type="hidden" name="{food_id}[unit_price]" 
																 value="<?php echo $product['price_value']; ?>"/>
											<?php }else {?>
													<input class="input-unit-price" type="hidden" name="{food_id}[unit_price]" 
																 value="<?php echo $product['promotion']['promotion_value'];?>"/>
											<?php }?>
											<input class="input-num" type="hidden" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>"/>
											<span class="col-red fz-15 food-num-val"><?php echo $product['quantity']; ?></span>
											<i class="icon icon-add pull-right" ></i>
										<?php } ?>
                </div>
            </li>
							<?php }  ?>
						<?php }  ?>
        </ul>
        <div class="submit-wrapper bg-body">
            <div class="submit bg-white">
                <!--<input type="submit" value="去结算" class="btn btn-fixed-submit pull-right"/>-->
								<a href="<?php echo $checkout; ?>" class="btn btn-fixed-submit pull-right">去结算</a>
                <!--<a class="check-all"><i class="icon icon-food-checkbox"></i></a>-->
								<?php foreach ($totals as $total) { ?>
                <span class="fz-12 col-red">商品总价:&nbsp;&nbsp;</span>
                <span class="fz-15 col-red">￥</span>
                <span class="fz-15 col-red total-price"><?php echo $total['value']; ?></span>
								<?php } ?>
            </div>
        </div>
    </form>
</div>
<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar');?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/cart.js"></script>