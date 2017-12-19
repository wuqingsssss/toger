<?php echo $header35; ?>
<?php echo $this->getChild('module/navtop');?>
<link type="text/css" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/food_item.css"/>
<!-- 公共头结束 -->
<div class="module with-bottom" id="m-head">
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


    <div class="title fz-16"><?php echo $heading_title; ?><?php echo $subtitle; ?></div>
    <div class="info clearfix">
    
      <?php if ($price) { ?>
					  <div class="pull-left">
        				<?php if (!isset($promotion['promotion_price'])) { ?>
    					 <span class="fz-18 col-red"><?php echo $price; ?></span>
    					<?php } else { ?>
    					<span class="fz-18 col-red"><?php echo $promotion['promotion_price']; ?></span>
    					<span class="fz-12 text-delete"><?php echo $price; ?></span>
    					<?php } ?>
					 </div>
	 <?php } ?>

        <div class="pull-right">
            <i class="icon icon-heart"></i>
            <i class="icon icon-comment"></i>
        </div>
    </div>
</div>
<div class="module with-bottom" id="m-info">
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
<div class="module" id="m-tab">
    <a href="#m-intro" class="col-red"><span>介绍</span></a>
    <a href="#m-method"><span>做法</span></a>
    <a href="#m-comment"><span>评价</span></a>
</div>
<div class="module with-bottom" id="m-intro">
    <div class="title"><?php echo $tab_description; ?></div>
    <div class="content">
       <?php echo $description; ?>
    </div>
</div>
<div class="module with-bottom" id="m-method">
    <div class="title"><?php echo $tab_cooking; ?></div>
    <div class="content">
               <?php echo $cooking; ?>
    </div>
</div>
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
<div class="module bg-body" id="m-add-cart" data-id="<?php echo $product_id?>" data-code="<?php echo $promotion[promotion_code];?>">
    <div class="add-cart bg-white">
        <input type="button" value="加入购物车" class="btn btn-fixed-submit pull-right"/>

        <div class="pull-left">
            <i class="icon icon-subtract"></i>
            <span class="col-red fz-15 food-num-val cart-num">1</span>
            <i class="icon icon-add"></i>
        </div>
    </div>
</div>
<?php echo $footer35; ?>
<script type="text/javascript">
$(function () {
    var $mAddCart = $('#m-add-cart'),
        foodId = $mAddCart.data('id'),
        code = $mAddCart.data('code'),
        $num = $mAddCart.find('.food-num-val');
    $mAddCart.on('click', 'input', function () {
        _.addCart(foodId, code,$num.html(),function () {
        	$mAddCart.find('.btn-fixed-submit').tipsBox('<span class="col-red fz-14 bold">+'+$num.html()+'</span>');
        	window.location="index.php?route=checkout/cart";
        });
    }).on(_.touchEnd+' '+_.onClick, '.icon-subtract', function () {
        $num.html(+$num.html() - 1 || 1);
    }).on(_.touchEnd+' '+_.onClick, '.icon-add', function () {
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