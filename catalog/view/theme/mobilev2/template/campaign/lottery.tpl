<html  ng-app="app" lang="en-us" class="no-js">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1,user-scalable=no"> 

<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<script type="text/javascript" name="baidu-tc-cerfication" data-appid="4489645" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>
<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>"
	rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<!--<link href="catalog/view/theme/<?php echo $template;?>/stylesheet/ionic.min.css" rel="stylesheet">-->
<!-- <script src="catalog/view/theme/<?php echo $template;?>/javascript/ionic.bundle.min.js"></script>-->

<!-- RESET USER AGENT -->
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template;?>/assets/ionic/css/ionic.css" />

<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $template;?>/stylesheet/stylesheet.css" />

	
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css"
	href="<?php echo $style['href']; ?>"
	media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.6.1.min.js"></script>
<!--<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.min.js"></script>-->

<script src="assets/libs/modernizr/modernizr.2.6.3.js"></script>

<script type="text/javascript"
	src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" type="text/css"
	href="catalog/view/javascript/jquery/ui/themes/flick/jquery-ui-1.8.16.custom.css" />
	
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="js/lodash-2.4.1.compat.min.js"></script>
	
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<!-- Fixes for IE -->
<!--[if lt IE 9]>
<script src="assets/libs/dist/html5shiv.js"></script>
<![endif]-->

<!--[if (gte IE 6)&(lte IE 8)]>
  <script type="text/javascript" src="assets/libs/selectivizr/1.0.2/selectivizr.js"></script>
  <noscript><link rel="stylesheet" href="[fallback css]" /></noscript>
<![endif]-->

    <script type="text/javascript" src="catalog/view/theme/<?php echo $template;?>/javascript/angular/1.2.27/angular.min.js"></script>
    <script type="text/javascript" src="catalog/view/theme/<?php echo $template;?>/javascript/angular/ui-router/0.2.13/angular-ui-router.min.js"></script>
    <script>
        angular.module('app', ['ui.router']);
        
        //文档禁止 touchmove
       // document.ontouchmove = function(e){ e.preventDefault(); }; 
    </script>



<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath = 'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/lottery/';
?>
<style type="text/css">
	 body {	
	    font-family: '微软雅黑', sans-serif;
	    background-color:#0ba29a;
	    font-weight: bold;
		color:#fef7c0;	    
		overflow-x:hidden;
		width:100%;
		max-width:400px;
		overflow:hidden;
	    }
	 li{
	 	list-style-type:none;
	 }
	 
	 li.list{
		list-style-type:disc;
		list-style-position:outside;
		margin-top:5px;
		padding-right:20px;
		color:#fef7c0;
		}
	
	table{
		font-size:11px;
		letter-spacing:2px;
	}
			
    #level2{
    	width:100%;
    	height:800px; 
    	position:relative; 
    	background-color:#0ba29a;
    	}
    	
    #container{
    	position:relative;
    	width:100%;
    	height:800px; 
    	margin:0px auto;
    	background-color:#000; 
    }
    
    #level1{
    }
    
    #con2{
  		position:relative; 
		top:70px; 
		left:0px;
		font-size:9px;
		color:white;
    }
    #res{
    	position:absolute; 
		top:50px; 
	    left:50%;
	    margin-left:-150px;
		font-size:12px;
		color:white;
		width:300px;
    }
    
    .vert{
    	vertical-align: top; 
    	}
    
	#box{
		width:100%;
		height:400px; 
		position:relative;
		background:url(<?php echo $imgPath.'bk.gif'; ?>) no-repeat;
		background-size:360px;
		
		}
		
	#text1{
		position:relative; 
		top:20px; 
		text-align:center;
		font-size:16px;
		margin-bottom:40px;}
		
	#disk{
		position:absolute; 
		top:33px; 
		left:48px;
		}
	#start{
		position:absolute; 
		top:126px; 
		left:153px;}
	#start img{cursor:pointer;}
	
	.ok_btn{
		width:100px;
		position:absolute; 
		left:50%; margin-left:-50px;
		bottom:20px;		
		}
		
	#lucky {
		display:block;
		font-size:11px;
		letter-spacing:2px;
	}
	
	#rule {
		display:none;
		font-size:11px;
		letter-spacing:2px;
	}
		
	#luckybtn{
		display:block;
		width:150px;
		height:40px;
		background:url(<?php echo $imgPath.'xinyun.png' ;?>) no-repeat;
		background-size:150px 40px;
	}
	
	#luckybtn:hover{
	    background:url(<?php echo $imgPath.'xinyun_down.png' ;?>) no-repeat;
	    background-size:150px 40px;
	} 
	
	#rulebtn{
		display:block;
		width:150px;
		height:40px;
		background:url(<?php echo $imgPath.'rule.png' ;?>) no-repeat;
		background-size:150px 40px;
	}
	
	#rulebtn:hover{
	    background:url(<?php echo $imgPath.'rule_down.png' ;?>) no-repeat;
	    background-size:150px 40px;
	} 

