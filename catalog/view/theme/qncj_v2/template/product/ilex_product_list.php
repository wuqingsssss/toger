<?php foreach ($products as $product) { ?>
    <div class="product">
      <?php if ($product['thumb']) { ?>
      <div class="image">
      <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
      <img src="<?php echo $product['thumb']; ?>" id="pdt_img<?php echo $product['product_id']; ?>" alt="<?php echo $product['name']; ?>" />
      </a>

      <ul class="ix-avg-sm-3">
        <li><span class="follow-button ix-icon-small icon-assist icon-active"><?php echo $product['follow'];?>&nbsp;</span><input type="hidden"  value="<?php echo $product['product_id']?>"></li>
        <!--li><span class="ix-icon-small icon-comment"><?php echo $product['reviews'];?>&nbsp;</span></li  -->
        <li><span class="ix-icon-small icon-cooking"><?php echo $product['cooking_time'];?> &nbsp;</span></li>
      </ul>
      </div>
      <?php } ?>

      <div class="product-rc">
        <div class="name">
          <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>">
            <?php echo $product['name']; ?>
          </a>
        </div>
        <div class="tag">
          <!-- 商品标签-->
          <?php if($product['icons']) {?>
             <?php foreach($product['icons'] as $icon) {?>
                <span class="icon icon-message-tag"><?php echo $icon['tag'];?></span>
              <?php }  ?>
          <?php }  ?>
        </div>
        <div class="description"><?php echo $product['description']; ?></div>
        <div class="subtitle"><?php echo $product['subtitle']; ?></div>

        <?php if ($product['price']) { ?>
          <div class="price">
            <?php if (empty($product['promotion']['promotion_code'])) { ?>
              <span class="price-new"><?php echo $product['price']; ?></span>
            <?php } else { ?>
              <span class="price-new"><?php echo $product['promotion']['promotion_price']; ?></span>
              <span class="price-old"><?php echo "原价".$product['price']; ?></span>           
            <?php } ?>
            <?php if ($product['tax']) { ?>
              <br />
              <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
            <?php } ?>
          </div>
        <?php } ?>


        <?php if(isset($promotion_type) && $promotion_type) {?>
          <div class="cart-info">
            <div class="quantity"><?php echo $text_qty; ?>
              <input type="text" id="product_<?php echo $product['product_id']; ?>_quantity" value="1" class="addcart" disabled>
            </div>
          </div>
        <?php } else { ?>
        <div class="cart-info">
          <div class="quantity"><?php echo $text_qty; ?>
            <input class="down" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','down','1','')" value="-">
            <input type="text" id="product_<?php echo $product['product_id']; ?>_quantity" value="1" class="addcart">
            <input class="up" type="button" onclick="update_product_quantity('product_','<?php echo $product['product_id']; ?>','up','1','')" value="+">
          </div>

          <div class="cart">
            <a rel="nofollow" onclick="addToCart('<?php echo $product['product_id']; ?>'
               <?php if($filter_category_id==1) { ?>
                   ,'<?php echo EnumPromotionTypes::ZERO_BUY; ?>'
               <?php } ?>
                   );" class="btn">
                   
              <span><?php echo $button_cart; ?></span>
            </a>
          </div>
      </div>

       <?php } ?>
  </div>
      <!--
      <div class="wishlist">
        <a rel="nofollow" onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="btn">
          <span><?php echo $button_wishlist; ?></span>
        </a>
      </div>
      
      <div class="compare">
        <a rel="nofollow" onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="btn">
          <?php echo $button_compare; ?>
        </a>
      </div>-->
    </div>
<?php } ?>

<script type="text/javascript">      
<!--
$('.follow-button').bind('click',function(){
	var htobj = $(this); 
	var product_id = htobj.parent().children('input:first').val();
	
	$.ajax({
		url: 'index.php?route=product/home/follow&product_id=' + product_id,
		dataType: 'json',
		success: function(data) {
			if(data['status']=='1'){
				location.href="index.php?route=account/account";
			}else if(data['status']=='2'){

				$.tipsBox({
					obj: htobj,
					str: "&#10084;+1",
			        callback: function() {
			        	htobj.html(data['follow']+'&nbsp;');
			        },
					color:'red'
					});
			}else{
				alert(data['info']);
			}
		}
	});
});

(function($) {
    $.extend({
        tipsBox: function(options) {
            options = $.extend({
                obj: null,  //jq对象，要在那个html标签上显示
                str: "+1",  //字符串，要显示的内容;也可以传一段html，如: "<b style='font-family:Microsoft YaHei;'>+1</b>"
                startSize: "14px",  //动画开始的文字大小
                endSize: "20px",    //动画结束的文字大小
                interval: 600,  //动画时间间隔
                color: "blue",    //文字颜色
                callback: function() {}    //回调函数
            }, options);
            $("body").append("<span class='num'>"+ options.str +"</span>");
            var box = $(".num");
            var left = options.obj.offset().left + options.obj.width() / 2;
            var top = options.obj.offset().top - options.obj.height();
            box.css({
                "position": "absolute",
                "left": left + "px",
                "top": top + "px",
                "z-index": 9999,
                "font-size": options.startSize,
                "line-height": options.endSize,
                "color": options.color
            });
            box.animate({
                "font-size": options.endSize,
                "opacity": "0",
                "top": top - parseInt(options.endSize) + "px"
            }, options.interval , function() {
                box.remove();
                options.callback();
            });
        }
    });
})(jQuery);
//-->
</script>