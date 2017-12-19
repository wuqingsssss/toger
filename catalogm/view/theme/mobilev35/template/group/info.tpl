<?php echo $header40; ?>

<!-- 页面自定义样式 -->
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/home.css" rel="stylesheet"/>
<link href="<?php echo HTTP_ASSETS . DIR_DIR; ?>view/theme/mobilev35/css/pullToRefresh.css" rel="stylesheet"/>
<link href="<?php echo HTTP_ASSETS . DIR_DIR; ?>view/theme/mobilev35/css/group.css" rel="stylesheet"/>

<?php echo $header; ?>

<div class="module" id="m-banner">
<?php if ($thumb) { ?>
    <div class="img-wrapper"><img src="<?php echo $thumb; ?>"></div>
<?php } ?>
</div>

<div class="module bg-white">
	<div class="col-red fz-16 title_pt text-center">【<?php echo $info['member_num']?>人团】<?php echo $info['name'] ?></div>
	<div class="col-gray fz-12 text_pt pull-left"><?php echo $info['desc'] ?></div>
	
	<input type="button" id="button-share" class="btn btn-submit btn-green btn-long" value="<?php echo $btn_share;?>" />
	
	<?php echo $share; ?>
    <div class="col-gray fz-12 text_pt" style="display:inline-block;">亲，此团发起后截止日期为<span class="col-red"><?php echo $date_end;?></span>日，如成团，将在<span class="col-red"><?php echo $date_shipping;?></span>日发货。如未成团，将在3-5个工作日内退款。</div>
    <div class="text_pt ">
		<?php foreach ($members as $member){?>
		<div class="photo">
			<?php if($member['is_blank']){?>		
				<img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/gray.png">
			<?php }else{ ?>
			<img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/full_color.png">
			<i><?php echo ($member['is_owner']) ? '团长' : '团员'?></i>
			<?php } ?>
		</div>
		<?php }?>
    
        <br>
		<div class="col-red fz-12 pull-left" style="clear:both;"> <?php echo $error_warning; ?>
		</div>
	</div>
</div>
<div class="module">
    <div class="fi-content fz-12 title_pt">
		<div class="img-wrapper">
			<?php echo htmlspecialchars_decode($product_info); ?>
		</div>
	</div>
</div>
<div class="module bg-body" id="m-add-cart" >
    <div class="add-cart bg-white">
		
		<div class="pull-right">
		     <input id="button-checkout"  value="<?php echo $btn_submit;?>" type="button" class="btn-submit btn btn-green" <?php if(!$btn_submit_status){echo 'disabled="true"';} ?>>
		</div>
	
        <div class="pull-left">
            <i class=" fz-12"><a href="<?php echo $this->url->link('group/group', '', 'SSL');?>">更多拼团</a></i>
            <span class="col-gray fz-15 food-num-val cart-num x">|</span>
            <i class=" fz-12"><a href="<?php echo $this->url->link('information/information','information_id=63', 'SSL');?>">玩法介绍</a></i>
        </div>
    </div>
</div>

<script src="<?php echo HTTP_CATALOG . DIR_DIR . 'view/theme/'; ?>mobilev35/js35/common.js"></script>
<div id="footer">
</div>

<<script type="text/javascript">
<!--
//立即支付
$('#button-checkout').bind('click', function () {
	$('#button-checkout').attr({"disabled":"true"});
 	$.ajax({
		url: '<?php echo $action_submit; ?>',
		type: 'post',
		data: '',
		dataType: 'json',
		beforeSend: function() {
			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>'); 			
		},
		complete: function() {
			$('.waiting').remove(); 

		},
		success: function(json) {
			$('.waiting').remove(); 
			console.log(json);

			if (json['error']) {					
				if (json['error']['warning']) {
					
					$('#button-checkout').removeAttr('disabled');
					_.toast(json['error']['warning'], 3000);
				}
				if (json['redirect']) {			
					window.location = json['redirect'];
				}
			} else {
				if (json['redirect']) {									
					window.location = json['redirect'];
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}		
	});
});

// 分享

$('#button-share').bind('click' , function() {
	<?php if($is_share) {?>
	   $('#blackbox').show();
	<?php }else{?>
	   window.location = "<?php echo $action_share;?>";
	<?php }?>
})

//-->
</script>

<!-- 公共js库引入 -->

<!-- 公共底部结束 -->
<!-- 页面js引入-->


</body>
</html>