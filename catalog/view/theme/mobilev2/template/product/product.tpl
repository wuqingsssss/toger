<?php echo $header; ?>
<div id="header" class="bar bar-header bar-default">
	<a href="<?php echo $back; ?>" class="button icon-left ion-chevron-left button-clear button-white"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
  <div class="product-info">
    <?php if ($thumb || $images) { ?>
    <div class="card">
        <?php if ($thumb) { ?>
	      <div class="image zoom-small-image" >
	      <?php if($popup) {?>
	      <img class="img-responsive" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image"   />
	      <?php } else {?>
	      <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image"   />
	      <?php } ?>
	     
	      <?php if($icons) {?>
	      <div class="lables clearfix">
	      <?php foreach($icons as $icon) {?>
	      	 <span class="icon icon_<?php echo $this->config->get('config_language_id')?>_<?php echo $icon; ?>"></span>
	      	 <?php } ?>
	      </div>
		  <?php }  ?>

		  <div class="icon-group1 clearfix">
			  <ul class=" ix-avg-sm-3">
				  <li  style="float: right"><span class="follow-button ix-icon-small icon-assist icon-active">
			         <?php echo $follow; ?>&nbsp;<input type="hidden"  value="<?php echo $product_id; ?>"></span></li>
				  <!--  <li><span class="ix-icon-small icon-comment">
			         <?php echo $review; ?>&nbsp;</span></li>-->
				  <!--  <li><span class="ix-icon-small icon-cooking">
			         <?php echo $cooking_time; ?> &nbsp;</span></li> -->
			  </ul>
		  </div>
			  <span class="ovbg">&nbsp;</span>


	      </div>
	  	<?php } ?>
      <div class="clear"></div>
    </div>
    <?php } ?>
    
    <div class="card">
      <ul class="summary">
    		<li class="title">
    			<h1><?php echo $heading_title; ?></h1>
    		</li>
            <li class="material">
                <?php if(empty($garnish)){?>
                     <span>&nbsp;</span>
                <?php }else {?>
                     <span><?php echo $garnish;?></span>
                <?php }?>
            </li>    		
		    <li class="subtitle"><span><?php echo $subtitle; ?></span></li>
    		<!-- <li>
    			<span class="unit"><?php echo $text_unit; ?><?php echo $unit; ?></span>
          &nbsp;&nbsp;&nbsp;&nbsp;
    			<span class="origin"><?php echo $text_origin; ?><?php echo $origin; ?></span>
    		</li> -->
		    
    		<li>
    			<?php if($manufacturer) {?>
		    	&nbsp;&nbsp;&nbsp;&nbsp;
		    	<span class="manufacturer"><?php echo $text_manufacturer; ?><a href="<?php echo $manufacturer_link; ?>"><?php echo $manufacturer; ?></a></span>
		    	<?php } ?>
    		</li>
		    <?php if ($storage) { ?>
		    <li>
        	<span class="storage"><strong><?php echo $text_storage; ?></strong><?php echo $storage; ?></span>
        </li>
		  <?php if ($points) { ?>
		  <li>
			  <span class="reward"><?php echo $text_points; ?><?php echo $points; ?></span>
		  </li>
		  <?php } ?>

          <!-- 
		  <?php if ($delivery) { ?>
		  <li class="delivery">
			  <span><strong><?php echo $text_delivery; ?></strong><?php echo $delivery; ?></span>
		  </li>
		  <?php } ?> 
           -->
		  <li>
			  <!-- tag begin -->
			  <?php if ($tags) { ?>
			  <div class="tags"><strong><?php echo $text_tags; ?></strong>
		      	   <?php foreach ($tags as $tag) { ?>
                      <span class="icon icon-message-tag"><?php echo $tag['tag'];?></span>
				  <?php } ?>
			  </div>
			  <?php } ?>
			  <!-- tag end -->
		  </li>

        <?php } ?>
	  </ul>
		  <ul id="product-params" class="ix-avg-sm-3 clearfix">
			  <li><span class="ix-icon-middle icon-weight"><?php echo $unit; ?>&nbsp;</span></li>
			  <li><span class="ix-icon-middle icon-cooking"><?php echo $cooking_time;?>&nbsp;</span></li>
			  <li><span class="ix-icon-middle icon-pabulum"><?php echo $calorie;?>&nbsp;</span></li>
		  </ul>
		<div class="mt50">
            <div class="ix-row ix-row-collapse">
                <ul>
                    <li>
        				<div class="ix-u-sm-6 ">
        					<?php if ($price) { ?>
        					<div class="price">
        
            				<?php if (!isset($promotion['promotion_price'])) { ?>
        					<span class="price-new"><?php echo $price; ?></span>
        					<?php } else { ?>				   
        					    <span class="price-new"><?php echo $promotion['promotion_price']; ?></span>
        					    <span class="price-old">
        					    <br/>
        						<span class="price-num"><?php echo "原价".$price; ?></span>
        						</span>
        					<?php } ?>
        					</div>
        					<?php } ?>
        				</div>
    				</li>
    				<li>
        				<div class="ix-u-sm-6 ">
        					<?php if($cart) {?>
        					<div>
        						<form id="cart-form">
        							<input type="hidden" name="promotion_code" value="<?php echo $p_code; ?>" />
        							<div class="cart">
        								<div class="choose-amount">
        									  <?php if($product_info[available]=='1'){?>
        									<input type="button" value="-" onclick="update_product_quantity('product_','<?php echo $product_id; ?>','down','1','')" class="down">
        									<input type="text" name="quantity" id="product_<?php echo $product_id; ?>_quantity" size="2" value="<?php echo $minimum; ?>" class="quantity" />
        									<input type="button" value="+" onclick="update_product_quantity('product_','<?php echo $product_id; ?>','up','1','')" class="up">
        									<input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
        									<br />
        									<button rel="nofollow" id="button-cart" class="button button-positive button-block">
        										<span><?php echo $button_cart; ?></span>
        									</button>
        									
        									   <?php }else{ ?>
        									   <button rel="nofollow" class="button button-positive button-block">
        										<span><?php echo $text_product_available[$product_info[available];?>]</span>
        									</button>
        									    <?php } ?>
        									     <?php echo $this->getChild('module/sharebtn',array('btn_hide'=>'#dfdsf','callback'=>''));?>
        								</div>
        								<?php if ($minimum > 1) { ?>
        								<div class="minimum"><?php echo $text_minimum; ?></div>
        								<?php } ?>
        							</div>
        							<!-- end .cart -->
        						</form>
        					</div>
        					<?php } ?>   
        				</div>
    				</li>
				</ul>
			</div>
		</div>

    		

		


    <?php include 'catalog/view/theme/mobilev2/template/product/ilex_product_option.php'; ?>
      
     <!-- 商品评论开始 -->
     <!-- 商品评论结束 -->
     
     <!-- 商品分享开始 -->
     <!-- 商品分享结束 -->
     
    
	  <?php if ($review_status) { ?>
      <div class="review">
        <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a rel="nofollow" onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a rel="nofollow" onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
      </div>
      <?php } ?>
    </div>
    
    <!-- 商品描述开始 -->
    <?php if($description) {?>    
    <div id="description" class="tab_section card">
	  <div class="tab_section_title">
	    <h3><?php echo $tab_description; ?></h3>
	  </div>
	  <div class="tab_section_content">
	    <?php echo $description; ?>
	  </div>
	</div> 
    <?php } ?>
    
    <?php if($cooking) {?>    
    <div id="cooking" class="tab_section card">
	  <div class="tab_section_title">
	    <h3><?php echo $tab_cooking; ?></h3>
	  </div>
	  <div class="tab_section_content">
	    <?php echo $cooking; ?>
	  </div>
	</div> 
    <?php } ?>
    
    </div><!-- 商品描述结束 -->

	<div class="tab_section card">
		<img src="image/page/shicaibaozheng_m.jpg" alt="" />
	</div>



	<div id="tabs" class="htabs">
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
  </div>
  
  <?php if (false && $products) { ?>
  <div id="tab-related">
  	<h2><span><?php echo $tab_related; ?></span></h2>
    <div class="product-grid" id="related-jcarousel" >
    	<?php echo $this->common_render_tpl('product/product_list.tpl',array('products' => $products)); ?>
    </div>
  </div>
  <?php } ?>
  
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"></div>
    <?php if($purchased_status){?>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    <input type="hidden" name="name" value="<?php echo $customer_name;?>" />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp; <span><?php echo $entry_good; ?></span><br />
    <br />
    <div class="captcha">
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    
    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
    </div>
    <br />
    <div class="left"><a id="button-review" class="button"><span><?php echo $button_review; ?></span></a></div>
    <?php } else {?>
       <?php echo $text_login_review; ?>
     <?php } ?>
  </div>
  <?php } ?>
  
