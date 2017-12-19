<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <div class="order-status"><img src="image/page/status1.jpg"></div>
    <h1><?php echo $heading_title; ?>
      <?php if ($weight) { ?>
      &nbsp;(<?php echo $weight; ?>)
      <?php } ?>
    </h1>
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
      	<div class="cart-thead clearfix">
      		<div class="column checkbox remove">
      			<input type="checkbox" value="1" onclick="$(this).parents('#basket').find(':checkbox').attr('checked', this.checked);"><label>全选</label>
      		</div>
      		<div class="column image"><?php echo $column_image; ?></div>
      		<div class="column name span4"><?php echo $column_name; ?></div>
      		<div class="column time span4">取菜时间</div>
      		<div class="column model"><?php echo $column_model; ?></div>
      		<div class="column quantity"><?php echo $column_quantity; ?></div>
      		<div class="column price"><?php echo $column_price; ?></div>
      		<div class="column total"><?php echo $column_total; ?></div>
      		<div class="column action"><?php echo $column_remove; ?></div>
      	</div>
      	
        <table>
          <thead>
            <tr>
              <td class="remove left span1"><input type="checkbox" value="1" onclick="$(this).parents('#basket').find(':checkbox').attr('checked', this.checked);"><label>全选</label></td>
              <td class="image"><?php echo $column_image; ?></td>
              <td class="name span4"><?php echo $column_name; ?></td>
              <td class="name span2">取菜时间</td>
              <td class="model"><?php echo $column_model; ?></td>
              <td class="quantity"><?php echo $column_quantity; ?></td>
              <td class="price"><?php echo $column_price; ?></td>
              <td class="total"><?php echo $column_total; ?></td>
              <td class="remove"><?php echo $column_remove; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="remove left"><input type="checkbox" name="remove[]" value="<?php echo $product['key']; ?>" /></td>
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
              <td class="">
              	<select name="date[<?php echo $product['key']; ?>]" onchange="$('#basket').submit();">
              		<option value="">设定取菜时间</option>
              		<?php foreach($dates as $result) {?>
              		<option value="<?php echo $result; ?>"  <?php if(isset($product['additional']['date']) && ($product['additional']['date']==$result)) {?>selected="selected"<?php }?>><?php echo $result; ?></option>
              		<?php } ?>
              	</select>
              </td>
              <td class="model"><?php echo $product['model']; ?></td>
              <?php if($product['rule_code']==EnumConsulationRules::getZeroBuy()){?>
	              <td class="quantity">
	              1
	              </td>
              <?php }else if(!$product['donation']) { ?>
	              <td class="quantity">
	              <input type="button" value=" - " <?php if($product['quantity'] <= 1) {?>disabled<?php }?> onclick="minus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_minus; ?>" title="<?php echo $text_minus; ?>">
	              <input style="min-width:10px;"type="text" onchange="$('#basket').submit();" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" />
	              <input type="button" value=" + " onclick="plus('quantity[<?php echo $product['key']; ?>]');" alt="<?php echo $text_plus; ?>" title="<?php echo $text_plus; ?>">
	              </td>
              <?php } else {?>
	              <td class="quantity">
	              1
	              </td>
              <?php } ?>
              <td class="price"><?php echo $product['price']; ?><input type="hidden" name="rule_code" id="rule_code" value="<?php echo $product['rule_code'];?>" /></td>
              <td class="total"><?php echo $product['total']; ?></td>
              <td class="center">
              	<a href="<?php echo $product['remove']; ?>" title="<?php echo $text_remove; ?>">
              	<img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $text_remove; ?>">
              </a>
              </td>
            </tr>
            <?php } ?>
            <tr>
            <td colspan="4" align="left">
            	<img src="catalog/view/theme/default/image/remove.png" alt="删除选中的商品" title="删除选中的商品">
            	<a onclick="$('#basket').attr('action', '<?php echo $remove; ?>');$('#basket').submit();">删除选中的商品</a>
            </td>
            <td colspan="4" align="right">
            	<a onclick="$('#basket').submit();" class="button highlight"><span><?php echo $button_update; ?></span></a>
            </tr>
          </tbody>
        </table>
      </div>
    </form>
 <div class="cart-module">
      <?php foreach ($modules as $module) { ?>
      <?php echo $module; ?>
      <?php } ?>
    </div>
    <div class="cart-total">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td colspan="5"></td>
          <td class="right zebiaoti"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right zonge"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="clear"></div>
    <div class="buttons no_border">
    <div class="right">
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