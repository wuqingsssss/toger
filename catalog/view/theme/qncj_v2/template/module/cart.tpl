<div id="aside_cart">
	<div class="box-heading">
		<h3>购物车</h3>
		<a href="<?php echo $checkout; ?>" title="进入购物车结算"><span class="shopcart_num">
		<?php echo $this->cart->countProducts(); ?></span></a>
	</div>
	<div class="box-content">
		<div class="aside_cart_main">
<?php if ($products || $vouchers) { ?>
<div class="aside_cart_container">
<table class="order_table">
<thead>
	<tr>
		<th class="order_table_th1">商品</th>
		<th class="order_table_th2">数量</th>
		<th class="order_table_th3">价格</th>
		<th class="order_table_th">取消</th>
	</tr>
</thead>
  <?php foreach ($products as $product) { ?>
  <tr>
    <td class="image order_table_td2">
     <?php if ($product['thumb']) { ?>
      <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
      <?php } ?>
    </td>
    <?php if(false) {?>
    <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
      <div>
        <?php foreach ($product['option'] as $option) { ?>
        - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
        <?php } ?>
      </div>
    </td>
    <?php } ?>
    
    <?php  
              $TOTAL_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::TOTAL_DONATION);
              $REGISTER_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::REGISTER_DONATION);
              $ZERO_BUY=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::ZERO_BUY);
   ?>
   <?php  if($TOTAL_DONATION || $REGISTER_DONATION || $ZERO_BUY) {?>
    <td class="quantity order_table_td1 tc">
    <span class="ft_b"><?php echo $product['quantity']; ?></span>
    </td>
   <?php } else {?>           
    <td class="quantity order_table_td1">
    <input type="button" onclick="shopcart_downToCart('<?php echo $product['key']; ?>');" value=" - " class="down" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
    <span class="ft_b"><?php echo $product['quantity']; ?></span>
<!--    <input style="min-width:10px;" type="text" name="quantity[<?php echo $product['key']; ?>]" id="product_<?php echo $product['product_id']; ?>_quantity" value="<?php echo $product['quantity']; ?>" size="3" />-->
    <input type="button" onclick="shopcart_upToCart('<?php echo $product['key']; ?>');" value=" + " class="up" title="<?php echo $text_plus; ?>">
    </td>
    <?php } ?>
    <td class="total order_table_td3"><?php echo $product['total']; ?></td>
    <td class="remove tc">
    	<img src="catalog/view/theme/default/image/close.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="removeCart('<?php echo $product['key']; ?>');" />
    </td>
  </tr>
  <?php } ?>
  <?php foreach ($vouchers as $voucher) { ?>
  <tr>
    <td class="image"></td>
    <td class="name"><?php echo $voucher['description']; ?></td>
    <td class="quantity">x&nbsp;1</td>
    <td class="total"><?php echo $voucher['amount']; ?></td>
    <td class="remove"><img src="catalog/view/theme/default/image/close.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="removeVoucher('<?php echo $voucher['key']; ?>');" /></td>
  </tr>
  <?php } ?>  
</table>
</div>
  <table class="total">
    <?php foreach ($totals as $total) { ?>
    <tr class="order_price">
      <td><?php echo $total['title']; ?>：</td>
      <td><em><?php echo $total['text']; ?></em></td>
    </tr>
    <?php } ?>
  </table>
  <div class="checkout">
    <a href="<?php echo $checkout; ?>" class="order_btn_1 button">
      <span class="invisible"><?php echo $button_checkout; ?></span>
    </a>
  </div>
<?php } else { ?>
<div class="empty"><?php echo $text_empty; ?></div>
<?php } ?>

<div style="margin:10px auto 0px; text-align:center;"><img src="image/page/erweima.jpg"></div>
	</div>
		
	</div>
	<div class="box-bottom"></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
   $(window).scroll(function() {
       var scrollVal = $(this).scrollTop();
        if ( scrollVal > 150) {
            $('#aside_cart').css({'position':'fixed','top' :'10px'});
        } else {
            $('#aside_cart').css({'position':'static','top':'auto'});
        }
    });
 });
</script>