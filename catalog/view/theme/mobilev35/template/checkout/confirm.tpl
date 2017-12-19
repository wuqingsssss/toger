<div class="order_p_list">
<?php foreach($groups as $key => $products) {?>
   <?php {?>
	    <table>
	    <tbody>
	      
	            <?php foreach ($products as $product) { ?>
		     	 <tr>
			          <th class="name" style="text-align: left;width: 60%;"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
			          <?php if(!empty($product['promotion']['promotion_code'])) {?>
                            <span class="label"><?php echo EnumPromotionTypes::getPromotionType($product['promotion']['promotion_code']);?></span>
                      <?php } ?>
			          <?php foreach ($product['option'] as $option) { ?>
			          <br />
			          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
			          <?php } ?></th>
			          
		              <th class="quantity" style="width: 10%;"><?php echo "×".$product['quantity']; ?></td>
			        
			          <th class="total" style="text-align: right;width: 22%;"><?php echo $this->currency->format($product['total']); ?></td>
		         </tr>
		      <?php } ?>
	       <?php } ?>
	   </tbody>
	  </table>
  <?php } ?>
</div>

