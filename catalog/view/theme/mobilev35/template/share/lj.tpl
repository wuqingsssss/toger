<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/share/';
$jsPath =  'catalog/view/theme/'.$template.'/javascript/share/';
$imgPath = 'catalog/view/theme/'.$template.'/image/share/lj/';
?>
<meta charset=UTF-8>
<meta name=apple-mobile-web-app-capable content="no">
<meta name=apple-mobile-web-app-status-bar-style content="black">
<meta name=viewport
	content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
<link href="<?php echo $cssPath; ?>lj.css" rel=stylesheet type="text/css">
<script src=<?php echo $jsPath; ?>zepto.js></script>
<script src=<?php echo $jsPath; ?>activity.js></script>
	<div class=page id=page-intro>
		<div class=content id=intro-title>
			<img src=<?php echo $imgPath;?>intro-title.png alt="title">
		</div>
		<div class="button button-next infinite animated pulse" id=intro-join>
			<img src=<?php echo $imgPath;?>intro-join.png alt="join">
		</div>
	</div>
	<div class="page page-question common-bg" id=page-breakfast>
		<div class="content cq" id=breakfast-question>
			<img src=<?php echo $imgPath;?>common-question.png alt="question">
		</div>
		<div class="content cq-number" id=breakfast-question-number>
			<img src=<?php echo $imgPath;?>breakfast-number.png alt="1">
		</div>
		<div class="content cq-title" id=breakfast-question-title>
			<img src=<?php echo $imgPath;?>breakfast-title.png alt="title">
		</div>
		<div class="content cq-wrapper" id=breakfast-question-wrapper>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-first"
						id=breakfast-qo-first>
						<img src=<?php echo $imgPath;?>breakfast-question-first.png alt="first">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-second"
						id=breakfast-qo-second>
						<img src=<?php echo $imgPath;?>breakfast-question-second.png alt="second">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-third"
						id=breakfast-qo-third>
						<img src=<?php echo $imgPath;?>breakfast-question-third.png alt="third">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-fourth"
						id=breakfast-qo-fourth>
						<img src=<?php echo $imgPath;?>breakfast-question-fourth.png alt="fourth">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page page-question common-bg" id=page-lunch>
		<div class="content cq" id=lunch-question>
			<img src=<?php echo $imgPath;?>common-question.png alt="question">
		</div>
		<div class="content cq-number" id=lunch-question-number>
			<img src=<?php echo $imgPath;?>lunch-number.png alt="1">
		</div>
		<div class="content cq-title" id=lunch-question-title>
			<img src=<?php echo $imgPath;?>lunch-title.png alt="title">
		</div>
		<div class="content cq-wrapper" id=lunch-question-wrapper>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-first"
						id=lunch-qo-first>
						<img src=<?php echo $imgPath;?>lunch-question-first.png alt="first">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-second"
						id=lunch-qo-second>
						<img src=<?php echo $imgPath;?>lunch-question-second.png alt="second">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-third"
						id=lunch-qo-third>
						<img src=<?php echo $imgPath;?>lunch-question-third.png alt="third">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-fourth"
						id=lunch-qo-fourth>
						<img src=<?php echo $imgPath;?>lunch-question-fourth.png alt="fourth">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page page-question common-bg" id=page-dinner>
		<div class="content cq" id=dinner-question>
			<img src=<?php echo $imgPath;?>common-question.png alt="question">
		</div>
		<div class="content cq-number" id=dinner-question-number>
			<img src=<?php echo $imgPath;?>dinner-number.png alt="1">
		</div>
		<div class="content cq-title" id=dinner-question-title>
			<img src=<?php echo $imgPath;?>dinner-title.png alt="title">
		</div>
		<div class="content cq-wrapper" id=dinner-question-wrapper>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-first"
						id=dinner-qo-first>
						<img src=<?php echo $imgPath;?>dinner-question-first.png alt="first">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-second"
						id=dinner-qo-second>
						<img src=<?php echo $imgPath;?>dinner-question-second.png alt="second">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
			<div class=cq-line>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-third"
						id=dinner-qo-third>
						<img src=<?php echo $imgPath;?>dinner-question-third.png alt="third">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
				<div class=cq-item>
					<div class="button button-next cq-option cq-option-fourth"
						id=dinner-qo-fourth>
						<img src=<?php echo $imgPath;?>dinner-question-fourth.png alt="fourth">
						<div class="content cq-selected hidden"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page page-result" id=page-result-1>
		<div class=content id=result-1-bottom>
			<img src="<?php echo $imgPath;?>result-1-bottom.png">
		</div>
		<div class=content id=result-1-middle>
			<img src="<?php echo $imgPath;?>result-1-middle.png">
		</div>
		<div class=content id=result-1-top>
			<img src="<?php echo $imgPath;?>result-1-top.png">
		</div>
		<div class="hide js-arrow ui-up-arrow" style="display: block">
			<a href=javascript: class="button-next button-to-share"><img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示> <img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示></a>
		</div>
	</div>
	<div class="page page-result" id=page-result-2>
		<div class=content id=result-2-bottom>
			<img src="<?php echo $imgPath;?>result-2-bottom.png">
		</div>
		<div class=content id=result-2-top>
			<img src="<?php echo $imgPath;?>result-2-top.png">
		</div>
		<div class="hide js-arrow ui-up-arrow" style="display: block">
			<a href=javascript: class="button-next button-to-share"><img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示> <img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示></a>
		</div>
	</div>
	<div class="page page-result" id=page-result-3>
		<div class=content id=result-3-bottom>
			<img src="<?php echo $imgPath;?>result-3-bottom.png">
		</div>
		<div class=content id=result-3-top>
			<img src="<?php echo $imgPath;?>result-3-top.png">
		</div>
		<div class="hide js-arrow ui-up-arrow" style="display: block">
			<a href=javascript: class="button-next button-to-share"><img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示> <img
				src=<?php echo $imgPath;?>arrow-up.png alt=滑动提示></a>
		</div>
	</div>
	<div class=page id=page-share>
		<div class=content id=share-top>
			<img src=<?php echo $imgPath;?>share-top.png alt="top">
		</div>
		<div class="content animated pulse infinite" id=share-redirect>
			<img src=<?php echo $imgPath;?>share-redirect.png alt="redirect">
		</div>
		<div class="content animated pulse infinite" id=share-share>
			<img src=<?php echo $imgPath;?>share-share.png alt="share">
		</div>
		<div class=content id=share-copyright>
			<img src=<?php echo $imgPath;?>share-copyright.png alt="copyright">
		</div>
		<div class="content hidden" id=share-qrcode>
			<div class=content>
				<img src=<?php echo $imgPath;?>qrcode.JPG alt="qrcode">
			</div>
		</div>
		<div class="content hidden" id=share-popup>
			<div class=content>
				<img src=<?php echo $imgPath;?>share-popup.png alt="share">
			</div>
		</div>
		<div class="animated button pulse2 infinite button-next"
			id=page-to-home>重新测试</div>
	</div>
	<div class=spinner-wrapper>
		<div class=spinner>
			<div class=double-bounce1></div>
			<div class=double-bounce2></div>
		</div>
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
var linkparent='<?php echo HTTP_SERVER;?>index.php?route=share/lj';
var deflaultimgurl="<?php echo HTTP_SERVER.$imgPath.'share.jpg'?>";

obj.title ="饥饿测试站";
obj.desc  = "你饥到要要要，我饿到不要不要，来此测试，告别饥饿！";
obj.link  = linkparent;
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
		});	

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