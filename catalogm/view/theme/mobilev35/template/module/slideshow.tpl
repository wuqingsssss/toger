<?php if($banners) { ?>
<style>
.ctrl{position:absolute;z-index:100; bottom:5px; width:100%;text-align: center; overflow:hidden;}
.ctrl span{ width:0.1rem; height:0.1rem;
 cursor:pointer; margin-right:1px; border:1px solid #ccc; background:#EAE7E7; display: inline-block;border-radius: 50%;color:#fff;}
.ctrl span.hov{background:#CFDC37; border:1px solid #CFDC37;color:#fff;font-weight:bold;}
</style>
<?php }?>
<?php if(!$ajax){?>
<div class="module banner banner-default<?php if($setting['class'])echo ' '.$setting['class'];?>" id="m-head-banner<?php echo $banner_id;?>">
<?php }?>
<?php if($banners) { ?>
<div id="ctrl" class="ctrl">
 <?php $first=true;$i=0; foreach ($banners as $banner){$i++; ?>
<span class="fz-12<?php if($first) echo ' hov';$first=false; ?>"></span>
 <?php } ?> 
</div>
    <ul>
     <?php $first=true; foreach ($banners as $banner) {  ?>
      <li<?php if(!$first) echo ' class="hidden"'; ?>>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>" style="display:block">
    <img <?php if(!$first) echo 'originalSrc';else echo 'src'; ?>="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-wrapper" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    </a>
    <?php } else { ?>
    <img <?php if(!$first) echo 'originalSrc';else echo 'src'; ?>="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-wrapper" data-thumb="<?php echo $banner['image']; ?>" data-slidecaption="" />
    <?php } ?>
    </li>
    <?php $first=false;} ?> 
    </ul>
    <?php } ?>
</div>
<?php if(!$ajax){?>
<script language="javascript">
$(function(){ 
	console.log('sideshow1');
	_.waiting&&_.waiting.show();
	$.ajax({
		url: 'index.php?route=module/slideshow'
		<?php foreach($setting as $key=>$item){ ?>		
		+'&<?php echo "$key=$item";?>'<?php }?>
		,
		dataType: 'json',
		success: function(data) {
			//console.log(data);	   
			 _.overlay&&_.overlay.destroy();
			 _.waiting&&_.waiting.hide();
            $("#m-head-banner<?php echo $banner_id;?>").html(data.output);
            $("#m-head-banner<?php echo $banner_id;?>").touchSlider({
    			flexible : true,
    		    speed : 200,
    		    auto:true,
    		    hrw:<?php echo $imageheight;?>/<?php echo $imagewidth;?>,
    		    delay: 3000,
    		paging : $("#m-head-banner<?php echo $banner_id;?> .ctrl span"),
    		counter : function (e){
    		$("#m-head-banner<?php echo $banner_id;?> .ctrl span").removeClass("hov").eq(e.current).addClass("hov");
    			}
    	});

    	 $("#m-head-banner<?php echo $banner_id;?> img").delayLoading({
    			//defaultImg: "<?php echo HTTP_CATALOG.$tplpath;?>images/loading.jpg",           // 预加载前显示的图片
    			//errorImg: "<?php echo HTTP_CATALOG.$tplpath;?>images/loading2.jpg",                        // 读取图片错误时替换图片(默认：与defaultImg一样)
    			imgSrcAttr: "originalSrc",           // 记录图片路径的属性(默认：originalSrc，页面img的src属性也要替换为originalSrc)
    			beforehand: 200,                       // 预先提前多少像素加载图片(默认：0)
    			event: false,                     // 触发加载图片事件(默认：scroll)
    			duration: "fast",                  // 三种预定淡出(入)速度之一的字符串("slow", "normal", or "fast")或表示动画时长的毫秒数值(如：1000),默认:"normal"
    			container: window,                   // 对象加载的位置容器(默认：window)
    			success: function (imgObj) { },      // 加载图片成功后的回调函数(默认：不执行任何操作)
    			error: function (imgObj) { }         // 加载图片失败后的回调函数(默认：不执行任何操作)
    		});
            
            
		}
	});	
	
	});
	   </script>
<?php } ?>