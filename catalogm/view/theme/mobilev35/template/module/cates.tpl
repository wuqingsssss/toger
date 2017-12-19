<?php if(!$ajax){?>
<link href="<?php echo HTTP_ASSETS.DIR_DIR;?>view/theme/mobilev35/css/pullToRefresh.css" rel="stylesheet"/>
<div class="module" id="m-week">
<?php if($setting['title']){?>
    <div class="title text-center">
        <div class="inline-block left tail"></div>
        <div class="fz-18 inline-block"><?php echo $setting['title'];?></div>
        <div class="inline-block right tail"></div>
    </div>
<?php }?>
    <?php if($setting['filter']){?>
    <div class="filter hidden">
        <div class="inline-block text-center">
            <div>
                <span class="inline-block fz-16">最新</span>
                <i class="icon icon-del with-left"></i>
            </div>
        </div>
        <div class="inline-block text-center">
            <div>
                <span class="inline-block fz-16">价格</span>
                <i class="icon icon-del with-left"></i>
            </div>
        </div>
        <div class="inline-block text-center">
            <div>
                <span class="inline-block fz-16">优惠活动</span>
                <i class="icon icon-del with-left"></i>
            </div>
        </div>
    </div>
    <?php }?>
</div>
 <?php if($setting['filter']){?>
<div class="overlay-container hidden" id="food-filter-overlay">
    <div class="overlay-content-container">
        <div class="overlay-content bg-white fz-14">
            <div class="filter">
                <div class="text-center">
                    <div>
                        <span class="inline-block fz-16">最新</span>
                        <i class="icon icon-del with-left"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div>
                        <span class="inline-block fz-16">价格</span>
                        <i class="icon icon-tri with-left"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div>
                        <span class="inline-block fz-16">优惠活动</span>
                        <i class="icon icon-del with-left"></i>
                    </div>
                </div>
            </div>
            <div class="list-wrapper">
                <div>
                    <a href="#">最热</a>
                    <a href="#">最新</a>
                    <a href="#">最多量</a>
                </div>
                <div>
                    <a href="#">0-10</a>
                    <a href="#">10-20</a>
                    <a href="#">20-50</a>
                    <a href="#">50以上</a>
                </div>
                <div>
                    <a href="#"><i class="bg-miao">秒</i>&nbsp;&nbsp;秒杀</a>
                    <a href="#"><i class="bg-tao">套</i>&nbsp;&nbsp;套餐</a>
                    <a href="#"><i class="bg-te">特</i>&nbsp;&nbsp;特价</a>
                    <a href="#"><i class="bg-xian">限</i>&nbsp;&nbsp;限时抢购</a>
                    <a href="#"><i class="bg-shou">首</i>&nbsp;&nbsp;新人专享</a>
                </div>
            </div>
        </div>
    </div>
</div>
 <?php }?>
<div class="module" id="m-foods">
    <ul id="m-foods-0" class="foods bg-body">
<?php }
$count=count($products);
foreach ($products as $index => $product) {  ?>
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
        <?php } 
        if(!$ajax){
        ?>
    </ul>
</div>
<script>
/**
 * @file ${FILE_NAME}. Created by PhpStorm.
 * @desc ${FILE_NAME}.
 *
 * @author yangjunbao
 * @since 15/10/29 上午10:31
 * @version 1.0.0
 */
$(function () {
	/* 分类筛选
    var $foodFilterOverlay = $('#food-filter-overlay');
    $foodFilterOverlay.tags = $foodFilterOverlay.find('.filter>div');
    $foodFilterOverlay.lists = $foodFilterOverlay.find('.list-wrapper>div');
    $foodFilterOverlay.init = function (index) {
        this.removeClass('hidden');
        this.tags.eq(index).trigger('touchend', [index]);
    };
    $foodFilterOverlay.tags.on('touchend', function (e, index) {
        var $this = $(this);
        index = index || $this.index();
        $this.find('i').removeClass('icon-del').addClass('icon-tri');
        $this.siblings().find('i').removeClass('icon-tri').addClass('icon-del');
        $foodFilterOverlay.lists.eq(index).show().siblings().hide();
    });
    $foodFilterOverlay.on('touchend', function(e) {
        if(e.target.className.indexOf('overlay-container') > -1) {
            $foodFilterOverlay.addClass('hidden');
        }
    });
    $('#m-week').find('.filter>div').on('click', function () {
        $foodFilterOverlay.init($(this).index());
    });
   /* */
    /*
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
*/
   
    $("#m-foods").trigger("scroll");
    
    var page=0,loading=false;
    
	Load();
    
    
  /* $("#m-foods").touchLoad(
    {'up':Load}		
    );

    
    if($(document).height()<=$(window).height()){
    	Load();
    }
    else
    	{
       $(window).on('scroll', function () {   	 
    
    	if(($(window).scrollTop()+$(window).height()+250>=$(document).height())) {
    		//console.log($(window).scrollTop(),$(window).height(),$(document).height());       	
    		Load();
    	}
    	
    });
    }*/
    function Load(){
    	       
    	if(page<0||loading){return;}
    	loading=true;
    	$.ajax({
    		url: 'index.php?route=module/cates'
    		<?php unset($setting['page']); foreach($setting as $key=>$item){ ?>		
    		+'&<?php echo "$key=$item";?>'<?php }?>
    		+'&page='+page,
    		dataType: 'json',
    		success: function(data) {
    			console.log(data);
    			loading=false;
    		    $("#m-foods").append('<ul id="m-foods-'+page+'" class="foods bg-body">'+data.output+'</ul>');

    		    $('#m-foods-'+page+' img').delayLoading({
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

    		     $('#m-foods-'+page+' .btn-add-cart').bind('click', function () {
    		            var $this = $(this);
    		            _.addCart($this.data('id'),$this.data('code'),1, function () {
    		 //           	_.addCartAnimation($this);
    		                $this.tipsBox('<span class="col-red fz-14 bold">+1</span>');
    		            });
    		        });
    		     
    		     page=data.setting.page;
    		     Load();
    		}
    	});
    }

    
});

</script>
<?php }?>