</style>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jQueryRotate.2.2.js"></script>
	<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.easing.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){		
		var maxwidth=$(window).width();		
		var cval=maxwidth-360;
		cval=40;
		if(maxwidth>400){	
		$('#box').css("width",360+cval);
		$('#box').css("height",360+cval);
		$('#box').css("background-size",360+cval);
		
		$('#disk').css("top",33+cval/2);
		$('#disk').css("left",48+cval/2);
		
		$('#disk').css("width",266+cval);
		$('#disk').css("height",266+cval);

		$('#start').css("top",126+cval/2);
		$('#start').css("left",153+cval/2);	
		
		//$("#res").css({"width":355});
		
		}
		else
		{
		  $('#box').width(360);
		 // $("#res").css("width",320);
		  
		}
		inital();			
	}); 
	 
	$(function(){
		 $("#startbtn").click(function(){
			lottery();
		});
	});

	function change_div(id){
		if (id == 'lucky' )
		{
			document.getElementById('rule').style.display = 'none' ;
			document.getElementById('lucky').style.display = 'block' ;
		}
		else
		{
			document.getElementById('lucky').style.display = 'none' ;
			document.getElementById('rule').style.display = 'block' ;
		}
	}

	function inital(){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=campaign/lottery/check',
			dataType: 'json',
			cache: false,
			error: function(){
				return false;
			},
			success:function(json){
				var status = json.status;
				var text   = json.text;
				if( status != 'ON'){
					$("#startbtn").unbind('click').css("cursor","default");
				}
				document.getElementById("text1").innerHTML=text;
			}
		});
	}
	
	function getResult(){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=campaign/lottery/getResult',
			dataType: 'json',
			cache: false,
			error: function(){
				return false;
			},
			success:function(json){
				$("#level1").css({"opacity":0.5});
				
				//$("#res").css({"width":355+"px"});
				
				var text   = json.text;
				var path   = json.path;
				var res =  "<ul><li><img src='"+path+"' width=300px/></li> ";
				res += "<li style='padding-left:5px;'>"+text+"</li>";
				res += "<li><div><img src='catalog/view/theme/dss/image/lottery/bt.png' width=300px/>";
				res += "<a href=index.php?route=common/home><img src='catalog/view/theme/<?php echo $template;?>/image/lottery/ok.png' class='ok_btn' /></a></div>";
				res += "</li></ul>";
				document.getElementById("res").innerHTML = res;
			}
		});
	}

	function lottery(){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=campaign/lottery/getrand',
			dataType: 'json',
			cache: false,
			error: function(){
				alert('出错了！');
				return false;
			},
			success:function(json){
				$("#startbtn").unbind('click').css("cursor","default");
				var a = json.angle;
				$("#startbtn").rotate({
					duration:3000,
					angle: 0,
	            	animateTo:1800+a,
					easing: $.easing.easeOutSine,
					callback: function(){
						getResult();
					}
				});
			}
		});
	}
</script>
</head>
<body class="ionic" style="margin: 0 auto; ">

<div id="page" class="page">
<div style="position: relative;"><a href="/"><img src="<?php echo $imgPath.'tt_bg.png' ;?>" alt＝"周三菜君给你加油" width="100%"></a></div>
<script>window._bd_share_config={
		"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":["qzone","tsina","renren","tqq","kaixin001","tqf","douban","tsohu","copy"],"bdPic":"","bdStyle":"0","bdSize":"16"},
		"slide":{"type":"slide","bdImg":"8","bdPos":"right","bdTop":"100"}};
		with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>
<div id="container">
	<div id="level1">
		<div id="level2">
			<div id= "text1"> </div>
			<div id="box">
		        <div id="disk"><img src="<?php echo $imgPath.'disk.gif' ;?>" width=266px;></div>
		        <div id="start"><img src="<?php echo $imgPath.'start.png' ;?>" id="startbtn" height=86px></div>
		        
			</div>
			<div id="cn2" style="text-align: center;width:100%;">
				<div style="margin-top:10px;">
				       <div><img src="<?php echo $imgPath.'ribon.png' ;?>" style="width:300px;" ></div>			        		 
				</div>
				<div style="margin-top:10px; padding-left:40px;margin-bottom:60px;">
						<div style="float:left"><a id="luckybtn" onclick="change_div('lucky')"></a></div>
						<div style="float:left"><a id="rulebtn" onclick="change_div('rule')"></a></div>
				</div>
				<hr size=1 style="color:#f8b551;border-style:solid;float: none;">
				<div id="rule" style="margin-top:10px; margin-left:20px">
					<ul>
		        		<li class="list">指定时间内用户下单并成功支付即可免费抽奖一次；</li>
		        		<li class="list">不限抽奖次数，下单越多，机会越多，100%中奖；</li>
		        		<li class="list">所中的奖品使用规则请仔细阅读中奖页面说明；</li>
		        		<li class="list">如活动受政府机关指令或遭受严重网络攻击等意外问题需暂停的，本平台无需为此承担赔偿或进行补偿。</li>
		        	</ul>	
				</div>		
				<div id="lucky" style="margin-top:10px; margin-left:20px">
					<table class="vert" style="margin-top:20px"">
		        			<?php foreach ($prizes as $prize) { ?>
				        		<tr>
				        			<th width=100px> <?php echo $prize['mobile']; ?> </th>
				        			<th width=200px style="text-align:left;padding-left:20px"> <?php echo $prize['prize']; ?> </th>
				        			<th width=100px> <?php echo $prize['time']; ?> </th>
				        		</tr>
				        	<?php } ?>
				    </table>
				</div>						
		    </div>
		</div>
	</div>
	<div id="res"></div>
</div>
</div>  
  	
</body>
</html>  	
