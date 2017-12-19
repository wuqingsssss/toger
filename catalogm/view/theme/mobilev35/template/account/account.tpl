<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/uc.css" rel="stylesheet"/>
<div id="uc_body">
		<div class="text-center uc-top">
				<div class="uc-tx"><a href="<?php echo $this->url->link('account/edit'); ?>"><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/touxiang.png"></a></div>
				<div class="uc-phone  fz-12"><?php echo $this->customer->getDisplayName(); ?></div>
		</div>
		<div class="text-center bg-white col-gray uc-info">
				<ul>
                        <a href="<?php echo $this->url->link('account/transaction'); ?>"><?php echo $text_transaction; ?>"> 
						<li class="fz-16 "> 余额 </li>
						<li class="fz-18 col-red"> <?php echo $total ? $total : 0.00; ?> </li>
				</ul>
				<ul class="uc-tx">
						<a href="<?php echo $this->url->link('account/coupon'); ?>"><?php echo $text_coupon; ?>"> 
								<li class="fz-16 ">优惠券 </li>
								<li class="fz-18 col-red"> <?php echo $c_count; ?> </li></a>
				</ul>
				<ul class="uc-tx">
						<a href="<?php echo $this->url->link('account/reward'); ?>"> 
								<li class="fz-16 "> 积分 </li>
								<li class="fz-18 col-red"> <?php echo $points; ?> </li>
						</a>
				</ul>
		</div>
		<div class="uc-menu col-gray">
				<ul>
						<a href="<?php echo $this->url->link('account/order'); ?>">
							<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm1.png">
									我的订单
									<span class="pull-right arrow-right"></span>
							</li>
						</a>
						<a href="<?php echo $address; ?>">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm2.png">
								我的收货地址
								<span class="pull-right arrow-right"></span> </li>
						</a>
<!--              <li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm3.png">
								<a href="uc-favorite.html">我的最爱</a>
								<a class="pull-right arrow-right"></a> </li>
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm4.png">
								<a href="uc-comment.html">我的评价</a>
								<a class="pull-right arrow-right"></a> </li>-->
				</ul>
				<ul>
<!--						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm5.png"><a href="uc-share.html">分享有礼</a><a class="pull-right arrow-right"></a> </li>
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm6.png"><a href="uc-message.html">消息中心</a><a class="pull-right arrow-right"></a> </li>-->
				</ul>
				<ul>
						<a href="<?php echo $this->url->link('account/edit'); ?>">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/uco1.png">
								个人资料
								<span class="pull-right arrow-right"></span>
						</li>
						</a>
						<a href="<?php echo $this->url->link('account/password/edit_pwd'); ?>">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm8.png">
								修改密码
								<span class="pull-right arrow-right"></span>
						</li>
						</a>
						<a href="<?php echo $this->url->link('information/information&information_id=45'); ?>">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm3.png">
								关于我们
								<span class="pull-right arrow-right"></span>
						</li>
						</a>
				</ul>
				<ul> 
						<a href="<?php echo $this->url->link('information/question','','SSL')?>">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm7.png">常见问题
								<span class="pull-right arrow-right"></span>
						</li>
						</a>
<!--<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/ucm8.png"><a href="uc-suggest.html">意见反馈</a><a class="pull-right arrow-right"></a></li>-->
						<a href="javascript:logout_alert();">
						<li class="fz-16 "><img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/uc/uco5.png">退出登录
								<span class="pull-right arrow-right"></span>
						</li>
						</a>
				</ul>
		</div>
		<div class="text-center uc-foot col-gray fz-16">
				<span>客服电话</span><span class="col-red"> <a href="tel:4000150077"> 400-015-0077</a></span>
		</div>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
<script type="text/javascript">
	function logout_alert(){
		var url = "<?php echo $this->url->link('account/logout'); ?>";
		_.confirm('是否确认退出?', function () {
				location.href = url;
			})
	}
</script>