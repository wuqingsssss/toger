<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/home.css" rel="stylesheet"/>
<style>
<!--
.headline_bj{
	background-size: 100%;
	padding-bottom: 10px;
	margin-bottom: 12px;
}
.rule div{
	font-size: 14px;
	width: 95%;
	line-height: 15px;
	color: #ff3262;
	margin-top: 8px;
}
.module{
	width: 96%;
	margin-top: 8px;
}
-->
</style>
<?php echo $content_top; ?>
<?php echo $this->getChild('common/breadcrumb'); ?>
<a id="share1">
<?php if ($promotion&&$promotion['page_header']){
echo $promotion['page_header'];
}?>
</a>
<div class="module" id="m-foods">
	<?php 
	$count=count($productgroups);
		 foreach( $productgroups as $key=> $group){
if($count>1){
 ?>	 		 
 <ul class="bg-body title text-center"><?php if ($group['banner']){?>

        <div class="fz-18 inline-block"><img src="<?php echo HTTP_IMAGE.$group['banner'];?>" width="100%"></div>

 <?php }else{?>
        <div class="inline-block left tail"></div>
        <div class="fz-18 inline-block"><?php echo $key;?></div>
        <div class="inline-block right tail"></div>
     <?php }?>
    </ul>
    <?php }?>
    <ul class="foods bg-body">
<?php 
foreach ($group['data'] as $index => $product) {  ?>
        <li>
        <?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>" class="img-wrapper pull-left">
             <span class="sell_out<?php if($product['available']=='1') echo ' hidden';?>"><font class="sell_info"><?php echo $product['status_name']; ?></font></span>
            <img originalSrc="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a>
 <?php } ?>
            <div class="content">
                <div class="title fz-16 text-overflow"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></div>
                 <div class="tag hidden"> 
		      	   <?php if($product['icons']) {?>
                      <?php foreach($product['icons'] as $icon) {?>
                          <span class="icon fz-12  icon-message-tag"><?php echo $icon['tag'];?></span>
                      <?php }  ?>
                  <?php }?>
		      </div>
                <div class="intro col-gray fz-12 text-overflow"><?php echo $product['subtitle']; ?></div>
                <div class="activity">
                <?php
                $code=EnumPromotionTypes::clearCode($product['promotion']['promotion_code']);?>
                 <?php if ( $code==(EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
                    <i class="icon-word miao"></i>
                       <?php }?>
                     <?php if ( $product['combine'] ) { ?>
                    <i class="icon-word tao"></i>
                       <?php }?>
                    <?php if ( $code==(EnumPromotionTypes::PROMOTION_SPECIAL)) { ?>
                    <i class="icon-word te"></i>
                    <?php }?>
                     <?php if ( $code==(EnumPromotionTypes::PROMOTION_NORMAL)) { ?>
                    <i class="icon-word xian"></i>
                       <?php }?>
                     <?php if ( $code==(EnumPromotionTypes::REGISTER_DONATION)) { ?>
                    <i class="icon-word shou"></i>
                       <?php }?>
                </div><?php if ($product['price']) { ?>
                <div class="prices">
                 <?php if (!empty($product['promotion']['promotion_code'])) { ?>
                    <span class="price fz-18 col-red"><?php echo $product['promotion']['promotion_price']; ?>&nbsp;&nbsp;</span>
                    <span class="price fz-12 text-delete"><?php echo $product['price'];?></span>
                     <?php } else { ?>
                      <span class="price fz-16 col-red"><?php echo $product['price'];?></span>
                      <?php } ?>
                </div>
                 <?php } ?>
                 <?php if($product['available']=='1'){?>
                <div class="add-cart"><span data-id="<?php echo $product['product_id']; ?>" data-code="<?php echo $product[promotion][promotion_code];?>" class="btn-img round btn-add-cart in-list" ></span></div>
            <?php }?>
            </div>
        </li>
        <?php } ?>
    </ul>
          <?php }?>
</div>
<?php echo $content_bottom; ?>
<?php if ($promotion&&$promotion['page_footer']){
echo $promotion['page_footer'];
}?>
<script type="text/javascript">
 $(document).ready(function(){
	$('.display .grid').click(function(){ display('grid');});

	$('.display .list').click(function(){ display('list'); });
	
	if($('.product-grid').length > 0){
		view = $.cookie('display');
		
		if (view=='list') {
			display(view);
		}
	};
	
    $("#m-foods img").delayLoading({
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
 
 //分享成功后处理
 function share_promotion_success(obj){
	 console.log(obj);
	 var url='index.php?route=promotion/promotion/share_success';
	 $.getJSON(url,
			   obj,
			 function(e) {
		 console.log(e);
		 if(!e.error)
			 {
			   if (e.redirect) {
				 window.location = e.redirect;
				}	    
			 }
    });    	
 }

</script>
<?php echo $this->getChild('module/navbar');?>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<?php echo $footer35; ?>