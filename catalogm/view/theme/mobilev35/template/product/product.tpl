<?php echo $header35; ?>
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'<a class="return" href="javascript:_.go();"></a>',
       'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
       'right'=>'<a class="search" href="javascript:"></a>
                 <a class="message has-new" href="javascript:"></a>'
),"wechathidden"=>1));?>
<link type="text/css" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/food_item_new.css"/>
<!-- 公共头结束 -->

<div class="module" id="m-banner">
    <?php if ($thumb) { ?>
    <div class="img-wrapper"><img src="<?php echo $thumb; ?>"></div>
<?php } ?>
<?php if($images) { ?>
<div class="module banner banner-default" id="m-product-images">
    <ul>
     <?php foreach ($images as $image) { ?>
      <li>
    <?php if ($image['link']) { ?>
    <a href="<?php echo $image['link']; ?>">
    <img src="<?php echo $image['image']; ?>" alt="<?php echo $image['title']; ?>" class="img-wrapper" data-thumb="<?php echo $image['image']; ?>" data-slidecaption="" />
    </a>
    <?php } else { ?>
    <img src="<?php echo $image['image']; ?>" alt="<?php echo $image['title']; ?>" class="img-wrapper" data-thumb="<?php echo $image['image']; ?>" data-slidecaption="" />
    <?php } ?>
    </li>
    <?php } ?> 
    </ul>
</div>
<?php } ?>
</div>
<div class="module" id="m-info">
    <div>
        <div>
            <i class="icon icon-weight"></i>
            <span><?php echo $unit; ?></span>
        </div>
    </div>
    <div>
        <div>
            <i class="icon icon-time"></i>
            <span><?php echo $cooking_time;?></span>
        </div>
    </div>
    <div>
        <div>
            <i class="icon icon-energy"></i>
            <span><?php echo $calorie;?></span>
        </div>
    </div>
</div>
<div class="module fz-16" id="m-mark"><?php echo $heading_title; ?><br><br><span class="fz-12 col-gray"><?php echo $subtitle; ?></span></div>

<?php if ($price) { ?>
<div class="module" id="m-price">
 <?php if (!isset($promotion['promotion_price'])) { ?>
    					 <span class="fz-18 col-red"><?php echo $price; ?></span>
    					<?php } else { ?>
    					<span class="fz-18 col-red"><?php echo $promotion['promotion_price']; ?></span>
    					<span class="fz-12 text-delete"><?php echo $price; ?></span>
    					<?php } ?>

</div>
	 <?php } ?>

<?php if($description||$cooking){?>
<?php if($description){?>
<div class="module" id="m-intro">
    <div class="fi-title">
        <div class="upon"><?php echo $tab_description; ?></div>
        <div class="down">Introduction</div>
    </div>
    <div class="fi-content fz-12">
    <?php echo $description; ?>
        
    </div>
</div>
<?php } ?>
<?php if($cooking){?>
<div class="module" id="m-step">
    <div class="fi-title">
        <div class="upon"><?php echo $tab_cooking; ?></div>
        <div class="down">procedure</div>
    </div>
    <div class="fi-content fz-12">
    <?php echo $cooking; ?>
    </div>
</div>
<?php } ?>
<?php } ?>
<div class="module with-bottom">
		<img src="<?php echo HTTP_CATALOG;?>image/page/shicaibaozheng_m.jpg" width="100%" />
	</div>
<div class="module with-bottom hidden" id="m-comment">
    <div class="title">菜品评价<span class="fz-12">(152条评价)</span></div>
    <div class="content">
        <div class="comment">
            <div class="info clearfix">
                <div class="img-wrapper pull-left"><img src="<?php echo HTTP_CATALOG.$tplpath;?>images/avatar.jpg"></div>
                <div class="username pull-left">村里的姑娘</div>
                <div class="pull-right">2015-10-12</div>
            </div>
            <div class="content">遇见对的人，自然会笑，吃了对的菜，自然会美。这是一道清润滋补的上佳菜品。西芹能镇静安神、排除肠毒。</div>
        </div>
        <div class="comment">
            <div class="info clearfix">
                <div class="img-wrapper pull-left"><img src="<?php echo HTTP_CATALOG.$tplpath;?>images/avatar.jpg"></div>
                <div class="username pull-left">村里的姑娘</div>
                <div class="pull-right">2015-10-12</div>
            </div>
            <div class="content">遇见对的人，自然会笑，吃了对的菜，自然会美。这是一道清润滋补的上佳菜品。西芹能镇静安神、排除肠毒。</div>
        </div>
    </div>
    <div class="more"><a href="#">查看全部评论>></a></div>
</div>
<div class="module bg-body" id="m-add-cart" data-id="<?php echo $product_id?>" data-code="<?php echo $promotion['promotion_code'];?>">
    <div class="add-cart bg-white">
    <?php if($product_info[available]=='1'){?>
        <div class="btn btn-fixed-submit bg-red pull-right"> 加入购物车</div>
        
<?php }else{ ?>
<div class="btn btn-fixed-box bg-gray pull-right"><?php echo $text_product_available[$product_info[available]];?></div>
<?php } ?>
        <div class="pull-left">
            <i class="icon icon-subtract"></i>
            <span class="col-red fz-15 food-num-val cart-num">1</span>
            <i class="icon icon-add"></i>
        </div>
    </div>
</div>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<?php echo $footer35; ?>
<script type="text/javascript">
$(function () {
	   var $mAddCart = $('#m-add-cart'),
       foodId = $mAddCart.data('id'),
       code = $mAddCart.data('code'),
       $num = $mAddCart.find('.food-num-val');
   
   $('#m-add-cart .btn-fixed-submit').bind('click', function () {
       _.addCart(foodId, code,$num.html(),function () {
       	$mAddCart.find('.btn-fixed-submit').tipsBox('<span class="col-red fz-14 bold">+'+$num.html()+'</span>');
       	window.location="index.php?route=checkout/cart";
       });
   });

   $('#m-add-cart .icon-subtract').bind('click', function () {
       $num.html(+$num.html() - 1 || 1);
   });
   $('#m-add-cart .icon-add').bind('click', function()  {
       $num.html(+$num.html() + 1);
   });
   
   
    var $addFollow = $('.icon-heart');
    $addFollow.bind('click', function () {
        _.addFollow(foodId,function () {
        	$addFollow.tipsBox('<span class="col-red fz-14 bold">&#10084;+1</span>');
        });
    });
    
});
</script>