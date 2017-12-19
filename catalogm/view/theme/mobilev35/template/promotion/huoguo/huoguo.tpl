<?php echo $header35; ?>
<!-- 公共头开始 -->
<div id = "m-header">
<?php if (!($this->detect->is_weixin_browser ())){?>
<div id="header">
    <div class="pull-left">
        <a class="return" href="javascript:_.go();"></a>
        </div>
    <div class="pull-right hidden">
        </div>
    <div class="text-center">
    <a class="locate fz-18">火锅</a>
            </div>
</div>
<?php }?>
<div id="phead" class="phead">
<?php if ($promotion&&$promotion['page_header']){
echo $promotion['page_header'];
}?>
</div>
</div>

<!-- 公共头结束 -->
<link rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/common.css"/>
<link rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/category.css"/>
<?php if (($this->detect->is_weixin_browser ())){
$padding_top=0;
 }
else{
$padding_top=44;
}
?>
<style>
#m-category {
  padding-top: <?php echo $padding_top;?>px;
}
#m-category > .cate-list > ul {
  padding: <?php echo $padding_top;?>px 0 50px;
}
#m-category > .fixed-cate {
  position: fixed;
  z-index: 498;
  top: <?php echo $padding_top;?>px;
  left: 0;
  width: 100%;
}
</style>
<!-- 公共头结束 -->
<?php echo $content_top;?>
<script>

	    $('#phead img').load(function(){  
	    	var $height=$('#phead img').height()||$('#phead img').width()*113/327;
	    	 console.log('$height',$height,$('#phead img').height());
	    	    $('#m-category').css('padding-top',$height+<?php echo $padding_top;?>+'px');
	    		$('#m-category > .cate-list > ul').css('padding-top',$height+<?php echo $padding_top;?>+'px');
	    		$('#m-category > .fixed-cate').css('top',$height+<?php echo $padding_top;?>+'px');
	    	
	    
	    }) ;

</script>
<style>.sell_out {
    opacity: 0.6;
    position: absolute;
    top: 0px;
    border-radius: 50%;
    background-color: #2C2929;
    margin-top: 5px;
    margin-left: 5px;
    line-height: 45px;
    padding: 10px;
    width: 45px;
    height: 45px;
    left: 0px;
    text-align: center;
}</style>
<script>var curcate=0</script>
<div class="module" id="m-category">
    <div class="cate-list">
        <ul>
				  <?php 
						 foreach ($progroups as $k2 => $goods) { ?>
            <li><?php echo $k2;?></li>
						<?php } ?>
        </ul>
    </div>
    <div class="fix-lists pull-left"></div>
    <div class="fixed-cate">
        <div class="fix-lists pull-left"></div>
        <div class="food-list-title">&nbsp;</div>
    </div>
    <div class="food-lists">
				<?php foreach ($progroups as $k2 => $goods) { ?>
        <div class="food-list-wrapper">
            <div id="cat<?php echo $k2;?>" class="food-list-title">&nbsp;<?php echo $k2; ?></div>
            <ul class="food-list">
				<?php foreach ($goods as $k3 => $product) { ?> 
                <li data-id="{food_id}">
                    <a href="<?php echo $product['href']; ?>" class="img-wrapper pull-left" style="position: relative;">
                    <span class="sell_out<?php if($product['available']=='1') echo ' hidden';?>"><font class="sell_info"><?php echo $product['status_name']; ?></font></span>
												<img originalSrc="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']?>"/>
										</a>

                    <div class="content">
                        <div class="title fz-16 text-overflow">
														<?php echo $product['name']?>
												</div>
                        <div class="activity">
													<?php $code = EnumPromotionTypes::clearCode($product['promotion']['promotion_code']); ?>
													<?php if ($code == (EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
														<i class="icon-word miao"></i>
													<?php } ?>
													<?php if ($product['combine']) { ?>
														<i class="icon-word tao"></i>
													<?php } ?>
													<?php if ($code == (EnumPromotionTypes::PROMOTION_SPECIAL)) { ?>
														<i class="icon-word te"></i>
													<?php } ?>
													<?php if ($code == (EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
														<i class="icon-word xian"></i>
													<?php } ?>
													<?php if ($code == (EnumPromotionTypes::REGISTER_DONATION)) { ?>
														<i class="icon-word shou"></i>
													<?php } ?>	
                        </div>
                          <div class="title fz-12 text-overflow">
														<?php echo $product['unit']?>
												</div>
												<?php if ($product['price']) { ?>
                        <div class="prices">
														<?php if (!empty($product['promotion']['promotion_code'])) { ?>
														<span class="price fz-18 col-red"><?php echo $product['promotion']['promotion_price']; ?>&nbsp;&nbsp;</span>
                            <span class="price fz-12 text-delete"><?php echo $product['price']; ?></span>
														<?php } else { ?>
														<span class="price fz-18 col-red"><?php echo $product['price']; ?>&nbsp;&nbsp;</span>
														<?php } ?>
                        </div>
												<?php } ?>
                    </div>
                     <?php if($product['available']=='1'){?>
                    <div class="add-cart"><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img round btn-add-cart in-list"></span></div>
                            <?php }?>
                </li>
								<?php } ?>
            </ul>
        </div>
				<?php } ?>
    </div>
</div>
<script src="<?php echo HTTP_ASSETS;?>assets/js/jquery/jquery.scrollTo.js" type="text/javascript"></script>
<script>
$(function () {
    $("#m-category img").delayLoading({
		defaultImg: "<?php echo HTTP_CATALOG.$tplpath;?>images/loading.jpg",           // 预加载前显示的图片
		errorImg: "<?php echo HTTP_CATALOG.$tplpath;?>images/loading2.jpg",                        // 读取图片错误时替换图片(默认：与defaultImg一样)
		imgSrcAttr: "originalSrc",           // 记录图片路径的属性(默认：originalSrc，页面img的src属性也要替换为originalSrc)
		beforehand: 200,                       // 预先提前多少像素加载图片(默认：0)
		event: "scroll",                     // 触发加载图片事件(默认：scroll)
		duration: "fast",                  // 三种预定淡出(入)速度之一的字符串("slow", "normal", or "fast")或表示动画时长的毫秒数值(如：1000),默认:"normal"
		container: window,                   // 对象加载的位置容器(默认：window)
		success: function (imgObj) { },      // 加载图片成功后的回调函数(默认：不执行任何操作)
		error: function (imgObj) { }         // 加载图片失败后的回调函数(默认：不执行任何操作)
	});

});
</script>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/category.js"></script>
<?php if ($promotion&&$promotion['page_footer']){
echo $promotion['page_footer'];
}?>
<?php echo $this->getChild('module/sharebtn',array('btn_hide'=>'#phead'));?>

<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>