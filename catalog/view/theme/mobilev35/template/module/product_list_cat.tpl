<div class="classify_con">
		<div class="classify_con_l fl">
			<div class="classify_l_nav">
			<?php
			foreach($products as $k1=> $p)
			{
				foreach($p['cats'] as $k2=> $cats)
				{
?>
					<a href="javascript:;"<?php if($cat_id==$cats['category_id']) {echo ' class="on"';}?>><?php echo $cats['name']?></a>
		   <?php }
		   }?>
			</div>
		</div>
		<!--右边-->
		<div class="classify_con_rdiv fr">
		<?php
		  	foreach($products as $k1=> $p)
			{ $first=true;
				foreach($p['cats'] as $k2=> $cats)
				{
							
?>
			<div class="classify_con_r fr<?php if (!$first) echo ' hidden';$first=false;?>">
				<ul>
					<li>
							<div class="c_column_title"><?php echo $cats['name']?></div>
					<?php 
					foreach($cats['goods'] as $k3=> $product)
				{
                      ?>
						<div class="c_column_dish">
							<a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo HTTP_CATALOG.$tplpath;?>images/dish_name.png" class="c_dish_img fl"/>
							</a><div class="fl c_dish_information">
								<span class="c_dish_name fl"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']?></a></span>
								<div class="c_dish_introduction">
									<div class="c_dish_pointer fl">
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
                     <?php if ( $code==(EnumPromotionTypes::PROMOTION_SPECIAL)) { ?>
                    <i class="icon-word xian"></i>
                       <?php }?>
                     <?php if ( $code==(EnumPromotionTypes::REGISTER_DONATION)) { ?>
                    <i class="icon-word shou"></i>
                       <?php }?>	
									</div>
									<?php if ($product['price']) { ?>
									<div class="c_dish_price fl">
						
					<?php if (!empty($product['promotion']['promotion_code'])) { ?>
                    <span class="c_current_price fl"><?php echo $product['promotion']['promotion_price']; ?>&nbsp;&nbsp;</span>
                    <span class="c_original_price"><?php echo $product['price'];?></span>
                    <?php } else { ?>
                    <span class="c_current_price fl"><?php echo $product['price'];?></span>
                      <?php } ?>
										
									</div>
									 <?php } ?>
								</div>
								<div class="c_dish_cart fr">
								<span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img round btn-add-cart in-list" ></span>
								</div>
							</div>
						</div>
						<?php } ?>
					</li>
				</ul>
			</div>
				   <?php }
		   } ?>
		</div>
	</div>