</div>

<script type="text/javascript"><!--
function changeUrl() {
	var redirect;
	redirect = document.getElementById('product-color').value;
	if(redirect!='#')
		document.location.href = redirect;
}
//--></script>

<script type="text/javascript"><!--
$('#button-cart').bind('click', function(event) {
	event.preventDefault();
	
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: $('#cart-form').serialize(),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
					$('.warning').fadeIn('slow');
				}
				
				for (i in json['error']) {
					$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
				}

				if (json['error']['alert']) {
					alert(json['error']['alert']);
				}
			}	 
						
			if (json['success']) {
				location = 'index.php?route=checkout/cart';
			}	
		}
	});
});
//--></script>

<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
$(document).ready(function(){
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" id="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			alert(json.success);
			
			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
		}
		
		$('#loading').remove();	
	}
});
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>

<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review-title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review-title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
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


$('.follow-button').bind('click',function(){

	var product_id = $(this).children('input:first').val();
	var htobj = $(this); 
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
			        	location.reload();
			        },
					color:"red"
				});
				
			}else{
				alert(data['info']);
			}
		}
	});
});
//--></script> 

<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script> 

<script type="text/javascript"><!--
$(document).ready(function(){
	if($('.date').length > 0){
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	}
	
	if($('.datetime').length > 0){
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
	}
	
	if($('.time').length > 0){
		$('.time').timepicker({timeFormat: 'h:m'});
	}
});

function calcprice(id){
	if(id){
		$.ajax({
			url: 'index.php?route=product/product/calc&product_option_value_id='+id,
			type: 'post',
			data: null,
			dataType: 'json',
			success: function(json) {
				if(json['price']){
					$('.summary .price').html(json['price']);
				}				
			}
		});
	}
}
//--></script> 
<?php echo $footer; ?>