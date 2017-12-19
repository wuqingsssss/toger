<?php
$tplPath = 'catalog/view/theme/dss/template/';
$cssPath = 'catalog/view/theme/dss/stylesheet/';
$jsPath = 'catalog/view/theme/dss/js/';
$imgPath = 'catalog/view/theme/dss/image/lottery/';

//require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect();
if ($detect->isMobile() && !$detect->isTablet()) {
    $cl = 'm';
}
if ($detect->isTablet()) {
    $cl = 't';
}
if (!$detect->isMobile() && !$detect->isTablet()) {
    $cl = 'd';
}
?>
<!doctype html>
<!--[if lt IE 7 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-us" id="<?php echo $cl; ?>" class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1">
    <meta charset="utf-8">
    <title><?php echo $heading_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <base href="<?php echo $base; ?>"/>
    <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>"/>
    <?php } ?>
    <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <?php } ?>
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon"/>
    <?php } ?>
<style type="text/css">
	 body {
	    font-family: 微软雅黑, sans-serif;
	    font-weight: bold
	    }
	 li{
	 	list-style-type:none;
	 }
	 .list{
		list-style-type:disc;
		list-style-position:outside;
		margin-top:5px;
		padding-right:20px}
		
    hr{
    	margin:10px;
    	height:1px;
    	border:0px;
    	background-color:white;
    	color:white;
    }
	
    #level2{
    	width:1000px; 
    	height:650px; 
    	position:relative; 
    	}
    	
    #container{
    	position:relative;
    	width:1000px; 
    	height:650px; 
    	background-color:#000;
    	margin:0px auto
    }
    
    #level1{
    }
    
    #con2{
  		position:absolute; 
		top:70px; 
		left:500px;
		font-size:12px;
		color:white;
    }
    #res{
    	position:absolute; 
		top:20px; 
		left:250px;
		font-size:20px;
		color:white;
		text-align:center;
		width:300px;
    }
    
    .vert{
    	vertical-align: top; 
    	}
    
	#box{
		width:1000px; 
		height:650px; 
		position:relative;
		background:url(<?php echo $imgPath.'bk.jpg'; ?>) no-repeat;
		}
		
	#text1{
		position:absolute; 
		top:80px; 
		left:160px;
		font-size:20px;
		color:yellow}
	#disk{
		width:650px; 
		height:650px; 
		position:absolute; 
		top:188px; 
		left:126px;}
	#start{
		width:163px; 
		height:320px; 
		position:absolute; 
		top:265px; 
		left:229px;}
	#start img{cursor:pointer}
</style>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jQueryRotate.2.2.js"></script>
	<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.easing.min.js"></script>

   
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/dss/stylesheet/ie7.css"/>
    <![endif]-->
<script type="text/javascript">
	$(document).ready(function(){
		inital();
	}); 
	 
	$(function(){
		 $("#startbtn").click(function(){
			lottery();
		});
	});

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
				$("#res").css({"width":500+"px"});
				var text   = json.text;
				var path   = json.path;
				var res =  "<ul><li><img src='"+path+"' width=400px/></li> ";
				res += "<li>"+text+"</li>";
				res += "<li><div><img src='catalog/view/theme/dss/image/lottery/bt.png' width=400px/>";
				res += "<div style='position:absolute; left:210px; bottom:50px'>";
				res += "<a href=index.php?route=common/home><img src='catalog/view/theme/dss/image/lottery/ok.png' width=120px/></a></div>";
				res += "</div></li></ul>";
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
<body >

<div id="container">
	<div id="level1">
		<div id="level2">
			<div id="box">
				<div id= "text1"> </div>
		        <div id="disk"><img src="<?php echo $imgPath.'disk.gif' ;?>"></div>
		        <div id="start"><img src="<?php echo $imgPath.'start.png' ;?>" id="startbtn"></div>
		        
		        <div id="con2">
		        <ul> 
				    <li style="text-align:center"> 
				        <div style="text-align:center"><img src="<?php echo $imgPath.'ribon.png' ;?>" width=400px ></div>			        		 
				    </li> 
				    <li> 
		        		<div style="text-align:center; margin-top:30px"><img src="<?php echo $imgPath.'xinyun.png' ;?>"  ></div>
		        	</li>
		        	<li>
		        		<hr style="margin-left:30px; margin-right:30px"/>
		        	</li>
		        	<li>
		        			<table class="vert" style="margin-top:20px"">
		        			<?php foreach ($prizes as $prize) { ?>
				        		<tr>
				        			<th width=150px> <?php echo $prize['mobile']; ?> </th>
				        			<th width=150px style="text-align:left;padding-left:20px"> <?php echo $prize['prize']; ?> </th>
				        			<th width=150px> <?php echo $prize['time']; ?> </th>
				        		</tr>
				        	<?php } ?>
				        	</table>
				        
		        	</li>
		        	<li> 
		        		<div style="text-align:center; margin-top:30px"><img src="<?php echo $imgPath.'rule.png' ;?>"  ></div>
		        	</li>
		        	<li>
		        		<hr style="margin-left:30px; margin-right:30px"/>
		        	</li>
		        	<li>
		        		<ul style="margin-top:20px">
		        			<li class="list">指定时间内用户下单并成功支付即可免费抽奖一次；</li>
		        			<li class="list">不限抽奖次数，下单越多，机会越多，100%中奖；</li>
		        			<li class="list">所中的奖品使用规则请仔细阅读中奖页面说明；</li>
		        			<li class="list">如活动受政府机关指令或遭受严重网络攻击等意外问题需暂停的，本平台无需为此承担赔偿或进行补偿。</li>
		        		</ul>
		        	</li>
		        </ul>
		        </div>
			</div>
		</div>
	</div>
	<div id="res"></div>
</div>
  
  	
</body>
</html>  	
