<?php echo $header35; ?>
    <!-- 页面自定义样式 -->
    <link href="<?php echo HTTP_CATALOG.$tplpath;?>template/promotion/15-11-11/css/index.css" rel="stylesheet"/>
    <style id="J_style"></style>
	<script type="text/javascript">
		! function() {
			function b() {
				var e, b = document.getElementById("J_style"),
					c = document.documentElement.clientWidth || document.body.clientWidth,  //可视区域的宽高和body的宽高一样
					d = 1; 
				d = c / 640, e = 100 * d,
				b.innerHTML = "html{font-size:" + e + "px;}", a = d;
				window._z = d;
			}
			var a = 0;
			b(), window.addEventListener("resize", b);
		}();
		window._idx = 10;
	</script>
	<!-- 公共头开始 -->
	<div id="header">
	    <div class="pull-left">
	        <a class="return" href="javascript:_.go();"></a>
	    </div>
	    <div class="text-center">
	        <a class="fz-18">双十一活动专区</a>
	    </div>
	</div>
	<!-- 公共头结束 -->
	
	<!--页面内容开始-->
	 <?php if ($promotion&&$promotion['page_header']){ 
 echo $promotion['page_header'];
 }?>
	
	<div class="types_one position-r">
		<!--菜品名称1-->
		<?php
		 foreach( $progroups as $key=> $group){ ?>
		<div class="headline_bj">
			<div class="col-red font-weight type_title fz-18">
				<div class="title_div">
					<img src="<?php echo HTTP_CATALOG.$tplpath;?>template/promotion/15-11-11/images/title.png" class="fl headline_title"/>
					<span class="fl col-pink" style="margin-left: 10px;"><?php echo $key;?></span>
				</div>
			</div>
			<div class="module with-bottom clear-f" id="m-nav">
			<?php foreach( $group as $key=> $product){ ?>
				
					<div class="clearfix">
						<div class="img-wrapper round position-r">		
						<a href="<?php echo $product['href']; ?>">			
							<img src="<?php echo $product['thumb']; ?>"/>
							<?php if(!$product['available']){?>
							<img src="<?php echo HTTP_CATALOG.$tplpath;?>template/promotion/15-11-11/images/close.png" class="close" style="width: inherit;"/>
							<?php }?>
							<div class="fz-14 col-black lh25"><?php echo $product['name']; ?></div>
							</a>
							<div class="dish_price clear-f">
							
							<?php if ($product['price']) { ?>
                <div class="prices">
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>                   
                    <span class="fz-16 col-pink fl"><?php echo $product['promotion']['promotion_price']; ?></span>
                     <?php } else { ?>
                     <span class="fz-12 col-black fl eleven_price"><?php echo $product['price'];?></span>
                      <?php } ?>
                </div>
                 <?php } ?>
														
							</div>
							<?php if($product['available']){?>
							
							<button data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="go_cart fl btn-add-cart">加入购物车</button>
							<?php }else{?>
							<button class="close_cart fl">加入购物车</button>
							<?php }?>
						</div>
					</div>
				
				<?php }?>
				
			</div>
		</div>
		<?php }?>
	<?php echo $this->getChild('module/sharebtn',array('btn_hide'=>'#share1'));?>
<?php echo $content_bottom; ?>
		 	
 <?php if ($promotion&&$promotion['page_footer']){?>
 <?php echo $promotion['page_footer'];?>
 <?php }?>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
