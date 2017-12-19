<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
       <?php } ?>
    </div>

    <div class="order-status"><img src="image/data/v2/cart-step1.jpg"></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
    <div class="cart-heading-title">
      <div class="ix-row ix-row-collapse">
        <div class="ix-u-sm-6">
          <img src="image/data/v2/shopping.png" alt=""/>
          <h1 class="inline"><?php echo $heading_title; ?></h1>


          <span class="cart-tip">
            共 <?php echo $this->cart->countProducts(); ?> 件菜品
          </span>
          <?php if ($weight) { ?>
          &nbsp;(<?php echo $weight; ?>)
          <?php } ?>
        </div>
        <!-- div class="ix-u-sm-6">
          <div class="pick-date-select tr">
            配送时间
            <select name="date" thevalue="<?php echo $select_date; ?>" data-init-val="<?php echo $select_date; ?>" onchange="$('#basket').submit();">
              <?php foreach($dates as $index => $result) {?>
              <option value="<?php echo $result; ?>" <?php if($select_date==$result) echo "selected"; ?> ><?php echo $result; ?></option>
              <?php } ?>
            </select>
          </div>
        </div-->
      </div>
    </div>

    <?php if ($attention) { ?>
    <div class="attention"><?php echo $attention; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

      <div class="cart-info">
        <table>
          <thead>
            <tr>
              <td class="remove left"><input type="checkbox" value="1" onclick="$(this).parents('#basket').find(':checkbox').attr('checked', this.checked);" title="全选/全不选"><label>全选</label></td>
              <td class="image"><?php echo $column_image; ?></td>
              <td class="name"><?php echo $column_name; ?></td>
              <td class="price"><?php echo $column_price; ?></td>
              <td class="promotion_title"><?php echo $column_promotion; ?></td>
              <td class="promotion_price"><?php echo $column_promotion_price; ?></td>
              <td class="quantity"><?php echo $column_quantity; ?></td>
              <td class="total"><?php echo $column_total; ?></td>
              <td class="remove"><?php echo $column_remove; ?></td>
            </tr>
          </thead>
          <tbody>
          <?php foreach($groups as $key => $products) {?>
<!--          	--><?php //if($key) {?>
<!--          	<tr class="take-date-head">-->
<!--          		<td colspan="10"><label><b>取菜时间：</b></label><b>--><?php //echo $key; ?><!--</b></td>-->
<!--          	</tr>-->
<!--          	--><?php //} ?>
            <?php foreach ($products as $product) { ?>
            <!-- 显示加价购商品 -->
            <!-- 显示满额减商品 -->
            <tr data-id="<?php echo $product['product_id']; ?>">
              <td class="remove left">
              	<input type="checkbox" name="remove[]" value="<?php echo $product['key']; ?>" />
              </td>
              <td class="image">
              <?php if ($product['thumb']) { ?>
                <a href="<?php echo $product['href']; ?>" class="popup" title="<?php echo $product['name']; ?>">
                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                </a>
                <?php } ?>
              </td>
              <td class="name"><a href="<?php echo $product['href']; ?>" class="popup" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
                <?php if (!$product['stock']) { ?>
                <span class="stock">***</span>
                <?php } ?>
                <div>
                  <?php foreach ($product['option'] as $option) { ?>
                  - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                  <?php } ?>
                </div>
                <?php if ($this->config->get('config_reward_status') &&  $product['points']) { ?>
                <small><?php echo $product['points']; ?></small>
                <?php } ?>
              </td>
              <?php if(empty($product['promotion']['promotion_price'])){?>
                  <td class="price right">
              <?php }else {?>
                  <td class="price right" style="text-decoration: line-through;">
              <?php }?>
              <?php echo $product['price']; ?><input type="hidden" name="rule_code" id="rule_code" value="<?php echo $product['rule_code'];?>" /> 
              </td>
              <td class="promotion_title">
                  <?php if(!empty($product['promotion']['promotion_code'])){?>
                       <span><?php echo EnumPromotionTypes::getPromotionType($product['promotion']['promotion_code']);?></span>
                  <?php } ?>
              </td>
              <td class="promotion_price">
              <?php if(empty($product['promotion']['promotion_price'])){?>
                 <span>-</span>
              <?php }else {?>
                 <?php echo $product['promotion']['promotion_price'];?>
              <?php } ?>
              </td>
              		
              <td class="quantity">
              <?php  if($product['promotion']['promotion_code'] == EnumPromotionTypes::TOTAL_DONATION ||
                        $product['promotion']['promotion_code'] == EnumPromotionTypes::REGISTER_DONATION || 
                        $product['promotion']['promotion_code'] == EnumPromotionTypes::ZERO_BUY ||
                        $product['promotion']['promotion_code'] == EnumPromotionTypes::EXCHANGE_BUY
                     ) { ?>
                 1
              <?php }else { ?>
	              <input type="button" value=" - " <?php if($product['quantity'] <= 1) {?>disabled<?php }?> onclick="minus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
	              <input style="min-width:10px;"type="text" onchange="$('#basket').submit();" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" />
	              <input type="button" value=" + " onclick="plus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_plus; ?>" title="<?php echo $text_plus; ?>">
              <?php } ?>
	              </td>
              <td class="total right"><?php echo $product['total']; ?></td>
              <td class="remove">
              	<a href="<?php echo $product['remove']; ?>" title="<?php echo $text_remove; ?>">
              	<img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
              </a>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <tr>
            <td colspan="5" align="left">
            	<img src="catalog/view/theme/default/image/remove.png" alt="删除选中的商品" title="删除选中的商品">
            	<a onclick="$('#basket').attr('action', '<?php echo $remove; ?>');$('#basket').submit();">删除选中的商品</a>
            </td>
            <td colspan="5" align="right">
<!--            	<a onclick="$('#basket').submit();" class="button highlight"><span><?php echo $button_update; ?></span></a>-->
            </tr>
          </tbody>
        </table>
        <?php if($exchange_buy){?>
	    <div class="exchange_buy">
	       <b style="color:#F60"><?php echo $exchange_buy['text']?></b>
	           &nbsp;<a id="exchange_buy" class="button" href="<?php echo $exchange_buy['href']; ?>"><span><?php echo $exchange_buy['exchange_buy_btn'];?></span></a>
	    </div>
	    <?php }?>
      </div>
    </form>
    <?php if(false) { ?>
    <div class="cart-module">
      <?php foreach ($modules as $module) { ?>
      <?php echo $module; ?>
      <?php } ?>
    </div>
    <?php }  ?>

    <div class="cart-total">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr class="ilex total">
          
          <td class="right zebiaoti"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right zonge"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="clear"></div>
    <div class="buttons no_border">
    <div class="right">
      <a href="index.php?route=common/home" class="btn">返回继续订餐</a>
      &nbsp;&nbsp;&nbsp;
      <a href="<?php echo $checkout; ?>" class="button highlight"><span><?php echo $button_checkout; ?></span></a>
    </div></div>
    <?php echo $content_bottom; ?></div>
</div>
<script type="text/javascript"><!--
$('.cart-module .cart-heading').bind('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}
		
	$(this).parent().find('.cart-content').slideToggle('slow');
});

function plus(name){
	var number=parseInt($('.cart-info input[name=\''+name+'\']').val())+ 1
	$('.cart-info input[name=\''+name+'\']').val(number);

	$('#basket').submit();
}

function minus(name){
	var number=parseInt($('.cart-info input[name=\''+name+'\']').val())- 1
	$('.cart-info input[name=\''+name+'\']').val(number);

	$('#basket').submit();
}
//--></script> 
<?php echo $footer; ?>