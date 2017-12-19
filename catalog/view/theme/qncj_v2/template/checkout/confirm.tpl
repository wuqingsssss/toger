<div class="checkout-product">
<?php foreach($groups as $key => $products) {?>
   <?php { ?>
	    <table>
		<thead>
	      <tr>
	        <td class="name"><?php echo $column_name; ?></td>
	        <!--  <td class="model"><?php echo $column_model; ?></td>-->
	         <td class="price"><?php echo $column_price; ?></td>
	         <td class="promotion_title"><?php echo $column_promotion; ?></td>
	        <td class="promotion_price"><?php echo $column_promotion_price;?></td>
	   
	        <td class="quantity"><?php echo $column_quantity; ?></td>
	       <td class="total"><?php echo $column_total; ?></td>
	      </tr>
	    </thead>
	    <tbody>
	            <?php  foreach ($products as $product) { ?>
		     	 <tr>
			        <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
			          <?php foreach ($product['option'] as $option) { ?>
			          <br />
			          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
		            <?php } ?>
		        </td>
		        <!--<td class="model"><?php echo $product['model']; ?></td>  -->
		        <?php if(!isset($product['promotion']['promotion_code'])){ ?>
                <td class="price">
                 <?php }else { ?>
                <td class="price" style="text-decoration: line-through;">
                <?php }?>
		        <?php echo $this->currency->format($product['price']); ?>
		        </td>
		        <td class="promotion_title">
                    <?php if(isset($product['promotion']['promotion_code'])) { ?>
                       <span><?php echo EnumPromotionTypes::getPromotionType($product['promotion']['promotion_code']);?></span>
                    <?php } ?>
                </td>
                <td class="promotion_price">
                  <?php if(!isset($product['promotion']['promotion_price'])){ ?>
                     <span>-</span>
                  <?php }else { ?>
                     <?php echo $this->currency->format($product['promotion']['promotion_price']);?>
			              <?php } ?>
			         </td>
		              <td class="quantity"><?php echo $product['quantity']; ?></td>
			        
			        <td class="total"><?php echo $this->currency->format($product['total']); ?></td>
		      </tr>
		      <?php } ?>
	   </tbody>
	  </table>   
	  <?php } ?>
  <?php } ?>
</div>

