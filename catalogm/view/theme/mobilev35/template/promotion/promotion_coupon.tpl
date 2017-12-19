<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/coupon/';
?><style>
.cursor{cursor: pointer;}
#content{margin: 0 auto;max-width:640px;min-width:200px;height:100%;position:relative;background-color:#00bab4;}
/*登录前领卷*/
#level1{
        position:absolute;
        width:640px;
		left:0px;
        top:120px;
}
#level1 ul{
        clear:both;
        width:100%;
        position:relative; 
        padding-top: 5%;
        text-align:center;  
}

level1_top2{dispaly:none;}

#level1 li .btn{width:135px;text-align:left;margin-right: 10px;font-size: 18px;color: 000;font-weight:bolder;text-align:center;
   border:none;
    background:url(<?php echo $imgPath; ?>btn_bg.png) no-repeat top center;}
    
#level1 li .btn.black{
    background:url(<?php echo $imgPath; ?>btn_bg1.png) no-repeat top center;}

#level1 li .btncode {width:165px;text-align:left;font-size:16px;margin-right: 10px;
    -moz-border-radius: 5px;      /* Gecko browsers */
    -webkit-border-radius: 5px;   /* Webkit browsers */
    border-radius:5px;            /* W3C syntax */}}
#level1 li  #recodeget{width:135px;}

#level1 li.msg{color:red;font-size:20px;display:block;line-height:26px;height:26px;}
#level1 li.msg a{color:red;font-size:20px;display:block;line-height:26px;height:26px;}
#level1 li.txt_gx {color:red;font-size:20px;display:block; line-height:40px;}
#level1 li.txt_gx a{color:red;font-size:20px;display:block; line-height:40px;}
#leve1_pwd{display: none;}
/*分享选择框*/
#level2{
		background-color:#dfdfdf;
		padding-top:5%;
		width:640px;
}

#level2_top li{padding:5px;
        width:640px;
		height:111px;
		position:relative; 
		text-align: center;	
		z-index:999;	
}
#level2_body{
        width:640px;
		height:692px; 
		position:relative;
        background:url(<?php echo $imgPath; ?>l2_bg.png) no-repeat;
		background-color:#dfdfdf;
		background-size:640px 633px;
		BACKGROUND-POSITION:0px 59px;		
}
#level2_body li{
        position: absolute;
        width:160px;
		height:70px; 
        text-indent: -2000px;
        cursor: pointer;
        opacity:0.8;
}
#level2_body li.l21{
        background:url(<?php echo $imgPath; ?>l2_1_bg.png) no-repeat;
        background-size:98px 45px;
        left:440px;
        top:305px;      
}
#level2_body li.l22{
        background:url(<?php echo $imgPath; ?>l2_2_bg.png) no-repeat;
        background-size:130px 60px;
        left:145px;
        top:123px;
}
#level2_body li.l23{
        background:url(<?php echo $imgPath; ?>l2_3_bg.png) no-repeat;
        background-size:130px 60px;
        left:360px;
        top:160px;
}
#level2_body li.l24{
        background:url(<?php echo $imgPath; ?>l2_4_bg.png) no-repeat;
        background-size:130px 60px;
        left:475px;
        top:446px;
}
#level2_body li.l25{
        background:url(<?php echo $imgPath; ?>l2_5_bg.png) no-repeat;
         background-size:130px 60px;
        left:65px;
        top:450px;
}
#level2_body li.l26{
        background:url(<?php echo $imgPath; ?>l2_6_bg.png) no-repeat;
         background-size:130px 60px;
        left:190px;
        top:269px;
}
#level2_body li.l27{
        background:url(<?php echo $imgPath; ?>l2_7_bg.png) no-repeat;
         background-size:98px 45px;
        left:50px;
        top:274px;
}
#level2_body li.l28{
        background:url(<?php echo $imgPath; ?>l2_8_bg.png) no-repeat;
        background-size:148px 64px;
        left:260px;
        top:424px;
}

#level2_foot li{
        width:640px;
		height:237px; 
		position:relative;
        background-color:#00bab4;
		text-align:center;

}
/*分享确认*/
#level3{
       width:640px;
       }
     #level3 ul{clear:both;
        width:100%;
        position:relative; 
        text-align:center;  
        background-color:#00bab4;
       }  

   #level3_1{display:none;}
   #level3_2{display:none;}
   #level3_body{padding-top: 10%;padding-bottom:  20%;}

