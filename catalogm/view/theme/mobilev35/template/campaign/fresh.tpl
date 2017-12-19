<?php echo $header35; ?>
<link href="assets/libs/mobiscroll/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/mobiscroll/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />
 <!-- s日期控件 -->
    <script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
	<script src="assets/libs/mobiscroll/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>

<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/fresh.css" rel="stylesheet"/>

<!-- 公共头结束 -->
<div id="m-metro">  
	<!-- 手机号 -->
	<div class="form-top"><img src="<?php echo HTTP_CATALOG . $tplpath; ?>/images/campaign/ibmbg_head.png"/></div>
	<?php

	  $showdate['start']='2016-01-15';
      $showdate['end']  ='2016-01-29';
	if(DEBUG){
      $showdate['start']='2015-12-15';
      $showdate['end']  ='2016-01-15';
	}
if(time()>strtotime($showdate['end'])){
?>
	<div class="form-middle">
 活动已结束，谢谢惠顾！
   </div>
   
   <?php
}elseif(time()<strtotime($showdate['start'])){?>
   <div class="form-middle">
暂未开始请耐心等待<br/>领取日期（<?php echo $showdate['start'];?>至<?php echo  $showdate['end'];?>）
   </div>

<?php }
      else
{
       if(time()<strtotime('2016-02-04')){
           $dates['min']='2016-01-25';
           $dates['max']='2016-02-05';
         }else{
           $dates['min']='2016-02-18';
           $dates['max']='2016-02-29';
         }
?>
	<div class="form-middle">

	<div class="form-group g1">
		<div class="input-group">             
			<input id="code" type="text" name="code" placeholder="请输入提货码"/>
		</div>
	</div>
	<div class="form-group hidden g2">
		<div class="input-group">             
			<input id="input-new-phone" type="tel" name="mobile" data-validate="phone" placeholder="输入手机号"/>
		</div>
	</div>
	<div class="form-group hidden g2">
		<div class="input-group">             
			<input id="name" type="text" name="name" placeholder="联系人"/>
		</div>
	</div>
	<div class="form-group hidden g2">
		<div class="input-group">             
			<input id="address" type="text" name="address" placeholder="详细地址，仅限5环内"/>
		</div>
	</div>
	<div class="form-group hidden g2">
		<div class="input-group">        
			<input type="cdate" id="pickdate" name="pickdate" required min="<?php echo $dates['min'];?>" max="<?php echo $dates['max'];?>" value="<?php echo $dates['min'];?>"/>
		</div>
		
		
	</div>

	<div class="form-group">
		<input type="button" value="确认领取" class="btn btn-block btn-brown btn-submit"  onclick="input()"/>
	</div>	
	</div>

   
	<?php }?>
	
	<div class="form-top"><img src="<?php echo HTTP_CATALOG . $tplpath; ?>/images/campaign/ibmbg_foot.png"/></div>
</div>
<?php //echo $this->getChild('module/sharebtn',array('btn_hide'=>'#'));?>
<script>
$(document).ready(function(){	

	 var currYear = (new Date()).getFullYear();	
		var opt={};
		opt.date = {preset : 'date'};
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
		$("#pickdate").mobiscroll('destroy').date($.extend(opt['date'], opt['default']));

});  
</script>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/fresh.js"></script>
<!-- 公共底部开始 -->
<?php echo $footer35; ?>