<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<link href="assets/libs/mobiscroll/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/mobiscroll/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />
 <!-- s日期控件 -->
    <script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/order.css?v=<?php echo STATIC_VERSION; ?>" />
	<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/general.css?v=<?php echo STATIC_VERSION; ?>" />
    <link rel="stylesheet" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/cart.css?v=<?php echo STATIC_VERSION; ?>" />
	<!-- S 可根据自己喜好引入样式风格文件 -->
	<!-- script src="m/mobiscroll/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script-->
    <!-- E 可根据自己喜好引入样式风格文件 -->
    <!-- E 日期控件 -->
<ul>
   <li class="order-title1"><img src="assets/image/lbs/1_18.png">配送方式<span class="order-tleft"><a onclick="turnbox($('#page'),$('#order-listaddress'));">修改</a></span></li>
</ul>
<?php if (!$shipping_address_id && $shipping_point_id>0) { ?>
<ul class="order-address active">
           <li> 
                     <span class="orderp s1">自提点</span>
                     <span><?php echo $shipping_point_info['name']; ?></span>
                </li>
                <li> 
                      <span class="orderp s1">联系电话</span>   
                      <span><?php echo $shipping_point_info['telephone']; ?></span>
               </li>
               <li>
                       <span class="orderp s1">自提地址</span>
                       <span><?php echo $shipping_point_info['address']; ?></span>
                </li>
      </ul>
<ul>
       <li class="order-title1">
         <span style="float:left;">取菜时间</span>
         <span style="float:left;"><input class="box-time" style="width:200px;height: 26px;
line-height: 26px;margin-left: 10px;" type="cdate" id="pickupdate" name="pickupdate" required min="<?php echo $dates[0];?>" max="<?php echo $dates[count($dates)-1];?>" value="<?php echo $select_date; ?>" onchange="updateAdditionalDate();"/>

        </span>
   </li>
   </ul>
<script type="text/javascript">
$(document).ready(function(){
    var currYear = (new Date()).getFullYear();	
		var opt={};
		opt.date = {preset : 'date'};
		//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
		opt.datetime = {preset : 'datetime'};
		opt.time = {preset : 'time'};
		opt.default = {
			theme: 'android-ics light', //皮肤样式
	        display: 'modal', //显示方式 
	        mode: 'scroller', //日期选择模式
			lang:'zh',
	        startYear:currYear, //开始年份
	        endYear:currYear ,//结束年份
	        stepMinute:60
		};
		$("#pickupdate").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
		$("#pickupdate").click();
});   
    
</script>
<?php }
elseif($shipping_address_id>0)
{
?>
<ul id="order-address" data="<?php echo $addressid;?>" class="order-address active">
                 <li>
                     <span class="orderp s1">收货人</span>
                     <span><?php echo $shipping_address['firstname'] ;?></span>
                </li>
                 <li> 
                      <span class="orderp s1">电  话</span>   
                      <span><?php echo $shipping_address['mobile'] ;?></span>
                 </li>
                 <li>
                       <span class="orderp s1">收货地址</span>
                       <span><?php echo $shipping_address['address_1'] ;?><?php echo $shipping_address['address_2'] ;?></span>
                 </li>
      </ul>
 <ul>
       <li class="order-title1">
         <span style="float:left;">配送时间</span>
         <span style="float:left;"><input class="box-time" style="width:200px;height: 36px;line-height: 36px;margin-left: 10px;" type="cdatetime" id="pickupdate" name="pickupdate" required min="<?php echo $dates[0];?>" max="<?php echo $dates[count($dates)-1];?>" mint="17:00" maxt="21:00" value="<?php echo $select_date; ?>" onchange="updateAdditionalDate();"/>
        </span>
   </li>
   </ul>
<script>
       $(document).ready(function(){
            var currYear = (new Date()).getFullYear();	
     		var opt={};
     		opt.date = {preset : 'date'};
     		//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
     		opt.datetime = {preset : 'datetime'};
     		opt.time = {preset : 'time'};
     		opt.default = {
     			theme: 'android-ics light', //皮肤样式
     	        display: 'modal', //显示方式 
     	        mode: 'scroller', //日期选择模式
     			lang:'zh',
     	        startYear:currYear, //开始年份
     	        endYear:currYear ,//结束年份
     	        stepMinute:60
     		};
     	  	var optDateTime = $.extend(opt['datetime'], opt['default']);
     	  	var optTime = $.extend(opt['time'], opt['default']);
     	    $("#pickupdate").mobiscroll(optDateTime).datetime(optDateTime);
     	    $("#pickupdate").click();
       });   
 </script>
<?php }else{ ?>
<script>
    $(document).ready(function(){
    	  turnbox($('#page'),$('#order-listaddress'));
       });   
</script>
<?php } ?>