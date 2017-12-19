<?php echo $header; ?>
<?php
$tplPath = 'catalog/view/theme/'.$template.'/template/';
$cssPath = 'catalog/view/theme/'.$template.'/stylesheet/';
$jsPath =  'catalog/view/theme/'.$template.'/js/';
$imgPath = 'catalog/view/theme/'.$template.'/image/coupon2/';
?>
<link href="<?php echo $cssPath; ?>couponpickup2.css" rel="stylesheet">

<style>
<?php if($coupon_info['share_bg']){ ?>
.hb{background: url(<?php echo HTTP_IMAGE.$coupon_info['share_bg'];?>) no-repeat 0px 0px /100% 100% ;}
<?php }?>
</style>
<div class="hb">
   <div class="hb_top"><img src="<?php if($coupon_info['share_image3']) {echo HTTP_IMAGE.$coupon_info['share_image3'] ;}else{ echo $imgPath.'cjsfl.png'; }?>"/></div>
   
    <div id="hb_body" class="hb_body">
     <div id="hb_tt" class="hb_tt<?php if($success!='-2')echo ' hide' ;?>"><img src="<?php  echo $imgPath.'title2.png'; ?>"/></div>
        <div class="hb_title">
        <img src="<?php if($coupon_info['share_image1']) {echo HTTP_IMAGE.$coupon_info['share_image1'] ;}else{ echo $imgPath.'title1.png'; }?>"/>
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
        恭喜您获得<font style="color:red;"><?php echo $coupon_info['name'];?></font>    
         </div>
         <div class="hb_foot">
               <a id="ljck" href="index.php?route=account/account" class="btn">立即查看</a>
                 <?php echo $this->getChild('module/sharebtn');?>
         </div>
         <div class="hb_info">
         <?php echo  str_replace(array("<br/>","\r\n", "\r", "\n"),array("","<br/>", "<br/>", "<br/>"),$coupon_info['usage']);?>
</div>
</div>

    </div>

        <div id="hb_body2" class="hb_body2 hide">
        <div class="hb_title">
        <img src="<?php if($coupon_info['share_image2']) {echo HTTPIMAGE.$coupon_info['share_image2'] ;}else{ echo $imgPath.'ohya.png'; }?>"/>
        </div>

         <div class="hb_box">
        </div>
       <div class="hb_foot">
               <a href="index.php?route=common/home" class="btn">去菜君家</a>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#ljlq").bind('click',function(){

	 $.ajax({
			url: 'index.php?route=promotion/couponpickup/pickup',
			type: 'post',
			data: 'userphone='+$('#user_phone').val()+'&partner=<?php echo $partner;?>&pid=<?php echo $pid;?>',
			dataType: 'json',
			success: function(json) {
			       	console.log(json);
			        $("#hb_tt").hide();
				if(json.success>0){
					  $('#successbox').html('恭喜您获得<font style="color:red;">'+ json.coupon_info.name+'</font> 优惠券 ');
					  $('#box1').hide(); 
					  $('#box2').show();  
	            }
				else if(json.success=='-1')
	            {
	            	  $('#error_msg').html(json.msg);
	            }

				else if(json.success=='-2')
	            { 	  $("#hb_tt").show();
					  $('#successbox').html('恭喜您获得<font style="color:red;">'+ json.coupon_info.name+'</font> 优惠券 ');
					  $('#box1').hide(); 
					  $('#box2').show();    
	            }
				else if(json.success=='-3')
	            { 	
	            	  $('#hb_body').hide(); 
					  $('#hb_body2').show();  
	            }else if(json.success=='-4')
	            {
	          
	            	$('#successbox').html('您有<font style="color:red;"><?php echo $coupon_info['name'] ?></font>尚未领取');
	            	$('#ljck').html('注册马上领取');
	            	$('#ljck').attr('href','index.php?route=account/register&partner=<?php echo $partner;?>');
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