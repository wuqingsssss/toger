<?php echo $header; ?>
<div class="bar bar-header bar-light">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
<?php if ($attention) { ?>
    <div class="attention"><?php echo $attention; ?></div>
<?php } ?>    
<?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
    
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
      <div class="cart-info">
      
     	 <?php foreach($groups as $key => $products) {?>
     	 <div class="card">
          	<?php if($key) {?>
          	<div class="item item-divider take-date-head">
          		<label><b>取菜时间：</b></label><b><?php echo $key; ?></b>
          	</div>
          	<?php } ?>
          	<div class="item item-text-wrap">
          	<div class="list">
          	<?php foreach ($products as $product) { ?>
          	<div class="item" data-id="<?php echo $product['product_id']; ?>">
          		<div class="row">
          			<div class="col remove"><input type="checkbox" name="remove[]" value="<?php echo $product['key']; ?>" /></div>
          			<div class="col image">
          				<?php if ($product['thumb']) { ?>
			                <a href="<?php echo $product['href']; ?>" class="popup" title="<?php echo $product['name']; ?>">
			                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
			                </a>
		                <?php } ?>
          			</div>
          			<div class="col name">
          				<a href="<?php echo $product['href']; ?>" class="popup" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
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
          			</div>
          			<div class="col price">
          				<?php echo $product['price']; ?>
          				<input type="hidden" name="rule_code" id="rule_code" value="<?php echo $product['rule_code'];?>" />
          			</div>
          			<?php  
		              $TOTAL_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::TOTAL_DONATION);
		              $REGISTER_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::REGISTER_DONATION);
		              $ZERO_BUY=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::ZERO_BUY);
	              ?>
	              <div class="col promation">
	              <?php if($TOTAL_DONATION) {?>
	              	<span class="icon promotion donation">满额赠送</span>
	              <?php } else if($REGISTER_DONATION) { ?>
	              	<span class="icon promotion register">首次赠购</span>
	              <?php } else if($ZERO_BUY) { ?>
	              	<span class="icon promotion zerobuy">0元抢购</span>
	              <?php } ?>
	              </div>
	              
	              <?php  if($TOTAL_DONATION || $REGISTER_DONATION || $ZERO_BUY) {?>
	              <div class="quantity">1</div>
              <?php }else { ?>
	              <div class="quantity">
	              <input type="button" value=" - " <?php if($product['quantity'] <= 1) {?>disabled<?php }?> onclick="minus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
	              <input style="min-width:10px;"type="text" onchange="$('#basket').submit();" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" />
	              <input type="button" value=" + " onclick="plus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_plus; ?>" title="<?php echo $text_plus; ?>">
	              </div>
              <?php } ?>
              
              
              <?php  if($TOTAL_DONATION || $REGISTER_DONATION || $ZERO_BUY) {?>
              <div class="time">
              	<select name="date[<?php echo $product['key']; ?>]" title="赠送商品无法选择取菜时间" readonly="readonly">
              		<?php foreach($dates as $index => $result) {?>
              			<?php if($index==0) {?>
              			<option value=""><?php echo $result; ?></option>
              			<?php }?>
              		<?php } ?>
              	</select>
              </div>
              <?php } else {?>
              <div class="time">
              	<select name="date[<?php echo $product['key']; ?>]" onchange="$('#basket').submit();">
              		<?php foreach($dates as $index => $result) {?>
              			<?php if($index==0) {?>
              			<option value=""><?php echo $result; ?></option>
              			<?php } else {?>
	              		<option value="<?php echo $result; ?>"  <?php if(isset($product['additional']['date']) && ($product['additional']['date']==$result)) {?>selected="selected"<?php }?>><?php echo $result; ?></option>
	              		<?php } ?>
              		<?php } ?>
              	</select>
              </div>
              <?php } ?>
              
	            <div class="remove">
              	<a href="<?php echo $product['remove']; ?>" title="<?php echo $text_remove; ?>">
              	<img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
              </a>
              </div>  
	          
          		</div>
          	</div>
       <?php } ?>
       </div>
       </div>
       
       </div>
	           <?php } ?>
        <!--  <table>
          <thead>
            <tr>
              <td class="remove left"><input type="checkbox" value="1" onclick="$(this).parents('#basket').find(':checkbox').attr('checked', this.checked);" title="全选/全不选"><label>全选</label></td>
              <td class="image"><?php echo $column_image; ?></td>
              <td class="name"><?php echo $column_name; ?></td>
              <td class="model"><?php echo $column_model; ?></td>
              <td class="price"><?php echo $column_price; ?></td>
              <td class="promotion">优惠</td>
              <td class="quantity"><?php echo $column_quantity; ?></td>
              <td class="total"><?php echo $column_total; ?></td>
              <td class="time">取菜时间</td>
              <td class="remove"><?php echo $column_remove; ?></td>
            </tr>
          </thead>
          <tbody>
          <?php foreach($groups as $key => $products) {?>
          	<?php if($key) {?>
          	<tr class="take-date-head">
          		<td colspan="10"><label><b>取菜时间：</b></label><b><?php echo $key; ?></b></td>
          	</tr>
          	<?php } ?>
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
              
              <td class="model"><?php echo $product['model']; ?></td>
              <td class="price right"><?php echo $product['price']; ?><input type="hidden" name="rule_code" id="rule_code" value="<?php echo $product['rule_code'];?>" /></td>
              <?php  
	              $TOTAL_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::TOTAL_DONATION);
	              $REGISTER_DONATION=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::REGISTER_DONATION);
	              $ZERO_BUY=isset($product['additional']['promotion_code']) && ($product['additional']['promotion_code']==EnumPromotionTypes::ZERO_BUY);
              ?>
              <td class="promation">
              <?php if($TOTAL_DONATION) {?>
              	<span class="icon promotion donation">满额赠送</span>
              <?php } else if($REGISTER_DONATION) { ?>
              	<span class="icon promotion register">首次赠购</span>
              <?php } else if($ZERO_BUY) { ?>
              	<span class="icon promotion zerobuy">0元抢购</span>
              <?php } ?>
              </td>
              		
              <?php  if($TOTAL_DONATION || $REGISTER_DONATION || $ZERO_BUY) {?>
	              <td class="quantity">1</td>
              <?php }else { ?>
	              <td class="quantity">
	              <input type="button" value=" - " <?php if($product['quantity'] <= 1) {?>disabled<?php }?> onclick="minus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
	              <input style="min-width:10px;"type="text" onchange="$('#basket').submit();" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" />
	              <input type="button" value=" + " onclick="plus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_plus; ?>" title="<?php echo $text_plus; ?>">
	              </td>
              <?php } ?>
              
              <td class="total right"><?php echo $product['total']; ?></td>
              <?php  if($TOTAL_DONATION || $REGISTER_DONATION || $ZERO_BUY) {?>
              <td class="time">
              	<select name="date[<?php echo $product['key']; ?>]" title="赠送商品无法选择取菜时间" readonly="readonly">
              		<?php foreach($dates as $index => $result) {?>
              			<?php if($index==0) {?>
              			<option value=""><?php echo $result; ?></option>
              			<?php }?>
              		<?php } ?>
              	</select>
              </td>
              <?php } else {?>
              <td class="time">
              	<select name="date[<?php echo $product['key']; ?>]" onchange="$('#basket').submit();">
              		<?php foreach($dates as $index => $result) {?>
              			<?php if($index==0) {?>
              			<option value=""><?php echo $result; ?></option>
              			<?php } else {?>
	              		<option value="<?php echo $result; ?>"  <?php if(isset($product['additional']['date']) && ($product['additional']['date']==$result)) {?>selected="selected"<?php }?>><?php echo $result; ?></option>
	              		<?php } ?>
              		<?php } ?>
              	</select>
              </td>
              <?php } ?>
              <td class="remove">
              	<a href="<?php echo $product['remove']; ?>" title="<?php echo $text_remove; ?>">
              		<img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
              	</a>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>-->
      </div>
    </form>
    
    <div class="cart_submit">
    	<div class="submit">
    		<a href="<?php echo $checkout; ?>" title="<?php echo $button_checkout; ?>" class="button button-positive"><?php echo $button_checkout; ?></a>
    	</div>
    	<div class="cart-total">
    		<ul>
    			<?php foreach ($totals as $total) { ?>
                    <li class="ilex <?php echo $total['code']; ?>"><span class="zebiaoti"><?php echo $total['title']; ?></span><span class="zonge"><?php echo $total['text']; ?></span></li>
                <?php } ?>
             </ul>
    
    	</div>
    	<a class="clear_cart" onclick="$('#basket').attr('action', '<?php echo $remove; ?>');$('#basket').submit();"><img src="catalog/view/theme/default/image/remove.png" alt="删除选中的商品" title="删除选中的商品">删除选中的商品</a>
    </div>
</div>
<?php echo $footer; ?>
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
