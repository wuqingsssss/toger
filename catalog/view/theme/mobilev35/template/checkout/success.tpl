<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/coupon/';
?>
<style>
.page{position: relative;}
.btn_share{
overflow:hidden;
font-size:18px;
background-color:#fff100;
border-radius: 5px;
padding: 8px 15px;
color:#804311;
margin: 10px 0;
}
a.btn_share{color:#804311;}
.btn_share img{
height:20px;
}
.black{
background:#000; opacity:0.8;filter: alpha(opacity=80); background-size:cover; width:100%; height:100%; position: fixed; top:0; left:0; z-index:5;
}
.hb_foot {
    width: 100%;
    text-align: center;
    margin-top:10%;
}
</style>
<div id="header" class="bar bar-header bar-positive">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="card">
  <div class="content">
  	<div class="tishi">
  		<div class="tishi-title"><?php echo $heading_title; ?></div>

   		<div class="tishi-content"><?php echo $text_message; ?></div>
<?php if($order_id&&$couponcode){
   		$detect = new Mobile_Detect();
 if(($detect->is_weixin_browser()||1))
{
?>
   		<div class="tishi-title">微信专享福利</div>
   		<div class="tishi-content">
给朋友们也发点红包吧
   		</div>
   		<div class="hb_foot">
   		<a id="share1" class="btn_share" ><img src="<?php echo $imgPath; ?>fenxiang2.png" />我要发红包</a> 		
   		<!-- a id="share2" class="btn_share" ><img src="<?php echo $imgPath; ?>fenxiang2.png" />分享给朋友</a-->
   		</div>
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
var linkparent='<?php echo HTTP_SERVER;?>index.php?route=promotion/couponpickup&code=<?php echo $couponcode;?>&partner=<?php echo $this->customer->getId();?>';
var deflaultimgurl='<?php echo HTTP_SERVER.$imgPath; ?>qc2.png';

obj.title ='双人套餐9.9，宅配包邮次日达，还有礼包等你拿';
obj.desc  = '菜君菜品超好，大波福利发放中';
obj.link  = linkparent+'&sid=1';
obj.imgUrl= deflaultimgurl;
sdata[1]=obj;
console.log(sdata);
$(document).ready(function()	
		{		
	    $('#share1').bind('click',function(){
		$('#blackbox').show();
		shareset(1);
		$('#blackbox').css('z-index','1000');
		$('#blackbox').show();
		
	});
	
	$('#share2').bind('click',function(){
		shareset(1);
	});
	
		});	

wx.ready(function () {
	//隐藏右上角按钮
	wx.hideOptionMenu();	
	shareset(1);
	wx.showOptionMenu();
});

function sharereturn()
{
	location.href="index.php?route=common/home";
	
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
		   title: sdata[n].title,
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

<?php }}?>
   		
	</div>
</div>
</div>
<div id="blackbox" class="black hide"><img src="<?php echo $imgPath; ?>fx.png" /></div>
<?php echo $footer; ?>