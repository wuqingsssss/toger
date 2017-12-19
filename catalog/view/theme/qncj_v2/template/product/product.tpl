<?php echo $header; ?>
<?php //echo $column_left; ?>
<?php //echo $column_right; ?>
<div id="content" class="wrap">
  <?php echo $content_top; ?>
  <?php echo $this->getChild('common/breadcrumb'); ?>  
  <div class="product-info">
    <?php if ($thumb || $images) { ?>
    <div class="left-content">
        <?php if ($thumb) { ?>
	      <div class="image zoom-small-image" >
	      <?php if($popup) {?>
	      <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="cloud-zoom " id="zoom1" rel="adjustX: 10, adjustY:-4" >
	      <img class="img-responsive" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image"   />
	      </a>
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
		 
	      </div>
	  	<?php } ?>
      <?php if ($images) { ?>
	      <div class="image-additional" id="image-additional-carousel">
		      <ul class="jcarousel-skin-opencart">
		          <?php if($small) {?>
		          <li>
		      		<a class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?php echo $thumb; ?>' " href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
		      			<img src="<?php echo $small; ?>" class="zoom-tiny-image" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" />
		      		</a>
		       	 </li>
		       	 <?php }?>
		        <?php foreach ($images as $image) { ?>
		         <li>
		        	<a class="cloud-zoom-gallery"  rel="useZoom: 'zoom1', smallImage: '<?php echo $image['middle'] ?>' " href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="" rel="">
		        		<img src="<?php echo $image['thumb']; ?>" class="zoom-tiny-image" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
		        	</a>
		        </li>
		        <?php } ?>
		    </ul>
       	 </div>
      <?php } ?>
      <div class="clear"></div>

		<?php if($this->config->get('config_share_product_status')) {  ?>
      <!-- JiaThis Button BEGIN -->
      <div class="jiathis_style">
        <a class="jiathis_button_tsina"></a>
        <a class="jiathis_button_tqq"></a>
        <a class="jiathis_button_weixin"></a>
        <a class="jiathis_button_fb"></a>
        <a class="jiathis_button_twitter"></a>
        <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"><?php echo $text_jiathis_more; ?></a>
      </div>
      <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1374294522691373" charset="utf-8"></script>
      <!-- JiaThis Button END -->
		<?php }  ?>
    </div>
    <?php } ?>
    
    <div class="right-content">
      <ul class="summary">
    		<li class="title">
    			<h1>
					<?php echo $heading_title; ?>
				</h1>
    		</li>
            <li class="material"><span><strong><?php echo $text_material; ?></strong></span>
                <?php if(empty($garnish)){?>
                     <span>&nbsp;</span>
                <?php }else {?>
                     <span><?php echo $garnish;?></span>
                <?php }?>
            </li>
		    <li class="subtitle"><span><strong><?php echo $text_subtitle; ?></strong></span>
		        <span><?php echo $subtitle; ?></span></li>

        <?php if ($review_status) { ?>
        <div class="review">
          <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a rel="nofollow" onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a rel="nofollow" onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
        </div>
        <?php } ?>

        <!-- <li class="sales">该商品本周已售出<span>200</span>份</li>  -->

    	 <!--	<li>
    			<span class="unit"><?php echo $text_unit; ?><?php echo $unit; ?></span>
          &nbsp;&nbsp;&nbsp;&nbsp;
    			<span class="origin"><?php echo $text_origin; ?><?php echo $origin; ?></span>
    		</li>
		-->    
    		<li>
    			<?php if($manufacturer) {?>
		    	&nbsp;&nbsp;&nbsp;&nbsp;
		    	<span class="manufacturer"><?php echo $text_manufacturer; ?><a href="<?php echo $manufacturer_link; ?>"><?php echo $manufacturer; ?></a></span>
		    	<?php } ?>
    		</li>
    	 <!--
        <?php if ($delivery) { ?>
        <li class="delivery">
          <span><strong><?php echo $text_delivery; ?></strong><?php echo $delivery; ?></span>
        </li>
        <?php } ?>
        --> 


        <?php if ($storage) { ?>
        <li>
          <span class="storage"><strong><?php echo $text_storage; ?></strong><?php echo $storage; ?></span>
        </li>
        <?php } ?>

		  <?php if ($points) { ?>
		  <li>
			  <span class="reward"><?php echo $text_points; ?><?php echo $points; ?></span>
		  </li>
		  <?php } ?>

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
		</ul>

		  <ul id="product-params" class="ix-avg-sm-4">		  
			  <li><span class="ix-icon-middle icon-weight"><?php echo $unit; ?>&nbsp;</span></li>
			  <li><span class="ix-icon-middle icon-cooking"><?php echo $cooking_time;?>&nbsp;</span></li>
			  <!--<li><span class="ix-icon-middle icon-classify"><?php echo $category_name;?>&nbsp;</span></li>  -->
			  <li><span class="ix-icon-middle icon-pabulum"><?php echo $calorie;?>&nbsp;</span></li>
		  </ul>


		  <div class="mt50">
			  <div class="ix-row ix-row-collapse">
				  <div class="ix-u-sm-6 ">
					  <?php if ($price) { ?>
					  <div class="price">
        				<?php if (!isset($promotion['promotion_price'])) { ?>
    					<span class="price-new"><?php echo $price; ?></span>
    					<?php } else { ?>
    					     <span class="price-new"><?php echo $promotion['promotion_price']; ?></span>
    					     <span class="price-old"><?php echo "原价".$price; ?></span>
    					<?php } ?>
					  </div>
					  <?php } ?>
				  </div>
				  <div class="ix-u-sm-6">
					  <?php if($cart) {?>
					  <div>
						  <form id="cart-form">
							  <input type="hidden" name="promotion_code" value="<?php echo $p_code; ?>" />
							  <div class="cart tr">

								  <div>
								  <?php if($product_info[available]=='1'){ ?>
									  <div class="quantity clearfix">
										  <?php echo $text_qty; ?>
										  <input type="button" value="-" onclick="update_product_quantity('product_','<?php echo $product_id; ?>','down','1','')" class="down">
										  <input type="text" name="quantity" id="product_<?php echo $product_id; ?>_quantity" size="2" value="<?php echo $minimum; ?>" />
										  <input type="button" value="+" onclick="update_product_quantity('product_','<?php echo $product_id; ?>','up','1','')" class="up">
										  <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
									  </div>
                                     
									  <a rel="nofollow" id="button-cart" class="button">
										  <span><?php echo $button_cart; ?></span>
									  </a>
									  
									   <?php }else{ ?>
									    <div class="quantity clearfix">
										            <?php //echo $text_product_available[$product_info[available]];?>
										</div>
									     <a rel="nofollow" class="button _black">
										  <span><?php echo $text_product_available[$product_info[available]];?></span>
									  </a>
									   
									    <?php } ?>

									  <!--	          <a class="button" rel="nofollow" onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>-->
									  <!--
                                         &nbsp;&nbsp;&nbsp;<?php echo $text_or; ?>&nbsp;&nbsp;&nbsp;
                                         <a class="btn" rel="nofollow" onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a> |
                                          <a class="btn" rel="nofollow" onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
                                     -->
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
			  </div>
		  </div>




      <!-- .share begin -->
      <div class="share"></div>
      <!-- .share end -->
    </div><!--END .right-content -->

    <div class="clear"></div>


    <?php if($description) {?>
    <div class="description tab_section">
      <div class="tab_section_title">
        <h3><?php echo $tab_description; ?></h3>
      </div>
      <div class="tab_section_content">
        <?php echo $description; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($cooking) {?>
    <div class="cooking tab_section">
      <div class="tab_section_title">
        <h3><?php echo $tab_cooking; ?></h3>
      </div>
      <div class="tab_section_content">
        <?php echo $cooking; ?>
      </div>
    </div>
    <?php } ?>

    <div class="tab_section">
	  <img src="image/page/shicaibaozheng.jpg" alt="" />
  </div>

    </div>



  
  <?php // echo $this->getChild('product/product/combine'); ?>
  
  <div id="tabs" class="htabs">
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
  </div>



  
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
  
 <?php echo $this->getChild('product/consulation/lists'); ?>
 
 <?php echo $content_bottom; ?>
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
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
//		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
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
				location = window.location;
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
//--></script> 




<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.ae.image.resize.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/cloud-zoom.1.0.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/cloud-zoom.css" media="screen" />

<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/dss/stylesheet/carousel.css" media="screen" />


<script type="text/javascript">
$('#image-additional-carousel ul').jcarousel({
	vertical: false,
	visible: 3,
	scroll: 1
});


$('#related-jcarousel ul').jcarousel({
	vertical: false,
	visible: 4,
	scroll: 1
});

</script>


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


$(function(){
	$(".cloud-zoom img").aeImageResize({ height: <?php echo $image_thumb_height; ?>, width: <?php echo $image_thumb_width; ?>});	
	$('.cloud-zoom,.cloud-zoom-gallery').CloudZoom();
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

<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script>
<?php echo $footer; ?>