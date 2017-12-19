<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/coupon/';
?>
<link href="<?php echo $cssPath; ?>couponpickup.css" rel="stylesheet">
<div class="hb">
   <div class="hb_top"><img src="<?php echo $imgPath; ?>cjsfl.png"/></div>
    <div id="hb_body" class="hb_body">
     <div id="hb_tt" class="hb_tt<?php if($success!='-2')echo ' hide' ;?>"><img src="<?php echo $imgPath; ?>title2.png"/></div>
        <div class="hb_title">
        <img src="<?php echo $imgPath; ?>title1.png"/>
        </div>
        
        <div id="box1" class="<?php if($success!='0')echo ' hide' ;?>">
         <div class="hb_box">
           <input type="text" id="user_phone" name="user_phone" placeholder="请输入您的手机号" />
           <span id="error_msg" class='msg'></span>
        </div>
       <div class="hb_foot">
               <a id="ljlq" class="btn">立即领取</a>
        </div>
        </div>

<div id="box2" class="<?php if($success=='0')echo ' hide' ;?>">
         <div id="successbox" class="hb_box2">
        恭喜您获得<font style="color:red;"><?php echo $packet_info['name'];?></font> 红包           
         </div>
         <div class="hb_foot">
               <a id="ljck" href="index.php?route=account/account" class="btn">立即查看</a>
               <?php if(is_weixin_browser){ ?>
                <a id="share1" class="btn">分享好友</a>
               <?php } ?>
         </div>
         <div class="hb_info">
         <?php echo str_replace(array("<br/>","\r\n", "\r", "\n"),array("","<br/>", "<br/>", "<br/>"),$packet_info['usage']);?>
         
</div>
</div>

    </div>

        <div id="hb_body2" class="hb_body2 hide">
        <div class="hb_title">
        <img src="<?php echo $imgPath; ?>ohya.png"/>
        </div>
        <div id="box1">
         <div class="hb_box">
        </div>
       <div class="hb_foot">
               <a href="index.php?route=common/home" class="btn">去菜君家</a>
        </div>
        </div>
    </div>

</div>
<div id="blackbox" class="black hide"><img src="<?php echo $imgPath; ?>fx.png" /></div>
<script type="text/javascript">
$("#ljlq").bind('click',function(){

	 $.ajax({
			url: 'index.php?route=promotion/packetpickup/pickup',
			type: 'post',
			data: 'userphone='+$('#user_phone').val()+'&partner=<?php echo $partner;?>&pid=<?php echo $pid;?>',
			dataType: 'json',
			success: function(json) {
			       	console.log(json);
			        $("#hb_tt").hide();
				if(json.success>0){
			
					  $('#successbox').html('恭喜您获得<font style="color:red;">'+ json.packet_info.name+'</font> 优惠券 ');
					  $('#box1').hide(); 
					  $('#box2').show();  
	            }
				else if(json.success=='-1')
	            {
	            	  $('#error_msg').html(json.msg);
	            }
				else if(json.success=='-2')
	            { 	$("#hb_tt").show();
					  $('#successbox').html('恭喜您获得<font style="color:red;">'+ json.packet_info.name+'</font> 优惠券 ');
					  $('#box1').hide(); 
					  $('#box2').show();  
	            }
				else if(json.success=='-3')
	            { 	
	            	  $('#hb_body').hide(); 
					  $('#hb_body2').show();  
	            }else if(json.success=='-4')
	            {
	          
	            	$('#successbox').html('恭喜您获得<font style="color:red;">菜君50元大礼包</font>，已经放入您的账户中');
	            	$('#ljck').html('注册领取');
	            	$('#ljck').attr('href','index.php?route=account/register');
					$('#box1').hide(); 
					$('#box2').show();
					
	            	//$('#hb_body').hide(); 
					//$('#hb_body2').show(); 

	            }
	            else{
	            	  $('#error_msg').html(json.msg);
			   }
	      }
	 });

	});
</script>
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
var linkparent='<?php echo HTTP_SERVER;?>index.php?route=promotion/packetpickup&code=<?php echo $packet_code;?>&partner=<?php echo $partner; ?>';
var deflaultimgurl="<?php echo empty($packet_info['share_image'])?HTTP_SERVER.$imgPath.'qc2.png':HTTP_IMAGE.$packet_info['share_image'];?>";

obj.title ="<?php echo empty($coupon_info['packet_title'])?'双人套餐9.9，宅配包邮次日达，还有红包等你拿':$packet_info['share_title'];?>";
obj.desc  = "<?php echo empty($coupon_info['packet_title'])?'菜君菜品超好，大波福利发放中':$packet_info['share_desc'];?>";
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
