<?php $products=getCategoryLatestProducts($category_id,$limit); ?>
                      <ul class="style2">
                      	<?php foreach($products as $index => $result) {?>
                      	 <li class="fore<?php echo ++$index;?>">
                              <div class="img_u1">
                              <a target="_blank" href="<?php echo $result['href']; ?>">
                              <img src="<?php echo $result['thumb']; ?>" alt="" />
                              </a>
                              </div>
                              <p class="name">
                              <a target="_blank" href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>">
                              	<?php echo $result['name']; ?>
                              </a>
                              </p>
                             <?php if($result['special']) {?>
                             	<p class="price"><?php echo $this->currency->format($result['special']); ?><span><?php echo $this->currency->format($result['price']); ?></span></p>
                             <?php } else {?>
	                             <p class="price">
	                              <?php if($result['price'] > 0) {?><?php echo $this->currency->format($result['price']); ?><?php } else {?>
	                             <?php echo $text_enquiry_price; ?>
	                             <?php } ?>
	                             <span></span>
	                             </p>
                             <?php } ?>
                            </li>
                            <?php } ?>
                      </ul>