/*分享结果指向*/
#level4{display:none;
        position: absolute;
        width:640px;
		height:760px; 
		left:0px;
        top:0px;
        background:url(<?php echo $imgPath; ?>l4_bg.png) no-repeat;
		background-size:402px 322px;
		BACKGROUND-POSITION:230px 5px;	
}
#recheck{text-align: center;}

#blackbox{
		  display:none;
		  width:640px; 
		  height:1050px; 
          position: absolute;
          left:0px;
          top:0px;
		  background-color:#000;
		  overflow: hidden;
		  z-index: 1000;
}
</style>
<div id="content">
<div id='level1'>
<ul><li style="text-align: right;margin-right: 20px;"><img src="<?php echo $imgPath; ?>logo2.png" width="35%" /></li></ul>
     <ul id="level1_top">
         <li><img src="<?php echo $imgPath; ?>l1-2.png" /></li>
     </ul>
      <ul id="level1_top2">
         <li id="ltop1"><img src="<?php echo $imgPath; ?>l3-4-1.png" /></li>
         <li id="ltop2"><img src="<?php echo $imgPath; ?>l3-3-1.png" /></li>
     </ul>
     <ul>
        <li><input id="user_phone" name="user_phone" type="text" class="btncode button" value="请输入您的手机号码 ">
            <input id="recodeget" type="button" value="获取验证码" class= "button btn"/> 
        </li>
     </ul>
     <ul>
         <li><input id="recode" name="recode" type="text" class="btncode button" value="请输入验证码">
             <input id="getcoupon" type="button" value="领取菜票" class= "button btn"/> 
         </li>
    </ul>
      <ul id="leve1_pwd">
         <li><input id="user_pwd" name="user_pwd" type="password" class="btncode button" value=""> 
             <input id="cur_pwd" type="button" value="请输入密码" class= "button btn"/>       
         </li>
    </ul>
    <ul style="height:106px;">
          <li id="user_msg" class="msg"></li>
          <li class="txt_gx"></li>
     </ul>
      <ul>
         <li><img id='usersubmit' src="<?php echo $imgPath; ?>l1-5.png" class="cursor"/></li>
    </ul>
</div>
<div id='level2'>
    <ul id="level2_top"><li><img src="<?php echo $imgPath; ?>qctop.png" /></li></ul>
    <ul id='level2_body'>
        <li class="l21"><a href="<?php echo $sharelink;?>">请妹子</a></li>
        <li class="l22"><a href="<?php echo $sharelink;?>">请基友</a></li>  
        <li class="l23"><a href="<?php echo $sharelink;?>">请闺蜜</a></li>
        <li class="l24"><a href="<?php echo $sharelink;?>">请帅哥</a></li>
        <li class="l25"><a href="<?php echo $sharelink;?>">请领导</a></li>
        <li class="l26"><a href="<?php echo $sharelink;?>">请同学</a></li>
        <li class="l27"><a href="<?php echo $sharelink;?>">请大叔</a></li>
        <li class="l28"><a href="<?php echo $sharelink;?>">请前任</a></li>
    </ul>
    <ul id='level2_foot'>
     <li><img src="<?php echo $imgPath; ?>qsc.png"/></li>
    </ul>
</div>
<div id='level3'>
   <ul id='level3_1'>
        <li id='level3_top'><img src="<?php echo $imgPath; ?>l3-3-1.png" width="95%"/></li>
        <li id='level3_top_foot'><img src="<?php echo $imgPath; ?>l3-bg2.png"/></li>
    </ul>
    <ul id="level3_2">
        <li id='level3_body'><img src="<?php echo $imgPath; ?>l3-4-1.png" width="95%"/></li>
        <li id='level3_foot'><img src="<?php echo $imgPath; ?>l3-2-1.png" width="70%"/></li>
        <li id="recheck" class="cursor"><img src="<?php echo $imgPath; ?>l1-7.png" width="70%"/></li>
    </ul>
