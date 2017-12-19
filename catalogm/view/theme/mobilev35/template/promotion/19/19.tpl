<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/';
$cssPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/css/';
$jsPath =  'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/template/promotion/'.$pid.'/img/';
?>
<link href="<?php echo $cssPath; ?>common.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $cssPath; ?>new_activity.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $jsPath; ?>zepto.js"></script>
<script src="<?php echo $jsPath; ?>common.js"></script>
    
<div class="header"><a href="index.php?route=common/home"><span data-return-url="" class="btn-return"></span></a><?php echo $product_info['name'];?></div>
<div class="wrapper">
    <div id="banner">
        <ul>
            <li id="pdt_img<?php echo $productid;?>">
            <?php echo htmlspecialchars_decode($product_info['description']);?>
            </li>
        </ul>
    </div>
    <div id="rule" class="clearfix">
        <div>
           <?php echo htmlspecialchars_decode($product_info['cooking']);?>
            <div id="rule-btn">
                <div id="btn-buy" class="btn">
                    <a href="javascript:addToCart('<?php echo $productid;?>');">点击购买<?php echo $product_info['name'];?></a>
                </div>
            </div>
        </div>
    </div>
    <div id="food-list">
        <h2>菜票限购菜品</h2>
        <ul class="clearfix">
             <?php foreach($productsrelated as $key=>$item ){?>
                <li>
                    <a href="<?php echo $item[href];?>" style="background-image: url(<?php echo $item[thumb];?>)">
                       <div>
                            <span><?php echo $item[name];?></span>
                            <span><?php echo $item[promotion]?$item[promotion_price]:$item[price];?></span>
                         </div>
                    </a>
                </li>
                <?php }?>
        </ul>
    </div>
</div>
<div id="blackbox" class="black hide"><img src="<?php echo $imgPath; ?>fx.png" /></div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $wx_appid;?>', // 必填，公众号的唯一标识
    timestamp:'<?php echo $assign[timestamp];?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $assign[noncestr];?>', // 必填，生成签名的随机串
    signature: '<?php echo $assign[signature];?>',// 必填，签名，见附录1
    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareWeibo','onMenuShareQQ'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
var sdata = new Array();
var obj = new Object();
var linkparent='<?php echo HTTP_SERVER;?>index.php?route=promotion/p&pid=<?php echo $pid;?>&productid=<?php echo $productid;?>';
var deflaultimgurl="<?php echo empty($coupon_info['share_image'])?HTTP_SERVER.$imgPath.'new_activity/gift.png':HTTP_IMAGE.$coupon_info['share_image'];?>";

obj.title ="<?php echo empty($coupon_info['share_title'])? $product_info['name'].'，菜君喊你吃晚餐!':$coupon_info['share_title'];?>";
obj.desc  = "<?php echo empty($coupon_info['share_title'])?'菜君菜品真是棒，还有福利大投放':$coupon_info['share_desc'];?>";

obj.link  = linkparent;
obj.imgUrl= deflaultimgurl;
sdata[1]=obj;
console.log(sdata);

wx.ready(function () {
	//隐藏右上角按钮
	wx.hideOptionMenu();	
	shareset(1);
	wx.showOptionMenu();
});

function sharereturn()
{
	$('#blackbox').hide();
}
function shareset(n)
{    //开启微信右上角按钮
	 // wx.showOptionMenu();
	 // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
	 
	 wx.onMenuShareAppMessage({
	      title: sdata[n].title,
	      desc:  sdata[n].desc,
	      link:  sdata[n].link,
	      imgUrl: sdata[n].imgUrl,
	      trigger: function (res) {
	        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回 
	      },
	      success: function (res) {
	    	  //领取优惠劵
	    	  //get_coupon(res)	    	  
	    	sharereturn();        
	      },
	      cancel: function (res) {
	    	  sharereturn();
	      },
	      fail: function (res) {
	        alert(JSON.stringify(res));
	        sharereturn();    
	      }
	    });
	// 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
	wx.onMenuShareTimeline({
		   title: sdata[n].title+','+obj.desc,
		   link:  sdata[n].link,
		   imgUrl: sdata[n].imgUrl,
	       trigger: function (res) {
	      // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
	    },
	    success: function (res) {
	    	//领取优惠劵
	    	  //get_coupon(res)
	    	sharereturn();
	    },
	    cancel: function (res) {
	    	sharereturn();
	    },
	    fail: function (res) {
	      alert(JSON.stringify(res));
	      sharereturn();
	    }
	  });
	 wx.onMenuShareQQ({
		   title: sdata[n].title,
		   desc:  sdata[n].desc,
		   link:  sdata[n].link,
		   imgUrl: sdata[n].imgUrl,
	      trigger: function (res) {
	    	  alert(sdata[n].title);
	      },
	      complete: function (res) {
	        alert(JSON.stringify(res));
	      },
	      success: function (res) {
	    	//领取优惠劵
	    	  sharereturn();
	      },
	      cancel: function (res) {
	    	  sharereturn();
	      },
	      fail: function (res) {
	        alert(JSON.stringify(res));
	        sharereturn();
	      }
	    });
	 wx.onMenuShareWeibo({
		   title: sdata[n].title,
		   desc:  sdata[n].desc,
		   link:  sdata[n].link,
		   imgUrl: sdata[n].imgUrl,
	      trigger: function (res) {
	        //alert('用户点击分享到微博');
	      },
	      complete: function (res) {
	        alert(JSON.stringify(res));
	        sharereturn();
	      },
	      success: function (res) {

	        sharereturn();
	      },
	      cancel: function (res) {
	        sharereturn();
	      },
	      fail: function (res) {
	        alert(JSON.stringify(res));
	        sharereturn();    
	      }
	    });
	 
	 console.log(sdata[n].title);
	  // 8.1 隐藏右上角菜单
	  //wx.hideOptionMenu();
	  // 8.2 显示右上角菜单
	 // wx.showOptionMenu();
	/*  //8.3 批量隐藏菜单项
	  wx.hideMenuItems({
	      menuList: [
	        'menuItem:readMode', // 阅读模式
	        'menuItem:share:timeline', // 分享到朋友圈
	        'menuItem:copyUrl' // 复制链接
	      ],
	      success: function (res) {
	        alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
	      },
	      fail: function (res) {
	        alert(JSON.stringify(res));
	      }
	    });
	// 8.5 隐藏所有非基本菜单项

	    wx.hideAllNonBaseMenuItem({
	      success: function () {
	        alert('已隐藏所有非基本菜单项');
	      }
	    });


	  // 8.6 显示所有被隐藏的非基本菜单项

	    wx.showAllNonBaseMenuItem({
	      success: function () {
	        alert('已显示所有非基本菜单项');
	      }
	    });


	  // 8.7 关闭当前窗口

	    wx.closeWindow();


	  // 9 微信原生接口
	  // 9.1.1 扫描二维码并返回结果

	    wx.scanQRCode();

	  // 9.1.2 扫描二维码并返回结果

	    wx.scanQRCode({
	      needResult: 1,
	      desc: 'scanQRCode desc',
	      success: function (res) {
	        alert(JSON.stringify(res));
	      }
	    });

	  */
}
</script>
<?php echo $footer; ?>