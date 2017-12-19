<div class="checkout-product">
<?php foreach($groups as $key => $products) {?>
   <?php {?>
		<!-- div class="checkout-heading"><label>取菜时间：</label><b><?php echo $key; ?></b></div-->
	    <table>
		<thead>
	      <tr>
	        <td class="name"><?php echo $column_name; ?></td>
	         <td class="price"><?php echo $column_price; ?></td>
	        <td class="promotion_price"><?php echo $column_promotion_price;?></td>
	        <td class="quantity"><?php echo $column_quantity; ?></td>
<!--	       <td class="total"><?php echo $column_total; ?></td>-->
	      </tr>
	    </thead>
	    <tbody>
	      
	            <?php foreach ($products as $product) { ?>
	             <?php  
	              $TOTAL_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::TOTAL_DONATION);
	              $REGISTER_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::REGISTER_DONATION);
	              $ZERO_BUY=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::ZERO_BUY);
              ?>
		     	 <tr>
			        <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
			          <?php if(!empty($product['promotion']['promotion_code'])) {?>
                            <span class="label"><?php echo EnumPromotionTypes::getPromotionType($product['promotion']['promotion_code']);?></span>
                      <?php } ?>
			          <?php foreach ($product['option'] as $option) { ?>
			          <br />
			          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
			          <?php } ?></td>
			          <?php if(!isset($product['promotion']['promotion_price'])){?>
                          <td class="price">
                          <?php }else {?>
                          <td class="price" style="text-decoration: line-through;">
                      <?php }?>
			          <?php echo $this->currency->format($product['price']); ?>
			          </td>
		            <td class="promotion_price">
                      <?php if(empty($product['promotion']['promotion_price'])){?>
                         <span>-</span>
                      <?php }else {?>
                         <?php echo $this->currency->format($product['promotion']['promotion_price']);?>
			              <?php } ?>
			              </td>
		              <td class="quantity"><?php echo $product['quantity']; ?></td>
			        
<!--			        <td class="total"><?php echo $this->currency->format($product['total']); ?></td>-->
		      </tr>
		      <?php } ?>
	       <?php } ?>
	   </tbody>
	  </table>
  <?php } ?>
</div>