</div>
<div id='level4'>
</div>
<div id='blackbox'>
</div>
</div>
<?php $detect = new Mobile_Detect();
 if($detect->is_weixin_browser()||1)
{
?>
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

var uid=0;
var sdata = new Array();
var linkparent='http://www.qingniancaijun.com.cn/index.php?route=promotion/coupon';
var deflaultimgurl='http://www.qingniancaijun.com.cn/<?php echo $imgPath; ?>qkimg.png';
var obj = new Object();
    obj.title ='连请妹纸吃顿饭都犹豫，就别说自己是暖男';
    obj.desc  = '别人都说你太man，商场血拼一整，天只有我懂你的美，请你来吃暖心餐';
    obj.link  = linkparent+'&sid=1';
    obj.imgUrl= deflaultimgurl;
sdata[1]=obj;
var obj = new Object();
    obj.title = '好基友，就要一晚（碗）一被（辈）子';
    obj.desc  = '做你基友真美妙,遥记当年捡肥皂,文字表达不可靠,直接给你送菜票';
    obj.link  = linkparent+'&sid=2';
    obj.imgUrl= deflaultimgurl;
sdata[2]=obj;
var obj = new Object();
    obj.title = '我吃饭不许你节食，是闺蜜就要共同面对体重';
    obj.desc  = '我们本是好姐妹,八卦逛街从不累,我吃饭也要你陪,面对体重共进退';
    obj.link  = linkparent+'&sid=3';
    obj.imgUrl= deflaultimgurl;   
sdata[3]=obj;
var obj = new Object();
   obj.title = '帅哥是姐用来心疼的，不是那些小浪蹄子用来欣赏的';
   obj.desc  = '可怜常做加班狗,现在还没女朋友,别总惦记撸啊撸,姐来解放你双手';
   obj.link  = linkparent+'&sid=4';
   obj.imgUrl= deflaultimgurl;
sdata[4]=obj;
var obj = new Object();
   obj.title = '领导！下次应酬带上我，身体要紧，喝酒我来！';
   obj.desc  = '火车能跑这么快,都靠领导长得帅,真心不为拍马屁,就想请你吃个菜';
   obj.link  = linkparent+'&sid=5';
   obj.imgUrl= deflaultimgurl;
sdata[5]=obj;
         
var obj = new Object(); 
   obj.title = '出来混饭卡，早晚要还的。不就是请老同学吃个饭么';
   obj.desc  = '过去找你蹭饭卡,换来五句草泥马,今天装回高富帅,请你吃饭真潇洒';
   obj.link  = linkparent+'&sid=6';
   obj.imgUrl= deflaultimgurl;
sdata[6]=obj;
var obj = new Object();
   obj.title = '我愿为你做晚饭，大叔收留我走可好';
   obj.desc  = '大叔底下好乘凉,年龄差距又何妨,有情饮水也能饱,甘愿做你小厨娘';
   obj.link  = linkparent+'&sid=7';
   obj.imgUrl= deflaultimgurl;
sdata[7]=obj;
var obj = new Object();
    obj.title = '致前任：当年你饿wei你下面，如今送菜隔空怀念';
    obj.desc  = '只恨眼瞎苦相恋,如今良人已不见,道声前任你好吗,可还记得我下面';
    obj.link  = linkparent+'&sid=8';
    obj.imgUrl= deflaultimgurl;
sdata[8]=obj;

var cbl=1;

$(document).ready(function()	
{		
	resizewindow('#content div,#level2 li,#level2 ul',640); //,#content ul,#content li

	
	<?php 
	 if($this->request->get['sid']>0)
	 { 
	 echo("showshare('".$this->request->get['sid']."');");
	 }
	else
	{  
	  echo('showl1();');
	}?>
	 
		
    
     $('#user_phone,#recode,#user_pwd').each(	
      function(){
    	  var oldval=$(this).val();
	    $(this).bind('click foucs',
	    function(){
	    	if($(this).val()==oldval) $(this).val(''); 
	     }).bind('blur',  function(){
		      if($(this).val()=='')$(this).val(oldval); 
	     });
		 }
	  );   

      $('#cur_pwd').bind('click',checkpwd);
      $('#leve1_pwd').hide();

function checkpwd()
  {
	       var user_pwd=$('#user_pwd').val();
	        if(user_pwd!=''){
	        	var reg = /^(?=.*[a-zA-Z~@#$%_+:\d])(?=.*).{4,16}$/; 
	        	
	        	console.log(reg);
	        	if(reg.test(user_pwd)){
	           $('#user_msg').html('请再次输入密码');
		       $('#cur_pwd').val('确认密码');
		       $('#cur_pwd').unbind();
		       $('#user_pwd').val('');
		       $('#cur_pwd').bind('click',function(){  
		    	   if($('#user_pwd').val()==user_pwd){
		    		     get_coupon();
		    		   $('#user_pwd').val('');
		    		   $('#leve1_pwd').hide();
		    	   }
		    	   else
		    		{
		    		   $('#user_msg').html('两次密码不一致！请重新输入！');
		    		   $('#user_pwd').val('');
		    		   $('#cur_pwd').val('输入密码');
		    		   $('#cur_pwd').unbind();
		    		   $('#cur_pwd').bind('click',checkpwd);
		    		};
		       });
	        }
	        else
	        {
	        	$('#user_msg').html('密码必须由 4-16位组成');
	        	$('#user_pwd').val('');
	        }  
	   }  
	   else
	  {
	      $('#user_msg').html('请输入密码！');
	  }   
}
     

    $('#usersubmit').bind('click',
	function(){
	    $('#level1').slideUp("slow");
	    $('#blackbox').slideUp("slow");
	        
	    $('#level2 #level2_body li').each(function(n){
	    	
	 	    $(this).effect("bounce", {times: 2}, 500);
		   // $(this).effect("shake", {times: 1}, 1000);
	    	
	    });
	    
	});
	$('#level3_top_foot').bind('click',
			function(){
	         $('#level3 #level3_1').slideUp("slow");
	         $('#level3 #level3_2').slideDown("slow");
	});
    $('#level3_foot').bind('click',
			function(){
    	     $('#blackbox').css('height','810px');
		     $('#blackbox').css('opacity','0.8');
		     $('#blackbox').css('z-index','1000');
		     $('#blackbox').show();
		     $('#level4').css('z-index','1001');
		     $('#level4').show();
		     wx.showOptionMenu();
    });  

    $('#level2 #level2_body li').each(function(n){
    	
	$(this).bind('click',
	              function(){
			                shareset((n+1));
			                $('#level1').hide();
			                $('#level3_body img').attr("src",'<?php echo $imgPath; ?>l3-4-'+(n+1)+'.png');
			                $('#level3_foot img').attr("src",'<?php echo $imgPath; ?>l3-2-'+(n+1)+'.png');
			                $('#level3_top img').attr("src",'<?php echo $imgPath; ?>l3-3-'+(n+1)+'.png');

			                $('#level2').slideUp("slow");
			                $('#level3 #level3_1').slideDown("slow");
							return;
							})
	           .bind("mouseover",
	               function(){
	          	        $(this).css('opacity','1');
							return;
							})
				.bind("mouseout",
	               function(){
	          	        $(this).css('opacity','0.5');
							return;
							});

	});

	
    $('#recodeget').bind('click',getrecode);
    $('#recheck').bind('click',sharereturn);
    

});	


	

function showshare(sn)
{
	$('#level1_top').hide();	
	$('#level1_top2 #ltop1 img').attr("src",'<?php echo $imgPath; ?>l3-3-'+sn+'.png');
	$('#level1_top2 #ltop2 img').attr("src",'<?php echo $imgPath; ?>l3-4-'+sn+'.png');
	$('#level1_top2').show();
	
    $('#level1').css('background-color','#00bab4');
    $('#level1').css('height',parseInt(2200*cbl)+"px"); 
    
    $('#usersubmit').attr("src",'<?php echo $imgPath; ?>l1-5-2.png');
    
    console.log($('#level1_top img'));
    
    $('#blackbox').css('height',parseInt(2200*cbl)+"px");
	$('#blackbox').css('opacity','0.8');
    $('#blackbox').css('z-index','1000');
    $('#blackbox').show();
    $('#level1').css('z-index','1002');
    $('#level1').show();
}
function showl1()
{   
	$('#level1_top').show();
	$('#level1_top2').hide();
	$('#blackbox').css('height',parseInt(1050*cbl)+"px");
	 $('#blackbox').css('opacity','0.8');
     $('#blackbox').css('z-index','1000');
     $('#blackbox').show();
     $('#level1').css('z-index','1002');
     $('#level1').show();
}

function getrecode(){

	$('#user_msg').addClass('msg');
    $('#user_msg').removeClass('txt_gx');
	$('#user_msg').html('');

	$('#recodeget').unbind();
	$('#recodeget').addClass("black");
	   $.ajax({
			url: 'index.php?route=promotion/coupon/getrecode',
			type: 'post',
			data: 'userphone='+$('#user_phone').val(),
			dataType: 'json',
			success: function(json) {
			       	console.log(json);
				      $('#getcoupon').unbind();
				      $('#recodeget').unbind();
				if(!json.success){
					  $('#user_msg').html(json.msg);
					  $('#recodeget').bind('click',getrecode);					  
					  $('#recodeget').removeClass("black");
	            }else{
	            	  $('#recodeget').addClass("black");
	            	  $('#user_msg').html('请查看您的手机获取验证码');
	            	  
	            	
	            	  
	            	  
	            	  $('#getcoupon').bind('click',
	            				function(){
	            		            get_coupon();
	            	               });
	            }

			}
	      });
	
}

//获取优惠劵
function get_coupon()
{     $('#user_msg').addClass('msg');
      $('#user_msg').removeClass('txt_gx');
	  $('#user_msg').html('');
     if($('#recode').val()=='') { 
	   $('#user_msg').html('请输入验证码');alert('请输入验证码');return;
	   }
     $('#getcoupon').addClass("black");
     $('#getcoupon').unbind();
	 $.ajax({
			url: 'index.php?route=promotion/coupon/getcoupon',
			type: 'post',
			data: 'userphone='+$('#user_phone').val()+'&recode='+$('#recode').val()+'&userpwd='+$('#user_pwd').val()+'&pid=<?php echo $this->request->get["pid"];?>'+'&sid=<?php echo $this->request->get["sid"];?>'+'&partner=<?php echo $this->request->get["partner"];?>',
			dataType: 'json',
			success: function(json) {
				console.log(json);
				
				 $('#getcoupon').unbind();
				
				if(!json.success){
					if(json.newuser){
						  $('#leve1_pwd').show();
						  $('#user_msg').html(json.msg);				  
						  $('#recodeget').unbind();
						  $('#recodeget').addClass("black");
						}else
						{
							
						$('#getcoupon').bind('click',get_coupon);
		
						 $('#leve1_pwd').hide();
						
						 $('#getcoupon').removeClass("black");
						  $('#user_msg').html(json.msg);				  
						 
						  $('#recodeget').unbind();
						  $('#recodeget').bind('click',getrecode);  
						  $('#recodeget').val('重新获取');
						  $('#recodeget').removeClass("black");
						}
					
					 
					  
	            }else{
	            	
	            	
	            	 $('#recodeget').unbind();
	            	 $('#recodeget').addClass("black");
	            	 
	            	 $('#user_msg').removeClass('msg');
	            	 $('#user_msg').addClass('txt_gx');
	            	 
	            	 $('#getcoupon').html('');
	            	 $('#getcoupon').unbind();
	            	 $('#getcoupon').addClass("black");
	            	 
	            	 $('#user_msg').html(json.msg);

	            }

			}
	      });
	}


function resizewindow(objs,w0){
	    var maxwidth=$(window).width();	
        if(maxwidth<w0){
        cbl=maxwidth/w0;
        $(objs).each(
  		       function(n){
  			         resetcsswh(this,'width,height,background-size,background-position,top,left,padding-top',cbl); 
  		       }
         ); 
    }	
}
function resetcsswh(obj,css,cbl)
{
	 var csslist=css.split(",");
	  for(var j=0;j<csslist.length;j++){  
			 var xy=$(obj).css(csslist[j]).split(" ");
			 var cssval='';
			 var oldcssval=$(obj).css(csslist[j]);
			 var replace=false;

	     for(var i=0;i<xy.length;i++){
	        if(xy[i].lastIndexOf('px')>-1 && parseInt(xy[i])>0){
		     cssval+=parseInt(parseInt(xy[i])*cbl)+"px "; 
		     replace=true;
	         }/*
	         else if(xy[i].lastIndexOf('%')>-1 && parseInt(xy[i])>0){
		     cssval+=parseInt(parseInt(xy[i])*cbl)+"% "; 
		     replace=true;
	         }*/
	         else
	         {
		     cssval+=xy[i]+" "; 
	         }
	  }
	  if(replace){
		  $(obj).css(csslist[j],cssval);
	  }
	  
	  }
	  

}





wx.ready(function () {
	//隐藏右上角按钮
	wx.hideOptionMenu();	
	

});
function sharereturn()
{
	   $('#blackbox').slideUp("slow");
	   $('#level4').hide("slow");
	   $('#level3 #level3_1').slideUp("slow");
	   $('#level3 #level3_2').slideUp("slow");	   
	   $('#level2').show();	
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
<?php }?>